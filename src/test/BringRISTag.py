import requests
from bs4 import BeautifulSoup

url = 'https://www.airkorea.or.kr/web/pmRelay?itemCode=11008&pMENU_NO=109'

req = requests.get(url)

html = req.text

soup = BeautifulSoup(html, 'html.parser')

elements = soup.findAll({'radio', 'input', 'select'})

for i in elements:
	print(i)

