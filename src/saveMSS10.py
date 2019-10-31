#지역번호별 측정소 저장 스크립트

import re
import ast
import pymysql
import requests
from bs4 import BeautifulSoup

url = None

host = 'localhost'
user = 'root'
pswd = '123123'
dbname = 'ReallyFine'
chrset = 'utf8'

connection = pymysql.connect(host=host, user=user, password=pswd, db=dbname, charset=chrset)
searchDate = "2019-10-01"
searchDate_f = "201910"

try:
	#cur : 커서 변수
	#sql_dist : district table 내부에 있는 데이터 검색 쿼리
	#district : 지역번호별 데이터
	#p_size : airkorea 데이터 사이즈
	cur = connection.cursor()
	sql_dist = "select dt_code, dt_name from district"
	cur.execute(sql_dist)
	district = cur.fetchall()
	p_size = 26
	dis_index = 0
	mss_name_list = []
	mss_loc = ""	

	#district 확인
	print("다음 지역들의 측정소 넘버를 조회합니다.\n",  district)
	print("총 지역 검색 수 : ", len(district))

	while dis_index < len(district):
		dt_code = district[dis_index][0]
		dt_name = district[dis_index][1]
		url = "https://www.airkorea.or.kr/web/pmRelaySub?strDateDiv=1&searchDate=" + searchDate + "&district=" + dt_code + "&itemCode=10007&searchDate_f=" + searchDate_f
		print("dis_index = ", dis_index)
		print("dt_code : ", dt_code, "/ dt_name : ", dt_name)
		print("search url : ", url)
		
		#크롤링
		req = requests.get(url)
		req.encoding = "euc-kr"
		html = req.content
		soup = BeautifulSoup(html, 'html.parser')
		elements = soup.find_all({'td'})
		dis_index = dis_index + 1

		#측정소별 리스트 생성
		mss_size = len(elements) / p_size
		print("mss_size : ", mss_size)

		#테이블 데이터 로딩 오류 점검
		if mss_size < 1:
			dis_index = dis_index - 1
			print("테이블 데이터 로딩 실패. try data loading again...")
			continue
		
		#전체 데이터 확인
		#print(elements)

		#측정소 이름 확인
		for i in range(int(mss_size)):
			mss_loc = elements[(i * p_size)].text
			mss_data = elements[(i * p_size) + 1].text
			#print(mss_data)
			
			#측정소망 이름만 추출
			pattern = re.compile('^\[.*\]')
			match = pattern.match(mss_data)
			start_index = len(match.group())
			end_index = len(match.string)
			mss_name = match.string[start_index:end_index]
			print(mss_loc, " : ", mss_name)
			print("mss match string : ", match.string)
			print("match group : ", match.group())
			print("match names : ", mss_name)

			#DB에 저장
			try:
				sql_check_mss = "select * from measure_station where mss_name like '%" + mss_name + "%' and dt_code = '" + dt_code + "';"
				#print(sql_check_mss)
				cur.execute(sql_check_mss)
				tmp = cur.fetchone()
				
				#print(tmp)
				if tmp == None:
					sql_save_mss = "insert into measure_station(mss_name, mss_location, dt_code) values (%s, %s, %s)"
					cur.execute(sql_save_mss, (mss_name, mss_loc, dt_code))
			finally:
				pass
finally:
	connection.commit()
	connection.close()
