# crawler.py
# defined html crawling class
import requests
from bs4 import BeautifulSoup

class RF_Crawler:
	def __init__(self):
		self.url = ''
		self.requestParam = ''	
		self.elements = ''	
	
	def setProperty(self, section, param):
		if section == 1 or section == 2:
			self.url = 'https://www.airkorea.or.kr/web/pmRelaySub?'
			self.url = self.setUrl(self.url, param)
		elif section == 3:
			self.url = 'https://www.weather.go.kr/cgi-bin/aws/nph-aws_txt_min_cal_test?'
			self.setUrl(section, self.url, param)


	def setUrl(self, section, url, param):
		if section == 1 or section == 2:
			for key in param:
				value = param[key]
				self.requestParam += key + '=' + value
				if value != list(param.values())[-1]:
					self.requestParam += '&'

			self.url += self.requestParam

			return self.url
		elif section == 3:
			self.url += param


	def printUrl(self):
		print("url : ", self.url)

	
	# get html tag elements
	# option tag : tag name
	# type : 1 - text, 2 - string
	def getAllElements(self, tag, type):
		req = requests.get(self.url)
	
		if type == 1:
			html = req.text
		elif type == 2:
			html = req.content
		
		soup = BeautifulSoup(html, 'html.parser')
		self.elements = soup.findAll({tag})

		return self.elements


	def getSoup(self):
		req = requests.get(self.url)
		html = req.content
		soup = BeautifulSoup(html, 'html.parser')
		
		return soup

	def getTbody(self):
		req = requests.get(self.url)
		html = req.text
		soup = BeautifulSoup(html, 'html.parser')
		self.elements = soup.findAll({'tbody'})

		return self.elements


	def getTable(self):
		req = requests.get(self.url)
		html = req.content
		soup = BeautifulSoup(html, 'html.parser')
		self.elements = soup.findAll({'table'})

		return self.elements


	def printObj(self):
		print("url : ", self.url)
		print("requestParam : ", self.requestParam)
		print("elements : ", self.elements)
		print("elements type : ", type(self.elements))


