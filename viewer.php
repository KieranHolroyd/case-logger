<?php include "head.php"; ?>
<div class="searchBox-container">
  <a href="search?type=cases"><input type="text" class="searchBox" id="searchQuery" placeholder="Search All Cases"><button class="searchCases" id="searchCases">Search</button></a>
</div>
<div class="grid">
  <div class="grid__col grid__col--4-of-6" style="padding-left: 20px !important;">
    <h1 class="info-title">Case List</h1>
    <div id="reports" style='height: calc(100vh - 118px) !important;' class="cscroll">

    </div>
    <button id="loadMore">Load More Cases</button>
  </div>
  <div class="grid__col grid__col--2-of-6">
    <div id="case_info" style='height: calc(100vh - 49px);' class="cscroll">
      <h1>Select A Case To View Info.</h1>
    </div>
  </div>
</div>
<script>
  var offset=0;
  var cases;
  var list = "";
  var player_punished, player_banned, moreinfo, setMoreInfo, reporting_player;
  function getReports(){
    if(offset === 0) {$('#reports').html("<img src='img/loadw.svg'>")}
    list="";
    $.post('api/getCases',{ 'offset':offset },function(data){
      cases=JSON.parse(data);
      if(cases.info.count < 100){
        $('#loadMore').hide();
      }
      for (let i = 1; i < Object.keys(cases.caseno).length + 1; i++) {
        reporting_player="";
        if(cases.caseno[i].reporting_player!=="[]" && cases.caseno[i].reporting_player!=="" && cases.caseno[i].reporting_player!==null && cases.caseno[i].reporting_player!=="null"){
          reporting_player=JSON.parse(cases.caseno[i].reporting_player);
        	reporting_player_name=reporting_player[1].name;
        } else {
        	reporting_player_name="undefined";
        }
        if(cases.caseno[i].pa==1){player_punished="Yes"} else {player_punished="No"}
        if(cases.caseno[i].ba==1){player_banned="Yes"} else {player_banned="No"}
        list += '<div class="case" onclick="getMoreInfo('+cases.caseno[i].id+')"><span style="float: right;font-size: 12px;">Lead Staff Member: '+cases.caseno[i].lead_staff+'</span><span style="font-size: 25px;">'+cases.caseno[i].id+'-'+reporting_player_name+'<br><span style="font-size: 12px; padding: 0;">Punishment? '+player_punished+' | Banned? '+player_banned+' | Timestamp: '+cases.caseno[i].timestamp+' | Report Type: '+cases.caseno[i].typeofreport+'</span></span></div>';
      }
      if(offset === 0) {$('#reports').html(list);} else {$('#reports').append(list);}
      
      offset = offset+100;
    });
  };
  let players_involved, playersArray, player_title;
  function getMoreInfo(id){
    $('#case_info').html("<p><img src='img/loadw.svg'></p>");
    players_involved="";
    playersArray="";
    player_title="";
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
      setMoreInfo = '<h2><span>Case ID:</span> '+moreinfo.report.id+'-'+player_title+'</h2><p id="case"><span>Lead Staff:</span> '+moreinfo.report.lead_staff+'</p><p id="case"><span>Other Staff:</span> '+moreinfo.report.other_staff+'</p><p id="case"><span>Type Of Report:</span><br> '+moreinfo.report.typeofreport+'</p><p id="case" style="text-transform: capitalize;"><span>Players Involved:</span><br> '+players_involved+'</p><p id="case"><span>Description Of Events:</span><br> '+moreinfo.report.doe+'</p><p id="case"><span>Link To Player Report:</span><br>'+moreinfo.report.ltpr+'</p><p id="case"><span>Points?:</span> '+moreinfo.report.points+'</p><p id="case"><span>Ammount Of Points:</span> '+moreinfo.report.aop+'</p><p id="case"><span>Offence Committed:</span><br> '+moreinfo.report.oc+'</p><p id="case"><span>Evidence Given:</span><br> '+moreinfo.report.evidence+'</p><p id="case"><span>Banned?:</span> '+moreinfo.report.banned+'</p><p id="case"><span>Ban Length:</span> '+moreinfo.report.ban_length+' Days</p><p id="case"><span>Ban Message:</span><br> '+moreinfo.report.bm+'</p><p id="case"><span>TS Ban:</span> '+moreinfo.report.ts+'</p><p id="case"><span>Ingame Ban:</span> '+moreinfo.report.ig+'</p><p id="case"><span>Website Ban:</span> '+moreinfo.report.wb+'</p><p id="case"><span>Permenant Ban:</span> '+moreinfo.report.perm+'</p><p id="case"><span>Timestamp:</span> '+moreinfo.report.timestamp+'</p>';
      $('#case_info').html(linkify(setMoreInfo));
    });
    
  }
  function userArrayLoaded(){
    if((userArray.info.slt==="1" || userArray.info.dev=="1")){
      getReports();
    }
  }
  $('#loadMore').click(function(){
    getReports();
  });
  $('#searchCases').click(function(){
    if($('#searchQuery').val()!==""){
  		window.location.href = "search?type=cases&query="+$('#searchQuery').val();
    }
  });
  $(document).ready(function(){
    $('#searchQuery').keydown(function (event) {
      if (event.which == 13 || event.keyCode == 13) {
        if($('#searchQuery').val()!==""){
          window.location.href = "search?type=cases&query="+$('#searchQuery').val();
        }
      }
    });
  });
</script>
</body>
</html>

