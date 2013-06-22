<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>レコード追加</title>
</head>
<body>
<?php
// insert_record.php
// 概要
//   レコードを追加する
// 仕様
//   パラメータ
//     encode …... エンコード
//     id_parent ... 親id
//     type ... 種類
//     group ... グループ
//     brief ... 概要
//     detail ... 詳細
// 共通処理を別ファイルに分離
require_once(dirname(__FILE__) . '/common.php');


/// テーブル作成
function insert_record($argv)
{
	
	//-----
	if (!array_key_exists('encode', $argv))
	{
		print ('ERROR:no encode.');
		return;
	}
	$encode = $argv['encode'];
	
	//-----
	if (!array_key_exists('id_parent', $argv))
	{
		print ('ERROR:no id_parent.');
		return;
	}
	$id_parent = $argv['id_parent'];
	
	//-----
	if (!array_key_exists('type', $argv))
	{
		print ('ERROR:no type.');
		return;
	}
	$type = $argv['type'];
	
	//-----
	if (!array_key_exists('group', $argv))
	{
		print ('ERROR:no group.');
		return;
	}
	$group = $argv['group'];
	
	//-----
	if (!array_key_exists('brief', $argv))
	{
		print ('ERROR:no brief.');
		return;
	}
	$brief = $argv['brief'];
	
	//-----
	if (!array_key_exists('detail', $argv))
	{
		print ('ERROR:no detail.');
		return;
	}
	$detail = $argv['detail'];
	
	
	
	
	// 以下無視
	return;
	
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

insert_record($_POST);

$def_encode = 'aiueo';
$def_id_parent = 'ia_parent';
$def_type = 'def type';
?>
<h1>レコード追加</h1>

<form action="./insert_record.php" method="POST">
  <ul>
    <li>エンコード　<input type="text" name="encode" value="<?php echo $def_encode; ?>"/></li>
    <li>親id <input type="text" name="id_parent" value="<?php echo $def_id_parent; ?>"/></li>
    <li>種類 <input type="text" name="type" value="<?php echo $def_type; ?>"/></li>
    <li>グループ <input type="text" name="group" value=""/></li>
    <li>概要 <input type="text" name="brief" value=""/></li>
    <li>詳細 <input type="text" name="detail" value=""/></li>
  </ul>
  <input type="submit" value="送信" />
</form>
<p>
</p>
</body>
</html>

