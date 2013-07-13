<?php
// @file create_database.php
// @brief データベースを作成する (コマンドラインツール)
// 実行方法
//   /Applications/MAMP/bin/php5.3/bin/php create_database.php

// 共通処理を別ファイルに分離
require_once(dirname(__FILE__) . '/common.php');

function create_database()
{

	// filename ... ファイル名 (含む拡張子) ex: 'sample.txt'
	// fileext ... ファイルの拡張子。 '.' は含まない。 ex: 'txt', 'csv'
	// folderpath ... ファイルが置かれているパス
	// detail ... ファイルの内容
	// lastupdate .... 最終更新日
	// revision .... 更新に絡む情報
	// hash .... sha1 の 40文字の文字列
	$sql_create = <<<EOD
CREATE TABLE IF NOT EXISTS master_data (
	filename    CHAR(255) DEFAULT 'no name',
	fileext     CHAR(255) DEFAULT '',
	folderpath  CHAR(255) DEFAULT '.',
	detail      LONGTEXT  DEFAULT '',
	lastupdate  DATETIME  DEFAULT '2000-01-01 00:00:00',
	revision    TEXT      DEFAULT '',
	hash        CHAR(40)  DEFAULT '',
	
	PRIMARY KEY(filename)
);
EOD;
	
	$sql_drop = <<<EOD
DROP TABLE IF EXISTS master_data;
EOD;
	
	try {
		// MySQLサーバへ接続
		$pdo = new_PDO();
		
		// すでにあったら削除
		$stmt = $pdo->prepare($sql_drop);
		$ret = $stmt->execute();
		if (!$ret)
		{
			show_pdo_error($stmt);
			return FALSE;
		}
	
		// 改めて作りなおす
		$stmt = $pdo->prepare($sql_create);
		$ret = $stmt->execute();
		if (!$ret)
		{
			show_pdo_error($stmt);
			return FALSE;
		}
		
	} catch(PDOException $e){
		echo 'exception!!';
		echo $e->getMessage();
		var_dump($e->getMessage());
		
		return FALSE;
	}
	
	// 切断
	$pdo = null;
	
	return TRUE;
}

// 関数呼び出し
$ret = create_database();
if ($ret)
{
	echo 'success!!';
}
else
{
	echo 'failed!!';
}

?>
