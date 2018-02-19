<?php include "head.php"; ?>
	<div class="card card-sm">
		<div class="card-head">
			<h2>Passport</h2>
		</div>
    <div class="card-med">
    	<div class="loginselector">
        <button id="chooseLogin" style="background-color: #222;">Login</button><button id="chooseSignup">Signup</button>
      </div>
    </div>
    <div class="card-body">
    	<div class="login-div">
    		<input id="l-email" type="text" placeholder="Email">
  			<input id="l-password" type="password" placeholder="Password">
    	</div>
      <div class="signup-div" style="display: none;">
        <input id="s-firstname" type="text" placeholder="First Name">
        <input id="s-lastname" type="text" placeholder="Last Name">
        <input id="s-email" type="text" placeholder="Email">
  			<input id="s-password" type="password" placeholder="Password">
        <input id="s-password-conf" type="password" placeholder="Confirm">
      </div>
    </div>
    <div class="card-footer">
    	<button id="continue">Continue</button>
    </div>
	</div>
	<script>
    var selected;
    $(document).ready(function(){
    	$('#chooseLogin').css('background-color', '#222');
      $('#chooseSignup').css('background-color', '#333');
    	$('.signup-div').slideUp(250);
      $('.login-div').slideDown(250);
      selected=1;
    });
		$('#chooseLogin').click(function(){
      $('#chooseLogin').css('background-color', '#222');
      $('#chooseSignup').css('background-color', '#333');
    	$('.signup-div').slideUp(250);
      $('.login-div').slideDown(250);
      selected=1;
    });
    $('#chooseSignup').click(function(){
      $('#chooseSignup').css('background-color', '#222');
      $('#chooseLogin').css('background-color', '#333');
    	$('.login-div').slideUp(250);
      $('.signup-div').slideDown(250);
      selected=0;
    });
    $('#continue').click(function(){
    	if(selected===0){
      	$.post('api/signupUser',{ 
          first_name: $('#s-firstname').val(),
      		last_name: $('#s-lastname').val(),
          email: $('#s-email').val(),
          password: $('#s-password').val(),
          cpassword: $('#s-password-conf').val()
        },function(data){
        	new Noty({
            type: 'success',
            layout: 'topRight',
            theme: 'metroui',
            timeout: 3000,
            text: data,
          }).show();
        });
      } else if(selected===1){
      	$.post('api/loginUser',{ 
          email: $('#l-email').val(),
      		password: $('#l-password').val(),
        },function(data){
          data=JSON.parse(data);
        	if (data.token=="Failed") {notify="Login Failed. Try Again"; type='error';} else {notify="Login Success. Redirecting";type='success';}
          new Noty({
            text: notify,
            progressBar: true,
            type: type
        	}).show();
          userArraySet();
          $.post("apiCheckLogin.php", {}, 
          function(data){
            console.log(data)
            if (!data) {
              console.log('Login Failed');
            } else {
              location.replace("index.php");
            }
        	});
        });
      }
    });
    function userArrayLoaded(){
    	if(userArray.info.username !== ""){
      	window.location.href = "./";
      }
    }
	</script>
<?php include "footer.php"; ?>