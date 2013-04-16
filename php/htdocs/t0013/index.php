<?php
// 共通処理を別ファイルに分離
require_once(dirname(__FILE__) . '/common.php');

header('Content-Type: text/html; charset:utf-8');

//-------------------------------------------------------------------------
$html_image = <<<EOD
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>ログ記録</title>
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<style>
  article, aside, dialog, figure, footer, header,
  hgroup, menu, nav, section { display: block; }
  .search_result { 
    margin:10px 10px 10px 10px; 
    background-color: #dddddd;
  }
</style>
</head>
<body>
  <header>
    <p>ログ一覧</p>
  </header>
<!--
  <nav>
    navi
  </nav>
-->
  <!-- メインコンテンツ -->
  <div>
  	<!-- 検索結果 -->
  	%s
  </div>
  <footer>
    <p>presented by t</p>
  </footer>
</body>
</html>
EOD;
//-------------------------------------------------------------------------

$sql_create = <<<EDO
CREATE TABLE IF NOT EXISTS %s (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`accesstime` DATETIME NOT NULL ,
`ipaddress` VARCHAR( 16 ) NOT NULL ,
`url` TEXT NOT NULL
);
EDO;

$sql_select = <<<EDO
select * from %s 
EDO;

$sql_insert = <<<EDO
insert into %s (`accesstime`, `ipaddress`, `url`) values 
(:accesstime, :ipaddress, :url);
EDO;

$tablename = 'log' . date('ym');
$sql_create = sprintf($sql_create, $tablename);
$sql_select = sprintf($sql_select, $tablename);
$sql_insert = sprintf($sql_insert, $tablename);

try {
	// MySQLサーバへ接続
	$pdo = new_PDO();
	
	$search_word = $_GET['words'];
	
	// チェック
	if(!mb_check_encoding($search_word))
	{
		exit('parameter is failed.');
	}
	
	// ログ記録
	$stmt = $pdo->prepare($sql_insert);
	$today = date('Y-m-d H:i:s');
	$stmt->bindValue('accesstime', $today);
	
	$ipaddress = $_SERVER['REMOTE_ADDR'];
	$stmt->bindValue('ipaddress', $ipaddress);
	
	$url = $_SERVER['REQUEST_URI'];
	$stmt->bindValue('url', $url);
	
	$ret = $stmt->execute($param_array);
	if (!$ret)
	{
		$pdo->exec($sql_create);
		$ret = $stmt->execute($param_array);
		if (!$ret)
		{	
			print("execute failed\n");
		}
	}
		
	$result_body = '';
				
    printf($today);
    printf('[');
    printf($ipaddress);
    printf(']');
    printf($url);
    	
} catch(PDOException $e){
	echo 'exception!!';
	echo $e->getMessage();
	var_dump($e->getMessage());
}

// 切断
$pdo = null;

?>
