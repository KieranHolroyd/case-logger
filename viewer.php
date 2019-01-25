<?php include "head.php";
Guard::init()->SLTRequired();
?>
<div class="searchBox-container">
    <a href="./search?type=cases"><input type="text" class="searchBox" id="searchQuery" placeholder="Search All Cases"><button class="searchCases" id="searchCases">Search</button></a>
</div>
<div class="grid new">
    <div class="grid__col grid__col--2-of-6" style="padding-left: 20px !important;">
        <h1 class="info-title new">Case List <i onclick="refreshCases();" class="fas fa-redo-alt"
                                                style="float: right;cursor: pointer;"></i></h1>
        <div id="reports" style='height: calc(100vh - 118px) !important;' class="selectionPanel">

        </div>
        <button id="loadMore" style="border-radius: 4px;">Load More Cases</button>
    </div>
    <div class="grid__col grid__col--4-of-6">
        <div class="infoPanelContainer" style='height: calc(100vh - 49px);'>
            <div id="case_info" class="infoPanel">
                <h1>Select A Case To View Info.</h1>
            </div>
        </div>
    </div>
</div>
<script>
    let offset = 0;
    let cases;
    let list = "";
    let player_punished, player_banned, moreinfo, setMoreInfo, reporting_player;

    let caseInfoChannel = pusher.subscribe(`caseInformation`);
    caseInfoChannel.bind("receive", (data) => {
        offset++;
        $('#reports').prepend(displayCase(data));
        $(`#caseNo${data.id}`).addClass('flash');
        setTimeout(()=>{
            $(`#caseNo${data.id}`).removeClass('flash');
        }, 3000);
    });

    function refreshCases() {
        offset = 0;
        getReports();
    }

    function displayCase(element) {
        reporting_player = "";
        let reporting_player_name;
        if (element.reporting_player !== "[]" && element.reporting_player !== "" && element.reporting_player !== null && element.reporting_player !== "null") {
            reporting_player = element.reporting_player;
            reporting_player_name = reporting_player[0].name;
        } else {
            reporting_player_name = "undefined";
        }
        if (element.pa) {
            player_punished = "<span class='punishmentincase'>Punishment Report</span>";
        } else {
            player_punished = "";
        }
        if (element.ba) {
            player_banned = "<span class='banincase'>Ban Report</span>";
        } else {
            player_banned = "";
        }
        return `<div class="selectionTab" id="caseNo${element.id}" onclick="getMoreInfo(${element.id})"><span style="float: right;font-size: 12px;">Lead: ${element.lead_staff}</span><span style="font-size: 25px;">${element.id}-${reporting_player_name}<br><span style="font-size: 12px; padding: 0;">${player_punished}${player_banned}<span class="timestamp">${element.timestamp}</span><span class="typeofreport">${element.typeofreport}</span></span></span></div>`;
    }

    function getReports() {
        if (offset === 0) {
            $('#reports').html("<img src='img/loadw.svg'>")
        }
        list = "";
        $.post('api/getCases', {'offset': offset}, function (data) {
            cases = JSON.parse(data);
            if (cases.info.count < 100) {
                $('#loadMore').hide();
            }
            if (!$.isEmptyObject(cases.caseno)) {
                for (let i = 1; i < Object.keys(cases.caseno).length + 1; i++) {
                    list += displayCase(cases.caseno[i]);
                }
                if (offset === 0) {
                    $('#reports').html(list)
                } else {
                    $('#reports').append(list)
                }
            } else {
                if (offset === 0) {
                    $('#reports').html("<h2 style='padding: 15px;'>There Has Been An Error Fetching More Cases</h2>")
                } else {
                    $('#reports').append("<h2 style='padding: 15px;'>There Has Been An Error Fetching More Cases</h2>")
                }
            }

            offset = offset + 100;
        });
    }

    let players_involved, playersArray, player_title;

    function getMoreInfo(id) {
        $('#case_info').html("<p><img src='img/loadw.svg'></p>");
        players_involved = "";
        playersArray = "";
        player_title = "";
        $.post('api/getMoreInfo', {'id': id}, function (data) {
            let res = JSON.parse(data);
            if (res.code === 200) {
                moreinfo = res.response;
                if (moreinfo.report.players !== "[]" && moreinfo.report.players !== "") {
                    for (let k in moreinfo.report.players) {
                        const player = moreinfo.report.players[k];
                        if (k === "0") {player_title = moreinfo.report.players[k].name;}
                        players_involved += `${player.type}: ${player.name} (${player.guid})<br>`;
                    }
                } else {
                    players_involved = "None";
                    player_title = moreinfo.report.lead_staff;
                }

                let punishments = ``;

                for (let p of moreinfo.report.punishments) {
                    punishments += p.html;
                }

                let bans = ``;

                for (let p of moreinfo.report.bans) {
                    bans += p.html;
                }

                setMoreInfo = `<h2><span>Case ID:</span> ${moreinfo.report.id}-${player_title}</h2><p id="case"><span>Lead Staff:</span> ${moreinfo.report.lead_staff}</p><p id="case"><span>Other Staff:</span> ${moreinfo.report.other_staff}</p><p id="case"><span>Type Of Report:</span><br> ${moreinfo.report.typeofreport}</p><p id="case" style="text-transform: capitalize;"><span>Players Involved:</span><br> ${players_involved}</p><p id="case"><span>Description Of Events:</span><br> ${linkify(moreinfo.report.doe)}</p><p id="case"><span>Timestamp:</span> ${moreinfo.report.timestamp}</p>${linkify(punishments)}${linkify(bans)}`;
                $('#case_info').html(setMoreInfo);
            } else {
                $('#case_info').html(`<p>${res.message}</p>`);
            }
        });

    }

    function markBanExpired(id, case_id) {
        $.post('/api/markBanExpired', {
            id: id
        }, data => {
            getMoreInfo(case_id);
        });
    }

    function userArrayLoaded() {
        getReports();
    }

    $('#loadMore').click(function () {
        getReports();
    });
    $('#searchCases').click(function () {
        if ($('#searchQuery').val() !== "") {
            window.location.href = "search?type=cases&query=" + $('#searchQuery').val();
        }
    });
    $(document).ready(function () {
        $('#searchQuery').keydown(function (event) {
            if (event.which == 13 || event.keyCode == 13) {
                if ($('#searchQuery').val() !== "") {
                    window.location.href = "search?type=cases&query=" + $('#searchQuery').val();
                }
            }
        });
    });
</script>
</body>
</html>

