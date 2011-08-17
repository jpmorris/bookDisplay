<head>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" title="bbxcss" />
<style type="text/css">
</style>
</head>

<script type="text/javascript" src="./navbar.js"> </script>
<form>
<?php

$id = $row['id'];
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
$fileLocation = $_POST['fileLocation'];
$fileName = $_POST['fileName'];
$coverimageLocation = $_POST['coverimageLocation'];
$coverimageName = $_POST['coverimageName'];



// CHANGE DATABASE INFO- CATEGORY
//get old categoryid
$query = "SELECT category FROM bd_book WHERE id = '$id'";
$result = mysql_query($sql);
$old = mysql_fetch_array($result);
$oldcategoryid = $old['category'];
//get new categoryid
$query = "SELECT * FROM bd_category WHERE category = '$category'";
$result = mysql_query($sql);
$new = mysql_fetch_array($result);
if(!$new){
	//categoryid is not in the database then insert
	$query = "INSERT INTO bd_category VALUES('NULL', '$category')";
	$result = mysql_query($query);
	checkResult($result, $query);
	$newcategoryid = mysql_insert_id();
}
else{
	//get new categoryid
	$newcategoryid = $new['id'];
}




// CHANGE DATABASE INFO- SUBCATEGORY
//get old subcategoryid
$query = "SELECT category FROM bd_book WHERE id = '$id'";
$result = mysql_query($sql);
$old = mysql_fetch_array($result);
$oldsubcategoryid = $old['subcategory'];
//get new subcategoryid
$query = "SELECT * FROM bd_subcategory WHERE subcategory = '$subcategory'";
$result = mysql_query($sql);
$new = mysql_fetch_array($result);
if(!$new){
	//subcategoryid is not in the database then insert
	$query = "INSERT INTO bd_subcategory VALUES('NULL', '$subcategory')";
	$result = mysql_query($query);
	checkResult($result, $query);
	$newsubcategoryid = mysql_insert_id();
}
else{
	//get new categoryid
	$nesubwcategoryid = $new['id'];
}


// CHANGE OF CATEGORY, SUBCATEGORY, OR FILENAME -> need to move file
$query = "SELECT * FROM bd_book WHERE book_id = '$id'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

//if either the filename or path change...
if(($fileName != $row['fileName']) || ($fileLocation != $row['fileLocation'])){

	//make sure the new directory exists
	`mkdir -p $rootdir.$fileLocation`;

	//move old file to new location/name
	rename($rootdir.$row['fileLocation'].$row['fileName'],$rootdir.$fileLocation.$fileName);
}

//if either the IMAGE filename or path change...
if(($coverimageNames != $row['coverimageName']) || ($coverimageLocation != $row['coverimageLocation'])){

	//make sure the new directory exists
	`mkdir -p $rootdir.$coverimageLocation`;

	//move old file to new location/name
	rename($rootdir.$row['coverimageLocation'].$row['coverimageName'],$rootdir.$coverimageLocation.$coverimageName);
}





// CHANGE TAG INFO
// breakup tag array
// parse tags by comma delimiter
$tagAryuntrimmed = explode(",",$tags);
//remove leading and trailing spaces in tagAry
foreach($tagAryuntrimmed as $untrimmedtag){
	$newtagAry = array_map('trim', $tagAryuntrimmed);
}

//get old tags
$query = "SELECT * FROM bd_tag AS tag JOIN bd_tagmap AS tagmap ON tagmap.tag_id = tag.id  WHERE book_id = '$id'";
$result = mysql_query($query);

while($new = mysql_fetch_array($result)){
	$oldtagAry[] = $new['tagname'];
	// convert from tagid to tags
}


//run over new tags
foreach($newtagAry as $newtag){
	//foreach newtag	
	//if is the same, then next
	$newexists = 0;
	foreach($oldtagAry as $oldtag){
		if($newtag == $oldtag ){
			$newexists = 1;
			break;
		}
	}
	if(!$newexists){
		//if does not exist, then insert into database
		$query = "INSERT INTO bd_tag VALUES('NULL', '$newtag')";
		$result = mysql_query($query);
		checkResult($result, $query);
		//get tag id
		$newtagid = mysql_insert_id();
		//insert book<->tag mapping into tagmap table
		$query = "INSERT INTO bd_tagmap VALUES('NULL', '$bookid', '$newtagid')";
		$result = mysql_query($query);
		checkResult($result, $query);
	}
}

//run over old tags
foreach($oldtagAry as $oldtag){
	//foreach oldtag
	$oldexists = 0;
	foreach($oldtagAry as $oldtag){
		if($newtag == $oldtag ){
			$oldexists = 1;
			break;
		}
	}
	if(!$oldexists){
		//if the old tag doesn't match any new tag, check to make sure the old tag isn't being used by any other books
		$query = "SELECT * FROM bd_tag AS tag JOIN bd_tagmap AS tagmap ON tagmap.tag_id = tag.id  WHERE tagname = '$oldtag'";
		$result = mysql_query($sql);
		$numrow = mysql_fetch_array($result);
		$oldtagid = mysql_insert_id();
		if($numrow == 1)
		//if the oldtag does not exist, then delete the old tag
		//delete tag
		$query = "DELETE FROM bd_tag WHERE id = '$oldtagid'";
		$result = mysql_query($sql);
		if(!$result){
			die;
		}
		//delete tagmap
		$query = "DELETE FROM bd_tagmap WHERE tag_id = '$oldtagid'";
		$result = mysql_query($sql);
		if(!$result){
			die;
		}
	}
	
}

	

//change database info - main table
$query = "UPDATE bd_book SET title='$title', author1='$author1', author2='$author2', author3='$author3', author4='$author4', author5='$author5', 
isbn='$isbn', category='$newcategoryid', subcategory='$newsubcategoryid', fileLocation='$fileLocation, fileName='$fileName', 
coverimageLocation='$coverimageLocation, coverimageName='$coverimageName', isocred='$isocred'";

//change database info - tags

//change directory/file structure info if necessary


?>

</form>