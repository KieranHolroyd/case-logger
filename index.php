<?php session_start(); include "head.php"; ?>
  <?php include "navbar.php"; ?>
  <div class="grid" style="padding-left:15px;">
    <div class="grid__col grid__col--1-of-6">
      <div style="margin-left: 5px;position: relative;max-width: 100%;background-color: #222;height: calc(100vh - 44px);overflow: auto;box-shadow: 0 0 5px 0 rgba(0,0,0,0.2);">
        <p class="label">Other Staff</p>
        <button class="pickbtn" id="addOtherStaff" style="display: none;">Add Other Staff</button>
        <button class="pickbtn" id="removeOtherStaff" style="display: none;">Remove Other Staff</button>
        <p class="label">Players</p>
        <button class="pickbtn" id="addPlayerReporter">Add Reporting Player</button>
        <button class="pickbtn" id="addPlayerReported">Add Reported Player</button>
        <button class="pickbtn" id="removePlayer">Remove Last Player</button>
        <p class="label">Type Of Report: <span id="typeofreportdisplay">Other</span></p>
        <button class="pickbtn" id="TypeOfReportButton">Select</button>
        <div class="reportTypes" id="TypeOfReportList">
          <button class="pickbtn tor" value="Player Report">Player Report</button>
          <button class="pickbtn tor" value="Tech Support">Tech Support</button>
          <button class="pickbtn tor" value="General Question">General Question</button>
          <button class="pickbtn tor" value="Website Tags">Website Tags</button>
          <button class="pickbtn tor" value="Teamspeak Tags">Teamspeak Tags</button>
          <button class="pickbtn tor" value="Forum Work">Forum Work</button>
          <button class="pickbtn tor" value="Whitelisting">Whitelist</button>
          <button class="pickbtn tor" value="Donation Support">Donation Support</button>
          <button class="pickbtn tor" value="Compensation">Compensation</button>
          <button class="pickbtn tor" value="Ban Log">Ban Log</button>
          <button class="pickbtn tor" value="Unban Log">Unban Log</button>
          <button class="pickbtn tor" value="Other">Other</button>
        </div>
        <p class="label">Punishment</p>
        <button class="pickbtn" id="PunishmentReportButton">Add Punishment Report</button>
        <p class="label">Ban</p>
        <button class="pickbtn" id="BanReportButton">Add Ban Report</button>
      </div>
    </div>
    <div class="grid__col grid__col--5-of-6" style="height: calc(100vh - 44px) !important;overflow: auto;">
      <div class="topBar">
        <h2 style="text-align: center;">Submitting As <span style="font-weight: bold;color: #00c5ff;" id="sas">Human</span></h2>      	
      </div>
      <div style="height: 60px;background-color: #232323;"></div>
      <div class="field">
      	<div class="fieldTitle">Description Of Events</div>
        <textarea class="fieldTextarea" id="doi" placeholder="Description Of The Events?*"></textarea>
      </div>
        <input id="lsm" type="hidden">
      	<input id="typeOfReportField" type="hidden" value="Other">
        <div id="otherStaffList"></div>
        <div id="playerList">
          <div class='field'>
          	<div class='fieldTitle'>Player Involved #1 (Reporter)</div>
            <input class='fieldInput' id='player1' placeholder='Add Reporter'><input class='fieldInput' id='playerGUID1' placeholder='Player GUID'>
          </div>
        </div>
      <div id="punishmentReport" style="display:none;">
      	<div class="field"><div class="fieldTitle">Ammount Of Points</div><input class="fieldInput" id="aop" type="text" placeholder="Amount Of Points Issued*"></div>
      	<div class="field"><div class="fieldTitle">Link To Player Report</div><input class="fieldInput" id="ltpr" type="text" placeholder="Link To Player Report"></div>
      	<div class="field"><div class="fieldTitle">Rule Broken</div><input class="fieldInput" id="oc" type="text" placeholder="Rule Broken*"></div>
      	<div class="field"><div class="fieldTitle">Evidence Supplied</div><textarea class="fieldTextarea" id="es" placeholder="Evidence Supplied?*"></textarea></div>
      </div>
      <div id="banReport" style="display:none;">
      	<div class="field"><div class="fieldTitle">Ban Length</div><input class="fieldInput" id="bl" type="text" placeholder="Ban Length (Days)*"></div>
        <div class="field"><div class="fieldTitle">Ban Message</div><input class="fieldInput" id="bm" type="text" placeholder="Ban Message*"></div>
        <div class="field"><div class="fieldTitle">Teamspeak Ban?</div><select class="fieldSelector" id="ts"><option value="1">No</option><option value="2">Yes</option></select></div>
        <div class="field"><div class="fieldTitle">Ingame Ban?</div><select class="fieldSelector" id="ig"><option value="1">No</option><option value="2">Yes</option></select></div>
        <div class="field"><div class="fieldTitle">Website Ban?</div><select class="fieldSelector" id="wb"><option value="1">No</option><option value="2">Yes</option></select></div>
        <div class="field"><div class="fieldTitle">Permanant Ban?</div><select class="fieldSelector" id="pb"><option value="1">No</option><option value="2">Yes</option></select></div>
      </div>
      <button id="modalLaunch" launch="confirmCase" onclick="confirmSubmit()" class="newsubmitBtn">Submit</button>
    </div>
	</div>
	<div class="modal" id="confirmCase">
    <button id="close">×</button>
    <div class="content" style="max-width: 900px;padding:0;">
      <div id="confirmBody" style='margin: 10px;padding-top: 10px;'></div>
      <button style='width:100%;margin: 0;' onclick="submit()">Send</button>
    </div>
  </div>
  <script>
    let banReport, punishmentReport, otherStaffParsed;
    //Functionality Script For The New Design
    $('#TypeOfReportButton').click(function(){
      $('#TypeOfReportList').slideToggle(200);
    });
    $(document).on('click', '.tor', function () {
      var typeofreport = $(this).attr('value');
      $('#typeOfReportField').val(typeofreport);
      $('#typeofreportdisplay').text(typeofreport);
      $('#TypeOfReportList').slideToggle(200);
    });
    $('#PunishmentReportButton').click(function(){
      if($('#PunishmentReportButton').attr('open')){
        $('#punishmentReport').slideUp();
        $('#PunishmentReportButton').text('Add Punishment Report');
        $('#PunishmentReportButton').removeAttr('open');
        punishmentReport = 0;
      } else {
        $('#punishmentReport').slideDown();
        $('#PunishmentReportButton').text('Remove Punishment Report');
        $('#PunishmentReportButton').attr('open', true);
        punishmentReport = 1;
      }
    });
    $('#BanReportButton').click(function(){
      if($('#BanReportButton').attr('open')){
        $('#banReport').slideUp();
        $('#BanReportButton').text('Add Punishment Report');
        $('#BanReportButton').removeAttr('open');
        banReport = 0;
      } else {
        $('#banReport').slideDown();
        $('#BanReportButton').text('Remove Punishment Report');
        $('#BanReportButton').attr('open', true);
        banReport = 1;
      }
    });
    function confirmSubmit(){
      var gotPoints, gotBanned;
      if(punishmentReport==1){gotPoints="Yes"}else{gotPoints="No"}
      if(banReport==1){gotBanned="Yes"}else{gotBanned="No"}
      otherStaffParsed="";
      for(let i = 1; i < otherStaff + 1; i++){
      	otherStaffParsed += $('#os'+i).val()+" ";
        console.log(otherStaffParsed);
      }
    	let list = '<div style="height: 100%;" id="case_info"><p id="case"><span>Case Title: '+$('#player1').val()+'</span></p><p id="case"><span>Lead Staff:</span> '+$('#lsm').val()+'</p><p id="case"><span>Other Staff:</span> '+otherStaffParsed+'</p><p id="case"><span>Type Of Report:</span> '+$('#typeOfReportField').val()+'</p><p id="case"><span>Description Of Events:</span> '+$('#doi').val()+'</p><p id="case"><span>Link To Player Report:</span>'+$('#ltpr').val()+'</p><p id="case"><span>Points?:</span> '+gotPoints+'</p><p id="case"><span>Ammount Of Points:</span> '+$('#aop').val()+'</p><p id="case"><span>Offence Committed:</span><br> '+$('#oc').val()+'</p><p id="case"><span>Evidence Given:</span><br> '+$('#es').val()+'</p><p id="case"><span>Banned?:</span> '+gotBanned+'</p><p id="case"><span>Ban Length:</span> '+$('#bl').val()+' Days</p><p id="case"><span>Ban Message:</span><br> '+$('#bm').val()+'</p><p id="case">(1=No, 2=Yes)</p><p id="case"><span>TS Ban:</span> '+$('#ts').val()+'</p><p id="case"><span>Ingame Ban:</span> '+$('#ig').val()+'</p><p id="case"><span>Website Ban:</span> '+$('#wb').val()+'</p><p id="case"><span>Permenant Ban:</span> '+$('#pb').val()+'</p><p id="case"><span>Timestamp:</span> '+currentTime()+'</p></div>';
      $('#confirmBody').html(linkify(list));
    }
    let staffList="";
    function submit(){
      $('#confirmBody').html("<center><h1>Sending Case...</h1></center>");
      $('#submitReport').css('pointer-events', 'none');
      $('#submitReport').text('Case Added Please Wait');
      let type;
      otherStaffParsed="";
      for(let i = 1; i < otherStaff + 1; i++){
      	otherStaffParsed += $('#os'+i).val()+" ";
        console.log(otherStaffParsed);
      }
      playerArray.forEach(function(value, index){
        console.log(value+index)
        type="";
        if(playerArray[index].reported==undefined){
        	type="reporter";
        } else {
        	type="reported";
        }
      	playerArray[index] = {
        	type: type,
          name: $('#player'+index).val(),
          guid: $('#playerGUID'+index).val()
        };
      });
      playerArray.splice(0,1);
    	$.post('submit.php',{
      	'lead_staff': $('#lsm').val(),
        'other_staff': otherStaffParsed,
        'description_of_events': $('#doi').val(),
        'player_guid': $('#guid').val(),
        'link_to_player_report': $('#ltpr').val(),
        'offence_committed': $('#oc').val(),
        'points_awarded': punishmentReport,
        'ammount_of_points': $('#aop').val(),
        'evidence_supplied': $('#es').val(),
        'ban_awarded': banReport,
        'ban_length': $('#bl').val(),
        'ban_message': $('#bm').val(),
        'ts_ban': $('#ts').val(),
        'ingame_ban': $('#ig').val(),
        'website_ban': $('#wb').val(),
        'ban_perm': $('#pb').val(),
        'players': playerArray,
        'type_of_report': $('#typeOfReportField').val(),
        'csrf': $('#csrf').val()
      }, function(data){
        $('#osi').val('');
        $('#doi').val('');
        $('#guid').val('');
        $('#ltpr').val('');
        $('#oc').val('');
        $('#apg').val('');
        $('#aop').val('');
        $('#es').val('');
        $('#bl').val('');
        $('#bm').val('');
        $('#pt').val('');
        $('#ts').val('1');
        $('#ig').val('1');
        $('#wb').val('1');
        $('#pb').val('1');
        $('#name').val('');
        $('#punishmentReport').slideUp();
        $('#PunishmentReportButton').text('Add Punishment Report');
        $('#PunishmentReportButton').removeAttr('open');
        punishmentReport = 0;
				$('#banReport').slideUp();
        $('#BanReportButton').text('Add Punishment Report');
        $('#BanReportButton').removeAttr('open');
        banReport = 0;
        $('#otherStaffList').html('');
        otherStaff=0;
        $('#playerList').html("<div class='field'><div class='fieldTitle'>Player Involved #1 (Reporter)</div><input class='fieldInput' id='player1' placeholder='Add Reporter'><input class='fieldInput' id='playerGUID1' placeholder='Player GUID'></div>");
        playerCount=1;
        playerArray = [{}];
        playerArray.push({
          reporter:''
        });
        setTimeout(function(){$('#submitReport').css('pointer-events', 'auto');$('#submitReport').text('Submit');},5000)
        updateDailyCases();
        new Noty({
          type: 'success',
          layout: 'topRight',
          theme: 'metroui',
          timeout: 3000,
          text: 'Case Logged Successfully!',
        }).show();
        closeAllModal();
      });
    };
    var otherStaff = 0;
    var playerCount = 1;
    var playerArray = [{}];
    $(document).ready(function(){
      playerArray.push({
        reporter:''
      });
    	$('#addOtherStaff').click(function(){
        if(otherStaff<10){
          otherStaff++;
          $('#otherStaffList').append("<div class='field' style='display: none;'><div class='fieldTitle'>Assistant Staff Member #"+otherStaff+"</div><select class='fieldSelector' id='os"+otherStaff+"'><option value='0'>Select A Staff Member</option>"+staffList+"</select></div>");
          $('#otherStaffList .field').last().slideDown(150);
        } else {
        	new Noty({
            type: 'error',
            layout: 'topRight',
            theme: 'metroui',
            timeout: 3000,
            text: 'Max Other Staff Reached (10)',
          }).show();
        }
      });
      $('#removeOtherStaff').click(function(){
        if(otherStaff>0){
          $('#otherStaffList .field').last().slideUp(150);
          setTimeout(function(){$('#otherStaffList .field').last().remove();},150);
          otherStaff--;
        }
      });
      $('#addPlayerReporter').click(function(){
          playerCount++;
        	playerArray.push({
          	reporter:''
          });
        	$('#playerList').append("<div class='field' style='display: none;'><div class='fieldTitle'>Player Involved #"+playerCount+" (Reporter)</div><input class='fieldInput' id='player"+playerCount+"' placeholder='Add Reporter'><input class='fieldInput' id='playerGUID"+playerCount+"' placeholder='Player GUID'></div>");
        	$('#playerList .field').last().slideDown(150);
      });
      $('#addPlayerReported').click(function(){
          playerCount++;
          playerArray.push({
            reported:''
          });
        	$('#playerList').append("<div class='field' style='display: none;'><div class='fieldTitle'>Player Involved #"+playerCount+" (Reported)</div><input class='fieldInput' id='player"+playerCount+"' placeholder='Add Reported Player'><input class='fieldInput' id='playerGUID"+playerCount+"' placeholder='Player GUID'></div>");
        	$('#playerList .field').last().slideDown(150);
      })
      $('#removePlayer').click(function(){
        if(playerCount>1){
          playerArray.splice(-1,1);
          $('#playerList .field').last().slideUp(150);
          setTimeout(function(){$('#playerList .field').last().remove();},150);
          playerCount--;
        }
      });
      gsl();
    });
    function gsl(){
      $.post('getStaffList.php',{},function(data){
        staff=JSON.parse(data);
        for (let i = 1; i < Object.keys(staff).length + 1; i++) {
          staffList += "<option value='"+staff[i].name+"'>"+staff[i].name+"</option>";
        }
        $('#removeOtherStaff').show();
        $('#addOtherStaff').show();
      });
    }
  </script>
</body>
<!--Created By Kieran Holroyd-->
</html>