<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta charset="utf-8"> 
<title>jQuery AJAX form submit using twitter bootstrap modal</title> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<link href="http://www.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet"> 
<script src="http://www.bootstrapcdn.com/twitter-bootstrap/2.2.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
<h4>Demo Page</h4>
 <div id="thanks"><p><a data-toggle="modal" href="#form-content" class="btn btn-primary">Contact us</a></p></div>
 <!-- model content --> 
 <div id="form-content" class="modal hide fade in" style="display: none; ">
         <div class="modal-header">
               <a class="close" data-dismiss="modal">×</a>
               <h3>Contact us</h3>
         </div>
     <div>
         <form class="contact">
             <fieldset>
                 <div class="modal-body">
                     <ul class="nav nav-list">
                         <li class="nav-header">Name</li>
                         <li><input class="input-xlarge" value=" krizna" type="text" name="name"></li>
                         <li class="nav-header">Email</li>
                         <li><input class="input-xlarge" value=" user@krizna.com" type="text" name="Email"></li>
                         <li class="nav-header">Message</li>
                         <li><textarea class="input-xlarge" name="sug" rows="3"> Thanks for the article and demo
                             </textarea></li>
                     </ul> 
                 </div>
             </fieldset>
         </form>
     </div>
     <div class="modal-footer">
         <button class="btn btn-success" id="submit">submit</button>
         <a href="#" class="btn" data-dismiss="modal">Close</a>
     </div>
 </div>
 <div id="eyu" ><p>this is eyu message !</p></div>
</div>
    
<script>
 $(function() {
//twitter bootstrap script
 $("button#submit").click(function(){
    $.ajax({
    		   type: "POST",
 url: "process.php",
 data: $('form.contact').serialize(),
         success: function(msg){
          $("#eyu").html(msg)
          $("#form-content").modal('hide'); 
         },
 error: function(){
 alert("failure");
 }
       });
 });
});
</script>
</body>
</html>
