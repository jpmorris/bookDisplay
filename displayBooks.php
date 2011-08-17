<html>
<head> 
	<link rel="stylesheet" type="text/css" href="style.css" media="screen" title="bbxcss" /> 
	<style type="text/css"> 
	</style> 
<script type="text/javascript">
function showCategory(str)
{
if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  } 
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","fetchBooks.php?q="+str,true);
xmlhttp.send();
}
</script>
</head>
<body>

<script type="text/javascript" src="./navbar.js"> </script>


<form id="displayForm" name="displayForm">
<select name="users" onchange="showCategory(this.value)">
<?php 
include 'config.php';

echo '<option value="">Select a category:</option>';

$sql="SELECT * FROM bd_category";
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result)){
 echo '<option value="'.$row["category"].'">'.$row["category"].'</option>';
}
?>
</select>
</form>
<br />
<div id="txtHint"><b>Category info will be listed here.</b></div>

</body>
</html>