def calculateChecksum(texto):
	calc=0
	for k in range (len(texto)):
		if texto[k] == "*":
			break
		caracter = texto[k]
		calc = calc ^ ord(caracter)
	calculatedChecksum = hex(calc).replace('0x', '')
	if len(calculatedChecksum)<2:
		calculatedChecksum="0%s" % calculatedChecksum
	#return "*%s<\r\n" % calculatedChecksum.upper() Antes devolvia el string, pero lo cambiamos para que devuelva solo el numero. Fijarse si arma bien la respuesta en el agente
	return calculatedChecksum.upper()

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
