#!/usr/bin/env python
# -*- coding: utf-8 -*-
import MySQLdb, sys

class db:
	def __init__(self):
		try:
			self.dataBase = MySQLdb.connect(host='127.0.0.1',user='root',passwd='',db='sarita')
			self.curs = self.dataBase.cursor()
		except MySQLdb.Error, e:
			print e
			sys.exit(0)

	def __ejecutar(self,sql):
		print sql
		try:
			self.curs.execute(sql)
			self.dataBase.commit()
			return self.curs
		except MySQLdb.Error,e:
			print "-" * 50
			print e
			print "-" * 50
			return e

	def sqlInsertOrUpdate(self, tabla, campos, update):
		sql = """INSERT INTO %s set %s ON DUPLICATE KEY UPDATE %s""" % (tabla, campos, update);
		self.__ejecutar(sql)

	def sqlInsert(self,tabla,campos):
		sql="""insert into %s set %s""" % (tabla,campos)
		self.__ejecutar(sql)

	def sqlSelect(self,campos,tabla,where,order=""):
		sql="""select %s from %s""" % (campos,tabla)
		if where != "":
			sql=sql+" where %s" % where
		if order !="":
			sql=sql+" order by %s" % order
		return self.__ejecutar(sql)

	def sqlUpdate(self,tabla,campos,where):
		sql="""update %s set %s where %s""" % (tabla,campos,where)
		self.__ejecutar(sql)

	def Close(self):
		self.curs.close()
