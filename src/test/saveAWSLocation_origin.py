# saveAWSLocation.py
import sys
import crawler
import ast
import re
import pymysql
import DBConnection

if len(sys.argv) < 3:
	print("python3", sys.argv[0], "searchDate beforeValue")
else:
	crawler = crawler.RF_Crawler()
	searchDate = sys.argv[2]
	beforeValue = sys.argv[3]
	measureBS = 'MINDB_60M'
	restParam = 'a&K'
	startLoc = 4090
	endLoc = 5090
	saveRange = endLoc - startLoc + 1

	db = DBConnection.DBCon()
	db.setAll('localhost', 'root', '123123', 'ReallyFine', 'utf8')
	con = db.ConnDB()
	cur = con.cursor()

	# save location data
	for index in range(saveRange):
		loc = startLoc + index
		param = searchDate + '&' + beforeValue + '&' + measureBS + '&' + str(loc) + '&' + restParam
		url = crawler.setProperty(3, param)
		elements = crawler.getAllElements('a', 2)
		print(elements)

		startPattern = re.compile('.*">')

		for index2 in range(0, len(elements), 2):
			str_ele = str(elements[index2])
			SNum_match = startPattern.match(str_ele)
			end = SNum_match.end()
			AWS_locNum = str_ele[end:-4]
		
			str_ele = str(elements[index2 + 1])
			SNum_match = startPattern.match(str_ele)
			end = SNum_match.end()
			AWS_locName = str_ele[end:-4]

			print(AWS_locNum, AWS_locName)
			sql = "select * from AWSLocation where AWS_code = " + AWS_locNum 
			cur.execute(sql)
			dtcheck = cur.fetchone()

			if dtcheck is not None:
				continue

			sql = "insert into AWSLocation(AWS_code, name) values(" + AWS_locNum + ", '" + AWS_locName + "');"
		
			print(sql)
			cur.execute(sql)
	
	cur.close()
	con.commit()
	con.close()
