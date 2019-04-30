# parser.py 
import requests
import json
from bs4 import BeautifulSoup

# URL
# url = 'https://www.airkorea.or.kr/web/pmRelay?itemCode=11008&pMENU_NO=109'

# network table request url
json_url = 'https://www.airkorea.or.kr/web/pmRelaySub?strDateDiv=1&searchDate=2019-04-30&district=02&itemCode=11008&searchDate_f=201904'

# convert json text what getting json obj
json_string = requests.get(json_url).text
print(json_string)

# load data using json module
data_list = json.loads(json_string)

# print(data_list)
