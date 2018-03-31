<!DOCTYPE html>
<html lang="en">
<head>
  <?php $url = "https://www.nitrexdesign.co.uk/caselogger/"; ?>
	<meta charset="UTF-8">
  <title>Psisyn.com | Staff</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toast-css/1.1.0/grid.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css">
  <link rel="stylesheet" href="<?php echo $url; ?>styles.css">
  <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo $url; ?>stylesold.css">
  <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
  <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
  <script src="<?php echo $url; ?>js/app.js"></script>
  <?php if(!isset($nonav)): ?>
    <script src="<?php echo $url; ?>js/dragUI.js"></script>
  <?php endif; ?>
  <script src="<?php echo $url; ?>js/modal.js"></script>
</head>
<body>
  <script>
    let loginToken = "<?php echo $_COOKIE['LOGINTOKEN'];?>";
    var userArray;
  	function userArraySet (){
      $.get("<?php echo $url; ?>api/getUserInfo", function(data){
        user=JSON.parse(data);
        userArray=user;
        if (typeof vm !== 'undefined') {
          vm.user = user;
        }
        if(userArray.info.id===""){
          if(window.location.pathname!=="/caselogger/holdingpage" && window.location.pathname!=="/caselogger/passport"){
            location.replace('<?php echo $url; ?>holdingpage');
            if (!isStaff()) {
              window.location.replace("<?php echo $url; ?>holdingpage");
            }
          }
        }
        if(userArray.info.slt==="1" || userArray.info.dev=="1"){
          $('#moreMenu').prepend("<a href='<?php echo $url; ?>viewer'>Case Viewer</a><a href='<?php echo $url; ?>search?type=cases'>Case Search</a><a href='<?php echo $url; ?>staff'>Staff Manager</a>");
        }
        if(userArray.info.dev=="1"){
          $('#moreMenu').prepend("<a href='<?php echo $url; ?>viewSuggestions'>View Suggestions</a>");
        }
        $('#welcome').html('Hello, '+userArray.info.username);
        $('#lsm').val(userArray.info.username);
        $('#sas').html(userArray.info.username);
        $('#moreMenu').append("<a href='<?php echo $url; ?>staffStatistics'>Staff Statistics</a><a onclick='logout();'>Logout</a>");
        $('#moreMenu').delay(150).prepend("<a href='<?php echo $url; ?>'>Dashboard</a><a href='<?php echo $url; ?>logger'>Case Logger</a><a href='<?php echo $url; ?>suggestions'>Suggestion Box</a><a href='<?php echo $url; ?>me'>My Stats</a><a href='<?php echo $url; ?>guides'>Guides</a><a href='<?php echo $url; ?>meetings'>Meetings</a><a href='<?php echo $url; ?>logs'>Server Logs</a>");
        $('#usernameNav').text(" | "+userArray.info.firstname+" "+userArray.info.lastname);
        userArrayLoaded();
    	});
    }
    function isStaff() {
      if (userArray.permissions.submitReport==1) {
        return true;
      } else {
        return false;
      }
    }
    $(window).on('load', userArraySet());
    function logout(){
    	$.post("<?php echo $url; ?>api/logoutUser", {token: loginToken}, function(data){
        window.location.replace("passport")
        console.log(data)
      })
      userArray = {};
    }
    function currentTime(){
      var currentTime = new Date()
      var hours = currentTime.getHours()
      var minutes = currentTime.getMinutes()
      var seconds = currentTime.getSeconds()
      if (minutes < 10){minutes = "0" + minutes;}
      if (seconds < 10){seconds = "0" + seconds;}
      var time = hours + ":" + minutes + ":" + seconds + " ";
      if(hours > 11){time += "PM";} else {time += "AM";}
      return time;
    }
  </script>
  <?php if(!isset($nonav)) include "navbar.php"; ?>