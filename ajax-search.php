<?php


include_once ('config.php');

if(isset($_GET['keyword'])){
    $keyword = 	trim($_GET['keyword']) ;
$keyword = mysqli_real_escape_string($dbc, $keyword);



$query = "SELECT
  book.id AS bookid, book.title, book.author1, book.category, book.subcategory, book.filelocation, book.filename, book.coverimagelocation, book.coverimagename,
  tagmap.tag_id, tagmap.book_id,
  tag.id, tag.tagname,
  category.id, category.category,
  subcategory.id, subcategory.subcategory
FROM
  bd_book AS book
LEFT OUTER JOIN
  bd_tagmap AS tagmap ON (book.id = tagmap.book_id)
LEFT OUTER JOIN
  bd_tag AS tag ON (tag.id = tagmap.tag_id)
LEFT OUTER JOIN
  bd_category AS category ON (book.category = category.id)
LEFT OUTER JOIN
  bd_subcategory AS subcategory ON (book.subcategory = subcategory.id)
WHERE
  	(book.title LIKE '%$keyword%' 
	OR 
	book.author1 LIKE '%$keyword%' 
	OR 
	category.category LIKE '%$keyword%' 
	OR 
	subcategory.subcategory LIKE '%$keyword%'
	OR 
	tag.tagname LIKE '%$keyword%') 
GROUP BY book.title";


// this was not selecting the cases when no tags were filled out
//"SELECT book.id AS bookid, book.title, book.author1, book.filelocation, book.filename, book.coverimagelocation, book.coverimagename, book.subcategory, book.category, category.id, subcategory.id, category.category,  subcategory.subcategory, tag.id, tagmap.tag_id, tagmap.book_id, tag.tagname FROM bd_book AS book JOIN bd_category AS category JOIN bd_subcategory AS subcategory JOIN bd_tag AS tag JOIN bd_tagmap AS tagmap WHERE book.category = category.id AND book.subcategory = subcategory.id AND tag.id = tagmap.tag_id AND book.id = tagmap.book_id AND (book.title LIKE '%$keyword%' OR book.author1 LIKE '%$keyword%' OR category.category LIKE '%$keyword%' OR subcategory.subcategory LIKE '%$keyword%' OR tag.tagname LIKE '%$keyword%') GROUP BY book.title;";




//echo $query;
$result = mysqli_query($dbc,$query);
if($result){
    if(mysqli_affected_rows($dbc)!=0){
          while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
          	echo '<div id="bookcontainer">';
       	    echo '<a href="'.$webpath.$row['filelocation'].$row['filename'].'"><img src="'.$webpath.$row['coverimagelocation'].$row['coverimagename'].'" id="bookimg"></img></a>';
       	    echo '<a href="'.$bookdisplaywebpath.'editBook.php?id='.$row['bookid'].'"><img src="'.$bookdisplaywebpath.'img/edit-button.png" id="editimg"></img></a>';
     echo '<b>'.$row['title'].'</b><br> '.$row['author1'];
     echo '</div>';
    }
    }else {
        echo 'No Results for :"'.$_GET['keyword'].'"';
    }
	echo '<br class="spacer">';
  
}
}else {
    echo 'Parameter Missing';
}




?>