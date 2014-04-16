from paytoo import *

print "Sistema de cobro"

importe=raw_input("Ingrese el valor a cobrar: ")
cliente=raw_input("Ingrese cliente: ")

transaccion = paytoo()
request=transaccion.cobrar(importe,cliente)
transaccion.confirmar(request.request_id,'888888')
