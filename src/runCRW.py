# runCRW.py
import crawler
import parser
import sys

if len(sys.argv) < 3:
	print("pm2.5 data : filename 1 localNumber searchDate\n")
	print("pm10 data : filename 2 localNumber searchDate\n")
	print("AWS data : filename 3 searchDate beforeValue measureBS locationCode\n")

else:
	crawler = crawler.RF_Crawler()
	param = {}
	section = sys.argv[1]
	file_name = ''
	
	# section 1 : pm25 fine dust crawler runable
	if section == '1':
		strDateDiv = '1'
		searchDate = sys.argv[3]
		district = sys.argv[2]
		itemCode = '11008'
		searchDate_f = sys.argv[3].replace("-", "")[0:6]
		file_name = '../doc/pm25Data.txt'
		dataType = 'pm25'
		p_section = 'public'
		param = {'strDateDiv':strDateDiv, 'searchDate':searchDate, 'district':district, 'itemCode':itemCode, 'searchDate_f':searchDate_f} 
	elif section == '2':
		strDateDiv = '1'
		searchDate = sys.argv[3]
		district = sys.argv[2]
		itemCode = '10007'
		searchDate_f = sys.argv[3].replace("-", "")[0:6]
		file_name = '../doc/pm10Data.txt'
		dataType = 'pm10'
		p_section = 'public'
		param = {'strDateDiv':strDateDiv, 'searchDate':searchDate, 'district':district, 'itemCode':itemCode, 'searchDate_f':searchDate_f} 
	elif section == '3':
		searchDate = sys.argv[2]
		beforeValue = sys.argv[3]
		measureBS = 'MINDB_60M'
		locationCode = sys.atgv[4]
		district = ''

	crawler.setProperty(int(section), param)
	table = crawler.getTbody()
	str_table = str(table)
	
	# print(str_table)	
	# print("type : ", type(str_table))
	
	# file save for data checking
	head = {'section':p_section, 'dataType':dataType, 'district':district, 'searchDate':searchDate}
	output = open(file_name, 'w')
	output.write(str(head) + '\n')
	output.write(str_table)
	output.close()

	# call data parser
	parser = parser.RF_Parser()
	parser.analysis(file_name)
