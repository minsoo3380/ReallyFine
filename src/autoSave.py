import os
import sys
from datetime import datetime

startIDX = 1
endIDX = 0
month = ''

if len(sys.argv) == 2:
	endIDX = int(sys.argv[1])
	month = '0' + str(date.month)

if len(sys.argv) == 3:
	startIDX = int(sys.argv[1])
	endIDX = int(sys.argv[2])
	month = '0' + str(date.month)
	
if len(sys.argv) == 4:
	month = sys.argv[1]
	if int(month) < 10:
		month = '0' + month
	startIDX = int(sys.argv[2])
	endIDX = int(sys.argv[3])

date = datetime.now()

year = str(date.year)
hour = str(date.hour)

for index in range(startIDX, endIDX):
	if index < 10:
		day = '0' + str(index)
	else:
		day = str(index)

	searchDate = year + '-' + month + '-' + day
	hour = '24'

	command = "sudo python3 saveAWSData.py " + searchDate + " " + hour
	print(command)
	os.system(command)
