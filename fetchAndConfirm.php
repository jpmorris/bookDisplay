<?php
session_start();

include 'config.php';

/**
*  Creats AWS request and signs the request
*  Wraps AWS response in user defined XML.
*  To test add your AWS access key code ID and secrete access key and set $useTestData = true;
*/
//header ("content-type: text/xml");
// Configurable values
// Amazon login info in config.php
$amazonErrorRootNode = "<Errors";      	   		       // First node with < from amazon for error  response.

// Developer values
$version = "v.01.00.00";					// Version of this script

// Debugging values
$debug = false;							// Debugging status
$useTestData = false;						// Use embedded testing data

// Program controlled values
$success = "false";						// Default success value.
$params = array();						// The parameters to pass to AWS
$returnXML = "";		     		     	        // XML returned
$returnXML = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" ;		     		     	        // XML returned

$isbn = ($_SESSION['isbn'] = $_POST['isbn']);
$isbn = trim($isbn);
if ($useTestData)
{
	$params =  array("Operation"=>"ItemSearch",
                        "Keywords"=>"Beatles Abbey Road",
                        "Service"=>"AWSECommerceService",
                        "Sort"=>"salesrank",
                        "SearchIndex"=>"Music",
                        "Count"=>"25",
			"ResponseGroup"=>"Medium,Tracks,Offers");
}
else
{
  $params =  array("Operation"=>"ItemLookup",
		   "ResponseGroup"=>"Small,Images,ItemAttributes",
		   "ItemId"=>$isbn);
//"ItemId"=>"007149619X");
}

$returnXML .= "<response>";
$returnXML .= "<version>";
$returnXML .= $version;
$returnXML .= "</version>";
if ($debug)
{
	$returnXML .= "<isTestData>";
	$returnXML .= $useTestData ? "true":"false";
	$returnXML .= "</isTestData>";
}

function aws_signed_request( $public_key, $private_key, $params)
{
	$method = "GET";
	$host = "ecs.amazonaws.com";
	$uri = "/onca/xml";

	$timestamp = gmstrftime("%Y-%m-%dT%H:%M:%S.000Z");
	$timestamp = "&Timestamp=" . rawurlencode($timestamp);

	$params["AWSAccessKeyId"] = $public_key;

	$workurl="";
    foreach ($params as $param=>$value)
    {
	$workurl .= ((strlen($workurl) == 0)? "" : "&") . $param . "=" . rawurlencode($value);
    }
    //$workurl = str_replace(" ","%20",$workurl);
    $workurl = str_replace(",","%2C",$workurl);
    $workurl = str_replace(":","%3A",$workurl);
    $workurl .= $timestamp;
    $params = explode("&",$workurl);
    sort($params);

    $signstr = "GET\n" . $host . "\n/onca/xml\n" . implode("&",$params);
    $signstr = base64_encode(hash_hmac('sha256', $signstr, $private_key, true));
    $signstr = rawurlencode($signstr);
    $signedurl = "http://" .$host . $uri . "?" . $workurl  . "&Signature=" . $signstr;
    return $signedurl;
}

// Make the signed url for AWS
$signedurl = aws_signed_request( $public_key, $private_key, $params);

if ($debug)
{
	$returnXML .= "<signed_url>";
	$returnXML .= $signedurl;
	$returnXML .= "</signed_url>";
}

// Make request to AWS
$response = @file_get_contents($signedurl);

// The file_get_contents has failed. See PHP documentation for that.
if ($response === false) // Equal and same data type
{
	$success = "false";
}
// AWS returned a response
else
  {
    $returnXML .= "<results>";
    // AWS did not return an error code
    if (strpos($response, $amazonErrorRootNode) == 0)
      {
	$success = "true";
	
	$returnXML .= substr($response, strpos($response, "?>")+2); // Strip Amazon XML header
      }
    // AWS returned an error code
    else
      {
	$success = "false";
	$returnXML .= substr($response, strpos($response, "?>")+2); // Strip Amazon XML header
      }
    $returnXML .= "</results>";
    
}

$returnXML .= "<success>";
$returnXML .= $success;
$returnXML .= "</success>";

$returnXML .= "</response>";
//print "START";
//print $returnXML;
//$returnXML is XML data, now need to parse



$xmlstring = new SimpleXMLElement($returnXML);

//echo $xmlstring;

//print $xmlstring->results->ItemLookupResponse->OperationRequest->Items->Request->Item->ItemAttributes->title;
//print  (string) $xmlstring->results->;


$title = (string) $xmlstring->results->ItemLookupResponse->Items->Item->ItemAttributes->Title;
$imageurl = (string) $xmlstring->results->ItemLookupResponse->Items->Item->LargeImage->URL;
$i = 1;
$authorAry = array();
foreach($xmlstring->results->ItemLookupResponse->Items->Item->ItemAttributes->Author as $author){
	$authorAry[$i] = (string) $author;
	$i++;
	if($i >=7){
	  break;	
	}	
}
//foreach($authorAry as $author){
//	echo $author,"<br>\n";
//}

//strip non-universal tradmark symbol
$title = preg_replace('/™/', '', $title);
$title = preg_replace('/â„¢/', '', $title);



$_SESSION['title'] = $title;
$_SESSION['imageurl'] = $imageurl;
$_SESSION['authorAry'] = $authorAry;

?>

<head> 
	<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" title="bbxcss" /> 
	<style type="text/css"> 
	</style> 
</head> 
	<body>
	
<script type="text/javascript" src="js/navbar.js"> </script>
		<form id="verifyform" enctype="multipart/form-data" name="form2" method="POST" action="uploadandinsertBook.php">
			<fieldset>
				<h1>Verify the information</h1>
					<img src="<?php echo $imageurl?>">
					<div id="titleheading">Title</div> 
					<div id="booktitle"><?php echo $title?></div>
				<h1>Additional information</h1>		
				 <ol>
		            <li> 
						<label for="tags">Tags (seperated by commas)</label>
						<input name="tags" type="text" id="tags">
		            </li> 
		            <li> 
						<label for="category">Category</label>
						<input name="category" type="text" id="category">
		            </li> 
		            <li> 
						<label for="subcategory">Subcategory</label>
						<input name="subcategory" type="text" id="subcategory">
		            </li> 
		            <li> 
						<label for="customimageurl">Custom image URL</label>
						<input name="customimageurl" type="text" id="customimageurl">
		            </li> 
					<li> 
						<label for="isocred">Is OCRed?</label>
						<input name="isocred" type="checkbox" value="1" id="isocred">
						
		            </li> 
		            <li> 
						<label for="file">File to upload</label>
						<input name="uploadedfile" type="file" id="file">
						
		            </li> 
		           
		        </ol> 
		       <input type="submit" name="Submit" value="Upload File and Insert info into Database">
		    </fieldset> 
		</form> 
	</body>