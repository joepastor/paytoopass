#!/usr/bin/env python
# -*- coding: utf-8 -*-
# Este archivo procesa los datos en la tabla mensajes y los envia

import os
from time import sleep
from XVM import *
from db import *

DB = db()
xvm = XVM()
while(1):
	sleep(1)
	# Comienzo a consultar todos los mensajes que no han sido procesados
	rs = DB.sqlSelect("id","mensajes","enviado=0")
	for id in rs.fetchall():
		xvm.sendQueuedMsg(id)