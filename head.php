<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
  <title>Psisyn.com | Staff</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toast-css/1.1.0/grid.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css">
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="stylesold.css">
  <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
  <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="js/app.js"></script>
  <script src="js/dragUI.js"></script>
  <script src="js/modal.js"></script>
</head>
<body>
  <script>
    let loginToken = "<?php echo $_COOKIE['LOGINTOKEN'];?>";
    var userArray;
  	function userArraySet (){
      $.get("api/getUserInfo", function(data){
        user=JSON.parse(data);
        userArray=user;
        if(userArray.info.id===""){
          if(window.location.pathname!=="/Purple-Iron-Bulldog/holdingpage" && window.location.pathname!=="/Purple-Iron-Bulldog/passport"){
            location.replace('holdingpage');
          }
        }
        if(userArray.info.slt==="1" || userArray.info.dev=="1"){
          $('#moreMenu').prepend("<a href='viewer'>Case Viewer</a><a href='search?type=cases'>Case Search</a><a href='staff'>Staff Manager</a>");
        }
        if(userArray.info.dev=="1"){
          $('#moreMenu').prepend("<a href='viewSuggestions'>View Suggestions</a>");
        }
        $('#welcome').html('Hello, '+userArray.info.username);
        $('#lsm').val(userArray.info.username);
        $('#sas').html(userArray.info.username);
        $('#moreMenu').append("<a href='staffStatistics'>Staff Statistics</a><a onclick='logout();'>Logout</a>");
        $('#moreMenu').delay(150).prepend("<a href='./'>Case Logger</a><a href='suggestions'>Suggestion Box</a><a href='me'>My Stats</a><a href='guides'>Guides</a>");
        $('#usernameNav').text(" | "+userArray.info.firstname+" "+userArray.info.lastname);
        userArrayLoaded();
    	});
    }
    $(window).on('load', userArraySet());
    function logout(){
    	$.post("api/logoutUser", {token: loginToken}, function(data){
        window.location.replace("passport")
        console.log(data)
      })
      userArray = {};
    }
  </script>
  <?php if(!isset($nonav)) include "navbar.php"; ?>