import pymysql

class DBCon:

	def __init__(self):
		self.host = ''
		self.user = ''
		self.passwordword = ''
		self.db = ''
		self.character = ''


	def setAll(self, host, user, password, db, character):
		self.host = host
		self.user = user
		self.password = password
		self.db = db
		self.character = character
		
	
	def setAllInput(self):
		self.host = input('please input db host name : ')
		self.user = input('please input db user name : ')
		self.password = input('please input db password : ')
		self.db = input('input DataBase name : ')
		self.character = input('input charset : ')

	
	def ConnDB(self):
		conn = pymysql.connect(host=self.host, user=self.user, password=self.password, db=self.db, charset=self.character)

		cur = conn.cursor()
		
		if cur is None:
			print('DataBase Connected fail...!')
		else:
			print('DataBase Connected Success')

		return cur
