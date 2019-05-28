# saveAWSData.py
import sys
import crawler
import ast
import pymysql
import DBConnection
# from datetime import datetime

if len(sys.argv) < 3:
	print("python3", sys.argv[0], "searchDate time")
else:
	crawler = crawler.RF_Crawler()
	beforeValue = '0'
	today = sys.argv[1]
	daySplit = today.split('-')
	searchDate = daySplit[0] + daySplit[1] + daySplit[2]
	sdtime = sys.argv[2] + '00'
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
		
		locnum = strnum

		sql = "select * from measure_station where mss_name like '" + locname + "%'"
		cur.execute(sql)
		result = cur.fetchall()
		length = len(result)

		if length == 0:
			continue
		
		saveList = []
		startTime = ''

		for id in range(length):
			mss_code = result[id][0]

			sql = "select create_time from AWSData where mss_code = " + str(mss_code)
			sql += " and create_date = '" + today + "' order by create_time desc"
			
			cur.execute(sql)
			ctime = cur.fetchone()
			
			if ctime is None:
				startTime = 1 
			else:
				startTime = int(ctime)
			
			saveList.append([mss_code, startTime])
		
			
		param = searchDate + sdtime + '&' + beforeValue + '&' + measureBS + '&' + locnum + '&' + restParam
		crawler.setProperty(3, param)
		soup = crawler.getSoup(2)
		data = soup.tr.table.contents
		
		for i in range(len(data)):
			try:
				data.remove('\n')
			except:
				pass

		for i in range(len(saveList)):
			for j in range(1, len(data) + 1):
				
	cur.close()
	con.close()
