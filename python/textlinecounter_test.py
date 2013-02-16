#!/usr/bin/env python
# -*- coding: utf_8 -*-

import textlinecounter
import unittest

class TestGlobalFunctions(unittest.TestCase):
	def setUp(self):
		pass
		
	def tearDown(self):
		pass
		
	def test_count_lines_in_text(self):
		self.assertEqual(1, textlinecounter.count_lines_in_text('abc'))
		self.assertEqual(2, textlinecounter.count_lines_in_text("abc\n"))
		self.assertEqual(1, textlinecounter.count_lines_in_text(''))
		self.assertEqual(1, textlinecounter.count_lines_in_text('あいうえお'))
		self.assertEqual(3, textlinecounter.count_lines_in_text("あ\nい\nう"))
		pass
	
	
	def test_count_lines_in_file(self):
		self.assertEqual(10, textlinecounter.count_lines_in_file('data_test/utf8_01.txt'))
		self.assertEqual(10, textlinecounter.count_lines_in_file('data_test/shift_jis_01.txt'))
		pass
		
	def test_(self):
		pass
	
		
if __name__ == '__main__':
	unittest.main()
	