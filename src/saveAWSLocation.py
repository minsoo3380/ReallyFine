# saveAWSLocation.py
import sys
import crawler

if len(sys.argv) < 3:
	print("python3", sys.argv[0], "searchDate beforeValue")
else:
	crawler = crawler.RF_Crawler()
	searchDate = sys.argv[1]
	beforeValue = sys.argv[2]
	measureBS = 'MINDB_60M'
	restParam = 'a&K'
	startLoc = 4090
	endLoc = 4143
	saveRange = endLoc - startLoc + 1

	for index in range(saveRange):
		loc = startLoc + index
		param = searchDate + '&' + beforeValue + '&' + measureBS + '&' + str(loc) + '&' + restParam
		url = crawler.setProperty(3, param)
		elements = crawler.getTable()
		print(elements)
