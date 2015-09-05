<?php
$route = '/paper/:paper_id/';
$app->delete($route, function ($paper_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$paper_id = prepareIdIn($paper_id,$host);

	$Add = 1;
	$ReturnObject = array();

 	$request = $app->request();
 	$_POST = $request->params();

	$query = "DELETE FROM paper WHERE paper_id = " . $paper_id;
	//echo $query . "<br />";
	mysql_query($query) or die('Query failed: ' . mysql_error());

	});
?>
