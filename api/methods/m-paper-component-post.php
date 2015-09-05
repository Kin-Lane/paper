<?php
$route = '/paper/component/';
$app->post($route, function () use ($app,$appid,$appkey){

	$Add = 1;
	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['paper_id'])){ $paper_id = mysql_real_escape_string($params['paper_id']); } else { $paper_id = 0; }
	if(isset($params['name'])){ $name = mysql_real_escape_string($params['name']); } else { $name = 'No Title'; }
	if(isset($params['type'])){ $type = mysql_real_escape_string($params['type']); } else { $type = ''; }
	if(isset($params['sort_order'])){ $sort_order = mysql_real_escape_string($params['sort_order']); } else { $sort_order = ''; }

  	$Query = "SELECT * FROM paper_component WHERE paper_id = " . $paper_id . " AND name = '" . $name . "' AND type = '" . $type . "'";
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{
		$ThisPaper = mysql_fetch_assoc($Database);
		$paper_component_id = $ThisPaper['paper_component_id'];
		}
	else
		{
		$Query = "INSERT INTO paper_component(paper_id,name,type,sort_order)";
		$Query .= " VALUES(";
		$Query .= mysql_real_escape_string($paper_id) . ",";
		$Query .= "'" . mysql_real_escape_string($name) . "',";
		$Query .= "'" . mysql_real_escape_string($type) . "',";
		$Query .= "'" . mysql_real_escape_string($sort_order) . "'";
		$Query .= ")";
		//echo $Query . "<br />";
		mysql_query($Query) or die('Query failed: ' . mysql_error());
		$paper_component_id = mysql_insert_id();

		if($type=='Page')
			{

			$url = "https://page.api.kinlane.com:443/page/";
			echo $url . "<br />";

			$title = $name;
			$body = '<p>hellow world!</p>';
			$fields_string = "";
			$fields = array(
							'appid' => urlencode($appid),
							'appkey' => urlencode($appkey),
							'title' => urlencode($title),
							'body' => urlencode($body)
							);

			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');

			$http = curl_init();

			curl_setopt($http,CURLOPT_URL, $url);
			curl_setopt($http, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($http,CURLOPT_POST, count($fields));
			curl_setopt($http,CURLOPT_POSTFIELDS, $fields_string);
			curl_setopt($http, CURLOPT_SSL_VERIFYPEER, false);

			$output = curl_exec($http);
			$http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
			$info = curl_getinfo($http);
			//echo $output;
			$Page = json_decode($output);
			var_dump($Page);
			$page_id = $Page->page_id;

			$UpdateQuery = "UPDATE paper_component SET page_id = " . $page_id . " WHERE paper_component_id = " . $paper_component_id;
			echo $UpdateQuery . "<br />";
			mysql_query($UpdateQuery) or die('Query failed: ' . mysql_error());
			}
		}

	$paper_component_id = prepareIdOut($paper_component_id,$host);
	$ReturnObject['paper_component_id'] = $paper_component_id;

	$app->response()->header("Content-Type", "application/json");
	echo format_json(json_encode($ReturnObject));

	});
?>
