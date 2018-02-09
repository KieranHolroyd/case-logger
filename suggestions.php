<?php session_start(); include "head.php"; $csrf=bin2hex(openssl_random_pseudo_bytes(32));$_SESSION['csrf']=$csrf; ?>
  <?php include "navbar.php"; ?>
  <div class="grid" style="padding:15px;">
    <div class="grid__col grid__col--2-of-6 grid__col--push-2-of-6">
      <div id="basic_report">
        <input id="csrf" type="hidden" value="<?php echo $csrf; ?>">
        <p>Submitting As <span style="font-weight: bold;" id="sas">Human</span></p>
        <input id="name" type="hidden">
        <textarea id="suggestion" class="fieldTextarea" placeholder="Your Suggestion?"></textarea>
      </div>
    </div>
  </div>
  <div style="margin-top: 120px;"></div>
  <button onclick="submit();" class="submitBtn">Submit</button>
  <script>
    function submit(){
    	$.post('addSuggestion.php',{
      	'name': $('#sas').text(),
        'suggestion': $('#suggestion').val(),
        'csrf': $('#csrf').val()
      }, function(data){
        $('#suggestion').val('');
        new Noty({
          type: 'success',
          layout: 'topRight',
          theme: 'metroui',
          timeout: 3000,
          text: data,
        }).show();
      });
    };
  </script>
</body>
<!--Created By Kieran Holroyd-->
</html>