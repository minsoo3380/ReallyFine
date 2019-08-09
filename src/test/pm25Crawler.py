# pm25Crawler.py 
# import requests
# from bs4 import BeautifulSoup

# Airkorea pm25 page url 
# url = 'https://www.airkorea.or.kr/web/pmRelay?itemCode=11008&pMENU_NO=109'

# pm25 table request url
# url = 'https://www.airkorea.or.kr/web/pmRelaySub?strDateDiv=1&searchDate=2019-04-30&district=02&itemCode=11008&searchDate_f=201904'

# getting html using requests.get
# req = requests.get(url)

# encoding requests data
# req.encoding = 'UTF-8'

# bring html document 
# html = req.text

# html doc check 
# print(html)

# use beautifulsoup
# soup = BeautifulSoup(html, 'html.parser')


# elements = soup.findAll({'tbody'})

# save table data
# file_name = "data/" + "pm25Result.txt" 
# print(file_name)
# output = open(file_name, 'w')

# for i in elements:
	# output.write(''.join(i))
	# text = i.get_text('\n', strip=True)
	# output.write(text)
	# print(text)



