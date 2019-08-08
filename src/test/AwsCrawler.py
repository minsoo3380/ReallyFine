# AwsCrawler.py 
import requests
from bs4 import BeautifulSoup

#URL
url = 'https://www.weather.go.kr/weather/observation/aws_table_popup.jsp'

# HTTP GET Request
req = requests.get(url)

# bring html source 
html = req.text

# print html
# print(html)

soup = BeautifulSoup(html, 'html.parser')

elements = soup.findAll({'thead', 'tbody'})

# write elements in fil
# crawling data of Aws Script tag 
# output = open("result/RS_AwsCrawler.txt", 'w') 
output = open("result/RS_AwsCrawler_table.txt", 'w')

for i in elements:
	output.write(''.join(i))
