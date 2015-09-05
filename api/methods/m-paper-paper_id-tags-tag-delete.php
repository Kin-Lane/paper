<?php
$route = '/paper/:paper_id/tags/:tag/';
$app->delete($route, function ($paper_id,$tag)  use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$paper_id = prepareIdIn($paper_id,$host);

	$ReturnObject = array();

 	$request = $app->request();
 	$param = $request->params();

	if($tag != '')
		{

		$paper_id = trim(mysql_real_escape_string($paper_id));
		$tag = trim(mysql_real_escape_string($tag));

		$CheckTagQuery = "SELECT tag_id FROM tags where Tag = '" . $tag . "'";
		$CheckTagResults = mysql_query($CheckTagQuery) or die('Query failed: ' . mysql_error());
		if($CheckTagResults && mysql_num_rows($CheckTagResults))
			{
			$Tag = mysql_fetch_assoc($CheckTagResults);
			$tag_id = $Tag['tag_id'];

			$DeleteQuery = "DELETE FROM paper_tag_pivot where tag_id = " . trim($tag_id) . " AND paper_id = " . trim($paper_id);
			$DeleteResult = mysql_query($DeleteQuery) or die('Query failed: ' . mysql_error());
			}

		$tag_id = prepareIdOut($tag_id,$host);

		$F = array();
		$F['tag_id'] = $tag_id;
		$F['tag'] = $tag;
		$F['paper_count'] = 0;

		array_push($ReturnObject, $F);

		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
