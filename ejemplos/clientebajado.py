# Client program

from socket import *
import sys

host = "localhost"
port = 4097
buf = 1024

host = sys.argv[1]
port  = int(sys.argv[2])

addr = (host,port)

print addr

UDPSock = socket(AF_INET,SOCK_DGRAM)

def_msg = "===Enter message to send to server===";
print "\n",def_msg


while (1):
    data = raw_input('>> ')
    if not data:
        break
    else:
        if(UDPSock.sendto(data,addr)):
            print "Sending message '",data,"'....."

UDPSock.close()
