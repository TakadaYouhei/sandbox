<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>t0001 / 実行 / テーブルを作成する</title>
</head>
<body>実行した t0001 / テーブルを作成する</h1>
<p>
<?php

$mysql_host = "localhost";
$mysql_port = "8889";
$mysql_db = "test";
$mysql_username = "root";
$mysql_password = "root";

$sql = <<<EOD
CREATE TABLE t0001 (
	id      INT       NOT NULL AUTO_INCREMENT,
	name    CHAR(40)  DEFAULT 'no name',
	comment TEXT      ,
	
	PRIMARY KEY(id)
);
EOD;

try {
	// MySQLサーバへ接続
	$pdo = new PDO("mysql:host=" . $mysql_host . ";port=" . $mysql_port ."; dbname=" . $mysql_db,
					$mysql_username, $mysql_password);
	$pdo->exec($sql);
	
} catch(PDOException $e){
	var_dump($e->getMessage());
}

// 切断
$pdo = null;

?>
</p>
</body>
</html>
