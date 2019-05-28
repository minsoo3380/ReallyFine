import os
from datetime import datetime

date = datetime.now()

year = str(date.year)
month = '0' + str(date.month)
hour = str(date.hour)


for index in range(1, 29):
	if index < 10:
		day = '0' + str(index)
	else:
		day = str(index)

	searchDate = year + '-' + month + '-' + day
	hour = '24'

	command = "sudo python3 saveAWSData.py " + searchDate + " " + hour
	print(command)
	os.system(command)
