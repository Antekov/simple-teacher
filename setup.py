import sqlite3

db_name = 'rams.local.sqlite'

conn = sqlite3.connect(db_name)
cursor = conn.cursor()

f = open('db.sqlite.sql', 'r', encoding='utf-8')
queries = f.read().split('\n\n')
f.close()

for query in queries:
    print(query)
    cursor.execute(query)
    conn.commit()
    print('OK')

f = open('db.data.local.sql', 'r', encoding='utf-8')
queries = f.read().split(';')
f.close()

for query in queries:
    print(query[:60])
    cursor.execute(query)
    conn.commit()
    print('OK')