from selenium import webdriver

browser = webdriver.Firefox()
url = "https://www.airkorea.or.kr/web/pmRelay?itemCode=11008&pMENU_NO=109"
req = browser.get(url)

print(req)
