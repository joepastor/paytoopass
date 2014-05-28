import sys

sys.path.insert(1, "..")
import SOAPpy
from random import randint

class paytoo:
	url = 'https://merchant.paytoo.info/api/merchant/?wsdl'
	url = 'https://go.paytoo.info/api/merchant/?wsdl'
	merchant_id = '97383913'
	api_password = 'testing'
	security_code = '777777' # Este codigo lo paso Michel. No sabemos exactamente que es, pero no es el codigo SMS
	def cobrarWallet(self,importe,cliente,password):
		print "Cobrando con wallet - %s al cliente %s:%s" % (importe,cliente,password)
		proxy = SOAPpy.WSDL.Proxy(self.url)
		token = proxy.auth(self.merchant_id, self.api_password)
		
		#response = proxy.SingleTransaction(cliente.zfill(8), '539482', importe, 'ARS', '1234', 'Order 1234');
		response = proxy.SingleTransaction(cliente.zfill(8), security_code, float(importe), 'USD', '1234', 'Order 1234');

		print "ID: %s" % response.request_id
		proxy.logout()
		return response
	
	def cobrarTarjeta(self,tarjeta,cliente,importe):
		print "Cobrando con tarjeta - %s al cliente %s %s" % (importe,tarjeta,cliente)
		ref_id = randint(1,9999) #Some Random Number
		print ref_id
		currency="USD"
		proxy = SOAPpy.WSDL.Proxy(self.url)
		token = proxy.auth(self.merchant_id, self.api_password)
		response = proxy.CreditCardSingleTransaction(tarjeta, cliente, float(importe), currency, ref_id, 'Order 1234');
		print "ID: %s" % response.request_id
		proxy.logout()
		return response

	def confirmar(self,request,password):
		print "Confirmando..."
		proxy = SOAPpy.WSDL.Proxy(self.url)
		token = proxy.auth(self.merchant_id, self.api_password)
		proceso = proxy.ConfirmTransaction(request,password)
		print "ID: %s" % proceso.request_id
		proxy.logout()
		return proceso