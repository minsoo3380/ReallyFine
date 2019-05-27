# saveAWSData.py
import sys
import crawler
import ast
import pymysql
import DBConnection
# from datetime import datetime

if len(sys.argv) < 2:
	print("python3", sys.argv[0], "searchDate")
else:
	crawler = crawler.RF_Crawler()
	beforeValue = '0'
	searchDate = sys.argv[1]
	measureBS = 'MINDB_60M'
	restParam = 'K'
	#date = datetime.now()

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
		locname = awsloc[index][1]
		
		if locname[-1] == '*':
			locname = locname[0:-2]
		
		'''
		if len(strnum) == 2:
			locnum = '40' + strnum
		else:
			locnum = '4' + strnum
		'''

		locnum = strnum

		sql = "select * from measure_station where mss_name like '" + locname + "%'"
		cur.execute(sql)
		result = cur.fetchall()

		if len(result) == 0:
			continue
		
		param = searchDate + '&' + beforeValue + '&' + measureBS + '&' + locnum + '&' + restParam
		crawler.setProperty(3, param)
		soup = crawler.getSoup()
		

		'''
		year = str(date.year)
		month = date.month

		if month < 10:
			month = '0' + str(month)
		else:
			month = str(month)

		day = date.day

		if day < 10:
			day = '0' + str(day)
		else:
			day = str(day)
		
		today = year + '-' + month + '-' + day
		endpoint = date.hour

		length = len(result)
		
		for id in range(length):
			mss_code = result[id][0]
			sql = "select create_time from AWSData where mss_code = " + str(mss_code) + " and create_date = '" + today + "' order by create_time desc"
			cur.execute(sql)
			
			startpoint = cur.fetchone()

			if startpoint is None:
				startpoint = 1
			else:
				startpoint = startpoint[0]

			for i in range(startpoint, endpoint + 1):
				hour = i
				if hour < 10:
					hour = '0' + str(hour)
				else:
					hour = str(hour)

				searchDate = year + month + day + hour
				print(searchDate)
				param = searchDate + '&' + beforeValue + '&' + measureBS + '&' + locnum + '&' + restParam

				url = crawler.setProperty(3, param)
				print(crawler.url)

				soup = crawler.getSoup()
		'''
	cur.close()
	con.close()
