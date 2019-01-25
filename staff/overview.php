<?php include "../head.php";
Guard::init()->SLTRequired();
?>
<div class="grid new">
    <div class="grid__col grid__col--2-of-6" style="padding-left: 20px !important;">
        <h1 class="info-title new">Team Overview</h1>
        <div id="staff" class="selectionPanel">
            <?php
            $teams = [null => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 100 => 0, 500 => 0];
            foreach ($pdo->query("SELECT * FROM users") as $r) {
                $teams[$r->staff_team]++;
            }
            foreach ($teams as $key => $count) {
                $team = Config::$teams[$key];
                if (gettype($team) == 'integer') {
                    $name = "Team {$key}";
                } else {
                    $name = $team;
                }
                $navKey = ($key == null) ? 0 : $key;
                echo "<a href='#team{$navKey}'><div class=\"selectionTab\"><span style=\"float: right;vertical-align: top;font-size: 12px;\">{$count} Members</span><span style=\"font-size: 25px;\">{$name}</span></div></a>";
            }
            ?>
        </div>
    </div>
    <div class="grid__col grid__col--4-of-6">
        <div class="infoPanelContainer">
            <div id="staff_info" class="infoPanel">
                <h1>Team Specific Statistics</h1>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(() => {
        checkHashString(window.location.hash);
    });
    window.onpopstate = () => {
        checkHashString(window.location.hash);
    };

    function checkHashString(str) {
        switch (str.substring(1, 5)) {
            case "team":
                getTeamStats(str.substring(5));
                break;
        }
    }

    function getTeamTitle(key) {
        switch (parseInt(key)) {
            case 0:
                return "<h1>Unassigned Members</h1>";
            case 100:
                return "<h1>Senior Management Team</h1>";
            case 500:
                return "<h1>Development Team</h1>";
            default:
                return `<h1>Staff Team ${key}</h1>`;
        }
    }

    function getTeamStats(key) {
        if (key === undefined) key = '0';
        $.get(`/api/teamStats?team=${key}`, data => {
            data = JSON.parse(data);

            let setHTML = getTeamTitle(key);

            setHTML += `<div class="field"><input class="fieldInput" type="text" onkeyup="initSearch(event)" placeholder="Search Staff (Case Sensitive)"></div>`;

            if (data.response.staff.length === 0) setHTML += '<p>Staff Team Empty</p>';
            if (data.response.staff.length > 0) setHTML += parseStaffTeam(data.response.staff);

            $('#staff_info').html(setHTML);
        });
    }

    function safetyCheck(member) {
        if (member.rank === null) member.rank = '';
        return member;
    }

    function parseStaffTeam(team) {
        let teams = "";

        for (let member of team) {
            member = safetyCheck(member);
            teams += `<div class="staffActivityCard searchStaff" onclick="openStaffMember(${member.id})">Open ${member.rank} ${member.username} in staff manager</div>`;
        }

        return teams;
    }

    function initSearch(e) {
        if (e.target.value.length === 0) clearSearch();
        if (e.target.value.length > 0) searchStaff(e.target.value);
    }

    function clearSearch() {
        $('.searchStaff').show();
    }

    function searchStaff(q) {
        $('.searchStaff').hide();
        $(`.searchStaff:contains("${q}")`).show();
    }

    function openStaffMember(id) {
        window.location.href = `/staff/#staf${id}`;
    }
</script>