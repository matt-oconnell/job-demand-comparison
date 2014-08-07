<html>

<head>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="js/chart.js"></script>
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/media.css">
	<meta name="viewport" content="width=device-width, user-scalable=no">
</head>

<body>
	<div class="grid">
	  <div id="header">
		  <p>Technology Demand Comparison</p>
	  </div>
	  <div class="col-1-3">
	  		<form method="post" name="compare-form" id="compare-form" action="scraper.php" method="POST">
	  			<div class="top">
					<div>
						<div class="input-line">
							<input type="text" name="language1" id="language1" data-input-id="1" value="php" autocomplete="off">
							<br/>
						</div>
						
						<div class="input-line">
							<input type="text" name="language2" id="language2" data-input-id="2" value="ruby" autocomplete="off">
							<br/>
						</div>
					</div>
				</div>
				<div class="mid">
					<label for="city">City</label><br/>
					<input type="text" name="city" id="city" value="Brooklyn" autocomplete="off">
					<label for="state">State (ex: NY)</label><br/>
					<input type="text" name="state" id="state" value="NY" autocomplete="off" style="text-transform: uppercase" maxlength="2">
					
					<!--
					<input type="text" name="salary" id="salary" value="80000">
					<label for="salary">Salary (0 for default)</label><br/>
					<select type="radius" name="radius" id="radius">
						<option value="5">5</option>
						<option value="10">10</option>
						<option value="20">20</option>
						<option value="30">30</option>
						<option value="50">50</option>
						<option value="100">100</option>
						<option value="150">150</option>
					</select>
					<label for="radius">Radius (miles)</label><br/>
					-->
					
			  		<div type="input" class="btn-add add"></div>
			  		<div type="input" class="btn-delete delete"></div>
					<button type="submit"  name="submit" id="submit" class="btn submit" value="SUBMIT">SUBMIT</button>
				</div>
			</form>
	     
	  </div>
	  <div class="col-2-3">
	  	<div class="top">
	     	<div class="left">
		     	<div class="chart-wrapper" data-chart-number="0">
					<h1>Indeed</h1>
					<canvas class="canvas-chart" id="chart-0" width="500" height="400"></canvas>
				</div>
				<div class="loader" data-load="0"></div>
	     	</div>
		  	<div class="right">
			  	<div class="chart-wrapper" data-chart-number="1">
					<h1>Career Builder</h1>
					<canvas class="canvas-chart" id="chart-1" width="500" height="400"></canvas>
				</div>
				<div class="loader" data-load="1"></div>
		  	</div>
	  	</div>
	  	<div class="bottom">
	  		<div class="metrics-0">
	  			<h3>Indeed Stats:</h3>
				<ul>
					<li>php: <strong>1735</strong></li>
					<li>ruby: <strong>1555</strong></li>
					<li>It took: <span>0.138</span> seconds to run.</li>
				</ul>
			</div>
	    	<div class="winner" style="display: block;">
	    		<h3>And the winner is:</h3>
	    		<h2>php</h2>
	    		<p>With <span>1735</span>(Indeed) Jobs and <span>136</span>(CareerBuilder) Jobs</p>
	    		 posted near <span>Brooklyn, NY</span>
	    		 <p></p>
	    	</div>
			<div class="metrics-1">	
				<h3>Career Builder Stats:</h3>
				<ul>
					<li>php: <strong>136</strong></li>
					<li>ruby: <strong>44</strong></li>
					<li>It took: <span>2.120</span> seconds to run.</li>
				</ul>
			</div>
			
	  	</div>
	  </div>
	</div>
	
</body>

<script src="js/main.js"></script>
</html>