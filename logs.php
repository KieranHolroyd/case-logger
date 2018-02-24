<?php include "head.php"; ?>
<div class="grid">
  <div class="grid__col grid__col--2-of-6" style="padding-left: 20px !important;">
    <h1 class="info-title">Server Logs</h1>
    <div id="reports" style='height: calc(100vh - 69px) !important;' class="cscroll">

    </div>
  </div>
  <div class="grid__col grid__col--4-of-6">
    <div id="case_info" style='height: 100vh;' class="cscroll">
      <h1>Select A Log To View Info.</h1>
    </div>
  </div>
</div>
<script>
  function getFiles() {
    $('#reports').html('<img src="img/loadw.svg">');
    $.get('api/getFiles', function (data) {
      var parsed = JSON.parse(data);
      var list = "";
      if (parsed.success==1){
        var files = JSON.parse(parsed.files);
        for(var i=0;i<Object.keys(files).length;i++){
          list += '<div class="case" onclick="getLogs(\''+escapeHtml(files[i].name)+'\')"><span style="font-size: 25px;text-transform:capitalize;">'+escapeHtml(files[i].name)+'</span></div>';
        }
        $('#reports').html(list);
      }
    });
  }
  function getLogs(name) {
    $('#case_info').html('<img src="img/loadw.svg">');
    $.get('api/getLogs', { "name":name } , function (data) {
      var parsed = JSON.parse(data);
      var list = "";
      if (parsed.success==1){
        list += "<h2>Viewing "+name+" Logs</h2>";
        var logs = JSON.parse(parsed.logs);
        for(var i=0;i<Object.keys(logs).length;i++){
          list += '<div style="padding:10px;border-bottom: 1px solid #222;"><span style="font-size: 15px;">'+escapeHtml(logs[i].text)+'</span><br><span style="font-size:12px;">'+escapeHtml(convertTimestamp(logs[i].time))+'</span></div>';
        }
        $('#case_info').html(list);
      }
    });
  }
  function convertTimestamp(timestamp) {
      var d = new Date(0);
      d.setUTCMilliseconds(timestamp*1000);
      var str = d.toUTCString();
      var strArr = str.split(", ");
      if (strArr.length == 2)
        str = strArr[1];
      return str;
      
  }
  function escapeHtml(unsafe) {
      return unsafe
          .replace(/&/g, "&amp;")
          .replace(/</g, "&lt;")
          .replace(/>/g, "&gt;")
          .replace(/"/g, "&quot;")
          .replace(/'/g, "&#039;")
          .replace(/\(/g, "&#040;")
          .replace(/\)/g, "&#041;");
  }
  getFiles();
</script>
<?php include "footer.php"; ?>