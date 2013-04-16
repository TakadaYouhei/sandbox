<?php
// @file create_database.php
// @brief データベースを作成する (コマンドラインツール)
// 実行方法
//   /Applications/MAMP/bin/php5.3/bin/php create_database.php

// 共通処理を別ファイルに分離
require_once(dirname(__FILE__) . '/common.php');

$sql_create = <<<EOD
CREATE TABLE IF NOT EXISTS master_data (
	filename    CHAR(20)  DEFAULT 'no name',
	folderpath  CHAR(255) DEFAULT '.',
	detail      TEXT      DEFAULT '',
	
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
	}

	// 改めて作りなおす
	$stmt = $pdo->prepare($sql_create);
	$ret = $stmt->execute();
	if (!$ret)
	{
		show_pdo_error($stmt);
	}
	
} catch(PDOException $e){
	echo 'exception!!';
	echo $e->getMessage();
	var_dump($e->getMessage());
}

// 切断
$pdo = null;

?>
