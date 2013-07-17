<?php
// test
// @file insert_from_files.php
// @brief フォルダをパースしてデータベースにデータを挿入する (コマンドラインツール)
// 実行方法
//   /Applications/MAMP/bin/php5.3/bin/php insert_from_files.php folderpath

// 共通処理を別ファイルに分離
require_once(dirname(__FILE__) . '/common.php');

// filename ... ファイル名 (含む拡張子) ex: 'sample.txt'
// fileext ... ファイルの拡張子。 '.' は含まない。 ex: 'txt', 'csv'
// folderpath ... ファイルが置かれているパス
// detail ... ファイルの内容
// lastupdate .... 最終更新日
// revision .... 更新に絡む情報
// hash .... sha1 の 40文字の文字列
$sql_insert = <<<EOD
INSERT INTO master_data (filename,    fileext,    folderpath,    detail,    lastupdate,    revision,    hash)
                 VALUES (:v_filename, :v_fileext, :v_folderpath, :v_detail, :v_lastupdate, :v_revision, :v_hash)
;
EOD;

$sql_exists_record = <<<EOD
SELECT * FROM `test`.`master_data` WHERE EXIST (
		SELECT * FROM `test`.`master_data`
		WHERE `hash` = :v_hash
	)
;
EOD;

$sql_update = <<<EDO
UPDATE `test`.`master_data` 
	SET 
		`fileext` = :v_fileext,
		`folderpath` = :v_folderpath,
		`detail` = :v_detail,
		`lastupdate` = :v_lastupdate,
		`revision` = :v_revision,
		`hash` = :v_hash
	WHERE
		`filename` = :v_filename
;
EDO;

/// @brief フォルダ以下にあるファイルを DB に登録する。既に登録済みなら、更新する。
/// @in $inpath 検索を開始するルートフォルダのパス
function insert_from_files($inpath)
{
	try {
		// MySQLサーバへ接続
		$pdo = new_PDO();
		
		$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		$pdo->query("SET NAMES utf8;");
		
		$stmt_insert = $pdo->prepare($sql_insert);
		$stmt_update = $pdo->prepare($sql_update);
		$stmt_exist = $pdo->prepare($sql_exists_record);
		
		
		// 検索
		$filelist = glob($inpath);
		
		foreach(glob($inpath) as $filepath)
		{
			$data = file_get_contents($filepath);
			if ($data == FALSE)
			{
				continue;
			}
			
			// DB に登録する他の情報を取得
			$filename = basename($filepath);
			$dirname = dirname($filepath);
			$fileext = get_file_ext($filename);
			$lastupdate = date('Y-m-d H:i:s', filemtime($filepath));	// ex) 1999-11-11 23:59:59
			$revision = '';
			$hash = sha1($data);
			
			// ファイルの内容確認
			//print ($filepath . "\n");
			
			if (!mb_check_encoding($filename, 'utf8'))
			{
				print("filename [" . $filename . "] is bad encoding!\n");
				continue;
			}
			if (!mb_check_encoding($dirname, 'utf8'))
			{
				print("dirname [" . $dirname . "] is bad encoding!\n");
				continue;
			}
			if (!mb_check_encoding($data, 'utf8'))
			{
				print("data in [" . $filename . "] is bad encoding\n");
				continue;
			}
			
			// pdo statement に渡す parameter
			$in_parameters = array (
								':v_filename' => $filename, 
								':v_fileext' => $fileext,
								':v_folderpath'=> $dirname, 
								':v_detail'=> $data,
								':v_lastupdate' => $lastupdate,
								':v_revision' => $revision,
								':v_hash' => $hash
								);
			
			// SQL 挿入
			$ret = $stmt_insert->execute($in_parameters);
			if ($ret)
			{
				print ("insert [" . $filename . "]\n");	// 挿入成功
				continue;
			}
	
			
			// 挿入失敗したら...
	
			// ハッシュ値を比較して、更新が必要か判定する
			$ret = $stmt_exist->execute(
							array(
								':v_hash' => $hash
								)
							);
			if ($ret)
			{
				// ヒットした = 内容は一緒。なので更新必要無し
				print ("not update [" . $filename . "]\n");
				continue;
			}
	
			// SQL 更新
			$ret = $stmt_update->execute($in_parameters);

			if (!$ret)
			{
				print ("SQL ERROR:{$sql_update}\n");	// プログラム何かミスった？
				break;
			}
			if ($stmt_update->rowCount() > 0)
			{
				print ("update [" . $filename . "]\n");
			}
		}
		
	} catch(PDOException $e){
		echo 'exception!!';
		echo $e->getMessage();
		var_dump($e->getMessage());
	}
	
	// 切断
	$pdo = null;
	
	return;
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
