<?php
	
	require 'rb.php';
	
	$host = "localhost";
	$dbname = "proyectophp";
	$user = "proyectoPHP";
	$password = "123456";
	
	R::setup( 'mysql:host='.$host.';dbname='.$dbname.'', $user, $password ); //for both mysql or mariaDB
	
	$action = $_REQUEST["action"];
	
	$response = array();
	$response["status"] = false;
	$response["error"] = "Bad request!";
	
	if($action == "put"){
		$imagen = $_FILES["imagen"]["tmp_name"];
	
		$check = getimagesize($_FILES["imagen"]["tmp_name"]);
		
		 if($check !== false) {
			 $imgData = base64_encode(file_get_contents($imagen));
			 $src = 'data: '.$check["mime"].';base64,'.$imgData;
			 
			 $img = R::dispense('img');
			 $img->data = $src;
			 $img->name = $_REQUEST['name'];
			 
			 R::store($img);
			 
			 $response["status"] = true;
		 }else{
			 $response["error"] = "File is not an image!";
		 }
		
	}else if($action == "get"){
		
		$type = $_REQUEST["type"];
		
		if($type == "tag"){
			$img = R::load("img", $_REQUEST['id']);
			
			$response["status"] = true;
			$response["data"] = '<img src="'.$img->data.'" width="500px" height="auto" />';
		}else if($type == "data"){
			$img = R::load("img", $_REQUEST['id']);
			
			$response["status"] = true;
			$response["data"] = $img->data;
		}
	}else if($action == "search"){
		$imgs = R::find( 'img', ' name LIKE ? ', [ $_REQUEST['name'].'%' ] );
		if(count($imgs) >0){
			$images = array();
			
			foreach($imgs as $img){
				$imgArray = array();
				$imgArray['id'] = $img->id;
				$imgArray['name'] = $img->name; 
				
				$images[] = $imgArray;
			}
			
			$response["status"] = true;
			$response['data'] = $images;
		}
	}
	
	echo json_encode($response);
		
?>