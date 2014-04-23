# TODO
# 20130123 - Convertir a decimal la longitud y longitud para poder expresar en google maps
# 20130123 - Diferenciar el tipo de dato con un switch o algo asi (RTT,RGP, etc)
# 20130124 - Cuando el tipo de informe (RTT;RGP) no exista, debe insertarlo en la base, pero devolver un aviso al server para que quede claro que se registro un evento que no existe

from db import *
from checksum import *
from socket import *
class XVM:
	tipo = 0
	directorioweb = "/Users/Joe/Sites/virloc/index.html"
	#def __init__(self):
	#    tipo=0
	#    fecha=0
	#    hora=0
	#    latitud=0
	DB = db()

	def set(self, dato):
		#Recibe un array
		# Posicion 0 - Informacion de posicion
		# Posicion 1 - ID del equip
		# Posicion 2 - Nro Mensaje
		# Posicion 3 - Checksum
		self.tipo = dato[0][1:4]
		self.fecha = dato[0][4:10]
		self.hora = dato[0][10:16]
		self.datetime = "20%s-%s-%s %s:%s:%s" % (self.fecha[4:6], self.fecha[2:4], self.fecha[0:2], self.hora[0:2], self.hora[2:4], self.hora[4:6])
		print self.datetime
		self.latitud = float(dato[0][16:24]) / 100000
		self.longitud = float(dato[0][24:33]) / 100000
		self.velocidad = dato[0][33:36]
		self.rumbo = dato[0][36:39]
		self.tipopos = dato[0][39:40]
		self.age = dato[0][40:42]
		self.ent = dato[0][42:44]
		self.numero_evento = dato[0][44:46]
		self.errorgps = dato[0][46:48]
		self.id_virloc = dato[1][3:7]
		self.nromensaje = dato[2]
		self.checksum = dato[3]

	def get(self):
		return "%s %s Vehiculo %s - Tipo %s localizado en %s %s a %s kms/h con rumbo %s" % (self.fecha, self.hora, self.id_virloc, self.tipo, self.latitud, self.longitud, self.velocidad, self.rumbo)

	def saveToFile(self):
		inp = open(self.directorioweb, "w")
		inp.write("%s,%s" % (self.latitud, self.longitud))
		inp.close()

	def saveToDB(self):
		DB = db()
		DB.sqlInsert("cocido", "id_virloc=%s,latitud=%s,longitud=%s,velocidad=%s,rumbo=%s,evento='%s',fecha='%s',numero_evento='%s'" % (self.id_virloc, self.latitud, self.longitud, self.velocidad, self.rumbo, self.nromensaje, self.datetime, self.numero_evento))

	def getIDMensaje(self):
		DB = db()
		d = DB.sqlSelect("max(id_mensaje) as id_mensaje", "mensajes", "")
		id_respuesta = 1
		for id_mensaje in d.fetchone():
			if id_mensaje:
				if id_mensaje + 1 != 65535:
					id_respuesta = id_mensaje + 1
		return str(id_respuesta).zfill(4)

	def sendDirectMsg(self, id_virloc, mensaje,vircom):
		# Este mensaje no pasa por la cola, va al virloc directo y luego se ingresa en la tabla

		# Obtengo el id de mensaje que debo asignar
		id_mensaje = self.getIDMensaje()
		id_mensaje_hex = hex(int(id_mensaje)).replace("0x","").zfill(4)

		# Si la variable "vircom" esta en 1, se enviara el mensaje a la pantalla del equipo (VIRCOM)
		# Si no, se considerara un mensaje interno (VIRLOC)
		# Como el mensaje es para el VIRCOM (pantalla) y no para el virloc, entonces se pone una "V" al final del id
		if int(vircom) == 1:
			# Mensaje para el vircom sin formato
			envio = ">%s;ID=%sV;#%s;" % (mensaje,id_virloc,id_mensaje)
		else:
			# Mensaje interno para el virloc
			envio = ">%s;ID=%s;#%s;" % (mensaje,id_virloc,id_mensaje)
	
		# Inserto el mensaje en la BD. Deberia poner un flag de enviado automaticamente en 1 (al momento no tiene ese flag la base de datos)
		DB.sqlInsert("mensajes", "id_mensaje=%s, id_mensaje_hex='%s', mensaje='%s Directo', id_virloc=%s" % (id_mensaje, id_mensaje_hex, envio, id_virloc))

		self.sendMsg(id_virloc,envio)

	def sendMsgToQueue(self,id_virloc, mensaje,vircom):

		id_mensaje = self.getIDMensaje()
		id_mensaje_hex = hex(int(id_mensaje)).replace("0x","").zfill(4)
		
		DB = db()
		DB.sqlInsert("mensajes", "mensaje='%s', id_virloc=%s" % (id_mensaje, id_mensaje_hex, mensaje, id_virloc))

	def sendQueuedMsg(self,id):
		# ID - es el id de la tabla mensajes. mensajes.id
		DB = db()
		d = DB.sqlSelect("mensaje, id_virloc", "mensajes", "id=%s" % id)
		for mensaje, id_virloc in d.fetchall():
			# Envio el mensaje
			self.sendMsg(id_virloc, mensaje)
			
			# Calculo el id de mensaje que debo asignar
			id_mensaje = self.getIDMensaje()
			id_mensaje_hex = hex(int(id_mensaje)).replace("0x","").zfill(4)
			
			# Update en la base
			DB.sqlUpdate("mensajes","id_mensaje='%s',id_mensaje_hex='%s',enviado=1" % (id_mensaje,id_mensaje_hex),"id=%s" % id)

	def ackMsgQueue(self):
		# Sirve para poner como recibido un mensjae enviado desde la cola
		print "ack";
		
	def sendMsg(self,equipo,mensaje):
		# Envia mensaje al puerto e ip de un virloc
		print "Enviando: %s a %s" % (mensaje, equipo)

		# Agrego el checksum al mensaje
		mensaje += "*%s<\r\n" % calculateChecksum(mensaje)

		# Busco el puerto e ip del equipo al que quiero contactar
		DB = db()
		d = DB.sqlSelect("ip,puerto", "equipos", "id=%s" % equipo)
		for ip, port in d.fetchall():
			addr = (ip, int(port))

		# Abro conexion y envio datos
		UDPSock = socket(AF_INET, SOCK_DGRAM)
		UDPSock.setsockopt(SOL_SOCKET, SO_REUSEADDR, 1)
		UDPSock.bind(('', 4097))
		UDPSock.sendto(mensaje, addr)
		UDPSock.close()