<?php

ini_set('mbstring.language', 'Japanese');
ini_set('default_charset', none);
ini_set('mbstring.internal_encoding', 'UTF-8');
ini_set('mbstring.http_input', 'pass');
ini_set('mbstring.http_output', 'pass');
ini_set('mbstring.substitute_character', 'none');
ini_set('mbstring.detect_order', 'SJIS,EUC-JP,JIS,UTF-8,ASCII');

header('Content-Type: text/html; charset:utf-8');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>t0005 / カラムの追加した</title>
</head>
<body>実行した t0005 / カラムの追加</h1>
<p>
<?php

$mysql_host = "localhost";
$mysql_port = "8889";
$mysql_db = "test";
$mysql_username = "root";
$mysql_password = "root";

$sql_alter_add = "ALTER TABLE `t0003` ADD `comment2` TEXT NOT NULL";

$sql_select = <<<EDO
select * from t0005;
EDO;

try {
	// MySQLサーバへ接続
	$pdo = new PDO("mysql:host=" . $mysql_host . ";port=" . $mysql_port ."; dbname=" . $mysql_db,
					$mysql_username, $mysql_password);
	// カラム追加
	$pdo->exec($sql_alter_add);
	
	// 参照
	$stmt = $pdo->query($sql_select);
	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo implode(", ", $row) . "<br />" . PHP_EOL;
    }
	
} catch(PDOException $e){
	var_dump($e->getMessage());
}

// 切断
$pdo = null;

?>
</p>
</body>
</html>
