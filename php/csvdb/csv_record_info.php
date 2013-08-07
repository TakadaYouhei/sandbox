<?php
// @file csv_record_info.php
// csv ファイルのレコードの種類が何かをチェックする関数等の定義

// csv のフォーマット概要
// 2013-08-08 12:12:12,TYPE,value1, value2, value3


/// csv の 1 レコード目に key となる文字列があったら、レコードはヘッダとして扱う
/// value には head db に登録する時のカラム名が入る。
$g_csv_record_headers = array(
	'header_type1' => 'csv_head1',
	'header_type2' => 'csv_head2'
);


/// csv レコードデータがヘッダ用のレコードなら true を返す
/// @param[in] $in_csv_record csvファイルの1行を読み込んで配列に格納した物
/// @return ヘッダ情報のレコードなら TRUE. それ以外なら FALSE を返す
function is_header_csv_record($in_csv_record)
{
	global $g_csv_record_headers;
	
	$record_type_str = $in_csv_record[1];
	
	if (array_key_exists($record_type_str, $g_csv_record_headers))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}


/// csv レコードヘッダを db に登録する際のカラム名を返す。
/// @param[in] $in_csv_record csvファイルの1行を読み込んで配列に格納した物
/// @return ヘッダ情報のレコードなら db に登録する際のカラム名を文字列で返す。それ以外なら NULL を返す
function get_header_column_name_csv_record($in_csv_record)
{
	global $g_csv_record_headers;

	$record_type_str = $in_csv_record[1];
	
	$result = $g_csv_record_headers[$record_type_str];
	
	return $result;
}


/// csv レコードヘッダの、情報を文字列で返す
/// @param[in] $in_csv_record csvファイルの1行を読み込んで配列に格納した物
function get_header_value_csv_record($in_csv_record)
{
	// memo : 今の所、csv の 3 つ目のデータが、ヘッダ情報。という想定。今後、ヘッダの種類によっては複雑な操作を経て value を決定する事が発生するかもしれない。
	$result = $in_csv_record[2];
	
	return $result;
}

?>
