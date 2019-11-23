import sys
import pymysql
import requests

conn = pymysql.connect(host="localhost", user="root", password="123123", db="ReallyFine", charset="utf8")
cur = conn.cursor()

sql_bringKey = "select * from admin where id = 'minsoo3380';"
cur.execute(sql_bringKey)
key = cur.fetchone()[1]

sql_mss = "select * from measure_station"
cur.execute(sql_mss)
mss = cur.fetchall()

mss_len = len(mss)

request_url = "http://openapi.airkorea.or.kr/openapi/services/rest/MsrstnInfoInqireSvc/getMsrstnList?_returnType=json&numOfRows=" + str(mss_len) + "&ServiceKey="  + key

req = requests.get(request_url)

output = open("../html/data/fine_dust_mss.json", "w")
output.write(req.text)
output.close()

