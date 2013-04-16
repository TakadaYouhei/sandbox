<?php
// 共通処理を別ファイルに分離
require_once(dirname(__FILE__) . '/../../lib/misc.php');

header('Content-Type: text/html; charset:utf-8');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>t0006 / 現状表示</title>
</head>
<body>実行した t0006 / 現状表示</h1>
<p>
<?php

echo dirname(__FILE__) . '<br/><br/>' . PHP_EOL;


$sql_drop = <<<EDO
DROP TABLE IF EXISTS t0006;
EDO;

$sql_create = <<<EOD
CREATE TABLE IF NOT EXISTS t0006 (
	id      INT       NOT NULL AUTO_INCREMENT,
	name    CHAR(40)  DEFAULT 'no name',
	comment TEXT      ,
	
	PRIMARY KEY(id)
);
EOD;

$sql_insert = <<<EDO
INSERT INTO t0006 (
	name,
	comment
) values (
	:NAME_VALUE,
	:COMMENT_VALUE
);
EDO;

$sql_select = <<<EDO
select * from t0006;
EDO;

try {
	// MySQLサーバへ接続
	$pdo = new_PDO();
	
	// すでにあったら削除
	$stmt = $pdo->prepare($sql_drop);
	$stmt->execute();

	// 一度作って
	$stmt = $pdo->prepare($sql_create);
	$stmt->execute();
	
	// 追加
	$stmt = $pdo->prepare($sql_insert);
	
	$stmt->bindValue(':NAME_VALUE', 'name');
	$stmt->bindValue(':COMMENT_VALUE', 'comment');
	$stmt->execute();
	
	$stmt->bindValue(':NAME_VALUE', 'なまえ');
	$stmt->bindValue(':COMMENT_VALUE', 'こめんと');
	$stmt->execute();
		
	// 参照
	$stmt = $pdo->prepare($sql_select);
	$stmt->execute();
	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo implode(", ", $row) . "<br />" . PHP_EOL;
    }
	
} catch(PDOException $e){
	echo 'exception!!';
	echo $e->getMessage();
	var_dump($e->getMessage());
}

// 切断
$pdo = null;

?>
</p>
<p>
  <form method="post" action="./alter_drop.php" >
    <input type="submit" value="削除" />
  </form>
</p>
</body>
</html>
