<?php $nonav=0; include "head.php"; ?>
<?php if(!isset($_COOKIE['bg'])){ ?>
	<body style="background: no-repeat center center fixed;background-size: cover;background-image: url(img/bg1.png);">
<?php } elseif(isset($_COOKIE['cbg'])) { ?>
<?php } else { ?>	
	<body style="background: no-repeat center center fixed;background-size: cover;background-image: url(img/bg<?php echo $_COOKIE['bg']; ?>.png);">
<?php }?>
<div class="dashboardOverlay">
	<div id="titleText" style="z-index:2;">
		<h1 id="welcome" style="padding: 10px;display:inline-block;">Hello, Human</h1><h1 style="padding: 10px;float:right;display:inline-block;" id="dtime"></h1>
	</div>
		<a href="logger">
			<div class="navCard">
				<div class="navCard-items">
					<p class="title" style="color:#1abc9c;"><i class="fas fa-clipboard" style="padding-right: 10px;color:#1abc9c;"></i> Log Case</p>
					<!-- <p class="shortcontent" style="color:#16a085;">Go here to log your cases.</p> -->
				</div>
			</div>  
		</a>
		<a href="guides">
			<div class="navCard">
				<div class="navCard-items">
					<p class="title" style="color:#e67e22;"><i class="fas fa-book" style="padding-right:10px;color:#e67e22;"></i> Guides</p>
					<!-- <p class="shortcontent" style="color:#d35400;">Tutorials and Operating Procedures.</p> -->
				</div>
			</div>  
		</a>
		<a href="me">
			<div class="navCard">
				<div class="navCard-items">
					<p class="title" style="color:#2ecc71;"><i class="fas fa-address-card" style="padding-right:10px;color:#2ecc71;"></i> Profile</p>
					<!-- <p class="shortcontent" style="color:#27ae60;">View your User Profile</p> -->
				</div>
			</div>  
		</a>
		<a href="meetings">
				<div class="navCard-small">
					<div class="navCard-items">
						<p class="title"><i class="far fa-calendar-alt"></i> Meetings</p>
						<p class="shortcontent">Go here to view previous meetings and input on upcoming ones.</p>
					</div>
				</div>  
		</a>
		<a href="logger.php">
				<div class="navCard-small">
					<div class="navCard-items">
						<p class="title">Test</p>
						<p class="shortcontent">Go here to log your cases.</p>
					</div>
				</div>  
		</a>
		<a href="logger.php">
				<div class="navCard-small">
					<div class="navCard-items">
						<p class="title">Test</p>
						<p class="shortcontent">Go here to log your cases.</p>
					</div>
				</div>  
		</a>
		<a id="modalLaunch" launch="selectBG" style="cursor: pointer;">
			<div class="navCard-small">
				<div class="navCard-items">
					<p class="title">Change Background Image</p>
					<p class="shortcontent">Change the background image of this page.</p>
				</div>
			</div>  
		</a>
	</div>
	<script>
		$('#dtime').text(currentTime());
		setInterval(() => {
			$('#dtime').text(currentTime());
		}, 1000);
		function selectBG(bg, custom) {
			var currentBG = Cookies.get('bg');
			if(!custom){
				Cookies.set('bg', bg, { expires: 720 });
				if(currentBG !== undefined) {$('#selectBG'+currentBG).text('[SELECT]');}
				$('#selectBG'+bg).text('[SELECTED]');
				$('body').css('background-image', 'url("img/bg'+bg+'.png")');
			}
		}
	</script>
</body>
<div class="modal" id="selectBG">
	<button id="close">×</button>
	<div class="content" style="max-width: 900px;border-radius: 5px;">
		<h2>Choose A Background Image</h2>
		<p>Background 1 (Default) <span id="selectBG1" style="cursor:pointer;" onclick="selectBG(1, false)"><?php if($_COOKIE['bg']==="1"){ echo "[SELECTED]"; } else { echo "[SELECT]"; }?></span></p>
		<img src="img/bg1.png" onclick="selectBG(1, false)" style="border-radius: 5px;box-shadow: 0 0 5px 0 rgba(0,0,0,0.3);margin:5px;width: calc(100% - 10px);" alt="Background 1 (Default)" title="Background 1 (Default)">
		<p>Background 2 <span id="selectBG2" style="cursor:pointer;" onclick="selectBG(2, false)"><?php if($_COOKIE['bg']==="2"){ echo "[SELECTED]"; } else { echo "[SELECT]"; }?></span></p>
		<img src="img/bg2.png" onclick="selectBG(2, false)" style="border-radius: 5px;box-shadow: 0 0 5px 0 rgba(0,0,0,0.3);margin:5px;width: calc(100% - 10px);" alt="Background 2" title="Background 2">
	</div>
</div>
<!--Created By Kieran Holroyd-->
</html>