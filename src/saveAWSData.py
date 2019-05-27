# saveAWSData.py
import sys
import crawler
import ast
import re
import pymysql
import DBConnection

if len(sys.argv) < 2:
	print("python3", sys.argv[0], "searchDate")
else:
	crawler = crawler.RF_Crawler()
	searchDate = sys.argv[1]
	beforeValue = '0'
	measureBS = 'MINDB_60M'
	restParam = 'a&K'

	db = DBConnection.DBCon()
	db.setAll('localhost', 'root', '123123', 'ReallyFine', 'utf8')
	con = db.ConnDB()
	cur = con.cursor()
	
	sql = "select * from AWSLocation as a"
	cur.execute(sql)
	
	result = cur.fetchall()
	length = len(result)
	awsloc = []

	for index in range(length):
		temp = [result[index][0], result[index][1]]
		awsloc.append(temp)

	# print(awsloc)
	cur.close()
	cur = con.cursor()

	for index in range(len(awsloc)):
		locnum = ''
		strnum = str(awsloc[index][0])

		if len(strnum) == 2:
			locnum = '40' + strnum
		else:
			locnum = '4' + strnum

		sql = "select * from measure_station where like '%" + awsloc[index][1] + '%'
		cur.execute(sql)
		result = cur.fetchall()

		if result in None:
			continue

		length = len(result)
		
		for id in range(length):
			param = 

	cur.close()
	con.close()
