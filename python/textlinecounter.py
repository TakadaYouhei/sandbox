#!/usr/bin/env python
# -*- coding: utf_8 -*-

import string
import difflib

def count_lines_in_text(text):
	'''
	text の行数を数えて返す
	'''
	lines = text.split("\n")
	result = len(lines)
	
	return result
	
	
def count_lines_in_file(filepath):
	'''
	テキストファイルを読み込み、行数を数えて返す
	'''
	result = 0
	
	# utf-8
	try:
		with open(filepath, 'r', encoding='utf-8') as fp:
			text = fp.read()
			result = count_lines_in_text(text)
		
		return result
	except Exception as ext:
		pass
		
	# shift_jis
	try:
		with open(filepath, 'r', encoding='Shift_JIS') as fp:
			text = fp.read()
			result = count_lines_in_text(text)
		
		return result
	except Exception as ext:
		pass
	
	return 0
	

def count_lines_of_diff_files(filepath1, filepath2):
	with open(filepath1, 'r', encoding='utf-8') as fp:
		text = fp.read()
		text1 = text.split("\n")

	with open(filepath2, 'r', encoding='utf-8') as fp:
		text = fp.read()
		text2 = text.split("\n")

	udifftext = difflib.unified_diff(text1, text2, 'text1', 'text2', n = 0)
	
	#for line in udifftext:
	#	print(line)
	
	result = 0
	
	try:
		itr = udifftext.__iter__()
		
		# 終端まで探す
		while True:
			# @@ を探す
			line = itr.__next__()
			print(str.format('1[{0}]', line))
			while True:
				if len(line) >= 2:
					if line[0] == '@' and line[1] == '@':
						# @@　が見つかった
						line = itr.__next__()
						break
				line = itr.__next__()
				print(str.format('2[{0}]', line))
			
			# 先頭が '-' か '+' の物が変更のあった所
			while True: 
				if len(line) >= 1:
					print(str.format('4[{0}]', line))
					if line[0] == '-' or line[0] == '+':
						result = result + 1
					elif len(line) >= 2:
						if line[0] == '@' and line[1] == '@':
							# @@ が見つかった
							# 次
							break
				line = itr.__next__()
				print(str.format('3[{0}]', line))
	except StopIteration as ext:
		pass
		
	return result
