<?php include "head.php"; include "navbar.php"; ?>
<button id="rollcall">Click Me To Fill Out The Roll Call</button>
<script>
	$('#rollcall').click(function(){
  	$.post('completeRollcall.php', { "name":userArray.info.username, "team":userArray.info.team, "rank":userArray.info.rank }, function(){
    	$('#rollcall').text('Roll Call Completed, Thank You.');
      setTimeout(function(){
      	window.location.href = "./";
      },2000)
      $('#rollcall').prop('disabled', true);
    });
  });
</script>
<?php include "footer.php"; ?>