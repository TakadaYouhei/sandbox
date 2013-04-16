<?php
// 共通処理を別ファイルに分離
require_once(dirname(__FILE__) . '/../../lib/misc.php');

header('Content-Type: text/html; charset:utf-8');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>t0007 / フォームから入力</title>
</head>
<body>実行した t0007 / フォームから入力</h1>
<p>
<?php


$sql_create = <<<EOD
CREATE TABLE IF NOT EXISTS t0007 (
	id      INT       NOT NULL AUTO_INCREMENT,
	name    CHAR(40)  DEFAULT 'no name',
	comment TEXT      ,
	
	PRIMARY KEY(id)
);
EOD;

$sql_insert = <<<EDO
INSERT INTO t0007 (
	name,
	comment
) values (
	:NAME_VALUE,
	:COMMENT_VALUE
);
EDO;

$sql_select = <<<EDO
select * from t0007;
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
	
	$name = $_POST['NAME'];
	$comment = $_POST['COMMENT'];
	
	$stmt->bindValue(':NAME_VALUE', $name);
	$stmt->bindValue(':COMMENT_VALUE', $comment);
	$stmt->execute();
			
	// 参照
	$stmt = $pdo->prepare($sql_select);
	$stmt->execute();
	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo htmlspecialchars(implode(", ", $row)) . "<br />" . PHP_EOL;
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
  <form method="post" action="./index.php" >
    名前 : <input type="text" name="NAME" /> <br/>
    コメント : <br/>
    <textarea type="textarea" name="COMMENT" rows="10" cols="40"></textarea> <br/>
    <input type="submit" value="送信" />
  </form>
</p>
</body>
</html>
