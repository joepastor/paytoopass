#!/usr/bin/env python
# -*- coding: utf-8 -*-

from XVM import *

xvm=XVM()

while 1:
	idvirloc = raw_input("Ingrese VIRLOC: ");
	mensaje = raw_input("Ingrese mensaje: ");
	pantalla = raw_input("Virloc = 0, Vircom = 1: ");
	mensaje = mensaje[:75]
	#mensaje = mensaje.ljust(95,"-")
	#respuesta = ">SMT00000000%s;" % (mensaje)
	xvm.enviarMensaje(idvirloc,mensaje,pantalla)
