# parser.py 
import requests
from bs4 import BeautifulSoup

#URL
url = 'https://www.airkorea.or.kr/web/pmRelay?itemCode=11008&pMENU_NO=109'

# HTTP GET Request
req = requests.get(url)

# bring html source 
html = req.text

# print html
# print(html)

soup = BeautifulSoup(html, 'html.parser')

elements = soup.findAll({'script'})

for i in elements:
	print(i)
