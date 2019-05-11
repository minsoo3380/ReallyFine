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

		self.parsing()
		

	# def parsing(self):
		if self.section == 'public':
			if self.dataType == 'pm25' or self.dataType == 'pm10':
				#pattern = re.compile
		

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
