<?php


include 'config.php';

if(isset($_POST['isbn'])){
	$isbn = ($_SESSION['isbn'] = $_POST['isbn']);
}
elseif(isset($_GET['isbn'])){
	$isbn = ($_SESSION['isbn'] = $_GET['isbn']);
}

$isbn = trim($isbn);




$params =  array("Operation"=>"ItemLookup",
		   "ResponseGroup"=>"Small,Images,ItemAttributes",
		   "ItemId"=>$isbn);

$returnXML = get_amazon_xml( $public_key, $private_key, $params);


$xmlstring = new SimpleXMLElement($returnXML);


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