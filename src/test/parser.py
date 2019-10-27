# parser.py
import sys
import ast
import re
import pymysql
import DBConnection

class RF_Parser: 

	def __init__(self):
		self.section = ''
		self.dataType = ''
		self.district = ''
		self.searchDate = ''

	# defined save data arrange function
	def analysis(self, file_name):
		tag_list = self.dataRead(file_name)

		head = tag_list[0]
		head = ast.literal_eval(head)
	
		self.section = head['section']
		self.dataType = head['dataType']
		self.district = head['district']
		self.searchDate = head['searchDate']

		self.parsing(tag_list)
		

	def parsing(self, tag_list):
		if self.section == 'public':
			if self.dataType == 'pm25' or self.dataType == 'pm10':
				pattern = re.compile('^<td>')
				
				data_list = []

				for index in range(len(tag_list)):
					match = pattern.match(tag_list[index])
					
					if match is None:
						continue
			
					#print(tag_list[index][4:-6])
					data_list.append(tag_list[index][4:-6])
		
				size = int(len(data_list) / 26)

				save_result = self.saveData(1, data_list, size)

				if save_result == 2 or save_result == 1:
					print('start save measure data')
					save_result = self.saveData(2, data_list, size)
					if save_result == 1:
						print('success saved measure data')
		return 1


	
	# save data local DataBase
	# logic 1 : save measure station data
	# logic 2 : save public fine dust data of pm25
	# logic 3 : save public fine dust data of pm10
	# logic 4 : save public asw data 
	# logic 5 : save private fine dust data 

	# return value 1 is success data save in all case
	# return value 2 is alread input location data
	# return value 0 is failed save
	def saveData(self, logic, data_list, size):
		if logic == 1:
			db = DBConnection.DBCon()
			db.setAll('localhost', 'root', '123123', 'ReallyFine', 'utf8')
			conn = db.ConnDB()
			cur = conn.cursor()

			# find district code 
			dt_name = data_list[1][1:3]
			sql = "select count(dt_code) from measure_station where dt_code = '"
			sql += self.district + "'"
			cur.execute(sql)

			data_check = cur.fetchone()

			if data_check is not None and data_check[0] == size:
				return 2
			
			print('measure station data is not exist, first saved measure station data and then saved data_values')
			sql = "select dt_code from district where dt_name = '"
			sql += dt_name + "'"
			cur.execute(sql)
			dt_code = cur.fetchone()[0]
			
			pattern = re.compile(']')

			for index in range(size):
				i = index * 26
				location = data_list[i]
				station = data_list[i + 1]
				search_char = pattern.search(station)
				end = search_char.end()

				sql = "select * from measure_station where exists(select * from measure_station where dt_code = '"
				sql += self.district + "' and mss_name = '" + station[end:] + "')"
				cur.execute(sql)
				
				ex_check = cur.fetchone()

				if ex_check is not None:
					continue
				else:
					sql = "insert into measure_station(dt_code, mss_location, mss_name) values('"
					sql += dt_code + "', '" + location + "', '" + station[end:] + "');"
				
					cur.execute(sql)
				
			cur.close()
			conn.commit()
			conn.close()
			
			
			return 1

		elif logic == 2:
			db = DBConnection.DBCon()
			db.setAll('localhost', 'root', '123123', 'ReallyFine', 'utf8')
			conn = db.ConnDB()
			cur = conn.cursor()

			pattern = re.compile(']')

			for index in range(size):
				i = index * 26
				station = data_list[i + 1]
				search_char = pattern.search(station)
				end = search_char.end()
				
				sql = "select mss_code from measure_station where mss_name = '"
				sql += station[end:] + "'"

				cur.execute(sql)
				mss_code = cur.fetchone()[0]
				startIDX = self.StartRange(logic, mss_code)
				
				for j in range(startIDX, 26):
					time = j - 1 
					j = i + j

					if data_list[j] == '-':
						data_list[j] ='-1' 
					elif data_list[j] == '':
						break
					
					sql = "insert into public_data_" + self.dataType + "(mss_code, data_value, created_date, created_time) values("
					sql += str(mss_code) + ", " + data_list[j] + ", '" + self.searchDate + "', " + str(time) + ");"

					cur.execute(sql)


			cur.close()
			conn.commit()
			conn.close()

			return 1

		elif logic == 3:
			db = DBConnection.DBCon()
			db.setAll('localhost', 'root', '123123', 'ReallyFine', 'utf8')
			conn = db.ConnDB()
			cur = conn.cursor()

			pattern = re.compile(']')

			for index in range(size):
				i = index * 26
				station = data_list[i + 1]
				search_char = pattern.search(station)
				end = search_char.end()
				
				sql = "select mss_code from measure_station where mss_name = '"
				sql += station[end:] + "'"

				cur.execute(sql)
				mss_code = cur.fetchone()[0]
				startIDX = self.StartRange(logic, mss_code)

				for j in range(startIDX, 26):
					time = j - 1 
					j = i + j

					if data_list[j] == '-':
						data_list[j] ='-1' 
					elif data_list[j] == '':
						break
					
					sql = "insert into public_data_pm10(mss_code, data_value, created_date, created_time) values("
					sql += str(mss_code) + ", " + data_list[j] + ", '" + self.searchDate + "', " + str(time) + ");"
					cur.execute(sql)


			cur.close()
			conn.commit()
			conn.close()

			return 1

	def StartRange(self, logic, mss_code):
		if logic == 1 or logic == 2:
			db = DBConnection.DBCon()
			db.setAll('localhost', 'root', '123123', 'ReallyFine', 'utf8')
			conn = db.ConnDB()
			cur = conn.cursor()
			
			startIDX = 2

			sql = "select max(created_time) from public_data_" + self.dataType + " where created_date = '"
			sql += self.searchDate + "' and mss_code = '" + str(mss_code) + "'"
			
			cur.execute(sql)

			data = cur.fetchone()[0]
		
			if data is None:
				cur.close()
				conn.close()
				return startIDX
			else:
				startIDX = data + 2
				cur.close()
				conn.close()
				return startIDX


	def dataRead(self, file_name):
		file = open(file_name, 'r')

		tag_list = []

		while True:
			line = file.readline()

			if not line:
				break	

			tag_list.append(line)

		file.close()

		return tag_list
