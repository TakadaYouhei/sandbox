#!/usr/bin/env python
# -*- coding: utf_8 -*-

import string

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
	
	with open(filepath, 'r', encoding='utf-8') as fp:
		text = fp.read()
		result = count_lines_in_text(text)
	
	return result
	
