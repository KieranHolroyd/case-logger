<?php include "../head.php"; 
if ($_GET['meeting'] == ""):
?>
<?php include "head.php"; ?>
<script src="https://unpkg.com/tippy.js@2.2.3/dist/tippy.all.min.js"></script>
<div id="titleText" style="z-index:2;display:table;width:100vw;text-align:center;table-layout: fixed;">
    <h1 id="welcome" style="display:table-cell;">Hello, Human</h1>
    <h1 style="display:table-cell;" title="<b><?php echo date("l m/d/Y"); ?>&nbsp;&nbsp;</b><img width='16px' src='https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.0.0/flags/4x3/us.svg'>"><?php echo date("l d/m/Y"); ?></h1>
    <h1 style="display:table-cell;">Welcome To Meetings</h1>
</div>
<div id="meetings"></div>
<button style="border-radius:4px;position:fixed;bottom:10px;left:10px;display:none;box-shadow:0 0 5px 0 rgba(0,0,0,0.2);" class="newMeeting" id="modalLaunch" launch="createMeeting">Schedule New Meeting</button>
<div class="modal" id="createMeeting">
	<button id="close">×</button>
	<div class="content" style="max-width: 600px;border-radius: 5px;">
        <div class="field"><div class="fieldTitle">Date</div><input type="date" id="date" class="fieldSelector"></div>
        <div class="field"><div class="fieldTitle">SLT Only?</div><select id="sltonly" class="fieldSelector">
            <option value="0">No</option>
            <option value="1">Yes</option>
        </select></div>
        <button class="newsubmitBtn" onclick="addNewMeeting()">Schedule Meeting</button>
    </div>
</div>
<script>
    tippy('h1');
    function userArrayLoaded() {
        if(userArray.info.slt==1){
            $('.newMeeting').slideDown(200);
        }
    }
    setTimeout(function () {
        if(userArray.info.slt==1){
            $('.newMeeting').slideDown(200);
        }
    }, 3000);
    function getMeetings() {
        $.get('https://www.nitrexdesign.co.uk/caselogger/api/getMeetings', function(data) {
            var list = "";
            var json = JSON.parse(data);
            for(var i=1; i<Object.keys(json).length + 1; i++){
                var color = "1abc9c";
                var today = "";
                var slt = "";
                if(json[i].date == "<?php echo date("d/m/Y"); ?>") {color = "ff9966";today = " [Today]";}
                if(json[i].slt !== undefined) {slt = " [SLT]";}
                list += '<a href="'+json[i].id+'"><div class="navCard-small"><div class="navCard-items"><p class="title" title="<b>'+json[i].wrongDate+' </b><img width=\'16px\' src=\'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.0.0/flags/4x3/us.svg\'>" style="color:#'+color+';">'+json[i].date+today+slt+'</p><p class="shortcontent" style="color:#16a085;">'+json[i].points+' Points From Staff</p></div></div></a>';
            }
            $('#meetings').html(list);
            tippy('.title');
        })
    }
    getMeetings();
    function addNewMeeting() {
        $.post('https://www.nitrexdesign.co.uk/caselogger/api/addMeeting', {
            "date":$('#date').val(),
            "slt":$('#sltonly').val()
        }, function (data) {
            getMeetings();
            new Noty({
                type: 'success',
                layout: 'topRight',
                theme: 'metroui',
                timeout: 3000,
                text: data,
            }).show(); 
        });
    }
</script>
<?php else:?>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<div class="grid">
    <div id="app" v-if="user.info.id !== null">
        <div class="grid__col grid__col--4-of-6" style="padding-left: 20px !important;">
            <h1 class="info-title">Meeting On {{ meetingDate() }}</h1>
            <div id="meetingPoints" style='height: calc(100vh - 69px) !important;' class="cscroll">
                <li v-for="(point, index) of meetingPoints" v-on:click="getMoreInfo(point.id)" class="case">
                    <h2>{{ point.name }}</h2>
                    <p>{{ point.author }}</p>
                </li>
                <li v-if="meetingPoints == ''">
                    <h2 class="case" style="background-color: #555;cursor: default;">No Points Have Been Submitted To This Meeting</h2>
                </li>
            </div>
        </div>
        <div class="grid__col grid__col--2-of-6">
            <div class="moreInfoPanel" id="pointInfo" style='height: 100vh;' class="cscroll">
                <h1>{{ moreMeeting.name }}</h1>
                <div v-if="moreMeeting.real">
                    <!-- <label style="margin: 10px;">Votes: </label>
                    <progress :title="votesTitle(moreMeeting.votes.down, moreMeeting.votes.up)" :value="moreMeeting.votes.up" :max="moreMeeting.votes.down + moreMeeting.votes.up"></progress> -->
                    <div class="description">
                        Description: {{ moreMeeting.description }}
                    </div>
                    <ul class="comments" style="margin: 10px;list-style-type: none;">
                        <div class="fieldTitle">Comments</div>
                        <div style="max-height: 400px;overflow: auto;">
                            <li v-if="myComment !== ''" class="case" style="background-color: #444;cursor: default;">
                                <div style="height: auto;width: 100%;overflow-y: auto;">
                                    <h3><span title="Preview! post the comment to remove this">[?]</span> {{ user.info.firstname }} {{ user.info.lastname }}</h3>
                                    <p style="white-space: initial;word-wrap: break-word;width: 100%;">{{ myComment }}</p>
                                </div>
                            </li>
                            <li v-for="comment of moreMeeting.comments" class="case" style="background-color: #555;cursor: default;height: auto;width: 100%;">
                                <div v-if="comment.empty == undefined" style="height: auto;width: 100%;overflow-y: auto;">
                                    <h3>{{ comment.author }}</h3>
                                    <p style="white-space: initial;word-wrap: break-word;width: 100%;">{{ comment.comment }}</p>
                                </div>
                            </li>
                        </div>
                        <li v-if="isEmptyObject(moreMeeting.comments)">
                            <div v-if="myComment == ''" class="case" style="background-color: #555;cursor: default;">No Comments</div>
                        </li>
                        <div style="display: inline;">
                            <input v-model="myComment" @keyup.enter="addComment" placeholder="Leave A Comment" style="width: 80%;display:inline;height:39px;border:1px solid #222;"></input><button @click="addComment" style="width: 20%;display:inline;margin: 0;">Post Comment</button>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        <button id="modalLaunch" launch="addPoint" class="newPointBtn" style="position: fixed;left: 10px;bottom: 10px;border-radius: 50%;width:40px;height:40px;font-size:16px;line-height:20px;padding:0;">+</button>
        <div class="modal" id="addPoint">
            <button id="close">×</button>
            <div class="content" style="max-width: 500px;background-color: #444;">
                <h2>Add Point To The Meeting</h2><br>
                <div class="field field-bdrs" style="border-radius: 6px;">
                    <div for="" class="fieldTitle">Point Name</div>
                    <input v-model="newPoint.name" type="text" class="fieldInput" placeholder="Name">
                </div>
                <div class="field field-bdrs" style="border-radius: 6px;">
                    <div for="" class="fieldTitle">Description</div>
                    <textarea v-model="newPoint.description" type="text" class="fieldTextarea" placeholder="More Information"></textarea>
                </div>
                <button class="newsubmitBtn" v-on:click="addPoint()">Add</button>
            </div>
        </div>
    </div>
</div>
<style>
.newPointBtn{
    background-color: #4286f4;
    color: #000;
    border: 0px solid transparent !important;
    transition: 125ms;
    cursor: pointer;
    box-shadow: 0 0 5px 0 rgba(0,0,0,0.2);
}
.newPointBtn:hover{
    box-shadow: 0 0 10px 0 rgba(0,0,0,0.4);
    border: 0px solid transparent !important;
    transform: scale(1.4) !important;
}
.description{
    margin: 10px;
    background-color: #4c4c4c;
    padding: 10px;
    text-align: justify;
    border-radius: 6px;
}
</style>
<script>
    let vm = new Vue({
        el: '#app',
        data: {
            meeting: {},
            meetingPoints: {},
            moreMeeting: { 
                "name": "Select A Point For More Options", 
                "real": false
            },
            user: {},
            myComment: "",
            currentPoint: 0,
            newPoint: {
                "name": "",
                "description": ""
            }
        }, 
        beforeMount(){
            this.initialize()
        },
        created() {
            setInterval(function () {
                vm.updateMeeting();
                if (vm.currentPoint !== 0) {
                    vm.getMoreInfo(vm.currentPoint);
                }
            }, 5000);
        },
        methods: {
            getMoreInfo(id, callback = "") {
                $.get('../api/getMeetingPoint?pointID=' + id, function (res) {
                    let response = JSON.parse(res);
                    let point = {}
                    point.name = response.name
                    point.author = response.author
                    point.votes = {}
                    point.comments = {}
                    point.description = response.description
                    if (response.votes !== "{}") {
                        let resvotes = JSON.parse(response.votes)
                        point.votes.up = resvotes.up
                        point.votes.down = resvotes.down
                    } else {
                        point.votes.up = 0
                        point.votes.down = 0
                    }
                    if (response.comments !== "{}") {
                        let rescomment = JSON.parse(response.comments)
                        for (let i = 0; i < Object.keys(rescomment).length; i++) {
                            point.comments[i] = rescomment[i]
                        }
                        let reversepoints = Object.assign([], point.comments).reverse();
                        point.comments = reversepoints;
                    }
                    point.real = true
                    vm.moreMeeting = point
                    vm.currentPoint = id
                    if (typeof callback === "function") {
                        callback()
                    }
                });
            },
            meetingDate() {
                return this.meeting.date.toLocaleString();
            },
            votesTitle(down, up) {
                return "Up: " + up + " | Down: " + down
            },
            initialize() {
                $.get("../api/getUserInfo", function(data){
                    vm.user=JSON.parse(data);
                })
                $.get('../api/getMeeting?meetingID=<?php echo $_GET['meeting']; ?>', function (res) {
                    let response = JSON.parse(res);
                    vm.meeting = response
                    $.get('../api/getMeetingPoints?meetingID=<?php echo $_GET['meeting']; ?>', function (res) {
                        let response = JSON.parse(res);
                        vm.meetingPoints = response
                    });
                });
            },
            updateMeeting(){
                $.get('../api/getMeetingPoints?meetingID=<?php echo $_GET['meeting']; ?>', function (res) {
                    let response = JSON.parse(res);
                    vm.meetingPoints = response
                });
            },
            addComment() {
                if (this.myComment !== "") {
                    let num = Object.keys(vm.moreMeeting.comments).length
                    this.getMoreInfo(this.currentPoint, function(){
                        let obj = '{"'+num+'":{"author":"'+ this.user.info.firstname +' '+ this.user.info.lastname +'","comment":"'+vm.myComment+'"}}'
                        $.post('../api/addComment',{"pointID": vm.currentPoint, "comment": obj}, function () {
                            new Noty({
                                type: "success",
                                text: "Success",
                                timeout: 4000
                            }).show();
                        });
                        let reversepoints = Object.assign([], vm.moreMeeting.comments).reverse();
                        Object.assign(reversepoints, JSON.parse(obj))
                        let secondreverse = Object.assign([], reversepoints).reverse();
                        vm.moreMeeting.comments = secondreverse;
                        vm.myComment = ""
                    });
                } else {
                    new Noty({
                        type: "error",
                        text: "Comment's can't be blank.",
                        timeout: 4000
                    }).show();
                }
            },
            isEmptyObject(obj) {
                return $.isEmptyObject(obj)
            },
            addPoint() {
                if (this.newPoint.name !== "" && this.newPoint.description !== "") {
                    $.post('../api/addPoint', {
                        "meetingID": <?php echo $_GET['meeting']; ?>, 
                        "pointName": this.newPoint.name, 
                        "pointDescription": this.newPoint.description, 
                        "name": this.user.info.firstname + " " + this.user.info.lastname
                    }, function () {
                        new Noty({
                            type: "info",
                            text: "Point Added.",
                            timeout: 4000
                        }).show();
                        vm.updateMeeting();
                    });
                } else {
                    new Noty({
                        type: "error",
                        text: "All Field Must Be Filled",
                        timeout: 4000
                    }).show();
                }
            }
        }
    });
</script>
</body>
</html>
<?php endif; ?>
<?php include "footer.php"; ?>