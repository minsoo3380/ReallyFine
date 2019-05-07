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
		table = crawler.getTable()
		str_table = str(table)
	
		# print(str_table)	
		# print("type : ", type(str_table))
	
		# file save for data checking
		head = {'section':'public', 'dataType':'pm25'}
		file_name = 'pm25Data.txt'
		output = open(file_name, 'w')
		output.write(str(head) + '\n')
		output.write(str_table)

		# call parser.py
