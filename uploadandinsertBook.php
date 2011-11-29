<head>
<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" title="bbxcss" />
<style type="text/css">
</style>
</head>

<script type="text/javascript" src="js/navbar.js"> </script>
<form>
<?php

include 'config.php';
// include '../Zend/Pdf.php';

// GET VARIABLE INFO

$title = $_SESSION['title'];
$imageurl = $_SESSION['imageurl'];
$authorAry = $_SESSION['authorAry'];
$tags = $_POST['tags'];
$category = $_POST['category'];
$subcategory = $_POST['subcategory'];
$isbn = $_SESSION['isbn'];
$customimageurl = $_POST['customimageurl'];

$isocred = 0;

if(isset($_POST['isocred'])){
	$isocred = 1;
}
$category = trim($category);
$subcategory = trim($subcategory);


// parse tags by comma delimiter
$tagAryuntrimmed = explode(",",$tags);
//remove leading and trailing spaces in tagAry
foreach($tagAryuntrimmed as $untrimmedtag){
	$tagAry = array_map('trim', $tagAryuntrimmed);
}

//get authors
if(isset($authorAry[1])){
	$author1 = $authorAry[1];
} else {
	$author1 = "";
}
if(isset($authorAry[2])){
	$author2 = $authorAry[2];
} else { $author2 = "";}
if(isset($authorAry[3])){
	$author3 = $authorAry[3];
}else { $author3 = "";}
if(isset($authorAry[4])){
	$author4 = $authorAry[4];
}else { $author4 = "";}
if(isset($authorAry[5])){
	$author5 = $authorAry[5];
}else { $author5 = "";}
//print info


$author1 = preg_replace('/ü/', 'u', $author1);
$author2 = preg_replace('/ü/', 'u', $author2);
$author3 = preg_replace('/ü/', 'u', $author3);
$author4 = preg_replace('/ü/', 'u', $author4);
$author5 = preg_replace('/ü/', 'u', $author5);

$author1 = preg_replace('/é/', 'e', $author1);
$author2 = preg_replace('/é/', 'e', $author2);
$author3 = preg_replace('/é/', 'e', $author3);
$author4 = preg_replace('/é/', 'e', $author4);
$author5 = preg_replace('/é/', 'e', $author5);

$author1 = preg_replace('/ö/', 'o', $author1);
$author2 = preg_replace('/ö/', 'o', $author2);
$author3 = preg_replace('/ö/', 'o', $author3);
$author4 = preg_replace('/ö/', 'o', $author4);
$author5 = preg_replace('/ö/', 'o', $author5);


$author1 = preg_replace('/\//', '', $author1);
$author2 = preg_replace('/\//', '', $author2);
$author3 = preg_replace('/\//', '', $author3);
$author4 = preg_replace('/\//', '', $author4);
$author5 = preg_replace('/\//', '', $author5);



echo $title,"<br>\n";
echo $imageurl,"<br>\n";
echo $customimageurl,"<br>\n";
$i = 1;
foreach($authorAry as $author){
	echo "Author: ".$author."<br>\n";
}
foreach($tagAry as $tag){
	echo "Tag: ".$tag."<br>\n";
}
echo "Category: ".$category."<br>\n";
echo "Subcategory: ".$subcategory."<br>\n";


//grab image extension
if($customimageurl != ""){
	$imageurl = $customimageurl;
}
$imagenameAry = explode(".", $imageurl);
$imageArysize = count($imagenameAry);
$imageextension = $imagenameAry[$imageArysize-1];


//remove illegal windows chars
$titleforfilename = preg_replace('/\'/', '', $title);
$titleforfilename = preg_replace('/,/', '', $titleforfilename);
$titleforfilename = preg_replace('/\?/', '', $titleforfilename);
$titleforfilename = preg_replace('/\[/', '', $titleforfilename);
$titleforfilename = preg_replace('/\]/', '', $titleforfilename);
$titleforfilename = preg_replace('/\//', '', $titleforfilename);
$titleforfilename = preg_replace('/\\\\/', '', $titleforfilename);
$titleforfilename = preg_replace('/=/', '', $titleforfilename);
$titleforfilename = preg_replace('/\+/', '', $titleforfilename);
$titleforfilename = preg_replace('/</', '', $titleforfilename);
$titleforfilename = preg_replace('/>/', '', $titleforfilename);
$titleforfilename = preg_replace('/:/', '', $titleforfilename);
$titleforfilename = preg_replace('/;/', '', $titleforfilename);
$titleforfilename = preg_replace('/\"/', '', $titleforfilename);
$titleforfilename = preg_replace('/\\*/', '', $titleforfilename);
$titleforfilename = preg_replace('/|/', '', $titleforfilename);
$titleforfilename = preg_replace('/\)/', '', $titleforfilename);
$titleforfilename = preg_replace('/\(/', '', $titleforfilename);
$titleforfilename = preg_replace('/&/', '', $titleforfilename);
$titleforfilename = preg_replace('/�/', '', $titleforfilename);
$titleforfilename = preg_replace('/™/', '', $titleforfilename);
$titleforfilename = preg_replace('/ü/', '', $titleforfilename);



//$titleforimagefilename = preg_replace('/\s/', '_',$titleforimagefilename);


//SANITY CHECKS

//check for ISBN in database

//if the title has more than 140 chars truncate 

if (strlen($titleforfilename) > 140) {
		$titleforfilename = substr($titleforfilename, 0, 140);
}




//does imagefile exist, if so die.
$imagefile = $titleforfilename."-".$author1.".".$imageextension;
$imagedirandfile = $rootdir.$imagedir.$imagefile;

if(file_exists($imagefile)){
	echo "The image exists: $imagefile\n";
	echo "<h2>Insersion Failed</h2>/n";
	exit();
}

$savedir = $rootdir.$category."/";
$savedirdb = $category."/";
if($subcategory){
	$savedir = $savedir.$subcategory."/";
	$savedirdb = $savedirdb.$subcategory."/";
}

$imagesavedir = $rootdir.$imagedir."/";
$imagesavedirdb = $imagedir;

$savefile = $titleforfilename."-".$author1.".".$filetype;
$savedirandfile = $savedir.$savefile;
//does file exist, if so die
if(file_exists($savedirandfile)){
	echo "The file exists: $savedirandfile\n";
	echo "<h2>Insersion Failed</h2>\n";
	exit();	
}

//last check: does category directory (and subdirectory)exist? if not, create.
if(!file_exists($savedir)){
  `mkdir -p "$savedir"`;
}
if($subcategory){
	if(!file_exists($savedir)){
	  `mkdir -p "$savedir"`;
	}
}



// copy image to image directory
if(!file_exists($imagefile)){
	file_put_contents($imagedirandfile, file_get_contents($imageurl));
}

// edit pdf metadata - ON THE TO-DO LIST
//$pdf = Zend_Pdf::load();


//UPLOAD FILE TO DIRECTORY

//establish upload directory if does not exist create


print "FILES: ".$_FILES['uploadedfile']['tmp_name']."<br>";
print "$savedirandfile<br>";

$tempfile = $_FILES['uploadedfile']['tmp_name'];

if(move_uploaded_file("{$tempfile}", $savedir."/".$savefile)) {
	if($debug){
	    echo "The file ".$savedirandfile." has been uploaded<br>\n";
	}
} else{
    die("There was an error uploading the file, please try again.<br>\n");
}



// INSERT INTO DATABASE

$con = mysql_connect($host, $user, $pass) or die ('Error: ' . mysql_error());
mysql_select_db($db_name);

//look up category
$query = "SELECT * FROM bd_category WHERE category = '$category'";
$result = mysql_query($query);
$numrows = mysql_num_rows($result);
//if category does not exist insert category
if($numrows == 0){
	if($debug){
		echo "No category $category .... Creating<br>\n";
	}
	$query = "INSERT INTO bd_category VALUES('NULL', '$category')";
	$result = mysql_query($query);
	checkResult($result, $query);
} else{
	if($debug){
		echo "Found category $category <br>\n";
	}
}

//look up subcategory
$query = "SELECT * FROM bd_subcategory WHERE subcategory = '$subcategory'";
$result = mysql_query($query);
$numrows = mysql_num_rows($result);
//if category does not exist insert category
if($numrows == 0){
	if($debug){
		echo "No subcategory $subcategory .... Creating<br>\n";
	}
	$query = "INSERT INTO bd_subcategory VALUES('NULL', '$subcategory')";
	$result = mysql_query($query);
	checkResult($result, $query);
} else{
	if($debug){
		echo "Found subcategory $subcategory <br>\n";
	}
}

//get id of category and subcategory
$query = "SELECT * FROM bd_category WHERE category = '$category'";
$result = mysql_query($query);
$row = mysql_fetch_assoc($result);
$categoryid = $row['id'];

$query = "SELECT * FROM bd_subcategory WHERE subcategory = '$subcategory'";
$result = mysql_query($query);
$row = mysql_fetch_assoc($result);
$subcategoryid = $row['id'];



$title = mysql_real_escape_string($title);
$author1 = mysql_real_escape_string($author1);
$author2 = mysql_real_escape_string($author2);
$author3 = mysql_real_escape_string($author3);
$author4 = mysql_real_escape_string($author4);
$author5 = mysql_real_escape_string($author5);
$query = "INSERT INTO bd_book VALUES('NULL','$title','$author1','$author2','$author3','$author4','$author5','$isbn','$categoryid','$subcategoryid','$savedirdb','$savefile','$imagesavedirdb','$imagefile', '$isocred')";
mysql_query($query,$con) or die(mysql_error());
$bookid = mysql_insert_id();


//INSERT TAGS INTO TAG TABLE AND CREATE TAG TABLE


foreach($tagAry as $tag){
	//check if tag exists
	$tag = mysql_real_escape_string($tag);
	$query = "SELECT * FROM bd_tag WHERE tagname = '$tag'";
	//clean up tags, if has an apostrophe in it
	$result = mysql_query($query);
	checkResult($result,$query);
	$numrows = mysql_num_rows($result);
	//if doesn't exist..insert
	if($numrows == 0){
		if($debug){
			echo "No tag '$tag'.... Inserting<br>\n";
		}		
		$query = "INSERT INTO bd_tag VALUES('NULL', '$tag')";
		$result = mysql_query($query);
		checkResult($result, $query);
		//get tag id
		$tagid = mysql_insert_id();
	} else{
		$row = mysql_fetch_assoc($result);
		$tagid = $row['id'];	
	}
	//insert book<->tag mapping into tagmap table
	$query = "INSERT INTO bd_tagmap VALUES('NULL', '$bookid', '$tagid')";
	$result = mysql_query($query);
	checkResult($result, $query);
}

echo "<h2>Insersion complete<h2><br>";

?>
</form>
</body>