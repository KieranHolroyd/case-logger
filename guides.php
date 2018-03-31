<?php include "head.php"; ?>
<script src="https://cdn.ckeditor.com/4.8.0/standard/ckeditor.js"></script>
<div class="grid">
  <div class="grid__col grid__col--2-of-6" style="padding-left: 20px !important;">
    <h1 class="info-title">Guide List</h1>
    <div id="cngcont" style="display: none;"><button class="newsubmitBtn" id="modalLaunch" launch="createGuide">Create New Guide</button></div>
    <div id="guides" class="cscroll">

    </div>
    <div id="cngcont" style="display: none;">
      <div style="height: 50px;"></div>
    </div>
  </div>
  <div class="grid__col grid__col--4-of-6">
    <div class="moreInfoPanel" id="guide_info" class="cscroll">
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
<div class="modal" id="editGuide">
  <button id="close">×</button>
  <div class="content" style="max-width: 900px;padding:0;">
    <div class="field"><div class='fieldTitle'>Guide Title</div><input id="Gtitle_edit" type="text" class='fieldInput' placeholder="Guide Title"></div>
  	<div class="field"><div class='fieldTitle'>Guide Body</div><textarea id="guide_body_edit" cols="30" rows="10"></textarea></div>
    <button style='width:100%;margin: 0;' id="editButton">Create</button>
  </div>
</div>
<script>
  var list, guide, item, currently_editing;
  function checkIfSLT(){
  	if(userArray.info.slt==1 || userArray.info.dev==1){
    	$('#cngcont').slideDown(100);
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
      checkIfSLT();
    });
  };
  function getFullGuide(id){
    $('#guide_info').html("<img src='img/loadw.svg'>");
    item = "";
    $.post('api/getFullGuide', { 'id':id }, function(data){
      guide = JSON.parse(data);
      if(userArray.info.slt==1 || userArray.info.dev==1){
      	item = "<button onclick='edit_open("+id+")' id='modalLaunch' launch='editGuide' style='margin: 0;width: 100%;'>Edit This</button>";
      }
    	item += "<h1>"+guide.title+"</h1><p>"+guide.time+" | "+guide.author+"</p><div>"+guide.body+"</div>";
    	$('#guide_info').html(item);
    });
  }
  getGuides();
  function newGuide(){
    if(userArray.info.slt==1){
      $.post('api/addGuide', {
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
    } else {
      new Noty({
        type: 'success',
        layout: 'topRight',
        theme: 'metroui',
        timeout: 3000,
        text: "You Must Be SLT To Submit A Guide",
      }).show();
    }
  }
  function edit_open(id){
	currently_editing = id;
  	$.post('api/getFullGuide', { 'id':id }, function(data){
      guide = JSON.parse(data);
    	$('#Gtitle_edit').val(guide.title);
    	edit_editor.setData(guide.body);
    });
  }
  function editGuide() {
    $.post('api/editGuide', { 
      'id':currently_editing,
      'title':$('#Gtitle_edit').val(),
      'body':edit_editor.getData()
    }, function(data) {
		getFullGuide(currently_editing);
		new Noty({
			type: 'success',
			layout: 'topRight',
			theme: 'metroui',
			timeout: 3000,
			text: data,
		}).show();
    });
  }
  $('#createButton').click(function(){
  	newGuide();
  });
  $('#editButton').click(function(){
  	editGuide();
  });
  var editor = CKEDITOR.replace('guide_body');
  var edit_editor = CKEDITOR.replace('guide_body_edit');
</script>
<?php include "footer.php"; ?>