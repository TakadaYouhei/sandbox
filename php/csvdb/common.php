<?php

// 雑多な共通処理

ini_set('mbstring.language', 'Japanese');
//ini_set('default_charset', none);
ini_set('mbstring.internal_encoding', 'UTF-8');
ini_set('mbstring.http_input', 'pass');
ini_set('mbstring.http_output', 'pass');
ini_set('mbstring.substitute_character', 'none');
ini_set('mbstring.detect_order', 'SJIS,EUC-JP,JIS,UTF-8,ASCII');

// メモ
// header('Content-Type: text/html; charset:utf-8');

/// PDO インスタンスの作成
function new_PDO() {
	$mysql_host = "localhost";
	$mysql_port = "8889";
	$mysql_db = "textfileviewdb";
	$mysql_username = "root";
	$mysql_password = "root";

	try {
		$pdo = new PDO("mysql:host=" . $mysql_host . ";port=" . $mysql_port ."; dbname=" . $mysql_db,
						$mysql_username, $mysql_password);
		
		return $pdo;
	} catch(PDOException $e){
		var_dump($e->getMessage());
		return null;
	}
}

/// PDO のエラー表示
function show_pdo_error($stmt) {
	$err_info = $stmt->errorInfo();
	
	printf ("SQL STATE error code %d\n", $err_info[0]);
	printf ("database error code %d\n", $err_info[1]);
	printf ("database error message / %s\n", $err_info[2]);
	
	exit(-1);
}

/// 拡張子を返す
function get_file_ext($filename) {
	$ext = substr($filename, strrpos($filename, '.') + 1);
	return $ext;
}

?>