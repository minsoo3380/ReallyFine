# parser.py
import sys
import ast
import re

class RF_Parser: 

	def __init__(self):
		self.section = ''
		self.dataType = ''

	# defined save data arrange function
	def analysis(self, file_name):
		tag_list = self.dataRead(file_name)

		head = tag_list[0]
		head = ast.literal_eval(head)
	
		self.section = head['section']
		self.dataType = head['dataType']

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

				for index in range(size):
					i = index * 26
					
					print(data_list[i + 1])
					#for j in range(26):
					
	
	# save data local DataBase
	# logic 1 : save measure station data
	# logic 2 : save public fine dust data of pm25
	# logic 3 : save public fine dust data of pm10
	# logic 4 : save public asw data 
	# logic 5 : save private fine dust data 
	def saveData(self, logic, data_list, size):
		if logic == 1:
			
		elif logic == 2 or logic == 3:
			data_range = 26
			
			


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
