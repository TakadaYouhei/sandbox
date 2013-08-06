<?php
// @file csv_record_info.php
// csv ファイルのレコードの種類が何かをチェックする関数等の定義


/// ここに定義されている文字列が、csv の 1 個目にあるレコードをヘッダ用レコードとする
$g_csv_record_headers = array(
	'header_type1',
	'header_type2'
);


/// csv レコードデータがヘッダ用のレコードなら true を返す
/// @param[in] $in_csv_record csvファイルの1行を読み込んで配列に格納した物
/// @return ヘッダ情報のレコードなら TRUE. それ以外なら FALSE を返す
function is_header_csv_record($in_csv_record)
{
	global $g_csv_record_headers;
	
	if (in_array($in_csv_record[0], $g_csv_record_headers))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}


?>
