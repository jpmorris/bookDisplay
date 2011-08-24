$(document).ready(function() {
    var search_input = $(this).val();
    var dataString = 'keyword='+ search_input;
	 $.ajax({
	     type: "GET",
	     url: "ajax-search.php",
	     data: dataString,
	     beforeSend:  function() {
		 
		 $('input#search_input').addClass('loading');
		 
	     },
	     success: function(server_response)
	     {

		 $('#searchresultdata').html(server_response).show();
		 $('span#category_title').html(search_input);
		 
		 if ($('input#search_input').hasClass("loading")) {
		     $("input#search_input").removeClass("loading");
		 } 
		 
	     }
	 });	
	$("#search_input").watermark("Begin Typing to Search");
    $("#search_input").keyup(function()    		
	 {
    var search_input = $(this).val();
    var dataString = 'keyword='+ search_input;
    if(search_input.length>=0)
    {
	 $.ajax({
	     type: "GET",
	     url: "ajax-search.php",
	     data: dataString,
	     beforeSend:  function() {
		 
		 $('input#search_input').addClass('loading');
		 
	     },
	     success: function(server_response)
	     {

		 $('#searchresultdata').html(server_response).show();
		 $('span#category_title').html(search_input);
		 
		 if ($('input#search_input').hasClass("loading")) {
		     $("input#search_input").removeClass("loading");
		 } 
		 
	     }
	 });
				     }return false;
				 });
});