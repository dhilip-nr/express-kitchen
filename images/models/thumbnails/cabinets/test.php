<?php
$data = scandir("./");
$res = [];
foreach($data as $item) {
	$name = explode(".", $item);
	
	if(isset($name[1]) && $name[1] == "png") {
		$res[] = [
			"name"=> $name[0],
			"image"=> "images/models/thumbnails/cabinets/".$name[0].".png",
			"model"=> "assets/models/cabinets/cube.js",
			"type"=> "8"
		];
	}
}
echo json_encode($res); exit;

?>