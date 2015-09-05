<?php
$route = '/paper/:paper_id/';
$app->get($route, function ($paper_id)  use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$paper_id = prepareIdIn($paper_id,$host);

	$ReturnObject = array();

	$Query = "SELECT * FROM paper WHERE paper_id = " . $paper_id;

	$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());

	while ($Database = mysql_fetch_assoc($DatabaseResult))
		{

		$paper_id = $Database['paper_id'];
		$title = $Database['title'];
		$author = $Database['author'];
		$summary = $Database['summary'];

		// manipulation zone
		$paper_id = prepareIdOut($paper_id,$host);

		$F = array();
		$F['paper_id'] = $paper_id;
		$F['title'] = $title;
		$F['author'] = $author;
		$F['summary'] = $summary;

		$ReturnObject = $F;
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
