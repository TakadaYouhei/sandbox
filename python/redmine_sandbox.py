#! /usr/local/bin/python

import urllib
import urllib2
import json

key = '04f6f038ac87dfcf5614aefeb9aa78f7c224595b'

def read_projects_list():
	response = urllib2.urlopen('http://localhost:3000/projects.json')
	html = response.read()
	projects = json.loads(html)
	print(projects)
	result = {}
	for proj in projects['projects']:
		print(proj['id'], proj['name'])
		result [proj['name']] = proj['id']
	return result

if __name__ == '__main__':
	ilist = read_projects_list()
	print('aiueo')
	print(ilist)