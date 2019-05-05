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
		elif section == '3':
			self.url = 'https://www.?'
			self.url = self.setUrl(self.url, param)


	def setUrl(self, url, param):
		for key in param:
			value = param[key]
			self.requestParam += key + '=' + value
			if value != list(param.values())[-1]:
				self.requestParam += '&'

		self.url += self.requestParam

		return self.url


	def printUrl(self):
		print("url : ", self.url)


	def getTable(self):
		req = requests.get(self.url)
		html = req.text
		soup = BeautifulSoup(html, 'html.parser')
		elements = soup.findAll({'tbody'})

		return elements

