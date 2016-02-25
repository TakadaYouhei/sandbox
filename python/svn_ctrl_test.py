#!/usr/bin/python
# -*- coding: utf_8 -*-

import svn_ctrl
import unittest


class TestGlobalFunctions(unittest.TestCase):
  def setUp(self):
    pass

  def tearDown(self):
    pass

  def test_constructor01(self):
    inst = svn_ctrl.SvnCtrl('file:///home/takadayouhei/svndb/')


if __name__ == '__main__':
  unittest.run()

