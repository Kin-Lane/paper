<?php
$route = '/paper/';
$app->get($route, function ()  use ($app){

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['query']) && $params['query'] != ''){ $query = trim(mysql_real_escape_string($params['query'])); } else { $query = '';}
	if(isset($params['page']) && $params['page'] != ''){ $page = trim(mysql_real_escape_string($params['page'])); } else { $page = 0;}
	if(isset($params['count']) && $params['count'] != ''){ $count = trim(mysql_real_escape_string($params['count'])); } else { $count = 250;}
	if(isset($params['sort']) && $params['sort'] != ''){ $sort = trim(mysql_real_escape_string($params['sort'])); } else { $sort = 'Title';}
	if(isset($params['order']) && $params['order'] != ''){ $order = trim(mysql_real_escape_string($params['order'])); } else { $order = 'ASC';}

	// Pull from MySQL
	if($query!='')
		{
		$Query = "SELECT * FROM paper WHERE Title LIKE '%" . $query . "%'";
		}
	else
		{
		$Query = "SELECT * FROM paper";
		}
	$Query .= " ORDER BY " . $sort . " " . $order . " LIMIT " . $page . "," . $count;
	//echo $Query . "<br />";

	$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());

	while ($Database = mysql_fetch_assoc($DatabaseResult))
		{

		$paper_id = $Database['paper_id'];
		$title = $Database['title'];
		$author = $Database['author'];
		$summary = $Database['summary'];

		// manipulation zone
		$host = $_SERVER['HTTP_HOST'];
		$paper_id = prepareIdOut($paper_id,$host);

		$F = array();
		$F['paper_id'] = $paper_id;
		$F['title'] = $title;
		$F['author'] = $author;
		$F['summary'] = $summary;

		array_push($ReturnObject, $F);
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
