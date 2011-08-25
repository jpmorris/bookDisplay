<?php 
include 'config.php';

?>

<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" title="bbxcss" />
<style type="text/css">
</style>

<script type="text/javascript" src="js/navbar.js"> </script>

<form id="verifyform" enctype="multipart/form-data" name="form" method="POST" action="processEdit.php">
<fieldset>
<h1>Verify the information</h1>

<?php 

$id = $_GET['id'];

$query="SELECT * FROM bd_book WHERE id = '".$id."'";

$result = mysql_query($query);
$row = mysql_fetch_assoc($result);

$title = $row['title'];
$author1 = $row['author1'];
$author2 = $row['author2'];
$author3 = $row['author3'];
$author4 = $row['author4'];
$author5 = $row['author5'];
$isbn = $row['isbn'];
$categoryid = $row['category'];
$subcategoryid = $row['subcategory'];
$filelocation = $row['filelocation'];
$filename = $row['filename'];
$coverimagelocation = $row['coverimagelocation'];
$coverimagename = $row['coverimagename'];
$isocred = $row['isocred'];


$imageurl = $webpath.$row['coverimagelocation'].$row['coverimagename'];


$query="SELECT * FROM bd_category WHERE id = '".$categoryid."'";
$result = mysql_query($query);
$row = mysql_fetch_assoc($result);
$category = $row['category'];


$query="SELECT * FROM bd_subcategory WHERE id = '".$subcategoryid."'";
$result = mysql_query($query);
$row = mysql_fetch_assoc($result);
if($row){
	$subcategory = $row['subcategory'];
}
else{
	$subcategory = "";
}


$query="select *, GROUP_CONCAT(tagname) as cattags from bd_tagmap as a join bd_tag as b on tag_id = b.id where book_id = '".$id."'";
$result = mysql_query($query);
$row = mysql_fetch_assoc($result);
$tags = $row['cattags'];

?>


<img src="<?php echo $imageurl?>">
<div id="titleheading">Title</div>
<div id="booktitle"><?php echo $title?></div>
				<h1>Additional information</h1>		
				 <ol>
				 	<li> 
					 	<input type="hidden" name="id" value="<?php echo $id?>" id="id">	
						<label for="title">Title</label>
						<input name="title" type="text" value="<?php echo $title?>" id="title">
		            </li> 
						 	<li> 
						<label for="tags">Tags</label>
						<input name="tags" type="text" value="<?php echo $tags?>" id="tags">
		            </li>             
		            <li> 
						<label for="author1">Author 1</label>
						<input name="author1" type="text" value="<?php echo $author1?>" id="author1">
		            </li> 
		            <li> 
						<label for="author2">Author 2</label>
						<input name="author2" type="text" value="<?php echo $author2?>" id="author2">
		            </li> 
		            <li> 
						<label for="author3">Author 3</label>
						<input name="author3" type="text" value="<?php echo $author3?>" id="author3">
		            </li> 
		            <li> 
						<label for="author4">Author 4</label>
						<input name="author4" type="text" value="<?php echo $author4?>" id="author4">
		            </li> 		            
		            <li> 
						<label for="author5">Author 5</label>
						<input name="author5" type="text" value="<?php echo $author5?>" id="author5">
		            </li> 		            
		            <li> 
						<label for="isbn">ISBN</label>
						<input name="isbn" type="text" value="<?php echo $isbn?>" id="isbn">
		            </li>
		            <li> 
						<label for="category">Category</label>
						<input name="category" type="text" value="<?php echo $category?>" id="category">
		            </li>
		            <li> 
						<label for="subcategory">Subcategory</label>
						<input name="subcategory" type="text" value="<?php echo $subcategory?>" id="subcategory">
		            </li>

		            <li> 
						<label for="filelocation">File Location</label>
						<input name="filelocation" type="text" value="<?php echo $filelocation?>" id="filelocation">
		            </li> 
		            <li> 
						<label for="filename">File Name</label>
						<input name="filename" type="text" value="<?php echo $filename?>" id="filename">
		            </li>
		             
		            <li> 
						<label for="coverimagelocation">Coverimage Location</label>
						<input name="coverimagelocation" type="text" value="<?php echo $coverimagelocation ?>" id="coverimagelocation">
		            </li> 
		            <li> 
						<label for="coverimagename">Coverimage Name</label>
						<input name="coverimagename" type="text" value="<?php echo $coverimagename?>" id="coverimagename">
		            </li> 
		            <li> 
						<label for="isocred">Is OCRed?</label>
						<input name="isocred" type="checkbox" <?php if($isocred == 1){echo 'checked="checked"';}?> id="isocred">

						
		            </li> 
		        </ol> 
		       <input type="submit" name="Submit" value="Submit Changes">
		    </fieldset> 
		</form> 
	</body>


<?php







?>