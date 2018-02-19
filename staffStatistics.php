<?php include "head.php"; ?>
<?php include "navbar.php"; ?>
<div class="cases-container">
  <div class="daily-cases"><b>Daily Cases</b></div><div class="weekly-cases"><b>Weekly Cases</b></div>
  <div class="topStaff"></div>
</div>
<script>
$(document).ready(function(){
  $.post('dailyCases.php', {}, function(data){
    var cases=JSON.parse(data);
    new Chartist.Line('.daily-cases', {
      labels: ['Four Days Ago', 'Three Days Ago', 'Two Days Ago', 'Yesterday', 'Today'],
      series: [
        [cases.fourdays, cases.threedays, cases.twodays, cases.yesterday, cases.today]
      ]
    }, {
      chartPadding: {
        right: 40
      },
      color: 'white'
    });
  });
  $.post('weeklyCases.php', {}, function(data){
    var cases=JSON.parse(data);
    new Chartist.Line('.weekly-cases', {
      labels: ['A Month Ago', 'Three Weeks Ago', 'Two Weeks Ago', 'Last Week', 'This Week'],
      series: [
        [cases.onemonth, cases.threeweeks, cases.twoweeks, cases.lastweek, cases.thisweek]
      ]
    }, {
      chartPadding: {
        right: 40
      },
      color: 'white'
    });
  });
});
</script>
<?php include "footer.php"; ?>