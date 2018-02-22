<?php $nonav=0; include "head.php"; ?>
<body style="background: no-repeat center center fixed;background-size: cover;background-image: url(https://thecatapi.com/api/images/get?format=src&type=png);">
	<div class="dashboardOverlay">
	<div id="titleText" style="z-index:2;">
		<h1 id="welcome" style="padding: 10px;display:inline-block;">Hello, Human</h1><h1 style="padding: 10px;float:right;display:inline-block;" id="dtime"></h1>
	</div>
	<a href="logger">
			<div class="navCard">
				<div class="navCard-items">
					<p class="title" style="color:#1abc9c;"><i class="fas fa-clipboard" style="padding-right: 10px;color:#1abc9c;"></i>Log Case</p>
					<!-- <p class="shortcontent" style="color:#16a085;">Go here to log your cases.</p> -->
				</div>
			</div>  
	</a>
	<a href="guides">
			<div class="navCard">
				<div class="navCard-items">
					<p class="title" style="color:#e67e22;"><i class="fas fa-book" style="padding-right:10px;color:#e67e22;"></i>Guides</p>
					<!--<p class="shortcontent" style="color:#d35400;">Tutorials and Operating Procedures.</p> -->
				</div>
			</div>  
	</a>
	<a href="me">
			<div class="navCard">
				<div class="navCard-items">
					<p class="title" style="color:#2ecc71;"><i class="fas fa-address-card" style="padding-right:10px;color:#2ecc71;"></i>Profile</p>
					<!-- <p class="shortcontent" style="color:#27ae60;">View your User Profile</p> -->
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
	</div>
	<script>
		$('#dtime').text(currentTime());
		setInterval(() => {
			$('#dtime').text(currentTime());
		}, 1000);
	</script>
</body>
<!--Created By Kieran Holroyd-->
</html>

  