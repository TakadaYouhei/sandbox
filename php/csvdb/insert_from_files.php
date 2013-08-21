<?php
// test
// @file insert_from_files.php
// @brief フォルダをパースしてデータベースにデータを挿入する (コマンドラインツール)
// 実行例
//
//   /Applications/MAMP/bin/php5.3/bin/php insert_from_files.php testdata/**/*.csv

// 共通処理を別ファイルに分離
require_once(dirname(__FILE__) . '/common.php');

// 参照する他のモジュール
require_once(dirname(__FILE__) . '/csv_record_info.php');

$tbl_header_name = '`test`.`csv_head`';
$tbl_record_name = '`test`.`csv_record`';

//
// ヘッダ挿入用 sql
//
// filename ... ファイル名 (含む拡張子) ex: 'sample.txt'
// fileext ... ファイルの拡張子。 '.' は含まない。 ex: 'txt', 'csv'
// folderpath ... ファイルが置かれているパス
// detail ... ファイルの内容
// lastupdate .... 最終更新日
// revision .... 更新に絡む情報
// hash .... sha1 の 40文字の文字列
$sql_insert_header = <<<EOD
INSERT INTO $tbl_header_name (filename,    folderpath,    regist_date,    hash,    plane_data,    csv_head1,    csv_head2,    csv_head3,    csv_head4)
                      VALUES (:v_filename, :v_folderpath, :v_regist_date, :v_hash, :v_plane_data, :v_csv_head1, :v_csv_head2, :v_csv_head3, :v_csv_head4)
;
EOD;

// 既に登録済みのファイルかどうかを判定するのに使用。
// 生データの sha1 で検索してヒットするかどうかでチェック(すれば ok ?)
$sql_exists_record = <<<EOD
SELECT * FROM $tbl_header_name WHERE EXISTS (
		SELECT * FROM $tbl_header_name
		WHERE $tbl_header_name.`hash` = :v_hash
	)
;
EOD;

$sql_insert_record = <<<EDO
INSERT INTO $tbl_record_name 
			(
				ref_id_head,    record_date,    
				csv_data01, csv_data02, csv_data03, csv_data04, csv_data05, csv_data06, csv_data07, csv_data08, csv_data09, csv_data10,
				csv_data11, csv_data12, csv_data13, csv_data14, csv_data15, csv_data16, csv_data17, csv_data18, csv_data19, csv_data20
			)
            VALUES 
            (
            	:v_ref_id_head, :v_record_date, 
				:v_csv_data01, :v_csv_data02, :v_csv_data03, :v_csv_data04, :v_csv_data05, :v_csv_data06, :v_csv_data07, :v_csv_data08, :v_csv_data09, :v_csv_data10,
				:v_csv_data11, :v_csv_data12, :v_csv_data13, :v_csv_data14, :v_csv_data15, :v_csv_data16, :v_csv_data17, :v_csv_data18, :v_csv_data19, :v_csv_data20
            )
;
EDO;

/// csv の指定のデータを返す。
/// データが無いときは NULL を返す
/// @param[in] $csv_record 配列
/// @param[in] $at 配列の何番目のデータを参照するのか
function get_csvdata($csv_record, $at)
{
	if (array_key_exists($at, $csv_record))
	{
		return $csv_record[$at];
	}
	else
	{
		return NULL;
	}
}

/// @brief フォルダ以下にあるファイルを DB に登録する。既に登録済みなら、更新する。
/// @in $inpath 検索を開始するルートフォルダのパス
function insert_from_files($inpath)
{
	global $sql_insert_header;
	global $sql_exists_record;
	global $sql_insert_record;
	
	try {
		// MySQLサーバへ接続
		$pdo = new_PDO();
		
		$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		$pdo->query("SET NAMES utf8;");
		
		$stmt_insert_header = $pdo->prepare($sql_insert_header);
		$stmt_insert_record = $pdo->prepare($sql_insert_record);
		$stmt_exist = $pdo->prepare($sql_exists_record);
		
		
		// 検索
		$filelist = glob($inpath);
		
		// ファイル毎に処理
		foreach(glob($inpath) as $filepath)
		{
			// db に登録する情報
			// ・ヘッダ情報
			// ・生データ
			// ・レコード情報
			
			print("$filepath\n");
			
			// 生データ
			$plane_data = file_get_contents($filepath);
			if ($plane_data == FALSE)
			{
				print('ERROR : [' . $filepath . "] error?\n");
				continue;
			}
			
			// ヘッダ に登録するヘッダ情報
			$filename = basename($filepath);
			$dirname = dirname($filepath);
			$file_datetime = date('Y-m-d H:i:s', filemtime($filepath));	// ex) 1999-11-11 23:59:59
			$hash = sha1($plane_data);
			$regist_datetime = date('Y-m-d H:i:s', time());		// 今の時間 = 登録時間
			
			//
			// 既に登録済みか確認
			//
			
			// ハッシュ値を比較して、既に登録済みか判定
			$ret = $stmt_exist->execute(
							array(
								':v_hash' => $hash
								)
							);
			if (!$ret)
			{
				// 何かプログラムを間違えた ?
				print ("CRITICAL ERROR! $stmt_exist->execute() error.\n");
				continue;
			}
			$ret = $stmt_exist->fetch();
			if ($ret > 0)
			{
				// ヒットした = 既に登録ズミなので無視
				print ("still registed [" . $filename . "]\n");
				continue;
			}
			
			//
			// csv ファイルを読み込んでその他のヘッダ情報を収集
			//
			$header_append = array();
			$fp = fopen($filepath, "r");
			while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
				// csv から 1 行ずつ読みこんで $data に配列として格納
				if (!is_header_csv_record($data))
				{
					// ヘッダ以外がきたら、そこでヘッダ情報の解析終了 (というファイルフォーマット)
					break;
				}

				// ヘッダレコードなら情報収集
				$header_append(get_header_column_name_csv_record($data), get_header_value_csv_record($data));
			}
			fclose($fp);
 
			
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
			if (!mb_check_encoding($plane_data, 'utf8'))
			{
				print("plane_data in [" . $filename . "] is bad encoding\n");
				continue;
			}
			
			// ヘッダ登録
			// pdo statement に渡す parameter
			$in_parameters = array (
								':v_filename' => $filename, 
								':v_folderpath'=> $dirname, 
								':v_regist_date' => $regist_datetime,
								':v_hash' => $hash,
								':v_plane_data' => $plane_data,
								':v_csv_head1' => '',
								':v_csv_head2' => '',
								':v_csv_head3' => '',
								':v_csv_head4' => ''
								);
			foreach ($header_append as $key => $value)
			{
				$in_parameters[':v_' . $key] = $value;
			}
			
			// ヘッダ登録 SQL 実行
			$ret = $stmt_insert_header->execute($in_parameters);
			if (!$ret)
			{
				print ("ERROR : insert failed. [" . $filename . "]\n");	// 挿入失敗
				continue;
			}
	
			//
			// ヘッダ登録に成功した
			// ==> レコード登録に進む
			//
			
			// 最後に登録した id を控える => レコード登録時に参照する
			$header_id = $pdo->lastInsertId();
		
			// csv をもう一度開いて１件ずつレコードを登録する
			$fp = fopen($filepath, "r");
			while (($data = fgetcsv($fp, 0, ",")) !== FALSE) 
			{
				if (!array_key_exists(0, $data))
				{
					// 最初のカラムが無い => 終端で良いかな ?
					// 空行は無い想定。あるとしたら、最終行だけかな?
					break;
				}
				
				// 最初のカラムは日時
				// => MYSQL の形式に変換　（フォーマットは 'Y-m-d H:i:s'）
				$record_date = '2013-01-02 14:23:11';	// フォーマットは 'Y-m-d H:i:s'
				
				// ２t目以降は普通にデータとして記録
				$in_parameters = array (
									':v_ref_id_head' => $header_id, 
									':v_record_date' => $record_date,
									':v_csv_data01' => get_csvdata($data, 1),
									':v_csv_data02' => get_csvdata($data, 2),
									':v_csv_data03' => get_csvdata($data, 3),
									':v_csv_data04' => get_csvdata($data, 4),
									':v_csv_data05' => get_csvdata($data, 5),
									':v_csv_data06' => get_csvdata($data, 6),
									':v_csv_data07' => get_csvdata($data, 7),
									':v_csv_data08' => get_csvdata($data, 8),
									':v_csv_data09' => get_csvdata($data, 9),
									':v_csv_data10' => get_csvdata($data, 10),
									':v_csv_data11' => get_csvdata($data, 11),
									':v_csv_data12' => get_csvdata($data, 12),
									':v_csv_data13' => get_csvdata($data, 13),
									':v_csv_data14' => get_csvdata($data, 14),
									':v_csv_data15' => get_csvdata($data, 15),
									':v_csv_data16' => get_csvdata($data, 16),
									':v_csv_data17' => get_csvdata($data, 17),
									':v_csv_data18' => get_csvdata($data, 18),
									':v_csv_data19' => get_csvdata($data, 19),
									':v_csv_data20' => get_csvdata($data, 20)
									);
				$ret = $stmt_insert_record->execute($in_parameters);
				if (!$ret)
				{
					print ("ERROR : insert record failed. \n");	// 挿入失敗
				}
			}
			fclose($fp);

			// csv レコードの登録完了			
		}		// end of foreach()
		
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
