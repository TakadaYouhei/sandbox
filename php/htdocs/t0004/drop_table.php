<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>t0004 / 実行 / テーブルを削除する</title>
</head>
<body>実行した t0004 / テーブルを削除する</h1>
<p>
<?php

$mysql_host = "localhost";
$mysql_port = "8889";
$mysql_db = "test";
$mysql_username = "root";
$mysql_password = "root";

$sql_create = <<<EOD
CREATE TABLE t0002 (
	id      INT       NOT NULL AUTO_INCREMENT,
	name    CHAR(40)  DEFAULT 'no name',
	comment TEXT      ,
	
	PRIMARY KEY(id)
);
EOD;

$sql_drop = <<<EDO
DROP TABLE t0002;
EDO;

try {
	// MySQLサーバへ接続
	$pdo = new PDO("mysql:host=" . $mysql_host . ";port=" . $mysql_port ."; dbname=" . $mysql_db,
					$mysql_username, $mysql_password);
	// 一度作って
	$pdo->exec($sql_create);
	
	// すぐ削除
	$pdo->exec($sql_drop);
	
} catch(PDOException $e){
	var_dump($e->getMessage());
}

// 切断
$pdo = null;

?>
</p>
</body>
</html>
