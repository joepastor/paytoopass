# Client program

from socket import *
from time import sleep

host = "localhost"
port = 4097
buf = 1024
addr = (host,port)
data = "RCVD"

UDPSock = socket(AF_INET,SOCK_DGRAM)


while (1):
        if(UDPSock.sendto(data,addr)):
            print "Sending message '",data,"'....."
	sleep(3)
UDPSock.close()
