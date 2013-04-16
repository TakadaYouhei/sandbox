<?php
// 共通処理を別ファイルに分離
require_once(dirname(__FILE__) . '/../../lib/misc.php');

header('Content-Type: text/html; charset:utf-8');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>t0005 / 現状表示</title>
</head>
<body>実行した t0005 / 現状表示</h1>
<p>
<?php

echo dirname(__FILE__) . '<br/><br/>' . PHP_EOL;

$sql_drop = <<<EDO
DROP TABLE IF EXISTS t0005;
EDO;

$sql_create = <<<EOD
CREATE TABLE IF NOT EXISTS t0005 (
	id      INT       NOT NULL AUTO_INCREMENT,
	name    CHAR(40)  DEFAULT 'no name',
	comment TEXT      ,
	
	PRIMARY KEY(id)
);
EOD;

$sql_insert1 = <<<EDO
INSERT INTO t0005 (
	name,
	comment
) values (
	'name',
	'comment'
);
EDO;

$sql_insert2 = <<<EDO
INSERT INTO t0005 (
	name,
	comment
) values (
	'名前',
	'コメント'
);
EDO;

$sql_select = <<<EDO
select * from t0005;
EDO;

try {
	// MySQLサーバへ接続
	$pdo = new_PDO();
	
	// すでにあったら削除
	$pdo->exec($sql_drop);

	// 一度作って
	$pdo->exec($sql_create);
	
	// 追加
	$pdo->exec($sql_insert1);
	$pdo->exec($sql_insert2);
	
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
<p>
  <form method="post" action="./alter_add.php" >
    <input type="submit" value="追加" />
  </form>
</p>
</body>
</html>
