<?php
// @file test_strtotime.php
// @brief strtotime の実装確認
// 実行方法
//   /Applications/MAMP/bin/php5.3/bin/php test_strtotime.php

$testcases = array(
    '2012-07-20 10:11:12'
  , '2012/07/20 10:11:12'
  , '20120720 101112'
  , '20120720101112'
  , '20120720,101112'
  , '20120720'
);

foreach ($testcases as $strdata)
{
	$datedata = strtotime($strdata);
	$after = date('Y-m-d H:i:s', $datedata);
	echo "input {$strdata} => {$after}\n";
}

?>
