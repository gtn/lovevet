
<!DOCTYPE html> 
<html>
<head>
	<title>Lovevet</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
	<link rel="stylesheet" href="css/custom.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
</head>

<body>
	<div data-role="page" id="lovevet">
		
		<div class="ui-panel-wrapper">
			<div class="ui-content" role="main">
			<?php      
				if(isset($_GET['login']) && $_GET['login']==1){
					//do login in here
					if(isset($_POST["text-basic"]) && isset($_POST["password"])){
						$url = "http://gtn02.gtn-solutions.com/moodle27/login/token.php?username=".$_POST["text-basic"]."&password=".$_POST["password"]."&service=moodle_mobile_app";
						$json = file_get_contents($url); 
						$data = json_decode($json);
						echo $data->{'token'};
					}
				}
			?>
		
				<form method='post' action='<?php echo $_SERVER['PHP_SELF'].'?login=1'?>'>
					
					<input name="text-basic" id="text-basic" value="" type="text" placeholder="Name"/>
					<input name="password" id="password" value="" autocomplete="off" type="password"placeholder="Password"/>
					<input type = "submit" value = "test login"/>
					<a href="schueler_examples.php" class="ui-shadow ui-btn ui-corner-all">Login</a>
					
				</form>
	
	
			</div><!-- /ui-content -->
		</div><!-- /ui-panel-wrapper -->
	
	
	</div><!.. /lovevet -->

</body>
</html>