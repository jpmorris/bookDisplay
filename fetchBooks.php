<?php
include 'config.php';

$q=$_GET["q"];

$sql="SELECT a.id as bd_book_id, a.coverimageLocation, a.coverimageName, a.fileLocation, a.fileName, b.id as bd_category_id, b.category FROM bd_book AS a JOIN bd_category AS b ON a.category = b.id WHERE b.category = '".$q."'";

$result = mysql_query($sql);

while($row = mysql_fetch_array($result))
  {
  	echo '<div id="bookcontainer">';
  	echo '<a href="http://spike/'.$row['fileLocation'].$row['fileName'].'"><img id="displayimg" src="http://spike/'.$row['coverimageLocation'].$row['coverimageName'].'"></a>';
 // pass unique book ide in url
  	echo '<a href="./editBook.php?id='.$row['bd_book_id'].'"><img src="http://spike/bookDisplay/edit-button.png"></a>';
  	echo '</div>';
  }
mysql_close($con);
?>