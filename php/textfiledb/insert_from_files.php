<?php
// test
// @file insert_from_files.php
// @brief フォルダをパースしてデータベースにデータを挿入する (コマンドラインツール)
// 実行方法
//   /Applications/MAMP/bin/php5.3/bin/php insert_from_files.php folderpath

// 共通処理を別ファイルに分離
require_once(dirname(__FILE__) . '/common.php');

$sql_insert = <<<EOD
INSERT INTO master_data (filename, folderpath, detail)
VALUES (:v_filename, :v_folderpath, :v_detail)
;
EOD;

$sql_update1 = <<<EDO
UPDATE `test`.`master_data` 
	SET 
		`folderpath` = :v_folderpath,
		`detail` = :v_detail
	WHERE
		`filename` = :v_filename
;
EDO;
$sql_update = <<<EDO
UPDATE `test`.`master_data` 
	SET 
		`folderpath` = :v_folderpath,
		`detail` = :v_detail
	WHERE
		`filename` = :v_filename
;
EDO;

function insert_from_files($inpath)
{

try {
	// MySQLサーバへ接続
	$pdo = new_PDO();
	
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	$pdo->query("SET NAMES utf8;");
	
	$stmt_insert = $pdo->prepare($sql_insert);
	$stmt_update = $pdo->prepare($sql_update);
	
	
	// 検索
	$filelist = glob($inpath);
	
	foreach(glob($inpath) as $filepath)
	{
		$data = file_get_contents($filepath);
		if ($data == FALSE)
		{
			continue;
		}
		
		//print ($filepath . "\n");
		
		$filename = basename($filepath);
		$dirname = dirname($filepath);
		
		if (!mb_check_encoding($filename, 'utf8'))
		{
			print("filename is bad encoding!\n");
			continue;
		}
		if (!mb_check_encoding($dirname, 'utf8'))
		{
			print("dirname is bad encoding!\n");
			continue;
		}
		if (!mb_check_encoding($data, 'utf8'))
		{
			print("data is bad encoding\n");
			continue;
		}
		
		// SQL 挿入
		$ret = $stmt_insert->execute(
						array(
							':v_filename' => $filename, 
							':v_folderpath'=> $dirname, 
							':v_detail'=> $data
							)
						);
		if ($ret)
		{
			print ("insert\n");
			continue;
		}

		
		// 挿入失敗したら

		// TODO : ハッシュ値を比較して、更新が必要か判定する

		// SQL 更新
		$ret = $stmt_update->execute(
						array(
							':v_folderpath'=> $dirname, 
							':v_detail'=> $data,
							':v_filename' => $filename
							)
						);
		if (!$ret)
		{
			print ("SQL ERROR:{$sql_update}\n");
			break;
		}
		if ($stmt_update->rowCount() > 0)
		{
			print ("update\n");
		}
	}
	
} catch(PDOException $e){
	echo 'exception!!';
	echo $e->getMessage();
	var_dump($e->getMessage());
}

// 切断
$pdo = null;
}


//------------------------------------

if ($argc <= 1)
{
	print('USAGE : php insert_from_files.php inpath\n');
	exit(1);
}

$inpath = $argv[1];
insert_from_files( $inpath );


?>
