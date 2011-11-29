<?php


function get_amazon_xml( $public_key, $private_key, $associatetag, $params)
{

	// Amazon login info in config.php
	$amazonErrorRootNode = "<Errors";

	// Program controlled values
	$success = "false";												// Default success value.
	$returnXML = "";		     		   							// XML returned
	$returnXML = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" ;		// XML returned 


	$returnXML .= "<response>";

	// Make the signed url for AWS
	$signedurl = aws_signed_request( $public_key, $private_key, $associatetag, $params);

	// Make request to AWS
	$response = @file_get_contents($signedurl);

	// The file_get_contents has failed. See PHP documentation for that.
	if ($response === false) // Equal and same data type
	{
		$success = "false";
		print "file_get_contents failed";
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
//	print $returnXML;
	//$returnXML is XML data, now need to parse
	return $returnXML;
}



function aws_signed_request( $public_key, $private_key, $associatetag, $params)
{
	$method = "GET";
	$host = "ecs.amazonaws.com";
	$uri = "/onca/xml";

	$timestamp = gmstrftime("%Y-%m-%dT%H:%M:%S.000Z");
	$timestamp = "&Timestamp=" . rawurlencode($timestamp);

	$params["AWSAccessKeyId"] = $public_key;
    $params["AssociateTag"] = $associatetag;
	
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




?>