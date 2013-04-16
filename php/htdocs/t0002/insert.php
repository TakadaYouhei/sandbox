<?php

print "ok";
ini_set('mbstring.language', 'Japanese');
ini_set('default_charset', none);
ini_set('mbstring.internal_encoding', 'UTF-8');
print "ok";
ini_set('mbstring.http_input', 'pass');
print "ok";
ini_set('mbstring.http_output', 'pass');
ini_set('mbstring.substitute_character', 'none');
ini_set('mbstring.detect_order', 'SJIS,EUC-JP,JIS,UTF-8,ASCII');

header('Content-Type: text/html; charset:utf-8');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>t0002 / 実行 / レコード追加する</title>
</head>
<body>実行した t0002 / レコード追加する</h1>
<p>
<?php

$mysql_host = "localhost";
$mysql_port = "8889";
$mysql_db = "test";
$mysql_username = "root";
$mysql_password = "root";

$sql_create = <<<EOD
CREATE TABLE IF NOT EXISTS t0002 (
	id      INT       NOT NULL AUTO_INCREMENT,
	name    CHAR(40)  DEFAULT 'no name',
	comment TEXT      ,
	
	PRIMARY KEY(id)
);
EOD;

$sql_insert1 = <<<EDO
INSERT INTO t0002 (
	name,
	comment
) values (
	'name',
	'comment'
);
EDO;

$sql_insert2 = <<<EDO
INSERT INTO t0002 (
	name,
	comment
) values (
	'名前',
	'コメント'
);
EDO;

try {
	// MySQLサーバへ接続
	$pdo = new PDO("mysql:host=" . $mysql_host . ";port=" . $mysql_port ."; dbname=" . $mysql_db,
					$mysql_username, $mysql_password);
	// 一度作って
	$pdo->exec($sql_create);
	
	// 追加
	$pdo->exec($sql_insert1);
	$pdo->exec($sql_insert2);
	
} catch(PDOException $e){
	var_dump($e->getMessage());
}

// 切断
$pdo = null;

?>
</p>
</body>
</html>
