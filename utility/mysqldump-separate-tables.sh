# If desired, these flags could also be used to create CSV rather than TSV
# files as the .txt files:
# --fields-terminated-by=',' --fields-optionally-enclosed-by='"'
# You still use --tab, even if you're going for commas!

mysqldump -u root -p --tab=/var/lib/mysql-files --compatible=postgresql brainspell
