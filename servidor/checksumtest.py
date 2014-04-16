from XVM import *

def calculateChecksum(texto):
        calc=0
        for k in range (len(texto)):
            if texto[k] == "*":
                break
            caracter = texto[k]
            calc = calc ^ ord(caracter)
	calculatedChecksum = hex(calc).replace('0x', '')
        return calculatedChecksum

def validateChecksum(texto):
	stringArray=texto.split("*")
	if len(stringArray)!=2:
		return 0
	data = stringArray[0]
	if len(stringArray[1]) < 3 or stringArray[1][2:3] != "<":
		return 0;
	message_checksum = stringArray[1][0:2]
	calculated_checksum = calculateChecksum(data)
	return calculated_checksum == message_checksum

recv_data=">RGV04080809-3460484-0586251000000001  GT507333 GW1742 040004000400 GM41;ID=0000;#000B;*36<"
recv_data=">RBU GR00;ID=0000;#000F;*10<"
print calculateChecksum(recv_data)
print validateChecksum(recv_data)
