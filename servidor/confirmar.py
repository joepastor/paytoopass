import sys

sys.path.insert(1, "..")
import SOAPpy
url = 'https://merchant.paytoo.info/api/merchant/?wsdl'
merchant_id = '66395537'
api_password = 'pruebatest'
proxy = SOAPpy.WSDL.Proxy(url)
token = proxy.auth(merchant_id, api_password)
proxy.ConfirmTransaction(10226,'888888')

