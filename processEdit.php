<head>
<link rel="stylesheet" type="text/css" href="css/style.css"
	media="screen" title="bbxcss" />
<style type="text/css">
</style>
</head>

<script type="text/javascript" src="js/navbar.js"> </script>
<form>








<?php


include 'config.php';

$id = $_POST['id'];
$title = $_POST['title'];
$tags = $_POST['tags'];
$author1 = $_POST['author1'];
$author2 = $_POST['author2'];
$author3 = $_POST['author3'];
$author4 = $_POST['author4'];
$author5 = $_POST['author5'];
$isbn = $_POST['isbn'];
$category = $_POST['category'];
$subcategory = $_POST['subcategory'];
$filelocation = $_POST['filelocation'];
$filename = $_POST['filename'];
$coverimagelocation = $_POST['coverimagelocation'];
$coverimagename = $_POST['coverimagename'];

$isocred = (isset($_POST['isocred']) && $_POST['isocred'] == "on") ? 1 : 0;


//
////// CHANGE CATEGORY/////
//

//get old categoryid
$query = "SELECT category FROM bd_book WHERE id = '$id'";
$result = mysql_query($query);
$old = mysql_fetch_assoc($result);
$oldcategoryid = $old['category'];
//get new categoryid
$query = "SELECT * FROM bd_category WHERE category = '$category'";
$result = mysql_query($query);
$row = mysql_fetch_assoc($result);


if(!$row){
	//categoryid is not in the database then insert
	$query = "INSERT INTO bd_category VALUES('NULL', '$category')";
	$result = mysql_query($query);
	checkResult($result, $query);
	$newcategoryid = mysql_insert_id();
}
else{
	//get new=old categoryid
	$newcategoryid = $row['id'];
}



//
////// CHANGE SUBCATEGORY /////
//



//get new subcategoryid
$query = "SELECT * FROM bd_subcategory WHERE subcategory = '$subcategory'";
$result = mysql_query($query);
$row = mysql_fetch_assoc($result);



if(!$row){
	//subcategoryid is not in the database then insert
	$query = "INSERT INTO bd_subcategory VALUES('NULL', '$subcategory')";
	$result = mysql_query($query);
	checkResult($result, $query);
	$newsubcategoryid = mysql_insert_id();
}
else{
	//get new categoryid
	$newsubcategoryid = $row['id'];
}


//
////// CHANGE FILE NAME/LOCATION /////
//

$query = "SELECT * FROM bd_book WHERE id = '$id'";
$result = mysql_query($query);
$row = mysql_fetch_assoc($result);



//if either the filename or path change...
if(($filename != $row['filename']) || ($filelocation != $row['filelocation'])){

	//check to make sure file doesn't already exist:
	if(file_exists($rootdir.$filelocation."/".$filename)) die('File exists.');

	//make sure the new directory exists
	if($filelocation != $row['filelocation']){
		print "1:".$filelocation."<br>";
		print "2:".$row['filelocation']."<br>";
		print "3:".$rootdir.$filelocation."<br>";
		
			`mkdir -p $rootdir$filelocation`;
	}

	//move old file to new location/name
	rename($rootdir.$row['filelocation'].$row['filename'], $rootdir.$filelocation."/".$filename) or die("File rename/move problem.");
}

//if either the IMAGE filename or path change...
if(($coverimagename != $row['coverimagename']) || ($coverimagelocation != $row['coverimagelocation'])){

	//check to make sure file doesn't already exist:
	if(file_exists($rootdir.$coverimagelocation."/".$coverimagename)) die('File exists.');


	//make sure the new directory exists
	if($coverimagelocation != $row['coverimagelocation']){
			`mkdir -p $rootdir$coverimagelocation`;
	}



	//move old file to new location/name
	rename($rootdir.$row['coverimagelocation'].$row['coverimagename'], $rootdir.$coverimagelocation."/".$coverimagename) or  die("File rename/move problem.");
}


//
////// CHANGE TAG INFO /////
//
// breakup tag array
// parse tags by comma delimiter
$tagAryuntrimmed = explode(",",$tags);
//remove leading and trailing spaces in tagAry
foreach($tagAryuntrimmed as $untrimmedtag){
	$newtagAry = array_map('trim', $tagAryuntrimmed);
}
//remove empty strings from newarray
$newtagAry = array_filter($newtagAry);

//get old tags
$query = "SELECT * FROM bd_tag AS tag JOIN bd_tagmap AS tagmap ON tagmap.tag_id = tag.id  WHERE tagmap.book_id = '$id'";
print $query."<br>";
$result = mysql_query($query);
$oldtagAry = array();
while($new = mysql_fetch_assoc($result)){
	$oldtagAry[] = $new['tagname'];
}



print "OLD: ";
print var_dump($oldtagAry);
print "<br>";

print "NEW: ";
print var_dump($newtagAry);
print "<br>";


if(isset($oldtagAry)){
	print "The array is set!<br>";
}
else{
	print "The array is not set!<br>";
}
if(empty($oldtagAry)){
	print "The array is empty!<br>";
}
else{
	print "The array is not empty!<br>";
}
if(is_null($oldtagAry)){
	print "The array is null!<br>";
}
else{
	print "The array is not null!<br>";
}


if(isset($newtagAry)){
	print "The array is set!<br>";
}
else{
	print "The array is not set!<br>";
}
if(empty($newtagAry)){
	print "The array is empty!<br>";
}
else{
	print "The array is not empty!<br>";
}
if(is_null($newtagAry)){
	print "The array is null!<br>";
}
else{
	print "The array is not null!<br>";
}




//check to see if you need to insert new tags
if(!empty($newtagAry)){
	foreach($newtagAry as $newtag){
		//foreach newtag
		//if is the same, then next
		$match = 0;
		foreach($oldtagAry as $oldtag){
			if($newtag == $oldtag ){
				$match= 1;
				break;
			}
		}
		if(!($match)){
			//if it's an old tag check for insersion
			//only insert into tag table if it's not there
			$query = "SELECT * FROM bd_tag WHERE tagname = '$newtag'";
			print "1".$query."<br>";
			$result = mysql_query($query);
			$row = mysql_fetch_assoc($result);
			$newtagid = $row['id'];
			if($row == 0){
				$query = "INSERT INTO bd_tag VALUES('NULL', '$newtag')";
				$result = mysql_query($query);
				checkResult($result, $query);
				$newtagid = mysql_insert_id();
				print "2".$query."<br>";
			}
			//get tag id

			//insert tag into tagmap always--already satisified the condition that it is a new maping (does not already exist)
			$query = "INSERT INTO bd_tagmap VALUES('NULL', '$id', '$newtagid')";
			print "3".$query."<br>";
			$result = mysql_query($query);
			checkResult($result, $query);
		}
	}
}

//check to see if you need to delete any old tags...
if(!empty($oldtagAry)){
	foreach($oldtagAry as $oldtag){
		//foreach oldtag check if it has as corresponding newtag
		$match = 0;
		foreach($newtagAry as $newtag){
			if($newtag == $oldtag ){
				$match = 1;
				break;
			}
		}


		if(!($match)){
			$query = "SELECT tagmap.id, tag.tagname, tag.id AS tagid, tagmap.book_id, tagmap.tag_id FROM bd_tag AS tag JOIN bd_tagmap AS tagmap ON tagmap.tag_id = tag.id WHERE tagname = '$oldtag'";
			$result = mysql_query($query);
			$row = mysql_fetch_assoc($result);
			$numrows = mysql_num_rows($result);
			print "4".$query."<br>";
			$oldtagid = $row['tagid'];
			print "oldtagid: ".$oldtagid."<br>";
			//only delete the tag completely if it is not found in any other books
			if($numrows == 1){
				//delete tagmap
				$query = "DELETE FROM bd_tag WHERE id = '$oldtagid'";
				print "5".$query."<br>";
				$result = mysql_query($query);
				if(!$result){
					die;
				}
			}
			// delete the tag map (to the particular book) every time:
			$query = "SELECT * FROM bd_tagmap WHERE tag_id = '$oldtagid' AND book_id = '$id'";
			print "6".$query."<br>";
			$result = mysql_query($query);
			$row = mysql_fetch_assoc($result);
			$query = "DELETE FROM bd_tagmap WHERE id = '".$row['id']."'";
			print "7".$query."<br>";
			$result = mysql_query($query);
			if(!$result){
				die;
			}


		}

	}
}


//
////// MAIN TABLE/////
//
$query = "UPDATE bd_book SET title='$title', author1='$author1', author2='$author2', author3='$author3', author4='$author4', author5='$author5', isbn='$isbn', category='$newcategoryid', subcategory='$newsubcategoryid', filelocation='$filelocation', filename='$filename', coverimagelocation='$coverimagelocation', coverimagename='$coverimagename', isocred='$isocred' WHERE id='$id'";

$result = mysql_query($query);
if(!$result){
	die("Error inserting into database.");
}
else{
	print '<h2>Edit Successuful</h2>';
	print '<a href="./editBook.php?id='.$id.'">Go Back</a><br>';
}




?>

</form>
