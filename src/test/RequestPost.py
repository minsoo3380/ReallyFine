# parser.py 
import requests
from bs4 import BeautifulSoup

#URL
url = 'https://www.airkorea.or.kr/web/pmRelay?itemCode=11008&pMENU_NO=109'

# requests post data
data = {'pMENU_NO':'109', 'itemCode':'11008', 'yyyy':'2019', 'mm':'04', 'strDateDiv':'1', 'searchDate':'2019-04-29', 'searchDate_yyyy':'2019', 'searchDate_mm':'04', 'district':'02'}

res = requests.post(url, data=data)

html = res.text

print(html)
