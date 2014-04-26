#!/usr/bin/env python
import select
import os
from time import sleep
from db import *
from paytoo import *
from XVM import *
from encriptacion import desencriptar

os.system("clear")
DB = db()
xvm=XVM()

def is_luhn_valid(cc): #Parametro ejemplo 4896889802135
	# Algoritmo sacado de internet para verificar la validez de la tarjeta
    num = map(int, str(cc))
    return sum(num[::-2] + [sum(divmod(d * 2, 10)) for d in num[-2::-2]]) % 10 == 0

while(1):
	sleep(1)

	# Comienzo a consultar todos los movimientos que no han sido procesados
	rs = DB.sqlSelect('id,cuenta,id_chofer,monto,tipo_cobro,id_virloc,password','pagos','estado="PENDING"')
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

		if tipo_cobro=='TARJETA':
			print "Procesando %s : $ %s para la tarjeta %s" % (id,monto,cuenta)
			
			print desencriptar(cuenta)
			
			# Primer digito de tarjeta
			# 3 american
			# 4 visa
			# 5 mastercard
			# 6 Discover
			
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
			xvm.sendDirectMsg(id_virloc,'SSC26',1)
			print "Transaccion COMPLETADA"
			estado=request.status
			mensaje=request.msg
		
		if request.status=="ERROR":
			xvm.sendDirectMsg(id_virloc,'SSC27',1)
			print "La transaccion no pudo ser completada"
			estado=request.status
			mensaje=request.msg
			xvm.sendDirectMsg(id_virloc,mensaje,1)
				
		if request.status=="PENDING":
			print "Necesario confirmar"
			xvm.sendDirectMsg(id_virloc,'SSC26',1)
			if tipo_cobro=='TARJETA_PREAUTH':
				print "Intentando confirmar"
				request2 = transaccion.confirmar(request.request_id,password)
				estado=request2.status
				mensaje=request2.msg
				print request2.msg
				print request2.status
				
				if request2.status=="OK":
					xvm.sendDirectMsg(id_virloc,'SSC26',1)
					print "Confirmacion COMPLETADA"
					DB.sqlUpdate("pagos","estado='%s',mensaje='%s'" % (request2.status,request2.msg),"id=%s" % id)
				else:
					xvm.sendDirectMsg(id_virloc,'SSC27',1)
					print "La transaccion no pudo ser confirmada"
					#xvm.enviarMensaje(id_virloc,"SMT0000000%s" % request2.msg,1)
					DB.sqlUpdate("pagos","estado='%s',mensaje='%s'" % (request2.status,request2.msg),"id=%s" % id)
					xvm.sendDirectMsg(id_virloc,"SMT0000000%s" % request2.msg,1)
			else:
				estado="TOSIGN"

		DB.sqlUpdate('pagos','estado="%s",mensaje="%s"' % (estado,mensaje),'id=%s' % id)

		restantes=restantes-1
		print "-"*50
