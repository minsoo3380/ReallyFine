#import libraries
import urllib.request
from bs4 import BeautifulSoup

#specify the url
urlpage = 'https://askdjango.github.io/lv2'
print(urlpage)

# query the website and return the html to the variable 'page'
page = urllib.request.urlopen(urlpage)
print(page)

# parse the html using beautiful soup and store in variable 'soup'
soup = BeautifulSoup(page, 'html.parser')

# find product items
results = soup.body
print(results)
