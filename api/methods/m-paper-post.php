<?php
$route = '/paper/';
$app->post($route, function () use ($app){

	$Add = 1;
	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['title'])){ $title = mysql_real_escape_string($params['title']); } else { $title = 'No Title'; }
	if(isset($params['author'])){ $author = mysql_real_escape_string($params['author']); } else { $author = ''; }
	if(isset($params['summary'])){ $summary = mysql_real_escape_string($params['summary']); } else { $summary = ''; }

  	$Query = "SELECT * FROM paper WHERE Title = '" . $title . "' AND Author = '" . $author . "'";
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{
		$ThisPaper = mysql_fetch_assoc($Database);
		$paper_id = $ThisPaper['paper_id'];
		}
	else
		{
		$Query = "INSERT INTO paper(title,author,summary)";
		$Query .= " VALUES(";
		$Query .= "'" . mysql_real_escape_string($title) . "',";
		$Query .= "'" . mysql_real_escape_string($author) . "',";
		$Query .= "'" . mysql_real_escape_string($summary) . "'";
		$Query .= ")";
		//echo $Query . "<br />";
		mysql_query($Query) or die('Query failed: ' . mysql_error());
		$paper_id = mysql_insert_id();
		}

	$host = $_SERVER['HTTP_HOST'];
	$paper_id = prepareIdOut($paper_id,$host);
	
	$ReturnObject['paper_id'] = $paper_id;

	$app->response()->header("Content-Type", "application/json");
	echo format_json(json_encode($ReturnObject));

	});
?>
