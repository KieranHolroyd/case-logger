<?php include "head.php"; ?>
<script src="https://cdn.ckeditor.com/4.8.0/standard/ckeditor.js"></script>
<div class="grid">
  <div class="grid__col grid__col--2-of-6" style="padding-left: 20px !important;">
    <h1 class="info-title">Guide List</h1>
    <div id="guides" class="cscroll">

    </div>
    <div id="cngcont" style="display: none;">
      <div style="height: 50px;"></div>
      <button id="modalLaunch" launch="createGuide" style='margin: 0;position: fixed;bottom:0;left:0;width: 32.35%;'>Create New Guide</button>
    </div>
  </div>
  <div class="grid__col grid__col--4-of-6">
    <div id="guide_info" class="cscroll">
      <h1>Select A Guide To View.</h1>
    </div>
  </div>
</div>
<div class="modal" id="createGuide">
  <button id="close">×</button>
  <div class="content" style="max-width: 900px;padding:0;">
    <div class="field"><div class='fieldTitle'>Guide Title</div><input id="Gtitle" type="text" class='fieldInput' placeholder="Guide Title"></div>
  	<div class="field"><div class='fieldTitle'>Guide Body</div><textarea id="guide_body" cols="30" rows="10"></textarea></div>
    <button style='width:100%;margin: 0;' id="createButton">Create</button>
  </div>
</div>
<script>
  let list, guide, item;
  function userArrayLoaded(){
  	if(userArray.info.slt==1){
    	$('#cngcont').fadeIn(200);
    }
  }
	function getGuides(){
    list="";
    $.get('api/getGuides',function(data){
      guides=JSON.parse(data);
      for (let i = 1; i < Object.keys(guides).length + 1; i++) {
        list += '<div class="case" onclick="getFullGuide('+guides[i].id+')"><span style="float: right;font-size: 12px;">Authored By: '+guides[i].author+'</span><span style="font-size: 25px;">'+guides[i].title+'<br></span></div>';
      }
      $('#guides').html(list);
    });
  };
  function getFullGuide(id){
    $('#guide_info').html("<img src='img/loadw.svg'>");
    item = "";
    $.post('api/getFullGuide', { 'id':id }, function(data){
      guide = JSON.parse(data);
      if(userArray.info.slt==1){
      	item = "<button onclick='edit("+id+")' style='margin: 0;width: 100%;'>Edit This</button>";
      }
    	item += "<h1>"+guide.title+"</h1><p>"+guide.time+" | "+guide.author+"</p><div>"+guide.body+"</div>";
    	$('#guide_info').html(linkify(item));
    });
  }
  getGuides();
  function newGuide(){
  	$.post('api/newGuide', {
    	'title':$('#Gtitle').val(),
      'body':editor.getData()
    }, function(data){
      $('#Gtitle').val('');
    	editor.setData('');
      console.log(data);
      getGuides();
      new Noty({
        type: 'success',
        layout: 'topRight',
        theme: 'metroui',
        timeout: 3000,
        text: data,
      }).show();
    });
  }
  function edit(id){
  	new Noty({
      type: 'info',
      layout: 'topRight',
      theme: 'metroui',
      timeout: 3000,
      text: 'Edit Functionality Not Yet Implemented.',
    }).show();
  }
  $('#createButton').click(function(){
  	newGuide();
  });
  var editor = CKEDITOR.replace('guide_body');
</script>
<?php include "footer.php"; ?>