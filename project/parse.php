<?php

function getSslPage($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
	curl_setopt($ch, CURLOPT_PROXY, 'http://proxy.petra.ac.id:8080');
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function clean($string) {
  	$string = str_replace("\n", "", $string);
   	return $string;
}

function parse($url){
	//Parse Start
	require_once('simple_html_dom.php');
	// GET HTML
	$html = getSslPage($url);
	$html = str_get_html($html);

	$js = $html->find('script',6);

	$tmp = explode(");",explode("push",$js->innertext)[1]);
	$target = $str = ltrim($tmp[0], '(');
	
	// JSON DECODE
	$json = json_decode($target,true);

	// View Data
	/*
	echo "ID        : ".$json['page']['product']['id'].'<br>';
	echo "Item      : ".$json['pdt_name'].'<br>';
	echo "Price     : ".$json['pdt_price'].'<br>';
	echo "Discount  : ".$json['pdt_discount'].'<br>';
	echo "Photo     : ".$json['pdt_photo'].'<br>';
	echo "Inventory : ".$json['page']['product']['inventoryCount'].'<br>';
	echo "Category  : ".$json['pdt_category'].'<br>';
	echo "Brand     : ".$json['pdt_brand'].'<br>';
	echo "Delivery  : ".$json['page']['product']['deliveryTime'].'<br>';
	*/
	$id = $json['page']['product']['id'];
	$item = clean($json['pdt_name']);
	$price = $json['pdt_price'];
	$discount = $json['pdt_discount'];
	$photo = $json['pdt_photo'];
	$inventory = $json['page']['product']['inventoryCount'];
	$category = str_replace(',', "|", $json['pdt_category']);
	$brand = $json['pdt_brand'];
	$delivery = str_replace("-", ">", $json['page']['product']['deliveryTime']);
	$date = date('Y/m/d');

	// Input to File
	$file = fopen("data.csv","a");
	$line = $id.",".$item.",".$price.",".$discount.",".$photo.",".$inventory.",".$category.",".$brand.",".$delivery.",".$date."\n";
	fputs($file,$line);
	fclose($file);
}

?>