<?php
// 共通処理を別ファイルに分離
require_once(dirname(__FILE__) . '/common.php');
require_once(dirname(__FILE__) . '/logfunc.php');

header('Content-Type: text/html; charset:utf-8');

//-------------------------------------------------------------------------
$html_image = <<<EOD
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>検索入力</title>
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<style>
  article, aside, dialog, figure, footer, header,
  hgroup, menu, nav, section { display: block; }
  #search_word { width: 50%% }
  .search_result { 
    margin:10px 10px 10px 10px; 
    background-color: #dddddd;
  }
</style>
</head>
<body>
  <header>
    <p>検索したいワードを入力して</p>
  </header>
<!--
  <nav>
    navi
  </nav>
-->
  <!-- メインコンテンツ -->
  <div>
    <form method="get" action="./search.php">
      <input id="search_word" type="text" name="words" value="%s"/>
      <input type="submit" value="検索" />
    </form>
  </div>
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

$sql_select = <<<EDO
select * from master_data 
where 
EDO;


try {
	// MySQLサーバへ接続
	$pdo = new_PDO();
	
	logfunc_add_accesslog($pdo);
	
	$search_word = $_GET['words'];
	
	// チェック
	if(!mb_check_encoding($search_word))
	{
		exit('parameter is failed.');
	}
	
	// 
	$search_word = $search_word;
	$word_array = explode(' ', $search_word);
	if (count($word_array) == 0)
	{
		print("count is 0");
		$word_array = array($search_word);
	}
	
	$sql_wheres = array();
	$param_array = array();
	
	foreach ($word_array as $value)
	{
		$sql_wheres[] = 'detail like ?';
		$param_array[] = '%' . $value . '%';
	}
	$sql_select .= join(' and ', $sql_wheres);
	$sql_select .= ';';
	
	print ($sql_select);
	
	// 検索
	$stmt = $pdo->prepare($sql_select);
	
	
	$ret = $stmt->execute($param_array);
	if (!$ret)
	{
		print("execute failed\n");
	}
		
	$result_body = '';
				
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$result_body .= '<div class="search_result">';
		$result_body .= '<a href="./view.php?filename=' . $row['filename'] . '">';
		$result_body .= $row['filename'] . "<br /></a>";
		$result_body .= htmlspecialchars(mb_substr($row['detail'], 0, 100)) . "<br />";
		$result_body .= "</div>\n";
    }
    
    printf($html_image, $search_word, $result_body);
    	
} catch(PDOException $e){
	echo 'exception!!';
	echo $e->getMessage();
	var_dump($e->getMessage());
}

// 切断
$pdo = null;

?>
