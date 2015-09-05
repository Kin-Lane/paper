<?php
$route = '/paper/tags/:tag/paper/';
$app->get($route, function ($tag)  use ($app){

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($_REQUEST['week'])){ $week = $params['week']; } else { $week = date('W'); }
	if(isset($_REQUEST['year'])){ $year = $params['year']; } else { $year = date('Y'); }

	$Query = "SELECT b.* from tags t";
	$Query .= " JOIN paper_tag_pivot btp ON t.tag_id = btp.tag_id";
	$Query .= " JOIN paper b ON btp.paper_id = b.ID";
	$Query .= " WHERE WEEK(b.Post_Date) = " . $week . " AND YEAR(b.Post_Date) = " . $year . " AND Tag = '" . $tag . "'";

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
