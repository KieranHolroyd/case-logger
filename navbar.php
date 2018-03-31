<div id="topLevel">
  <div id="nav">
    <a href="<?php echo $url; ?>">Dashboard</a>
    <a style="cursor:pointer;" id="openMore" opened="false">Navigation</a>
    <a id="time" style="color: #222;cursor:default;"></a>
  </div>
  <div id="moreMenu"></div>
</div>
<script>
  $('#time').text(currentTime());
  setInterval(function(){
  	$('#time').text(currentTime());
  }, 1000);
</script>