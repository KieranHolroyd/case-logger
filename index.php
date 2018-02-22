<?php $nonav=0; include "head.php"; ?>
<body style="background: no-repeat center center fixed;background-size: cover;background-image: url(https://thecatapi.com/api/images/get?format=src&type=png);">
	<div id="titleText">
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
	<script>
		$('#dtime').text(currentTime());
		setInterval(() => {
			$('#dtime').text(currentTime());
		}, 1000);
		function getBGImg() {
			var bg = $('body').css('background-image');
			bg = bg.replace('url(','').replace(')','').replace(/\"/gi, "");
			return bg;
		}
		function getImageBrightness(imageSrc,callback) {
			var img = document.createElement("img");
			img.src = imageSrc;
			img.style.display = "none";
			img.crossOrigin = '';
			document.body.appendChild(img);

			var colorSum = 0;

			img.onload = function() {
				// create canvas
				var canvas = document.createElement("canvas");
				canvas.width = this.width;
				canvas.height = this.height;

				var ctx = canvas.getContext("2d");
				ctx.drawImage(this,0,0);

				var imageData = ctx.getImageData(0,0,canvas.width,canvas.height);
				var data = imageData.data;
				var r,g,b,avg;

				for(var x = 0, len = data.length; x < len; x+=4) {
					r = data[x];
					g = data[x+1];
					b = data[x+2];

					avg = Math.floor((r+g+b)/3);
					colorSum += avg;
				}

				var brightness = Math.floor(colorSum / (this.width*this.height));
				callback(brightness);
			}
		}
		getImageBrightness(getBGImg(),function(brightness) {
			console.warn(brightness);
			if(brightness > 115){
				setColor(0);
			}
		});
		function setColor(val) {
			switch (val) {
				case 1:
					$('#titleText h1').css('color', '#ddd');
					$('#titleText p').css('color', '#ddd');
					break;
				case 0:
					$('#titleText h1').css('color', '#333');
					$('#titleText p').css('color', '#333');
					break;
			}
		}
	</script>
</body>
<!--Created By Kieran Holroyd-->
</html>

  