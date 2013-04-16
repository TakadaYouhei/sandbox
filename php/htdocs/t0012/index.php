<?php
// 置換テスト

header('Content-Type: text/html; charset:utf-8');

$pattern = '/abcd/';
$rep = 'ABCD';
$text_before = 'abcdefghijklmnopqrstuvwxyz';
$text_after = preg_replace($pattern, $rep, $text_before);

echo ("text_before = ${text_before} <br/>");
echo ("text_after = ${text_after} <br/>");

echo ("<hr/>");

$pattern = '/%[a-zA-Z0-9_]+/';
$rep = 'REPLACED';
$text_before = "%abc func()\n%def1234 test()\n call %abc\n";
$text_after = preg_replace($pattern, $rep, $text_before);

echo ("text_before = ${text_before} <br/>");
echo ("text_after = ${text_after} <br/>");

echo ("<hr/>");

$pattern = '/(%[a-zA-Z0-9_]+)/';
$rep = 'REPLACED[${1}]';
$text_before = "%abc func()\n%def1234 test()\n call %abc\n";
$text_after = preg_replace($pattern, $rep, $text_before);

echo ("text_before = ${text_before} <br/>");
echo ("text_after = ${text_after} <br/>");

echo ("<hr/>");

$pattern = array('/(%[a-zA-Z_]+)[0-9]+/', '/(%[a-zA-Z_0-9]+)/');
$rep = array('A', 'B');
$text_before = "%abc %abc0123 %def %def123";
$text_after = preg_replace($pattern, $rep, $text_before);

echo ("text_before = ${text_before} <br/>");
echo ("text_after = ${text_after} <br/>");

echo ("<hr/>");

$pattern = array('/(%[a-zA-Z_]+)[0-9]*/', '/(%[a-zA-Z_0-9]+)/');
$rep = array('A', 'B');
$text_before = "%abc %abc0123 %def %def123";
$text_after = preg_replace($pattern, $rep, $text_before);

echo ("text_before = ${text_before} <br/>");
echo ("text_after = ${text_after} <br/>");

//--------------------------------------------------------
echo ("<hr/>");

$pattern = array('/a/', '/aa/', '/aaa/', '/aaaa/');
$rep = array('A', 'B', 'C', 'D');
$text_before = "aaaa aaaaa aaaaaa aaaaaaa aaaaaaaa aaaaaaaaa";
$text_after = preg_replace($pattern, $rep, $text_before);

echo ("text_before = ${text_before} <br/>");
echo ("text_after = ${text_after} <br/>");

//--------------------------------------------------------
echo ("<hr/>");

$pattern = array('/aaaa/', '/aaa/', '/aa/', '/a/');
$rep = array('A', 'B', 'C', 'D');
$text_before = "aaaa aaaaa aaaaaa aaaaaaa aaaaaaaa aaaaaaaaa";
$text_after = preg_replace($pattern, $rep, $text_before);

echo ("text_before = ${text_before} <br/>");
echo ("text_after = ${text_after} <br/>");


?>