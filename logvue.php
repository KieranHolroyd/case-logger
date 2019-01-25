<?php session_start();
include "head.php";
Guard::init()->StaffRequired();
?>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<div class="grid new" id="root" v-cloak style="padding-left:15px;z-index: 25;">
    <div class="grid__col grid__col--1-of-6" style="box-shadow: 0 0 5px 0 rgba(0,0,0,0.2);">
        <div class="selectionPanel new"
             style="margin-left: 5px;max-width: 100%;background-color: #1c1b30;height: 100vh;overflow: auto;">
            <p class="label" style="text-align: center;"><?= Config::$name; ?> Staff</p>
            <p class="label">Case Tools</p>
            <button class="pickbtn" id="addOtherStaff" @click="addAssistantStaffMember()" disabled>Add Staff</button>
            <button class="pickbtn" id="addPlayerReporter" @click="addPlayer()">Add Player</button>
            <button class="pickbtn" id="TypeOfReportButton" style="position: relative;text-align: left;"
                    :class="{ open: menus.reportType.isOpen }" @click="toggleList('reportType', 'TypeOfReportList')">
                Report Type: {{ menus.reportType.current }} <i id="torangle" style="position: absolute;right: 10px;"
                                                               :class="{ open: menus.reportType.isOpen }"
                                                               class="ts2 fas fa-angle-down"></i></button>
            <div class="reportTypes" id="TypeOfReportList">
                <button class="pickbtn submenuBtn" @click="selectReportType('Other')">Other</button>
                <button class="pickbtn submenuBtn" @click="selectReportType('Report')">Report</button>
                <button class="pickbtn submenuBtn" @click="selectReportType('General Support')">General Support</button>
                <button class="pickbtn submenuBtn" @click="selectReportType('General Tags')">General Tags</button>
                <button class="pickbtn submenuBtn" @click="selectReportType('Whitelisting')">Whitelisting</button>
                <button class="pickbtn submenuBtn" @click="selectReportType('Compensation')">Compensation</button>
                <button class="pickbtn submenuBtn" @click="selectReportType('Ban Log')">Ban Log</button>
                <button class="pickbtn submenuBtn" @click="selectReportType('Unban Log')">Unban Log</button>
            </div>
            <p class="label">Punishments</p>
            <button class="pickbtn" id="modalLaunch" launch="addPunishment">Add Punishment Report</button>
            <button class="pickbtn" id="modalLaunch" launch="addBan">Add Ban Report</button>
        </div>
    </div>
    <div class="grid__col grid__col--5-of-6" style="height: 100vh !important;overflow: auto;z-index: 0;">
        <div class="infoPanelContainer">
            <div class="infoPanel">
                <div class="field">
                    <div class="fieldTitle" id="doeTitle">Description Of Events <span id="doeTitleWords"
                                                                                      style="color: #555;">({{CountDOE}} Words)</span>
                    </div>
                    <textarea class="fieldTextarea" id="doi" placeholder="Description Of The Events*" v-model="info.description"></textarea>
                </div>
                <input id="lsm" type="hidden" value="<?= $user->info->username; ?>">
                <input id="typeOfReportField" type="hidden" value="Other">
                <div id="otherStaffList">
                    <div v-for="(val, key) in assistantStaff" class="field">
                        <div class="fieldTitle">Assistant Staff Member #{{key+1}} <i @click="removeAssistantStaff(key)"
                                                                                     style="color: #aaaaaa;float: right;cursor: pointer;"
                                                                                     class="fas fa-trash"></i></div>
                        <select class="fieldSelector" id="os1" v-model="assistantStaff[key].selected">
                            <option value="0" @input="updateAssistant(key, 0)">Select A Staff Member</option>
                            <option v-for="(staff_val, staff_key) in staff_list" :value="staff_val.name">
                                {{staff_val.display}}
                            </option>
                        </select>
                    </div>
                </div>
                <div id="playerList">
                    <div v-for="(val, key) in playersInvolved" class='field'>
                        <div class='fieldTitle'>Player Involved #{{key+1}} <select style="margin-bottom: 0;" type="text" v-model="val.type">
                                <option value="0">Reporter</option>
                                <option value="1">Reported</option>
                            </select>
                        <i v-if="key > 0" @click="removePlayer(key)"
                            style="color: #aaaaaa;float: right;cursor: pointer;"
                            class="fas fa-trash"></i></div>
                        <input v-model='val.name' class='fieldInput' :placeholder='"Add " + playerTypes[val.type]'>
                        <input v-model='val.uuid' class='fieldInput' placeholder='Player UUID (Steam or BattleEye)'>
                    </div>
                </div>
                <button onclick="confirmSubmit(event)" class="newsubmitBtn">Submit</button>
            </div>
        </div>
    </div>
    <button class="newEditBtn "><i style="font-size: 14px;" class="fas fa-question"></i></button>
    <div class="modal" id="confirmCase">
        <button id="close">×</button>
        <div class="content" style="max-width: 900px;padding:0;min-height: 600px;transition: 300ms;">
            <div id="confirmBody" style='margin: 10px;padding-top: 10px;'></div>
            <button style='width:100%;margin: 0;transition: 0;' id="submitRealButton" onclick="submit()">Send</button>
        </div>
    </div>
    <div class="modal" id="addPunishment">
        <button id="close">×</button>
        <div class="content" style="max-width: 400px;padding:0;">
            <div class="field">
                <div class="fieldTitle">Select Player To Punish</div>
                <select class="fieldSelector" id="selectPlayerToPunish">
                    <option disabled selected value="0">Choose A Player</option>
                    <option v-for="(val, key) in playersInvolved" v-if="val.type == 1 && val.name != ''">{{val.name}}</option>
                </select>
            </div>
            <div class="field">
                <div class="fieldTitle">Amount Of Points Issued</div>
                <input type="number" id="amountOfPoints" class="fieldInput" placeholder="10">
            </div>
            <div class="field">
                <div class="fieldTitle">Rules Broken</div>
                <input type="text" id="rulesBroken" class="fieldInput" placeholder="rdm, failrp, etc">
            </div>
            <div class="field">
                <div class="fieldTitle">Comments/Evidence</div>
                <textarea class="fieldTextarea" id="punishmentComments"
                          placeholder="Link to player report, video of offence"></textarea>
            </div>
            <button style='width:100%;margin: 0;transition: 0;border-bottom-right-radius: 3px;border-bottom-left-radius: 3px;'
                    id="submitRealButton" onclick="addPunishmentReport()">Add Punishment Report
            </button>
        </div>
    </div>
    <div class="modal" id="addBan">
        <button id="close">×</button>
        <div class="content" style="max-width: 400px;padding:0;">
            <div class="field">
                <div class="fieldTitle">Select Player To Ban</div>
                <select class="fieldSelector" id="selectPlayerToPunish">
                    <option disabled selected value="0">Choose A Player</option>
                    <option v-for="(val, key) in playersInvolved" v-if="val.type == 1 && val.name != ''">{{val.name}}</option>
                </select>
            </div>
            <div class="field">
                <div class="fieldTitle">Ban Length</div>
                <input class="fieldInput" id="bl" type="text" placeholder="Ban Length (Days) (0 for perm)*"></div>
            <div class="field">
                <div class="fieldTitle">Ban Message</div>
                <input class="fieldInput" id="bm" type="text" placeholder="Ban Message*"></div>
            <div class="field">
                <div class="fieldTitle">Teamspeak Ban?</div>
                <select class="fieldSelector" id="ts">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select></div>
            <div class="field">
                <div class="fieldTitle">Ingame Ban?</div>
                <select class="fieldSelector" id="ig">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select></div>
            <div class="field">
                <div class="fieldTitle">Website Ban?</div>
                <select class="fieldSelector" id="wb">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select></div>
            <div class="field">
                <div class="fieldTitle">Permanent Ban?</div>
                <select class="fieldSelector" id="pb">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select></div>
            <button style='width:100%;margin: 0;transition: 0;border-bottom-right-radius: 3px;border-bottom-left-radius: 3px;'
                    id="submitRealButton" onclick="addBanReport()">Add Ban Report
            </button>
        </div>
    </div>
</div>
<script>
    let vm = new Vue({
        el: '#root',
        data: {
            menus: {
                reportType: {
                    current: 'Other',
                    isOpen: false
                }
            },
            assistantStaff: [],
            playersInvolved: [
                {'name': '', 'uuid': '', 'type': 0}
            ],
            playerTypes: {
                0: 'Reporter',
                1: 'Reported'
            },
            staff_list: [],
            info: {
                description: ''
            },
            reports: {
                punishment: {
                    player: 0,
                    points: null,
                    rules: "",
                    comments: ""
                },
                ban: {
                    player: 0,
                    length: null,
                    message: "",
                    ts: false,
                    ig: false,
                    wb: false,
                    pb: false
                }
            }
        },
        methods: {
            toggleList(bind, list) {
                this.menus[bind].isOpen = !this.menus[bind].isOpen;
                $(`#${list}`).slideToggle(200);
            },
            selectReportType(type) {
                this.toggleList('reportType', 'TypeOfReportList');
                this.menus.reportType.current = type;
            },
            addAssistantStaffMember() {
                this.assistantStaff = [...this.assistantStaff, {selected: 0}];
            },
            removeAssistantStaff(key) {
                this.assistantStaff.splice(key, 1);
            },
            addPlayer() {
                this.playersInvolved = [...this.playersInvolved, {'name': '', 'uuid': '', 'type': 0}];
            },
            removePlayer(key) {
                console.log(this.playersInvolved.length);
                if (this.playersInvolved.length > 1) {
                    this.playersInvolved.splice(key, 1);
                }
            },
        },
        computed: {
            /**
             * @return {number}
             */
            CountDOE() {
                return (this.info.description.length !== 0) ? this.info.description.trim().split(/\s+/).length : 0;
            }
        }
    });
</script>
<style>
    #torangle.open {
        transform: rotate(180deg);
    }
</style>
<script>
    let banReport, punishmentReport, otherStaffParsed, tor = false, aps = false;
    let punishment_reports = [], ban_reports = [];

    $('#addPointsButton').click(function () {
        $('#addPointsList').slideToggle(300);
        if (!aps) {
            $('#pointangle').css('transform', 'rotate(180deg)');
        } else {
            $('#pointangle').css('transform', 'rotate(0deg)');
        }
        aps = !aps;
    });

    function addPunishmentReport() {
        $.post('/api/punishment', {
            points: $('#amountOfPoints').val(),
            rules: $('#rulesBroken').val(),
            comments: $('#punishmentComments').val(),
            player: $('#selectPlayerToPunish').val()
        }, data => {
            data = JSON.parse(data);

            if (data.code === 200) {
                punishment_reports = [...punishment_reports, data.response[0]];
                new Noty({
                    type: 'success',
                    text: `Added Punishment Report For ${$('#selectPlayerToPunish').val()}`,
                    timeout: 3000
                }).show();
            } else {
                new Noty({
                    type: 'error',
                    text: `Failed To Add Punishment Report For ${$('#selectPlayerToPunish').val()} <b>[Error: ${data.message}]</b>`,
                    timeout: 3000
                }).show();
            }
        })
    }

    function addBanReport() {
        $.post('/api/ban', {
            length: $('#bl').val(),
            message: $('#bm').val(),
            teamspeak: $('#ts').val(),
            ingame: $('#ig').val(),
            website: $('#wb').val(),
            permanent: $('#pb').val(),
            player: $('#selectPlayerToBan').val()
        }, data => {
            data = JSON.parse(data);

            if (data.code === 200) {
                ban_reports = [...ban_reports, data.response[0]];
                new Noty({
                    type: 'success',
                    text: `Added Ban Report For ${$('#selectPlayerToBan').val()}`,
                    timeout: 3000
                }).show();
            } else {
                new Noty({
                    type: 'error',
                    text: `Failed To Add Ban Report For ${$('#selectPlayerToBan').val()} <b>[Error: ${data.message}]</b>`,
                    timeout: 3000
                }).show();
            }
        })
    }

    function preparePlayers() {
        let string = "<option selected>Choose A Player</option>";
        let reported = 0;
        playerArray.map((value, index) => {
            index++;
            let name = $(`#player${index}`).val();
            if (value.reported !== undefined && name !== '') {
                reported++;
                string = `${string}<option value="${name}">${name}</option>`;
            }
        });
        if (reported === 0) string = "<option selected>No Reported Players Found</option>";
        $('#selectPlayerToPunish').html(string);
        $('#selectPlayerToBan').html(string);
    }

    $('#PunishmentReportButton').click(function () {
        preparePlayers();
        launchModal('addPunishment');
    });
    $('#BanReportButton').click(function () {
        preparePlayers();
        launchModal('addBan');
    });

    function checks(get) {
        let rList = [];
        let errors = {wordCount: false, otherStaff: false, players: false};
        let error = false;
        if (wordCount($('#doi').val()) < 2 && !errors.wordCount) {
            error = true;
            rList.push(" You must have more than 2 words");
            errors = {...errors, wordCount: true}
        }
        for (let i = 1; i < otherStaff + 1; i++) {
            if (parseInt($('#os' + i).val()) === 0 && !errors.otherStaff) {
                error = true;
                rList.push(" All Other Staff Must Be Selected");
                errors = {...errors, otherStaff: true}
            }
        }
        playerArray.map((value, index) => {
            if ($('#player' + index).val() === "" && !errors.players) {
                error = true;
                rList.push(" All Players Must Be Filled In");
                errors = {...errors, players: true}
            }
        });
        if (!error) {
            rList.push(" None");
        }
        if (get === "error") {
            return error;
        } else if (get === "rList") {
            return rList;
        }
    }

    function confirmSubmit(ev) {
        $('#submitRealButton').removeAttr('disabled');
        $('#submitRealButton').css('background-color', '#222');
        $('#submitRealButton').css('border', '');
        $('#submitRealButton').css('color', '#fff');
        $('#submitRealButton').fadeIn(200);
        $('#confirmCase .content').css('max-width', '900px');
        $('#confirmCase .content').css('min-height', '200px');
        $('#confirmCase .content').css('border-radius', '');
        var gotPoints, gotBanned;
        if (punishmentReport === 1) {
            gotPoints = "Yes"
        } else {
            gotPoints = "No"
        }
        if (banReport === 1) {
            gotBanned = "Yes"
        } else {
            gotBanned = "No"
        }
        otherStaffParsed = "";
        for (let i = 1; i < otherStaff + 1; i++) {
            otherStaffParsed += $('#os' + i).val() + " ";
            console.log(otherStaffParsed);
        }
        let list = '<div style="height: 100%;" id="case_info"><p id="case"><span>Case Title: ' + $('#player1').val() + '</span></p><p id="case"><span>Lead Staff:</span> ' + $('#lsm').val() + '</p><p id="case"><span>Other Staff:</span> ' + otherStaffParsed + '</p><p id="case"><span>Type Of Report:</span> ' + $('#typeOfReportField').val() + '</p><p id="case"><span>Description Of Events:</span> ' + $('#doi').val() + '</p><p id="case"><span>Timestamp:</span> ' + currentTime() + '</p><p id="case"><span>Errors:</span>' + checks("rList") + '</p></div>';
        if (checks("error")) {
            $('#submitRealButton').attr('disabled', 'true');
            $('#submitRealButton').css('background-color', '#3f3f3f');
            $('#submitRealButton').css('border', 'none');
            $('#submitRealButton').css('color', '#ccc');
        }
        $('#confirmBody').html(linkify(list));
        if (ev.ctrlKey) {
            submit();
        } else {
            launchModal('confirmCase');
        }
    }

    function onetwotoyesno(val) {
        if (val === 1) return 'Yes';
        return 'No';
    }

    let staffList = "";

    function submit() {
        if (!checks("error")) {
            $('#confirmCase .content').css('max-width', '100px');
            $('#confirmCase .content').css('min-height', '100px');
            setTimeout(function () {
                $('#confirmCase .content').css('border-radius', '50%');
            }, 100);
            $('#confirmBody').html("<center><h1><img src='img/loadw.svg'></h1></center>");
            $('#submitRealButton').fadeOut(200);
            let type;
            otherStaffParsed = "";
            for (let i = 1; i < otherStaff + 1; i++) {
                otherStaffParsed += $('#os' + i).val() + " ";
                console.log(otherStaffParsed);
            }
            playerArray.map((value, index) => {
                let domIndex = index + 1;
                console.log(value, index);
                type = "";
                if (playerArray[index].reported === undefined) {
                    type = "reporter";
                } else {
                    type = "reported";
                }
                playerArray[index] = {
                    type: type,
                    name: $('#player' + domIndex).val(),
                    guid: $('#playerGUID' + domIndex).val()
                };
                console.log(playerArray[index], index);
            });
            $.post('api/submitCase', {
                'lead_staff': $('#lsm').val(),
                'other_staff': otherStaffParsed,
                'description_of_events': $('#doi').val(),
                'players': JSON.stringify(playerArray),
                'type_of_report': $('#typeOfReportField').val(),
                'punishment_reports': JSON.stringify(punishment_reports),
                'ban_reports': JSON.stringify(ban_reports)
            }, function (data) {
                $('#osi').val('');
                $('#doi').val('');
                $('#ltpr').val('');
                $('#oc').val('');
                $('#apg').val('');
                $('#aop').val('');
                $('#es').val('');
                $('#bl').val('');
                $('#bm').val('');
                $('#pt').val('');
                $('#ts').val('0');
                $('#ig').val('0');
                $('#wb').val('0');
                $('#pb').val('0');
                $('#name').val('');
                $('#punishmentReport').slideUp();
                $('#PunishmentReportButton').text('Add Punishment Report');
                $('#PunishmentReportButton').removeAttr('open');
                punishmentReport = 0;
                punishment_reports = [];
                ban_reports = [];
                $('#banReport').slideUp();
                $('#BanReportButton').text('Add Ban Report');
                $('#BanReportButton').removeAttr('open');
                banReport = 0;
                $('#otherStaffList').html('');
                otherStaff = 0;
                $('#playerList').html("<div class='field'><div class='fieldTitle'>Player Involved #1 (Reporter)</div><input class='fieldInput' id='player1' placeholder='Add Reporter'><input class='fieldInput' id='playerGUID1' placeholder='Player GUID'></div>");
                playerCount = 1;
                playerArray = [];
                playerArray.push({
                    reporter: ''
                });
                $('#doeTitleWords').text('(' + wordCount($('#doi').val()) + ' Words)');
                new Noty({
                    type: 'success',
                    layout: 'topRight',
                    theme: 'metroui',
                    timeout: 3000,
                    text: 'Case Logged Successfully!',
                }).show();
                $('#confirmBody').fadeOut(200);
                setTimeout(() => {
                    $('#confirmBody').html('<center><img src="img/success.svg"></center>');
                    $('#confirmBody').fadeIn(200);
                }, 200);
                setTimeout(() => {
                    closeAllModal()
                }, 1000)
            });
        } else {
            new Noty({
                type: 'warning',
                text: checks("rList"),
                timeout: 10000
            }).show();
        }
    }

    let otherStaff = 0;
    let playerCount = 1;
    let playerArray = [];
    $(document).ready(function () {
        playerArray.push({
            reporter: ''
        });
        // $('#addOtherStaff').click(function () {
        //     if (otherStaff < 10) {
        //         otherStaff++;
        //         $('#otherStaffList').append("<div class='field' style='display: none;'><div class='fieldTitle'>Assistant Staff Member #" + otherStaff + "</div><select class='fieldSelector' id='os" + otherStaff + "'><option value='0'>Select A Staff Member</option>" + staffList + "</select></div>");
        //         $('#otherStaffList .field').last().slideDown(150);
        //     } else {
        //         new Noty({
        //             type: 'error',
        //             layout: 'topRight',
        //             theme: 'metroui',
        //             timeout: 3000,
        //             text: 'Max Other Staff Reached (10)',
        //         }).show();
        //     }
        // });
        // $('#removeOtherStaff').click(function () {
        //     if (otherStaff > 0) {
        //         $('#otherStaffList .field').last().slideUp(150);
        //         setTimeout(function () {
        //             $('#otherStaffList .field').last().remove();
        //         }, 150);
        //         otherStaff--;
        //     }
        // });
        // $('#addPlayerReporter').click(function () {
        //     if (playerCount < 25) {
        //         playerCount++;
        //         playerArray.push({
        //             reporter: ''
        //         });
        //         $('#playerList').append("<div class='field' style='display: none;'><div class='fieldTitle'>Player Involved #" + playerCount + " (Reporter)</div><input class='fieldInput' id='player" + playerCount + "' placeholder='Add Reporter'><input class='fieldInput' id='playerGUID" + playerCount + "' placeholder='Player GUID'></div>");
        //         $('#playerList .field').last().slideDown(150);
        //     } else {
        //         new Noty({
        //             text: 'Maximum Of 25 Players',
        //             type: 'error'
        //         }).show();
        //     }
        // });
        // $('#addPlayerReported').click(function () {
        //     if (playerCount < 25) {
        //         playerCount++;
        //         playerArray.push({
        //             reported: ''
        //         });
        //         $('#playerList').append("<div class='field' style='display: none;'><div class='fieldTitle'>Player Involved #" + playerCount + " (Reported)</div><input class='fieldInput' id='player" + playerCount + "' placeholder='Add Reported Player'><input class='fieldInput' id='playerGUID" + playerCount + "' placeholder='Player GUID'></div>");
        //         $('#playerList .field').last().slideDown(150);
        //     } else {
        //         new Noty({
        //             text: 'Maximum Of 25 Players',
        //             type: 'error'
        //         }).show();
        //     }
        // })
        // $('#removePlayer').click(function () {
        //     if (playerCount > 1) {
        //         playerArray.splice(-1, 1);
        //         $('#playerList .field').last().slideUp(150);
        //         setTimeout(function () {
        //             $('#playerList .field').last().remove();
        //         }, 150);
        //         playerCount--;
        //     }
        // });
        gsl();
    });

    function wordCount(str) {
        return str.trim().split(/\s+/).length;
    }

    function gsl() {
        $.get('api/getStaffList', function (data) {
            let staff = JSON.parse(data);
            vm.staff_list = staff;
            for (let i = 1; i < Object.keys(staff).length + 1; i++) {
                staffList += `<option value='${staff[i].name}'>${staff[i].display}</option>`;
            }
            $('#addOtherStaff').removeAttr('disabled');
            $('#removeOtherStaff').removeAttr('disabled');
        });
    }
</script>
</body>
<!--Created By Kieran Holroyd-->
</html>