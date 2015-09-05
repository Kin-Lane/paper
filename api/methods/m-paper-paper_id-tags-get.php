<?php
$route = '/paper/:paper_id/tags/';
$app->get($route, function ($paper_id)  use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$paper_id = prepareIdIn($paper_id,$host);

	$ReturnObject = array();

 	$request = $app->request();
 	$param = $request->params();

	$Query = "SELECT t.tag_id, t.tag FROM tags t";
	$Query .= " JOIN paper_tag_pivot btp ON t.tag_id = btp.tag_id";
	$Query .= " WHERE btp.paper_id = " . $paper_id;

	$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());

	while ($Database = mysql_fetch_assoc($DatabaseResult))
		{

		$tag_id = $Database['tag_id'];
		$Tag_Text = $Database['tag'];

		$tag_id = prepareIdOut($tag_id,$host);

		$F = array();
		$F['tag_id'] = $tag_id;
		$F['tag'] = $Tag_Text;

		array_push($ReturnObject, $F);
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
