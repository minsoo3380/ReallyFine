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
hour = None;
t_hour = None;

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

	tmp_now = datetime.now()
	hour = tmp_now.hour
else:
	now = datetime.now()
	year = format(now.year, "4d")
	month = format(now.month, "02d")
	day = format(now.day, "02d")
	hour = now.hour

t_hour = "t" + str(hour)

searchDate = year + "-" + month + "-" + day
searchDate_f = searchDate.replace("-", "")[0:6]
connection = pymysql.connect(host=host, user=user, password=pswd, db=dbname, charset=chrset)

# log : current time
print("===========================================================================")
print(sys.argv[0], " | current time :  ", searchDate)
#print(searchDate)

try:
	#db에서 지역번호 가져오고
	#cur : DB 커서 저장 변수
	#sql_ : sql 문장 저장 변수
	#distrcit : 지역 번호 fetch
	#p_size : 일일 데이터 사이즈
	#all_list : 총 데이터 저장 리스트
	#list_count : 지역별 측정소 총 카운트
	#dis_index : district count 변수
	#m_sizeL : 지역별 길이 리스트
	#dt_list : 최종 저장 리스트 <지역별 구분> [3차원 리스트]
	cur = connection.cursor()
	sql_dist = "select dt_code, dt_name from district"
	cur.execute(sql_dist)
	district = cur.fetchall()
	p_size = 26
	all_list = []
	data_list = []
	list_count = 0
	dis_index = 0	
	m_sizeL = []
	data_list = []

	#district 확인	
	print("district list : \n\n", district, "\n")

	#지역번호별 테이블 검색 및 저장
	while dis_index < len(district):
		mss_list = []
		dt_code = district[dis_index][0]
		dt_name = district[dis_index][1]
		url = "https://www.airkorea.or.kr/web/pmRelaySub?strDateDiv=1&searchDate=" + searchDate + "&district=" + dt_code + "&itemCode=11008&searchDate_f=" + searchDate_f

		#현재 지역 넘버, 네임 출력
		print("current working dt_code : ", dt_code, " / dt_name : ", dt_name)
		#print("current working dt_code : ", dt_code)
		
		#크롤링
		req = requests.get(url)
		req.encoding = "euc-kr"
		html = req.content
		soup = BeautifulSoup(html, 'html.parser')
		elements = soup.find_all({'td'})
		
		#파싱
		#측정소별 리스트 생성
		mss_size = int(len(elements) / p_size)
		m_sizeL.append(mss_size)
		dis_index = dis_index + 1		

		if mss_size < 1:
			dis_index = dis_index - 1
			print("테이블 데이터를 가져오는데 실패했습니다, 다시 요청합니다.")
			continue

		list_count += mss_size
		print("size of district mss : ", mss_size, "\n")
		
		#지역별 데이터 저장 리스트
		local_list = []	
		for i in range(int(mss_size)):
			mss_list = []
			
			# -1 : error measure data
			# -2 : don't measure yet
			for j in range(p_size):
				text_data = elements[(i * p_size) + j].text
				
				if text_data == "":
					#print("Null")
					mss_list.append(-2)
				elif text_data == "-":
					#print(text_data)
					mss_list.append(-1)
				else:
					mss_list.append(text_data)
					#print(text_data)

			local_list.append(mss_list)
			all_list.append(mss_list)
			#print("current mss_list : ", mss_list)
			
			
		#지역별 리스트 저장
		tmp_list = []
		tmp_list.append(dt_code)
		tmp_list.append(local_list)
		data_list.append(tmp_list)
		#print("\n")
	
	print("All data length : ", len(all_list), "Sum of list_count : ", list_count)
	#print("All data list : ", data_list)

	#data_list의 데이터 하나씩 DB에 저장
	#검색 년월일에 해당 데이터 유무 검사
	#그냥 위에서 바로바로 insert or update 하는 걸로 변경
	#놉 생각해보니,,여기서 데이터 전체 데이터 길이가 안 맞으면 저장하면 안 되니까 아래에서 따로 저장
	#최종 데이터 셋 : data_list
	#data_list[x] : 지역별 리스트
	#data_list[x][y] : 해당 지역 데이터 | y = 0 : 지역번호, y = 1 : 측정데이터셋
	#data_list[x][1][z] : 각 데이터 셋별 세부 데이터

	if len(all_list) != int(list_count):
		print("error for data loading or parsing, please restart this program")
	elif len(all_list) == int(list_count):
		print("wait for second")

	
	#하나씩 저장
	#형식 : mss_code(%s), create_date(%s), 24개소 데이터(%d)
	
	# mss_code => data_list[x][0] : 지역번호 & data_list[x][1][1] : 측정소망 (지역명 추출만해서)
	# DB measure_station에서 가져옴

	# len(data_list) == len(district) 지역개수
	# len(data_list[x][1]) == 측정소망 개수
	# len(data_list[x][1][z]) == 26 <local, mss, data set[24]>
	
	# 측정소 지역명칭 추출 패턴
	pattern = re.compile('^\[.*\]')
	
	for dist_len in range(len(data_list)):
		dt_code = data_list[dist_len][0]
		#print(dt_code)
		
		for mss_len in range(len(data_list[dist_len][1])):
			#print(data_list[dist_len][1][mss_len])
			#t_data : 현재 시간 측정된 데이터
			pre_mss_name = data_list[dist_len][1][mss_len][1]

			#현재의 데이터를 저장하는 방식은 차후에 업데이트시 해당 컬럼만
			#업데이트 되는 문제가 발생함
			#고로 전체 업데이트 쿼리로 변경
			#print("current hour : ", hour)
			#print("cur data : ", data_list[dist_len][1][mss_len]) 
			#t_data = data_list[dist_len][1][mss_len][hour + 1]
			#print("t_data : ", t_data)			

			#패턴 매칭
			match = pattern.match(pre_mss_name)
			start_index = len(match.group())
			end_index = len(match.string)
			mss_name = match.string[start_index:end_index]
			
			#print(mss_name)
			sql_find_msscode = "select mss_code from measure_station where dt_code = '"
			sql_find_msscode += dt_code + "' and mss_name like '%" + mss_name + "%'"
			#print("find_msscode sql : ", sql_find_msscode)
			
			try:
				cur.execute(sql_find_msscode)
				fetchset = cur.fetchone()
				mss_code = fetchset[0]
			except:
				print("there is not existence mss_code of ", mss_name, " check a DataBase")
				
				print("we found new measure statoin of fine dust")
				os.system("sudo python3 saveMSS25.py")
				print("save the new mss_location please restart this program")

			#print("mss_name : ", mss_name, "|| mss_code : ", mss_code)
			# 데이터가 없다면 insert 있다면 update!
			# how?
			sql_checkdata = "select * from web_pm25 where mss_code = " + str(mss_code) 
			sql_checkdata += " and created_date = '" + searchDate + "';"
			#print(sql_checkdata)
			cur.execute(sql_checkdata)
			fetchset = cur.fetchone()

			# tuple로 재구성
			data_tuple = []
			data_tuple.append(mss_code)
			data_tuple.append(searchDate)

			for index in range(2,26):
				#print(data_list[dist_len][1][mss_len][index])
				#print(type(data_list[dist_len][1][mss_len][index]))
				data_tuple.append(int(data_list[dist_len][1][mss_len][index]))

			#data_tuple = tuple(data_tuple)
			#print("data_tuple : ", data_tuple)	
			#print("mss_code type : ", type(mss_code))			

			if fetchset is None:
				#print("mss_code : ", mss_code, mss_name, " has not data set")
				sql_input = "insert into web_pm25(mss_code, created_date, t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12, t13, t14, t15, t16, t17, t18, t19, t20, t21, t22, t23, t24)"
				sql_input += " values (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
				#print(sql_input)

				#for i in range(26):
				#	print(data_list[dist_len][1][mss_len][i])
				#
				
				try:
					cur.execute(sql_input, data_tuple)
					print(mss_code, " : " , mss_name, " data insert successful")
				except Exception as ex:
					print("mss_code : ", mss_code, " mss_name : ", mss_name, " data don't insert for DataBase")
					print("Exception : ", ex)
					pass

			else:
				update_set = ""

				for index_update in range(2, 26):
					t_hour = "t" + str(index_update - 1)
					update_set += t_hour + " = " + str(data_list[dist_len][1][mss_len][index_update])
					if index_update != 25:
						update_set += ", "

				#print(update_set)
					
				sql_input = "update web_pm25 set " + update_set 
				sql_input += " where mss_code = " + str(mss_code)
				sql_input += " and created_date = '" + searchDate + "';"
		
				#print(sql_input)
				try:
					cur.execute(sql_input)
					print(mss_code, " : " , mss_name, " data update successful")
				except Exception as ex:
					print("mss_code : ", mss_code, " mss_name : ", mss_name, " data don't update for DataBase")
					print("Exception : ", ex)
					pass
			
		
finally:
	connection.commit()
	connection.close()
