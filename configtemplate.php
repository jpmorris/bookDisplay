<?php
//constants
$rootdir = "/media/storage1/docs/"; //parent directory of where the books will be stored
$bookdir = "books/"; 				//name of the book folder
$imagedir = "books/coverimages/"; 	//location of coverimage folder
$filetype = "pdf"; 					//the filetype of the books in the library
$public_key = "<AWS access key>";				// AWS access key code ID
$private_key = "<AWS secret key>";      // AWS secret access key)
$host = "<hostname>";
$user = "<username>";
$pass = "<password>";
$db_name = "<db name>";

$debug = 1;

//defined functions
function checkResult($result,$query){
	if(!$result){
		$message = 'Invalid query: '.mysql_error()."\n";
		$message .= 'Whole query: '.$query;
		die($message);
	}
}

//database connection

$con = mysql_connect($host, $user, $pass);
if (!$con){
	die('Could not connect: ' . mysql_error());
}
mysql_select_db($db_name);
mysql_query("SET NAMES utf8");
?>