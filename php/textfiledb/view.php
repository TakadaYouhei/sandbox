<?php
// 共通処理を別ファイルに分離
require_once(dirname(__FILE__) . '/common.php');

header('Content-Type: text/html; charset:utf-8');

//-------------------------------------------------------------------------

$sql_select = <<<EDO
select * from master_data 
where
	filename = :filename
;
EDO;


try {
	// MySQLサーバへ接続
	$pdo = new_PDO();
	
	// 追加
	$stmt = $pdo->prepare($sql_select);
	
	$filename = $_GET['filename'];
	
	// チェック
	if(!mb_check_encoding($filename))
	{
		exit('parameter is failed.');
	}
	
	// 
	$stmt->bindValue(':filename', $filename);
	$ret = $stmt->execute();
	if (!$ret)
	{
		exit("execute failed\n");
	}
		
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$detail = $row['detail'];
	$detail = str_replace("\t", '    ', $detail);
	$html_detail = htmlspecialchars($detail);
	
	// キーワード置換
	// %aaa000_12345_123
	// %bbb000_12345_123_123
	// %bbb000_12345_123 123
/*
%aaa000_12345_123
*/
	$pattern = array (
					'/^%([a-z]+[0-9]+_[0-9]+_[0-9]+)/m',
					'/%([a-z]+[0-9]+_[0-9]+_[0-9]+)([ \n\r\t\/])/',
					'/%([a-z]+[0-9]+_[0-9]+_[0-9]+)_([0-9]+)/',
					'/(\/\/.+)$/m',
				);
	$replace = array (
					'<span id="${1}" />%${1}',
					'<a href="view.php?filename=${1}#${1}">%${1}</a>${2}',
					'<a href="view.php?filename=${1}#${1}_${2}">%${1}_${2}</a>',
					'<span style="color: green; margin-top: 0; margin-bottom: 0;">${1}</span>',
				);
	$html_detail = preg_replace($pattern, $replace, $html_detail);
	
	print ("<div style=\"font-weight:bold\">${filename}</div>");
	print ("<hr />");
	print ("<pre>");
	print ($html_detail);
    print ("</pre>");
    
} catch(PDOException $e){
	echo 'exception!!';
	echo $e->getMessage();
	var_dump($e->getMessage());
}

// 切断
$pdo = null;

?>
