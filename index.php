<?php $nonav=0; include "head.php"; ?>
<body>
	<div>
		<h1 id="welcome" style="padding: 10px;display:inline-block;">Hello, Human</h1><h1 style="padding: 10px;float:right;display:inline-block;" id="dtime"></h1>
	</div>
  <a href="logger">
		<div class="navCard-small">
			<div class="navCard-items">
				<p class="title">Log Case</p>
				<p class="shortcontent">Go here to log your cases.</p>
			</div>
		</div>  
  </a>
  <a href="guides">
	<div class="navCard-small">
		<div class="navCard-items">
			<p class="title">Guides</p>
			<p class="shortcontent">Tutorials and Operating Procedures.</p>
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
	<div class="navCard">
		<div class="navCard-items">
			<p class="title">Test</p>
			<p class="shortcontent">Go here to log your cases.</p>
		</div>
	</div>  
  </a>
  <a href="logger.php">
		<div class="navCard">
			<div class="navCard-items">
				<p class="title">Test</p>
				<p class="shortcontent">Go here to log your cases.</p>
			</div>
		</div>  
  </a>
  <a href="logger.php">
		<div class="navCard">
			<div class="navCard-items">
				<p class="title">Test</p>
				<p class="shortcontent">Go here to log your cases.</p>
			</div>
		</div>  
  </a>
  <a href="logger.php">
		<div class="navCard">
			<div class="navCard-items">
				<p class="title">Test</p>
				<p class="shortcontent">Go here to log your cases.</p>
			</div>
		</div>  
  </a>
	<script>
		$('#dtime').text(currentTime());
		setInterval(() => {
			$('#dtime').text(currentTime());
		}, 1000);
	</script>
</body>
<!--Created By Kieran Holroyd-->
</html>

  