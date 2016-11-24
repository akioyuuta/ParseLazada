<?php

include "parse.php";

// Read Target URL
$f = fopen("target.dat","r");
while(($str = fgets($f)) !== false){
	//echo str_replace("\r\n", "", $str)."<br>";
	parse(str_replace("\r\n", "", $str));
}

?>