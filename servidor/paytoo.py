import sys

sys.path.insert(1, "..")
import SOAPpy

class paytoo:
	url = 'https://merchant.paytoo.info/api/merchant/?wsdl'
	merchant_id = '66395537'
	api_password = 'pruebatest'
	
	def cobrarWallet(self,importe,cliente,password):
		print "Cobrando con wallet - %s al cliente %s:%s" % (importe,cliente,password)
		proxy = SOAPpy.WSDL.Proxy(self.url)
		token = proxy.auth(self.merchant_id, self.api_password)
		
		#response = proxy.SingleTransaction(cliente.zfill(8), '539482', importe, 'ARS', '1234', 'Order 1234');
		response = proxy.SingleTransaction(cliente.zfill(8), '123456', float(importe), 'ARS', '1234', 'Order 1234');
		print "ID: %s" % response.request_id
		return response

		proxy.logout()
	
	def cobrarTarjeta(self,tarjeta,cliente,importe):
		print "Cobrando con tarjeta - %s al cliente %s" % (importe,cliente)
		proxy = SOAPpy.WSDL.Proxy(self.url)
		token = proxy.auth(self.merchant_id, self.api_password)
		response = proxy.CreditCardSingleTransaction(tarjeta, cliente, float(importe), 'ARS', '1234', 'Order 1234');
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