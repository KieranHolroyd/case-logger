<ul id="navBar">
  <div style="float: right;margin-right:10px;color: #BFFF00;display:inline;"><span id="time" style="color: #BFFF00 !important;"></span> | <span id="dailyCasecount" onclick="updateDailyCases()" style="color: #BFFF00 !important;"></span><span id="usernameNav" style="color: #BFFF00 !important;"></span></div>
  <img style="height: 20px; width: 20px;float: right;margin-right:10px;" src="https://www.nitrexdesigncode.com/account/img/psisynlogo.png" alt="Psisyn Logo">
</ul>
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
  setInterval(updateDailyCases, 30000);
  function updateDailyCases(){
    $.post('dailyCases.php', {}, function(data){
      var cases=JSON.parse(data);
      $('#dailyCasecount').text(cases.today+' Cases Today');
      $('#dailyCasecount').attr('title', 'Updated: '+currentTime()+"\nClick To Update\n"+cases.yesterday+" Cases Yesterday\n"+cases.twodays+" Cases Two Days Ago\n"+cases.threedays+" Cases Three Days Ago\n"+cases.fourdays+" Cases Four Days Ago");
    });
  }
  updateDailyCases();
</script>