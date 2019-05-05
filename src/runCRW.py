# runCRW.py
import crawler
import sys

if len(sys.argv) < 3:
	print("pm2.5 data : filename 1 localNumber searchDate\n")
	print("pm10 data : filename 2 localNumber searchDate\n")
	print("AWS data : filename 3 \n")

else:
	crawler = crawler.RF_Crawler()
	param = {}
	section = sys.argv[1]
	
	if section == '1':
		param = {'strDateDiv':'1', 'searchDate':sys.argv[3], 'district':sys.argv[2], 'itemCode':'11008', 'searchDate_f':sys.argv[3].replace("-", "")[0:6]} 
		crawler.setProperty(1, param)
		crawler.printUrl()
		table = crawler.getTable()
		print(type(table))
