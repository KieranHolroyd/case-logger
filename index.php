<?php $nonav=0; include "head.php"; ?>
<?php if(isset($_COOKIE['cbg'])) { ?>
	<body style="background: no-repeat center center fixed;background-size: cover;background-image: url('<?php echo htmlspecialchars(strip_tags($_COOKIE['cbg'])); ?>');">
<?php } else if(!isset($_COOKIE['bg'])){ ?>
	<body style="background: no-repeat center center fixed;background-size: cover;background-image: url(img/bg1.png);">
<?php } else { ?>	
	<body style="background: no-repeat center center fixed;background-size: cover;background-image: url(img/bg<?php echo htmlspecialchars(strip_tags($_COOKIE['bg'])); ?>.png);">
<?php } ?>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<div class="dashboardOverlay" id="app">
	<div id="titleText" style="z-index:2;">
		<h1 style="padding: 10px;display:inline-block;">{{ user.info.firstname }} {{ user.info.lastname }} <small style="font-weight: 300;font-size: 15px;">{{ user.info.rank }}</small></h1><h1 style="padding: 10px;float:right;display:inline-block;" id="dtime"></h1>
	</div>
		<a href="logger">
			<div class="navCard-small">
				<div class="navCard-items">
					<p class="title" style="color:#e17bed;"><i class="fas fa-clipboard" style="padding-right: 10px;color:#e17bed;"></i> Log Case</p>
					<p class="shortcontent">Log your cases here.</p>
				</div>
			</div>  
		</a>
		<a href="guides">
			<div class="navCard-small">
				<div class="navCard-items">
					<p class="title" style="color:#e85575;"><i class="fas fa-book" style="padding-right:10px;color:#e85575;"></i> Guides</p>
					<p class="shortcontent">View all staff documentation and guides here.</p>
				</div>
			</div>  
		</a>
		<a href="me">
			<div class="navCard-small">
				<div class="navCard-items">
					<p class="title" style="color:#2ecc71;"><i class="fas fa-address-card" style="padding-right:10px;color:#2ecc71;"></i> Profile</p>
					<p class="shortcontent">View your profile.</p>
				</div>
			</div>  
		</a>
		<a href="meetings">
			<div class="navCard-small">
				<div class="navCard-items">
					<p class="title" style="color:#4286f4;"><i class="far fa-calendar-alt" style="padding-right: 10px;color:#4286f4;"></i> Meetings</p>
					<p class="shortcontent">Go here to view previous meetings and input on upcoming ones.</p>
				</div>
			</div>  
		</a>
		<a href="logs" v-if="this.user.info.dev == 1">
			<div class="navCard-small">
				<div class="navCard-items">
					<p class="title" style="color:#48d6c0;"><i class="fas fa-book" style="padding-right: 10px;color:#48d6c0;"></i> Server Logs</p>
					<p class="shortcontent">Go here to view live logs from the Takistan server.</p>
				</div>
			</div>  
		</a>
		<a href="viewer" v-if="this.user.info.slt == 1">
			<div class="navCard-small">
				<div class="navCard-items">
					<p class="title" style="color: #aae263;"><i class="fas fa-eye" style="padding-right: 10px;color: #aae263;"></i> View Cases</p>
					<p class="shortcontent">View cases submitted by staff members.</p>
				</div>
			</div>  
		</a>
		<a href="search?type=cases" v-if="this.user.info.slt == 1">
			<div class="navCard-small">
				<div class="navCard-items">
					<p class="title" style="color: #e58957"><i class="fas fa-search" style="padding-right: 10px;color: #e58957;"></i> Search Cases</p>
					<p class="shortcontent">Search cases submitted by staff members.</p>
				</div>
			</div>  
		</a>
		<a href="staff" v-if="this.user.info.slt == 1">
			<div class="navCard-small">
				<div class="navCard-items">
					<p class="title" style="color: #f75454"><i class="fas fa-clipboard-list" style="padding-right: 10px;color: #f75454;"></i> Manage Staff</p>
					<p class="shortcontent">Manage the staff team using this case logger.</p>
				</div>
			</div>  
		</a>
		<a href="staffStatistics">
			<div class="navCard-small">
				<div class="navCard-items">
					<p class="title" style="color: #bc53f4"><i class="fas fa-chart-line" style="padding-right: 10px;color: #bc53f4;"></i> Staff Statistics</p>
					<p class="shortcontent">View Global Staff Statistics.</p>
				</div>
			</div>  
		</a>
		<a id="modalLaunch" launch="selectBG" style="cursor: pointer;">
			<div class="navCard-small">
				<div class="navCard-items">
					<p class="title"><i class="fas fa-cog" style="padding-right: 10px;"></i> Settings</p>
					<p class="shortcontent">Change your settings.</p>
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
				Cookies.set('cbg', cimg, { expires: 0 });
				Cookies.set('bg', bg, { expires: 720 });
				if(currentBG !== undefined) {$('#selectBG'+currentBG).text('[SELECT]');}
				$('#selectBG'+bg).text('[SELECTED]');
				$('body').css('background-image', 'url("img/bg'+bg+'.png")');
			}
		}
		function setCustomBackground() {
			var cimg = $('#cimg').val();
			Cookies.set('bg', 0, { expires: 0 });
			Cookies.set('cbg', cimg, { expires: 720 });
			$('body').css('background-image', 'url("'+cimg+'")');
		}
		let vm = new Vue({
			el: '#app',
			data: {
				user: {}
			}
		});
		$.get("<?php echo $url; ?>api/getUserInfo", function(data){
			vm.user=JSON.parse(data);
		})
		function userArrayLoaded() {
			return false;
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
		<p>Have Your Own Background? [E.G. Imgur/gyazo links] <?php if(isset($_COOKIE['cbg'])){ echo "[SELECTED]"; } ?></p>
		<div class="field"><input class="fieldInput" style="background-color: #222;margin-top: 10px;" id="cimg" type="text" onkeyup="setCustomBackground();" placeholder="Your Link..." <?php if(isset($_COOKIE['cbg'])){ echo "value='".htmlspecialchars(strip_tags($_COOKIE['cbg']))."'"; } ?>></div>
		<button type="button" style="margin-top: 10px;" class="newsubmitBtn" onclick="setCustomBackground();">Set Custom Image</button>
	</div>
</div>
<!--Created By Kieran Holroyd-->
</html>