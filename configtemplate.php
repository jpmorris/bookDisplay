<?php
session_start();

include 'functions.php';

//constants -- youll want to change these
$rootdir = "/media/storage1/docs/books/";
$imagedir = "coverimages/";
$webpath = 'http://spike/books/';
$fetchbyISBNpage = "fetchbyISBN.php";
$bookdisplaywebpath = 'http://spike/bookDisplay/';
$filetype = "pdf";
$public_key = "kjfkqljnrlwrkeklwr";				// AWS access key code ID
$private_key = "skhfskajfklsadjflksjafkldsjfklsdjfkljsfa";      // AWS secret access key)
$host = "localhost";
$user = "localuser";
$pass = "pass";
$db_name = "bookDisplay";
$awsversion = "2010-11-01";
$associatetag = "httpwwwawebsite-20"; //associatetag need to get this from amazon

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