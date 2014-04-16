#!/usr/bin/env python

#Agente que "escucha" en el puerto especificado el envio de paquetes por parte de los virlocs
#El objetivo de este script es recibir la informacion lo mas rapido posible e insertarlo
#en la base de datos, para que luego otro proceso se encargue de analizar y "cocinar" los datos.exec
#
#Se hace de esta forma, para evitar que por demoras en procesos internos, se vea demorada la escucha
#de paquetes y optimizar asi el transito de la informacion

from socket import *
import os
from time import sleep
from db import *
from sys import argv
from checksum import *

# Librerias solo utiles para el debugger
from datetime import *
import random

os.system("clear")

debug=0
if len(sys.argv)>1:
    if sys.argv[1]=="debug":
        debug=1

host=''
port=4097
buf=2048

address = (host, port)
server_socket = socket(AF_INET, SOCK_DGRAM)
server_socket.bind(address)

while(1):
	if debug == 0:
		# Si no es debug, entonces escucho el puerto y almaceno en una variable el resultado obtenido
		print "Oyendo...."
		recv_data, address = server_socket.recvfrom(2048)
		host = address[0]
		port = address[1]
		buf = 1024
		addr = (host,port)
	else:
		# Si es debug, entonces genero un paquete arbitrario para poder utilizar a fines de analisis
		print "Debug mode, ACTIVATED"

		sleep(1)
		recv_data=">RTT200113194747-3462877-05837157000155300DF5800 04080810 000 00000038005104271880;ID=1234;#0088;*5F<" #CASA
		recv_data=">RTT230113141347-3462886-05837142000135300DF5801 04080812 000 00000038005104261805;ID=1234;#07C5;*22<" #CASA
		recv_data=">RGP311004123911-3460487-058625120781373007F0000;.....<" # Ejemplo Virloc
		recv_data=">RGP311004123911-3460487-058625120781373007F0000;ID=1234;#07C5;*22<" # Ejemplo VirLoc Corregido
		recv_data=">RTT230113141347-3462886-05837142000135300DF5801 04080812 000 00000038005104261805;ID=1234;#07C5;*22<" #CASA
		recv_data=">RTT230113141347-3462886-05837142000135300DF5801 04080812 000 00000038005104261805;ID=1234;#07C5;*22<" #CASA
		recv_data=">RTT%s-3462886-058371420%s135300DF5801 04080812 000 00000038005104261805;ID=%s;#07C5;*22<" % (datetime.now().strftime("%d%m%y%H%M%S"),random.choice(range(10,95)),random.choice(range(1235,1237))) # POSSSSTA

		# Paquetes de ejemplo del desarrollo de Cristian
		recv_data=">RUS01,080513154955,8888----"
		recv_data=">RUS02,080513154955,8888----,00001,0000.000,6565444------- 1,123.78-,1"

		recv_data=">RGP310513205510-3460484-058625100000003005F0001;ID=0000;#000A;*51<"
		recv_data=">RGV04080809-3460484-0586251000000001  GT507333 GW1742 040004000400 GM41;ID=0000;#000B;*36<"
		recv_data=">RVR VIRTUALTEC VIRLOC10 BR156 VL10_135B32 FVR135 VR135 FT2EON GPSFW:;ID=0000;#000D;*74<"
		recv_data=">RAD00 310513205527 0009003800510000000004271376;ID=0000;#000C;*34<"
		recv_data=">RGV04080809-3460484-0586251000000001  GT507333 GW1742 040004000400 GM41;ID=0000;#000B;*36<"
		recv_data=">RBU GR00;ID=0000;#000F;*10<"
		recv_data=">RUS03,080513161927,8888----,00001,0000.000,08587726--------,1010101---------,7.89-,2;ID=0000;#000B;*36<"
		recv_data=">RUS03,080513161927,8888----,00001,0000.000,08316484--------,1010101---------,7.89-,2;ID=0000;#000B;*36<"

		#Lo que hay que enviar es: >ACK;ID=<virlocid>;<nro_mensaje>;<checksum><"
		#id y numero de mensaje #000f

		host = "0.0.0.0"
		port = "4197"
		buf = 1024
		addr = (host,port)

	print recv_data
	array=recv_data.split(";")
	id_virloc=array[1]
	id_mensaje=array[2]

	respuesta=">ACK;%s;%s;" % (id_virloc,id_mensaje)
	respuesta+=calculateChecksum(respuesta)
	print "Respondiendo %s a %s:%s" % (respuesta,host,port)
	if debug==0:
		print "Respondiendo"
		UDPSock = socket(AF_INET,SOCK_DGRAM)
		UDPSock.sendto(respuesta,addr)


	# Apenas recibo informacion la inserto en la base de datos
	DB = db()
	DB.sqlInsert("crudo","info='%s',host='%s',port=%s" % (recv_data,host,port))
	DB.sqlInsertOrUpdate("equipos","id=%s,ip='%s',puerto=%s" % (id_virloc.replace('ID=', ''),host,port),"ip='%s',puerto=%s" % (host,port))
	DB.Close;
	# ---
