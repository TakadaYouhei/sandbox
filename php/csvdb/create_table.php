<?php
// @file create_database.php
// @brief データベースにテーブルを作成する (コマンドラインツール)
// 実行方法
//   /Applications/MAMP/bin/php5.3/bin/php create_table.php

// 共通処理を別ファイルに分離
require_once(dirname(__FILE__) . '/common.php');

function create_table()
{

	// ヘッダ情報のテーブル作成
	//
	// hash 生データのハッシュ(sha1)。２重登録防止に使用
	// plane_data csv ファイル生データ
	$sql_create_head = <<<EOD
CREATE TABLE IF NOT EXISTS csv_head (
    id          INT NOT NULL AUTO_INCREMENT,
	filename    CHAR(255) DEFAULT 'no name',
	folderpath  CHAR(255) DEFAULT '.',
	regist_date DATETIME  DEFAULT '2000-01-01 00:00:00',
	hash        CHAR(40)  DEFAULT NULL UNIQUE,
	plane_data  LONGTEXT  DEFAULT '',
	csv_head1   CHAR(255) DEFAULT '',
	csv_head2   CHAR(255) DEFAULT '',
	csv_head3   CHAR(255) DEFAULT '',
	csv_head4   CHAR(255) DEFAULT '',
	
	PRIMARY KEY(id)
);
EOD;
	
	// レコード情報のテーブル作成
	$sql_create_record = <<<EOD
CREATE TABLE IF NOT EXISTS csv_record (
    id          INT NOT NULL AUTO_INCREMENT,
    ref_id_head INT NOT NULL,
	record_date DATETIME  DEFAULT '2000-01-01 00:00:00',
	csv_data01   CHAR(255) DEFAULT '',
	csv_data02   CHAR(255) DEFAULT '',
	csv_data03   CHAR(255) DEFAULT '',
	csv_data04   CHAR(255) DEFAULT '',
	csv_data05   CHAR(255) DEFAULT '',
	csv_data06   CHAR(255) DEFAULT '',
	csv_data07   CHAR(255) DEFAULT '',
	csv_data08   CHAR(255) DEFAULT '',
	csv_data09   CHAR(255) DEFAULT '',
	csv_data10   CHAR(255) DEFAULT '',
	csv_data11   CHAR(255) DEFAULT '',
	csv_data12   CHAR(255) DEFAULT '',
	csv_data13   CHAR(255) DEFAULT '',
	csv_data14   CHAR(255) DEFAULT '',
	csv_data15   CHAR(255) DEFAULT '',
	csv_data16   CHAR(255) DEFAULT '',
	csv_data17   CHAR(255) DEFAULT '',
	csv_data18   CHAR(255) DEFAULT '',
	csv_data19   CHAR(255) DEFAULT '',
	csv_data20   CHAR(255) DEFAULT '',
	
	PRIMARY KEY(id)
);
EOD;

	
	$sql_drop_head = <<<EOD
DROP TABLE IF EXISTS csv_head;
EOD;
	$sql_drop_record = <<<EOD
DROP TABLE IF EXISTS csv_record;
EOD;
	
	try {
		// MySQLサーバへ接続
		$pdo = new_PDO();
		
		// すでにあったら削除
		$stmt = $pdo->prepare($sql_drop_head);
		$ret = $stmt->execute();
		if (!$ret)
		{
			show_pdo_error($stmt);
			return FALSE;
		}
		$stmt = $pdo->prepare($sql_drop_record);
		$ret = $stmt->execute();
		if (!$ret)
		{
			show_pdo_error($stmt);
			return FALSE;
		}
	
		// 改めて作りなおす
		$stmt = $pdo->prepare($sql_create_head);
		$ret = $stmt->execute();
		if (!$ret)
		{
			show_pdo_error($stmt);
			return FALSE;
		}
		$stmt = $pdo->prepare($sql_create_record);
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
$ret = create_table();
if ($ret)
{
	echo 'success!!';
}
else
{
	echo 'failed!!';
}

?>
