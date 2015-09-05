<?php
$route = '/paper/:paper_id/';
$app->put($route, function ($paper_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$paper_id = prepareIdIn($paper_id,$host);

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['title'])){ $title = mysql_real_escape_string($params['title']); } else { $title = 'No Title'; }
	if(isset($params['author'])){ $author = mysql_real_escape_string($params['author']); } else { $author = ''; }
	if(isset($params['summary'])){ $summary = mysql_real_escape_string($params['summary']); } else { $summary = ''; }

  	$Query = "SELECT * FROM paper WHERE paper_id = " . $paper_id;
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{
		$query = "UPDATE paper SET";

		$query .= " title = '" . mysql_real_escape_string($title) . "'";

		if($author!='') { $query .= ", author = '" . $author . "'"; }
		if($summary!='') { $query .= ", summary = '" . $summary . "'"; }

		$query .= " WHERE paper_id = '" . $paper_id . "'";

		//echo $query . "<br />";
		mysql_query($query) or die('Query failed: ' . mysql_error());
		}

	$paper_id = prepareIdOut($paper_id,$host);
	
	$F = array();
	$F['paper_id'] = $paper_id;
	$F['title'] = $title;
	$F['author'] = $author;
	$F['summary'] = $summary;

	array_push($ReturnObject, $F);

	$app->response()->header("Content-Type", "application/json");
	echo stripslashes(format_json(json_encode($ReturnObject)));

	});
?>
