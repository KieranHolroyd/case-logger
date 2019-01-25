<?php $nonav=0; include "head.php";
Guard::init()->StaffRequired();
?>
<?php
$custom = (isset($_COOKIE['cbg']) && !empty($_COOKIE['cbg'])) ? $_COOKIE['cbg'] : false;
if(!$custom){
    echo "<body style=\"background-size: stretch;background: url('https://cdn.discordapp.com/attachments/528343271840153620/528474876739190793/wallpaper_1.jpg') no-repeat fixed center center;\">";
} else {
    echo "<body style=\"background-size: stretch;background: url('".htmlspecialchars(strip_tags($_COOKIE['cbg']))."'\">";
}?>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
<div class="dashboardOverlay" id="app" v-cloak>
	<div id="titleText" style="z-index:2;" v-if="loaded">
		<h1 style="padding: 10px;display:inline-block;">{{ user.firstName }} {{ user.lastName }} <small style="font-weight: 300;font-size: 15px;">{{ user.rank }}</small></h1>
        <h1 style="padding: 10px;float:right;display:inline-block;" id="dtime">00:00:00 AM</h1>
	</div>
    <h4 style="position: fixed;bottom: 10px;left: 10px;"><?= Config::$name; ?> Staff</h4>
    <div class="navCards" v-if="loaded">
        <a href="logger">
            <div class="navCard-small">
                <div class="navCard-items">
                    <p class="title" style="color:#e17bed;"><i class="fas fa-clipboard" style="padding-right: 10px;color:#e17bed;"></i> Log Case</p>
                    <p class="shortcontent">Log your cases here.</p>
                </div>
            </div>
        </a>
        <a href="policies">
            <div class="navCard-small">
                <div class="navCard-items">
                    <p class="title" style="color:#2ecc71;"><i class="fas fa-book" style="padding-right:10px;color:#2ecc71;"></i> Policies</p>
                    <p class="shortcontent">View all staff documentation and policies here.</p>
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
                    <p class="title" style="color:#2ecc71;"><i class="far fa-calendar-alt" style="padding-right: 10px;color:#2ecc71;"></i> Meetings</p>
                    <p class="shortcontent">Go here to view previous meetings and input on upcoming ones.</p>
                </div>
            </div>
        </a>
        <a onclick="openOverlay('#messages');">
            <div class="navCard-small">
                <div class="navCard-items">
                    <p class="title" style="color:#2ecc71;"><i class="fas fa-comment-alt" style="padding-right: 10px;color:#2ecc71;"></i> Staff Chat</p>
                    <p class="shortcontent">Staff Group Chat.</p>
                </div>
            </div>
        </a>
        <a href="viewer" v-if="this.user.isSLT == 1">
            <div class="navCard-small">
                <div class="navCard-items">
                    <p class="title" style="color: #f75454;"><i class="fas fa-eye" style="padding-right: 10px;color: #f75454;"></i> View Cases</p>
                    <p class="shortcontent">View cases submitted by staff members.</p>
                </div>
            </div>
        </a>
        <a href="search?type=cases" v-if="this.user.isSLT == 1">
            <div class="navCard-small">
                <div class="navCard-items">
                    <p class="title" style="color: #f75454"><i class="fas fa-search" style="padding-right: 10px;color: #f75454;"></i> Search Cases</p>
                    <p class="shortcontent">Search cases submitted by staff members.</p>
                </div>
            </div>
        </a>
        <a href="staff/" v-if="this.user.isSLT == 1">
            <div class="navCard-small">
                <div class="navCard-items">
                    <p class="title" style="color: #f75454"><i class="fas fa-clipboard-list" style="padding-right: 10px;color: #f75454;"></i> Manage Staff</p>
                    <p class="shortcontent">Manage the staff team using this case logger.</p>
                </div>
            </div>
        </a>
        <a href="staff/interviews" v-if="this.user.isSLT == 1">
            <div class="navCard-small">
                <div class="navCard-items">
                    <p class="title" style="color: #f75454"><i class="fas fa-microphone" style="padding-right: 10px;color: #f75454;"></i> Staff Interviews</p>
                    <p class="shortcontent">Manage And Edit Staff Interviews.</p>
                </div>
            </div>
        </a>
        <a href="staff/overview" v-if="this.user.isSLT == 1">
            <div class="navCard-small">
                <div class="navCard-items">
                    <p class="title" style="color: #f75454"><i class="fas fa-info-circle" style="padding-right: 10px;color: #f75454;"></i> Staff Overview</p>
                    <p class="shortcontent">Get an overview of the staff team.</p>
                </div>
            </div>
        </a>
        <a href="game" v-if="this.user.rankLevel <= 8">
            <div class="navCard-small">
                <div class="navCard-items">
                    <p class="title" style="color: #f75454"><i class="fas fa-server" style="padding-right: 10px;color: #f75454;"></i> Game Panel</p>
                    <p class="shortcontent">Manage The Ingame Server.</p>
                </div>
            </div>
        </a>
        <a href="logs" v-if="this.user.isSLT == 1">
            <div class="navCard-small">
                <div class="navCard-items">
                    <p class="title" style="color: #f75454"><i class="fas fa-scroll" style="padding-right: 10px;color: #f75454;"></i> Server Logs</p>
                    <p class="shortcontent">View Live Server Logs.</p>
                </div>
            </div>
        </a>
        <a href="staff/audit" v-if="this.user.isSLT == 1">
            <div class="navCard-small">
                <div class="navCard-items">
                    <p class="title" style="color: #f75454"><i class="fas fa-list-alt" style="padding-right: 10px;color: #f75454;"></i> Audit Log</p>
                    <p class="shortcontent">View All Recent Events From The Logger.</p>
                </div>
            </div>
        </a>
        <a href="staff/statistics">
            <div class="navCard-small">
                <div class="navCard-items">
                    <p class="title" style="color: #f75454"><i class="fas fa-chart-line" style="padding-right: 10px;color: #f75454;"></i> Statistics</p>
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
        <a id="modalLaunch" launch="recentUpdates" style="cursor: pointer;">
            <div class="navCard-small">
                <div class="navCard-items">
                    <p class="title"><i class="fas fa-cog" style="padding-right: 10px;"></i> Updates</p>
                    <p class="shortcontent">View all the recent updates.</p>
                </div>
            </div>
        </a>
        <?php if($user->needMoreInfo()): ?>
        <a id="modalLaunch" launch="moreinfoneeded" style="cursor: pointer;">
            <div class="navCard-small">
                <div class="navCard-items">
                    <p class="title">We need info</p>
                    <p class="shortcontent">Please update information about yourself here.</p>
                </div>
            </div>
        </a>
        <?php endif; ?>
    </div>
	</div>
	<script>
		$('#dtime').text(currentTime());
		setInterval(() => {
			$('#dtime').text(currentTime());
		}, 1000);
		function selectBG(bg, custom) {
			if(!custom){
				Cookies.set('cbg', 'https://staff.arma-life.com/img/bg'+bg+'.png', { expires: 720 });
				$('#selectBG'+bg).text('[SELECTED]');
				$('body').css('background-image', 'url("img/bg'+bg+'.png")');
			}
		}
		function setCustomBackground() {
			let cimg = $('#cimg').val();
			Cookies.set('cbg', cimg, { expires: 720 });
			$('body').css('background-image', 'url("'+cimg+'")');
		}
		let vm = new Vue({
			el: '#app',
			data: {
				user: {info: {}},
                loaded: false
			}
		});
		$.get("<?php echo $url; ?>api/getUserInfoNew", function(data){
            vm.user=JSON.parse(data).response;
            vm.loaded = true;
        });
		function userArrayLoaded() {
			return false;
		}
	</script>
</body>
<?php if($user->needMoreInfo()): ?>
    <div class="modal" id="moreinfoneeded" style="display: block;">
        <button id="close">×</button>
        <div class="content open" style="max-width: 900px;border-radius: 5px;">
            <h2>Hold on a second,</h2>
            <p>We need some information about you</p><br>
            <?php
                if (in_array('region', $user->neededFields)) {
                    echo "<div class='field'>
                        <div class='fieldTitle'>Your Region</div>
                        <select class='fieldSelector' id='userRegion'>
                            <option selected disabled>Choose A Global Region</option>
                            <option value='EU'>European Union</option>
                            <option value='NA'>North America</option>
                            <option value='SA'>South America</option>
                            <option value='AF'>Africa</option>
                            <option value='AU'>Oceania</option>
                        </select></div>";
                }
                if (in_array('steamid', $user->neededFields)) {
                    echo "<div class='field'>
                            <div class='fieldTitle'>Your Steam ID</div>
                            <input type='text' id='userSteamID' class='fieldInput' placeholder='Steam 64 ID'>
                        </div>";
                }
            ?>
            <button onclick="saveNeededInfo()" class="createPointBtn">Save information</button>
        </div>
    </div>
    <script>
        let needed = `<?= json_encode($user->neededFields); ?>`;

        function saveNeededInfo() {
            let needParse = JSON.parse(needed);
            if (needParse.indexOf('region') > -1) {
                console.log(userArray.info.id);
                $.post('/api/saveStaffRegion', {
                    region: $('#userRegion').val(),
                    id: userArray.info.id
                }, data => {
                    new Noty({
                        text: 'Saved Region, Once All Tasks Complete, Reload The Page.',
                        type: 'success'
                    }).show();
                });
            }
            if (needParse.indexOf('steamid') > -1) {
                console.log(userArray.info.id);
                $.post('/api/saveStaffUID', {
                    uid: $('#userSteamID').val(),
                    id: userArray.info.id
                }, data => {
                    new Noty({
                        text: 'Saved SteamID, Once All Tasks Complete, Reload The Page.',
                        type: 'success'
                    }).show();
                });
            }
        }
    </script>
<?php endif; ?>
<div class="modal" id="selectBG">
    <button id="close">×</button>
    <div class="content" style="max-width: 900px;border-radius: 5px;">
        <h2>Choose A Background Image</h2>
        <p>Background 1 (Default) <span id="selectBG1" style="cursor:pointer;"
                                        onclick="selectBG(1, false)"><?php if ($_COOKIE['bg'] === "1") {
                    echo "[SELECTED]";
                } else {
                    echo "[SELECT]";
                } ?></span></p>
        <img src="https://cdn.discordapp.com/attachments/528343271840153620/528474876739190793/wallpaper_1.jpg" onclick="selectBG(3, false)"
             style="border-radius: 5px;box-shadow: 0 0 5px 0 rgba(0,0,0,0.3);margin:5px;width: calc(100% - 10px);"
             alt="Background 1 (Default)" title="Background 1 (Default)">
        <p>Background 2 <span id="selectBG2" style="cursor:pointer;"
                              onclick="selectBG(2, false)"><?php if ($_COOKIE['bg'] === "2") {
                    echo "[SELECTED]";
                } else {
                    echo "[SELECT]";
                } ?></span></p>
        <img src="img/bg2.png" onclick="selectBG(2, false)"
             style="border-radius: 5px;box-shadow: 0 0 5px 0 rgba(0,0,0,0.3);margin:5px;width: calc(100% - 10px);"
             alt="Background 2" title="Background 2">
        <p>Have Your Own Background? [E.G. Imgur/gyazo links] <?php if (isset($_COOKIE['cbg'])) {
                echo "[SELECTED]";
            } ?></p>
        <div class="field"><input class="fieldInput" style="background-color: #222;margin-top: 10px;" id="cimg"
                                  type="text" onkeyup="setCustomBackground();"
                                  placeholder="Your Link..." <?php if (isset($_COOKIE['cbg'])) {
                echo "value='" . htmlspecialchars(strip_tags($_COOKIE['cbg'])) . "'";
            } ?>></div>
        <button type="button" style="margin-top: 10px;" class="newsubmitBtn" onclick="setCustomBackground();">Set Custom
            Image
        </button>
    </div>
</div>
<div class="modal" id="recentUpdates">
    <button id="close">×</button>
    <div class="content" style="max-width: 900px;border-radius: 5px;">
        <h2>Recent Platform Updates</h2>
        <label>11/12/2018</label>
        <ul>
            <li>Added Live Server Logs (Admin+ Only)</li>
        </ul>
        <label>02/12/2018</label>
        <ul>
            <li>Updated Search (Added Punishment Reports & Unban Reports)</li>
            <li>Update Logger (Add Points Fixed, Automatic Punishment & Ban Reporting)</li>
        </ul>
        <label>26/11/2018</label>
        <ul>
            <li>Changed the design of the menu (now in top right).</li>
            <li>Added more stuff to the staff manager.</li>
        </ul>
        <label>21/11/2018</label>
        <ul>
            <li>Permalinks are now always present in the Staff Manager page.</li>
            <li>You can now access the navigation menu with right click anywhere.</li>
        </ul>
        <label>20/11/2018</label>
        <ul>
            <li>Added Staff Group Chat.</li>
            <li>Case viewer updates with new cases in real-time.</li>
            <li>Updated the dashboard grid on the homepage.</li>
        </ul>
    </div>
</div>
<!--Created By Kieran Holroyd-->
</html>