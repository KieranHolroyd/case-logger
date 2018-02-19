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
  <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
  <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="main.js"></script>
</head>
<body>
  <script>
    let loginToken = "<?php echo $_COOKIE['LOGINTOKEN'];?>";
    var userArray;
  	function userArraySet (){
      $.post("apiGetUserInfo", {}, function(data){
        user=JSON.parse(data);
        userArray=user;
        if(userArray.info.id===""){
          if(window.location.pathname!=="/sites/SVTeR8KxJVbx/holdingpage" && window.location.pathname!=="/sites/SVTeR8KxJVbx/passport"){
            location.replace('holdingpage');
          }
        }
        if(userArray.info.slt==="1" || userArray.info.dev=="1"){
          $('#navBar').prepend("<a href='viewer'>Case Viewer</a><a href='staff'>Staff Manager</a>");
        }
        if(userArray.info.dev=="1"){
          $('#navBar').prepend("<a href='viewSuggestions'>View Suggestions</a>");
        }
        $('#welcome').html('Hello, '+userArray.info.username);
        $('#lsm').val(userArray.info.username);
        $('#sas').html(userArray.info.username);
        $('#navBar').append("<a href='staffStatistics'>Staff Statistics</a><a onclick='logout();'>Logout</a>");
        $('#navBar').delay(150).prepend("<a href='./'>Case Logger</a><a href='suggestions'>Suggestion Box</a><a href='me'>My Stats</a><a href='guides'>Guides</a>");
        $('#usernameNav').text(" | "+userArray.info.firstname+" "+userArray.info.lastname);
        userArrayLoaded();
    	});
    }
    $(window).on('load', userArraySet());
    function logout(){
    	$.post("apiLogout", {token: loginToken}, function(data){
        window.replace("passport")
        console.log(data)
      })
      userArray = {};
      setTimeout(function(){location.reload()}, 250);
    }
  </script>