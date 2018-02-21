<div id="topLevel">
  <div id="nav">
    <a href="./">Home</a>
    <a style="cursor:pointer;" id="openMore" opened="false">Navigation</a>
  </div>
  <div id="moreMenu"></div>
</div>
<script>
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
  $('#time').text(currentTime());
  setInterval(function(){
  	$('#time').text(currentTime());
  }, 1000);
</script>