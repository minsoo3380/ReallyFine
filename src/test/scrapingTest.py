#import libraries
import urllib.request
from bs4 import BeautifulSoup

#specify the url
urlpage = 'https://www.airkorea.or.kr/web/pmRelay?itemCode=11008&pMENU_NO=109'
print(urlpage)

# query the website and return the html to the variable 'page'
page = urllib.request.urlopen(urlpage)
print(page)

# parse the html using beautiful soup and store in variable 'soup'
soup = BeautifulSoup(page, 'html.parser')

# find product items
results = soup.find_all('div', attrs={'id':'subDiv'})
print(results)
