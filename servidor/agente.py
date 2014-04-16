#!/usr/bin/env python
# -*- coding: utf-8 -*-

#Agente que "escucha" en el puerto especificado el envio de paquetes por parte
# de los virlocs
#El objetivo de este script es recibir la informacion lo mas rapido posible e
#insertarlo en la base de datos, para que luego otro proceso se encargue de
#analizar y "cocinar" los datos.exec
#
#Se hace de esta forma, para evitar que por demoras en procesos internos,
#se vea demorada la escucha #de paquetes y optimizar asi el transito de la
#informacion

#TODO
#20130609	- Responder si el chofer está autorizado o no
#		- Enviar mensaje al virloc
#		- Listado de mensajes y algoritmo para el id hexadecimal
#		desde el 8000 al 65535 (8000 - FFFF)


from socket import *
import os
#from time import sleep
from db import *
from sys import argv
from checksum import *
from XVM import *

# Librerias solo utiles para el debugger
from datetime import *

os.system("clear")

debug = 0
if len(sys.argv) > 1:
    if sys.argv[1] == "debug":
        debug = 1

host = ''
port = 4097
buf = 1024
server_address = (host, port)

while(1):
	recv_data = ""
	address = ""
	if debug == 0:
		server_socket = socket(AF_INET, SOCK_DGRAM)
		server_socket.setsockopt(SOL_SOCKET, SO_REUSEADDR, 1)
		server_socket.bind(server_address)

		# Si no es debug, entonces escucho el puerto y almaceno en una variable el resultado obtenido
		print("Esperando informacion...")
		recv_data, address = server_socket.recvfrom(buf)
		server_socket.close()
		host = address[0]
		port = address[1]
		addr = (host,port)
	else:
		# Si es debug, entonces genero un paquete arbitrario para poder utilizar a fines de analisis
		print("Debug mode, ACTIVATED")
		x = XVM()
		x.enviarMensaje(1234,"Enviando Mensaje")
		recv_data=input("Ingrese Paquete: ")
		#>RUS02,080513154955,8888----,00001,0000.000,6565444------- 1,123.78-,1
		#>RUS03,080513161927,8888----,00001,0000.000,08316484--------,1010101---------,7.89-,2;ID=1234;#000B;*03<
		#>RUS01,080513154955,8888----;ID=0000;#000C;*36<
		#>RTT200113194747-3462877-05837157000155300DF5800 04080810 000 00000038005104271880;ID=1234;#0088;*5F<
		#>RTT230113141347-3462886-05837142000135300DF5801 04080812 000 00000038005104261805;ID=1234;#07C5;*22<

		# Paquetes de ejemplo del desarrollo de Cristian
		#>RGV04080809-3460484-0586251000000001  GT507333 GW1742 040004000400 GM41;ID=0000;#000B;*36<
		#>RVR VIRTUALTEC VIRLOC10 BR156 VL10_135B32 FVR135 VR135 FT2EON GPSFW:;ID=0000;#000D;*74<
		#>RGV04080809-3460484-0586251000000001  GT507333 GW1742 040004000400 GM41;ID=0000;#000B;*36<
		#>RBU GR00;ID=0000;#000F;*10<
		#>RAD00 310513205527 0009003800510000000004271376;ID=0000;#000C;*34<
		#>RGP310513205510-3460484-058625100000003005F0001;ID=0000;#000A;*51<
		host = "0.0.0.1"
		port = "4097"
		addr = (host,port)

	print(recv_data)
	array=recv_data.split(";")
	id_virloc=array[1].split("=")[1]
	id_mensaje=array[2]
	checksum=array[3]

	if validateChecksum(recv_data):
		# Apenas recibo informacion la inserto en la base de datos
		DB = db()
		DB.sqlInsert("crudo","info='%s',host='%s',port=%s" % (recv_data,host,port))
		DB.Close;
		# ---

		if debug == 0:
			respuesta=">ACK;ID=%s;%s;" % (id_virloc,id_mensaje)
			respuesta+="*%s<\r\n" % calculateChecksum(respuesta)
			print(("Respondiendo a %s:%s | %s" % (host,port,respuesta)))
			UDPSock = socket(AF_INET,SOCK_DGRAM)
			#UDPSock.setsockopt(SOL_SOCKET, SO_BROADCAST, 1)
			UDPSock.bind(('',4097))
			UDPSock.sendto(respuesta,addr)
			UDPSock.close()

	else:
		print " ¡ERROR DE CHECKSUM! " * 3

	# En esta parte, envio los mensajes que estén encolados si es que existen
