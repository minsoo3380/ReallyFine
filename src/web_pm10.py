#현재 re를 이용하여 패턴 매치중에 작업 스톱
#가져온 html elements[0]의 next.next.를 확인중이었음
#홀 수번째의 next에는 개행문자가 삽입되어있기에 이거 제거하고 파싱하는게 필요
#2019-08-13 23:24분 저장시작
import re
import sys
import ast
import pymysql
import requests
from bs4 import BeautifulSoup
from datetime import datetime

url = None;
now = None;
year = None;
month = None;
day = None;

host = 'localhost'
user = 'root'
pswd = '123123'
dbname = 'ReallyFine'
chrset = 'utf8'

if len(sys.argv) == 2:
	#두 번째 인자 : 검색할 날짜
	now = sys.argv[1]
	year = now[0:4]
	month = now[5:7]
	day = now[8:10]
else:
	now = datetime.now()
	year = format(now.year, "4d")
	month = format(now.month, "02d")
	day = format(now.day, "02d")

searchDate = year + "-" + month + "-" + day
searchDate_f = searchDate.replace("-", "")[0:6]
connection = pymysql.connect(host=host, user=user, password=pswd, db=dbname, charset=chrset)

try:
	#db에서 지역번호 가져오고
	cur = connection.cursor()
	sql_dist = "select dt_code from district"
	cur.execute(sql_dist)
	district = cur.fetchall()
	
	#지역번호별 테이블 검색 및 저장
	for index in range(len(district)):
		dt_code = district[index][0]
		url = "https://www.airkorea.or.kr/web/pmRelaySub?strDateDiv=1&searchDate=" + searchDate + "&district=" + dt_code + "&itemCode=10007&searchDate_f=" + searchDate_f
		
		#크롤링
		req = requests.get(url)
		req.encoding = "euc-kr"
		html = req.content
		soup = BeautifulSoup(html, 'html.parser')
		elements = soup.findAll({'tbody'})

		pattern = re.compile('^<td>')
		
finally:
	connection.close()
