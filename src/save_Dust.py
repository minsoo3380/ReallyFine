#!//usr/lib/python3.5

# 미세먼지 저장 스크립트 
# pm10, pm2.5 둘다 저장
# argv[1], argv[2] 날짜 지정

import os
import sys
from datetime import datetime
from datetime import date

# len(argv) == 1 : 오늘 날짜 저장
# len(argv) == 2 : 특정 날짜 저장
# len(argv) == 3 : 앞의 날짜 부터 뒤의 날짜까지 저장

function = 0
base_cmd10 = "sudo python3 /var/www/html/ReallyFine/src/web_pm10.py "
base_cmd25 = "sudo python3 /var/www/html/ReallyFine/src/web_pm25.py "
command = []

def IsLeapYear(year):
	if year % 4 == 0:
		if year % 100 == 0:
			if year % 400 == 0:
				return True
			else:
				return False
		else:
			return True
	else:
		return False


if len(sys.argv) == 1:
	command.append(base_cmd10)
	command.append(base_cmd25)

elif len(sys.argv) == 2:
	command.append(base_cmd10 + sys.argv[1])
	command.append(base_cmd25 + sys.argv[1])
elif len(sys.argv) == 3:
	function = 1
	str_month = ''
	str_date = ''

	startDate = date(int(sys.argv[1][0:4]), int(sys.argv[1][5:7]), int(sys.argv[1][8:10]))
	endDate = date(int(sys.argv[2][0:4]), int(sys.argv[2][5:7]), int(sys.argv[2][8:10]))
	#print(startDate, endDate)

	# 날짜 차이값 계산
	delta = endDate - startDate
	#print(delta.days)

	# 데이터 초기화
	year = int(sys.argv[1][0:4])
	month = int(sys.argv[1][5:7])
	date = int(sys.argv[1][8:10])

	months = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]

	for index in range(delta.days + 1):
		searchDate = ''

		if IsLeapYear(year) == True:
			months[1] = months[1] + 1
		else:
			if months[1] == 29:
				months[1] -= 1
		if index == 0:
			date += index
		else:
			date += 1

		print("year : ", year, " month : ", month, " date: ", date)
		
		if months[month - 1] < date:
			print("m : ", months[month -1 ], "d : ", date)
			date -= months[month -1]
			month += 1
			
			if month == 13:
				month -= 12
				year += 1
		if date < 10:
			str_date = '0' + str(date)
		else:
			str_date = str(date)
		if month < 10:
			str_month = '0' + str(month)
		else:
			str_month = str(month)
		
		searchDate += str(year) + "-" + str_month + "-" + str_date

		#print(searchDate)
		command.append("sudo python3 web_pm10.py " + searchDate)
		command.append("sudo python3 web_pm25.py " + searchDate)
		
		for i in range(2):
			os.system(command[i])

		command = []

if function == 0:
	for index in range(2):
		os.system(command[index])
