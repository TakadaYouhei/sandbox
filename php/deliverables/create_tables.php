<?php

// 共通処理を別ファイルに分離
require_once(dirname(__FILE__) . '/common.php');

/// テーブル作成
function create_tables()
{
	// tbl_deli_data
	// 成果物データテーブル
	/* データを一意に識別するためのID。親子や関連を定義する際に参照 */
	/* 親のID */
	/* このレコードの更新日時 */
	/* 種類。成果物'data' プロセス'process' */
	/* グループ化とかしたくなる ? */
	/* 概要 */
	/* 詳細 */
	$sql_create1 = <<<EOD
		CREATE TABLE IF NOT EXISTS tbl_deli_data (
			id INT NOT NULL AUTO_INCREMENT,	
			id_parent INT DEFAULT NULL,				
			update_time DATETIME NOT NULL,			
			type_text     CHAR(32) DEFAULT 'data',	
			group_text    CHAR(255),				
			brief       TEXT,						
			detail      TEXT,						
			
			PRIMARY KEY(id)
		);
EOD;
	
	// tbl_deli_Relation
	// 成果物 関連 テー物
	$sql_create2 = <<<EOD
		CREATE TABLE IF NOT EXISTS tbl_deli_relation (
			rel_id INT NOT NULL AUTO_INCREMENT,
			id_source INT NOT NULL,					-- 関連の元。流れの厳選
			id_destinate INT NOT NULL,				-- 関連の先。流れる先
			update_time DATETIME NOT NULL,			-- このレコードの更新日時

			PRIMARY KEY(rel_id)
		);
EOD;
	
	try {
		// MySQLサーバへ接続
		$pdo = new_PDO();
	
		// 作る
		$stmt = $pdo->prepare($sql_create1);
		$ret = $stmt->execute(array());
		if (!$ret)
		{
			show_pdo_error($stmt);
		}
		// 作る
		$stmt = $pdo->prepare($sql_create2);
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
}

create_tables();

?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>テーブルを作成する</title>
</head>
<body>
<h1>テーブル作成したよ</h1>
<p>
</p>
</body>
</html>

