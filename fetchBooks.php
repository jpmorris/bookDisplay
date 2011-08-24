<?php
include 'config.php';

$q=$_GET["q"];

$query="SELECT a.id as bd_book_id, a.coverimagelocation, a.coverimagename, a.filelocation, a.filename, b.id as bd_category_id, b.category FROM bd_book AS a JOIN bd_category AS b ON a.category = b.id WHERE b.category = '".$q."'";

$result = mysql_query($query);

while($row = mysql_fetch_assoc($result))
  {
  	echo '<div id="bookcontainer">';
  	echo '<a href="'.$webpath.$row['filelocation'].$row['filename'].'"><img id="displayimg" src="'.$webpath.$row['coverimagelocation'].$row['coverimagename'].'"></a>';
 // pass unique book id in url
  	echo '<a href="./editBook.php?id='.$row['bd_book_id'].'"><img src="'.$bookdisplaywebpath.'edit-button.png"></a>';
  	echo '</div>';
  }
mysql_close($con);
?>