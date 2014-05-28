#!/usr/bin/env python
import select
from time import sleep
from db import *
from paytoo import *
from XVM import *
from encriptacion import desencriptar,getDecryptedCard,getCard

DB = db()
xvm=XVM()

def is_luhn_valid(cc): #Parametro ejemplo 4896889802135
	# Algoritmo sacado de internet para verificar la validez de la tarjeta
    num = map(int, str(cc))
    return sum(num[::-2] + [sum(divmod(d * 2, 10)) for d in num[-2::-2]]) % 10 == 0

while(1):
	sleep(1)
	try:
		# Comienzo a consultar todos los movimientos que no han sido procesados
		rs = DB.sqlSelect('id,cuenta,id_chofer,monto,tipo_cobro,equipos_id,password','pagos','estado="PENDING"')
		restantes=rs.rowcount
	
		for id,cuenta,id_chofer,monto,tipo_cobro,id_virloc,password in rs.fetchall():
			#print id_virloc
			#print cuenta,monto
			transaccion = paytoo()
			estado=""
			mensaje=""
			if tipo_cobro=='WALLET':
				print "Procesando %s : $ %s para la Wallet %s" % (id,monto,cuenta)
				request=transaccion.cobrarWallet(monto,cuenta,password)
				print request
				if request.status=="TOSIGN":
					response2=transaccion.confirmar(response.request_id,password)
					print response2
					if response2.status == "OK":
						print "TODO GUEY MAN"
					else:
						print response2.status
						print response2.msg
	
			if tipo_cobro=='TARJETA':
				print "Procesando %s : $ %s para la tarjeta %s" % (id,monto,cuenta)
				
				Customer = {
				'email': 'jlopez.mail@gmail.com',
				'firstname': 'Joe',
				'lastname': 'Pastor',
				'address': 'Some Cool Street',
				'city': 'BUE',
				'zipcode': 'CA1143',
				'state': 'AR',
				'country': 'AR',
				'level': ''
				}

				if id_virloc == 5000:
					CreditCard = getDecryptedCard(cuenta,'000')
				else:
					CreditCard = getCard(cuenta,'000')	

				print CreditCard
						
				CreditCard = {
				'cc_type': 'VISA',
				'cc_holder_name': 'DEMO USER',
				'cc_number': '4444333322221111',
				'cc_cvv': '123',
				'cc_month': '04',
				'cc_year': '14'
				}
	
	
				request = transaccion.cobrarTarjeta(CreditCard,Customer,monto)
				estado=request.status
				mensaje=request.msg
				print "Transaccion ingresada. "
				print "Estado: %s Mensaje: %s" % (estado,mensaje)
				DB.sqlUpdate("pagos",'estado="%s",mensaje="%s"' % (estado,mensaje),"id=%s" % id)
	
			if request.status=="OK":
				xvm.sendMsgToQueue(id_virloc,'SSC26',1)
				print "Transaccion COMPLETADA"
				estado=request.status
				mensaje=request.msg
			
			if request.status=="ERROR":
				xvm.sendMsgToQueue(id_virloc,'SSC27',1)
				print "La transaccion no pudo ser completada"
				estado=request.status
				mensaje=request.msg
					
			if request.status=="PENDING":
				print "Necesario confirmar"
				xvm.sendMsgToQueue(id_virloc,'SSC26',1)
				if tipo_cobro=='TARJETA_PREAUTH':
					print "Intentando confirmar"
					request2 = transaccion.confirmar(request.request_id,password)
					estado=request2.status
					mensaje=request2.msg
					print request2.msg
					print request2.status
					
					if request2.status=="OK":
						xvm.sendMsgToQueue(id_virloc,'SSC26',1)
						print "Confirmacion COMPLETADA"
						DB.sqlUpdate("pagos","estado='%s',mensaje='%s'" % (request2.status,request2.msg),"id=%s" % id)
					else:
						xvm.sendMsgToQueue(id_virloc,'SSC27',1)
						print "La transaccion no pudo ser confirmada"
						DB.sqlUpdate("pagos","estado='%s',mensaje='%s'" % (request2.status,request2.msg),"id=%s" % id)
						xvm.sendMsgToQueue(id_virloc,'SMT0000000%s' % request2.msg,1)
				else:
					estado="TOSIGN"
	
			DB.sqlUpdate('pagos','estado="%s",mensaje="%s"' % (estado,mensaje),'id=%s' % id)
	
			restantes=restantes-1
			print "-"*50
	except ValueError,NameError:
		print ValueError,NameError
