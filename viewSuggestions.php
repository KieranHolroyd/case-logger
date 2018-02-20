<?php include "head.php"; include "navbar.php"; ?>
<div class="grid">
  <div class="grid__col grid__col--4-of-6" style="padding-left: 20px !important;">
    <h1 class="info-title" id="welcome"></h1>
    <div id="reports">
			
    </div>
  </div>
  <div class="grid__col grid__col--2-of-6">
    <div id="case_info">
    </div>
  </div>
</div>
<script>
  var setMoreInfo = "";
  var suggestion = "";
  var name = "";
	function getSuggestions(){
    $.get('api/getSuggestions',function(data){
      moreinfo=JSON.parse(data);
      for (let i = 1; i < Object.keys(moreinfo).length + 1; i++) {
        setMoreInfo += '<div class="staffActivityCard" id="'+moreinfo[i].id+'" onclick="more('+moreinfo[i].id+')"><span id="name">'+moreinfo[i].name+'</span><br><span id="suggestion">'+moreinfo[i].suggestion+'</span></div>'
      }
      $('#reports').html(setMoreInfo);
    });
  }
  function more(id){
  	suggestion = $('#'+id+' #suggestion').text();
    name = $('#'+id+' #name').text();
    setMoreInfo = "<h2>"+name+"'s Suggestion</h2><br><p><span>Suggestion: </span>"+suggestion+"</p>";
    $('#case_info').html(linkify(setMoreInfo));
  }
  getSuggestions();
</script>
<?php include "footer.php"; ?>