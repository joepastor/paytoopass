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

	def enviarMensaje(self, id_virloc, mensaje,vircom):
		# Si la variable "vircom" esta en 1, se enviara el mensaje a la pantalla del equipo (VIRCOM)
		# Si no, se considerara un mensaje interno (VIRLOC)
		DB = db()
		d = DB.sqlSelect("ip,puerto", "equipos", "id=%s" % id_virloc)
		for ip, port in d.fetchall():
			addr = (ip, int(port))
			#print addr

		id_mensaje = self.getIDMensaje()
		id_mensaje_hex = hex(int(id_mensaje)).replace("0x","").zfill(4)
		if int(vircom) == 1:
			# Mensaje para el vircom sin formato
			respuesta = ">%s;ID=%sV;#%s;" % (mensaje,id_virloc,id_mensaje)
		else:
			# Mensaje interno para el virloc
			respuesta = ">%s;ID=%s;#%s;" % (mensaje,id_virloc,id_mensaje)
	
		# Comentario 1: Como el mensaje es para el VIRCOM (pantalla) y no para el virloc, entonces se pone una "V" al final del id
		#respuesta = ">SMT%s;ID=%s;%s;" % (id_mensaje,id_virloc,mensaje)
		respuesta += "*%s<\r\n" % calculateChecksum(respuesta)
		DB.sqlInsert("mensajes", "id_mensaje=%s, id_mensaje_hex='%s', mensaje='%s', id_virloc=%s" % (id_mensaje, id_mensaje_hex, mensaje, id_virloc))
		#exit()
		print "Enviando: %s a %s:%s" % (respuesta, ip, port)
		UDPSock = socket(AF_INET, SOCK_DGRAM)
		UDPSock.setsockopt(SOL_SOCKET, SO_REUSEADDR, 1)
		UDPSock.bind(('', 4097))

		#print respuesta
		#print addr
		UDPSock.sendto(respuesta, addr)
		UDPSock.close()
