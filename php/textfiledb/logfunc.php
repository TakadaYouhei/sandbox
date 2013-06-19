<?php
// 共通処理を別ファイルに分離
require_once(dirname(__FILE__) . '/common.php');

function logfunc_add_accesslog($pdo) {

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
				print("logfunc execute failed\n");
			}
		}
	} catch(PDOException $e){
		echo 'exception!!';
		echo $e->getMessage();
		var_dump($e->getMessage());
	}
}

?>
