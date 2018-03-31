<?php include "head.php"; ?>
<div class="grid">
  <div class="grid__col grid__col--4-of-6" style="padding-left: 20px !important;">
    <h1 class="info-title">Staff List</h1>
    <div id="staff" class="cscroll">

    </div>
  </div>
  <div class="grid__col grid__col--2-of-6">
    <div id="staff_info" class="cscroll">
      <h1>Select A Staff Member To Get Statistics</h1>
    </div>
  </div>
</div>
<script>
  var offset=0;
  var staff;
  var list = "";
  var activity = "";
  var player_punished, player_banned, moreinfo, setMoreInfo;
  let players_involved, playersArray, player_title;
  let staffbgc = "";
  let stafftextc = "";
  function getStaffTeam(){
    $('#staff').html("<img src='img/loadw.svg'>");
    list="";
    $.get('api/getStaffTeam', function(data){
      staff=JSON.parse(data);
      for (let i = 1; i < Object.keys(staff).length + 1; i++) {
        if(staff[i].team==1){staffbgc="#BFFF00";} 
        else if (staff[i].team==2){staffbgc="#429ef4";}
        else if (staff[i].team==3){staffbgc="#41f49d";}
        else if (staff[i].team==4){staffbgc="#f44141";}
        else {staffbgc="";stafftextc="#fff";}
        list += '<div class="staff" style="color: '+staffbgc+';" onclick="getMoreInfo('+staff[i].id+')"><span style="float: right;font-size: 12px;color: '+staffbgc+';">Staff Team '+staff[i].team+' '+staff[i].rank+'</span><span style="font-size: 25px;color: '+staffbgc+';">'+staff[i].name+'<br></span></div>';
      }
      $('#staff').html(list);
      offset = offset+20;
    });
  };
  let actwarn_start;
	let actwarn_end;
  function getMoreInfo(id){
    $('#staff_info').html("<p><img src='img/loadw.svg'></p>");
    actwarn_start = "";
    actwarn_end = "";
    $.post('api/getStaffMoreInfo',{ 'id':id },function(data){
      moreinfo=JSON.parse(data);
      if(moreinfo.team==1){staffbgc="#BFFF00";} 
      else if (moreinfo.team==2){staffbgc="#429ef4";}
      else if (moreinfo.team==3){staffbgc="#41f49d";}
      else if (moreinfo.team==4){staffbgc="#f44141";}
      else {staffbgc="#fff";}
      if(moreinfo.activity_warning==true){actwarn_start="<span style='color: orange;' title='Activity Warning'>";actwarn_end="</span>";}
      setMoreInfo="<h1 style='color: "+staffbgc+";'>"+moreinfo.name+"</h1><p style='color: "+staffbgc+";'>"+moreinfo.rank+" of Staff Team "+moreinfo.team+"</p><p style='color: "+staffbgc+";'>"+moreinfo.casecount+" Cases Complete ("+actwarn_start+moreinfo.casecount_week+" This week"+actwarn_end+")</p>";
      if(moreinfo.casecount>0){
      	setMoreInfo+="<div style='color: "+staffbgc+";' class='staffActivityCard' onclick='getStaffActivity(\""+moreinfo.name+"\")'>Get Activity</div>";
      }
      	setMoreInfo+="<div style='color: "+staffbgc+";' class='staffActivityCard' onclick='assign_team_menu();'>Assign Team</div>";
        setMoreInfo+="<div id='assignTeamMenu' style='display: none;color: "+staffbgc+";'><div class='staffActivityCard' onclick='assign_team(`"+id+"`, `1`);'>Staff Team 1</div><div class='staffActivityCard' onclick='assign_team(`"+id+"`, `2`);'>Staff Team 2</div><div class='staffActivityCard' onclick='assign_team(`"+id+"`, `3`);'>Staff Team 3</div><div class='staffActivityCard' onclick='assign_team(`"+id+"`, `4`);'>Staff Team 4</div></div>";
      if(moreinfo.rank_lvl>=6 || moreinfo.rank_lvl==""){
        setMoreInfo+="<div style='color: "+staffbgc+";' class='staffActivityCard' onclick='assign_rank_menu();'>Assign Rank</div>";
        setMoreInfo+="<div id='assignRankMenu' style='display: none;color: "+staffbgc+";'><div class='staffActivityCard' onclick='assign_rank(`"+id+"`, `9`);'>Trial Staff</div><div class='staffActivityCard' onclick='assign_rank(`"+id+"`, `8`);'>Moderator</div><div class='staffActivityCard' onclick='assign_rank(`"+id+"`, `7`);'>Administrator</div><div class='staffActivityCard' onclick='assign_rank(`"+id+"`, `6`);'>Senior Administrator</div></div>";
        setMoreInfo+="<div style='color: "+staffbgc+";' class='staffActivityCard' id='rfl"+id+"' onclick='removeFromLogger("+id+")'>Remove From Logger</div>";
      }
      $('#staff_info').html(setMoreInfo);
    });
  }
  function assign_rank_menu(){
  	$('#assignRankMenu').slideToggle(250);
  }
  function assign_team_menu(){
  	$('#assignTeamMenu').slideToggle(250);
  }
	function assign_rank(id, rank){
  	$.post('api/setStaffRank',{ 'id':id,'rank':rank },function(data){
    	getStaffTeam();
      getMoreInfo(id);
    });
  }
  function assign_team(id, team){
    $.post('api/setStaffTeam',{ 'id':id,'team':team },function(data){
      getStaffTeam();
      getMoreInfo(id);
    });
  }
  function removeFromLogger(id){
  	$('#rfl'+id).attr('onclick', 'removeFromLoggerConfirm('+id+')');
    $('#rfl'+id).text('Confirm');
    setTimeout(function(){
    	$('#rfl'+id).attr('onclick', 'removeFromLogger('+id+')');
    	$('#rfl'+id).text('Remove From Logger');
    },3000)
  }
  function removeFromLoggerConfirm(id){
  	$.post('api/removeStaff',{ 'id':id },function(data){
    	getStaffTeam();
      $('#staff_info').html("<h1>Select A Staff Member To Get Statistics</h1>");
    });
  }
  function getStaffActivity(name){
    $('#staff_info').html("<img src='img/loadw.svg'>");
    var other_staff;
    var other_staff_text;
    $.post('api/getStaffActivity',{ 'id':name },function(data){
      $('#staff_info').css('background', '#3a3a3a');
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
      $('#staff_info').html(setMoreInfo);
    });
  }
  function getCase(id){
    $('#staff_info').html("<img src='img/loadw.svg'>");
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
      setMoreInfo = '<style>#staff_info{color: '+stafftextc+' !important;}</style><h2><span>Case ID:</span> '+moreinfo.report.id+'-'+player_title+'</h2><p id="case"><span>Lead Staff:</span> '+moreinfo.report.lead_staff+'</p><p id="case"><span>Other Staff:</span> '+moreinfo.report.other_staff+'</p><p id="case"><span>Type Of Report:</span><br> '+moreinfo.report.typeofreport+'</p><p id="case" style="text-transform: capitalize;"><span>Players Involved:</span><br> '+players_involved+'</p><p id="case"><span>Description Of Events:</span><br> '+moreinfo.report.doe+'</p><p id="case"><span>Link To Player Report:</span>'+moreinfo.report.ltpr+'</p><p id="case"><span>Points?:</span> '+moreinfo.report.points+'</p><p id="case"><span>Ammount Of Points:</span> '+moreinfo.report.aop+'</p><p id="case"><span>Offence Committed:</span><br> '+moreinfo.report.oc+'</p><p id="case"><span>Evidence Given:</span><br> '+moreinfo.report.evidence+'</p><p id="case"><span>Banned?:</span> '+moreinfo.report.banned+'</p><p id="case"><span>Ban Length:</span> '+moreinfo.report.ban_length+' Days</p><p id="case"><span>Ban Message:</span><br> '+moreinfo.report.bm+'</p><p id="case"><span>TS Ban:</span> '+moreinfo.report.ts+'</p><p id="case"><span>Ingame Ban:</span> '+moreinfo.report.ig+'</p><p id="case"><span>Website Ban:</span> '+moreinfo.report.wb+'</p><p id="case"><span>Permenant Ban:</span> '+moreinfo.report.perm+'</p><p id="case"><span>Timestamp:</span> '+moreinfo.report.timestamp+'</p>';
      $('#staff_info').html(linkify(setMoreInfo));
    });
  }
  function userArrayLoaded(){
    if(userArray.info.id===""){
      location.replace('holdingpage');
    }
    if((userArray.info.slt==="0" && userArray.info.dev=="0") || userArray.info.slt===""){
      location.replace('holdingpage');
    }
    if((userArray.info.slt==="1" || userArray.info.dev=="1") && userArray.info.id!==""){
      getStaffTeam();
    }
    $('#welcome').html("Hello, "+userArray.info.username);
  }
</script>
</body>
</html>