<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type"></meta>
<title>Keyword Search</title>
<link href="css/style.css" type="text/css" rel="stylesheet"></link>
<script type="text/javascript" src="js/navbar.js"></script>
</head>

<?php
include 'config.php';

$keywords = ($_SESSION['keywords'] = $_POST['keywords']);
$keywords = trim($keywords);


$params =  array("Operation"=>"ItemSearch",
			"ResponseGroup" => "Small, Images",
		   "SearchIndex" => "Books",
		   "Keywords"=>$keywords,
           "Version"=>$awsversion);


$returnXML = get_amazon_xml( $public_key, $private_key, $associatetag, $params);


$xmlstring = new SimpleXMLElement($returnXML);


//get and print out relevant data for each result:
foreach($xmlstring->results->ItemSearchResponse->Items->Item as $item){
	$isbn = (string) $item->ASIN;
	$title = (string) $item->ItemAttributes->Title;
	$author1 = (string) $item->ItemAttributes->Author;
	$imageurl = (string) $item->LargeImage->URL;
	
	//print $isbn."<br>".$title."<br>".$author1."<br>".$imageurl."<br><br>";
	
	echo '<div id="bookcontainer">';
	echo '<a href="'.$bookdisplaywebpath.$fetchbyISBNpage.'?isbn='.$isbn.'"><img src="'.$imageurl.'" id="bookimg"></img></a>';
	echo '<b>'.$title.'</b><br> '.$author1;
	echo '</div>';
	
	
}


?>

</html>