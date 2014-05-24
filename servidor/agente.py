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
#from time import sleep
from db import *
from sys import argv
from checksum import *
from XVM import *

# Librerias solo utiles para el debugger
from datetime import *

debug = 0
if len(sys.argv) > 1:
    if sys.argv[1] == "debug":
        debug = 1

host = ''
port = 4097
buf = 1024
server_address = (host, port)

while(1):
    try:
    	recv_data = ""
    	address = ""
        
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
    
    	array=recv_data.split(";")
    	id_virloc=array[1].split("=")[1]
    	id_mensaje=array[2]
    	checksum=array[3]
    
        if validateChecksum(recv_data):
            # Apenas recibo informacion la inserto en la base de datos
            DB = db()
            DB.sqlInsert('crudo','info="%s",host="%s",port=%s' % (recv_data,host,port))
            DB.Close;
            # ---
            print recv_data

            if recv_data[0:4] != ">ACK": # Si el paquete que recibono es un ACK entonces respondo ACK.
                # Esta parte no utiliza el enviador de mensajes para que sea lo mas rapido posible
                # El virloc se bloquea si no recibe respuesta de los mensajes que envia
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
    except ValueError,NameError:
        print ValueError,NameError