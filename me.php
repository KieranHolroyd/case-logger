<?php include "head.php"; ?>
<div class="grid">
  <div class="grid__col grid__col--4-of-6" style="padding-left: 20px !important;">
    <h1 class="info-title" id="welcome"></h1>
    <div id="reports" class="cscroll" style="height: calc(100vh - 69px);">
			
    </div>
  </div>
  <div class="grid__col grid__col--2-of-6">
    <div id="case_info" class="cscroll">
    </div>
  </div>
</div>
<script>
  var player_punished, player_banned, moreinfo, setMoreInfo;
  var players_involved, playersArray, player_title;
	function getStaffActivity(){
    $('#reports').html('<img src="img/loadw.svg">');
    var other_staff;
    var other_staff_text;
    $.post('api/getStaffActivity',{ 'id':userArray.info.username },function(data){
      activity="";
      moreinfo=JSON.parse(data);
      for (let i = 1; i < Object.keys(moreinfo.log).length + 1; i++) {
        other_staff="";
        other_staff_text="";
        reporting_player="";
        if(moreinfo.log[i].reporting_player!=="[]" && moreinfo.log[i].reporting_player!=="" && moreinfo.log[i].reporting_player!==null && moreinfo.log[i].reporting_player!=="null"){
          reporting_player=JSON.parse(moreinfo.log[i].reporting_player);
        	reporting_player_name=reporting_player[1].name;
        } else {
        	reporting_player_name="undefined";
        }
        if(moreinfo.log[i].other_staff==true){other_staff="other_staff";other_staff_text=" (Support)";}
      	activity += '<div class="staffActivityCard '+other_staff+'" onclick="getCase('+moreinfo.log[i].id+')">'+moreinfo.log[i].id+" - "+reporting_player_name+other_staff_text+'<br>'+moreinfo.log[i].doe+'</div>'
      }
      setMoreInfo="<h1>"+name+"</h1><div>"+activity+"</div>";
      $('#reports').html(setMoreInfo);
    });
  }
  function getCase(id){
    $('#case_info').html("<img src='img/loadw.svg'>");
    players_involved = "";
    playersArray = "";
    player_title = "";
  	$.post('api/getMoreInfo',{ 'id':id },function(data){
      moreinfo=JSON.parse(data);
      if(moreinfo.report.players!=="[]" && moreinfo.report.players!==""){
        playersArray=JSON.parse(moreinfo.report.players);
        for (var i = 1; i < Object.keys(playersArray).length + 1; i++) {
          players_involved += playersArray[i].type+": "+playersArray[i].name+" ("+playersArray[i].guid+")<br>";
        };
        player_title = playersArray[1].name;
      } else {
      	players_involved = "None";
        player_title = moreinfo.report.lead_staff;
      }
      setMoreInfo = '<h2><span>Case ID:</span> '+moreinfo.report.id+'-'+player_title+'</h2><p id="case"><span>Lead Staff:</span> '+moreinfo.report.lead_staff+'</p><p id="case"><span>Other Staff:</span> '+moreinfo.report.other_staff+'</p><p id="case"><span>Type Of Report:</span><br> '+moreinfo.report.typeofreport+'</p><p id="case" style="text-transform: capitalize;"><span>Players Involved:</span><br> '+players_involved+'</p><p id="case"><span>Description Of Events:</span><br> '+moreinfo.report.doe+'</p><p id="case"><span>Link To Player Report:</span><br> <a class="report_link" href="'+moreinfo.report.ltpr+'">'+moreinfo.report.ltpr+'</a></p><p id="case"><span>Points?:</span> '+moreinfo.report.points+'</p><p id="case"><span>Ammount Of Points:</span> '+moreinfo.report.aop+'</p><p id="case"><span>Offence Committed:</span><br> '+moreinfo.report.oc+'</p><p id="case"><span>Evidence Given:</span><br> '+moreinfo.report.evidence+'</p><p id="case"><span>Banned?:</span> '+moreinfo.report.banned+'</p><p id="case"><span>Ban Length:</span> '+moreinfo.report.ban_length+' Days</p><p id="case"><span>Ban Message:</span><br> '+moreinfo.report.bm+'</p><p id="case"><span>TS Ban:</span> '+moreinfo.report.ts+'</p><p id="case"><span>Ingame Ban:</span> '+moreinfo.report.ig+'</p><p id="case"><span>Website Ban:</span> '+moreinfo.report.wb+'</p><p id="case"><span>Permenant Ban:</span> '+moreinfo.report.perm+'</p><p id="case"><span>Timestamp:</span> '+moreinfo.report.timestamp+'</p>';
      $('#case_info').html(setMoreInfo);
    });
  }
  function getMoreInfo(){
    $('#case_info').html('<img src="img/loadw.svg">');
    actwarn_start = "";
    actwarn_end = "";
    $.post('api/getStaffMoreInfo',{ 'id':userArray.info.id },function(data){
      moreinfo=JSON.parse(data);
      if(moreinfo.activity_warning==true){actwarn_start="<span style='color: orange;' title='Activity Warning'>";actwarn_end="</span>";}
      setMoreInfo="<h1>Information About "+moreinfo.name+"</h1><p>Your Rank: "+moreinfo.rank+"</p><p>Your Total All Time Cases: "+moreinfo.casecount+"</p> <p>"+actwarn_start+moreinfo.casecount_week+" Of Those Were Logged This week"+actwarn_end+"</p>";
      $('#case_info').html(setMoreInfo);
    });
  }
  function userArrayLoaded(){
  	getStaffActivity();
    getMoreInfo();
  };
</script>
<?php include "footer.php"; ?>