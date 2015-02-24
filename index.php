
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
	
	
				<form>

					<a href="trainer_login.php" class="ui-shadow ui-btn ui-corner-all">Ausbilder</a>
					<a href="schueler_login.php" class="ui-shadow ui-btn ui-corner-all">Auszubildender</a>
				
				</form>
	
	            <?php 
	            $properties = parse_ini_file("properties.ini");
	            
	            //get Exacomp token
	            $url = $properties["url"]."login/token.php?username=trainer1&password=Trainer1!&service=exacompservices";
	            $json = file_get_contents($url);
	            $data = json_decode($json);
	            $exacomp_token = $data->{'token'};
	            
	            require_once('./curl.php');
	             
	            $serverurl = $properties["url"].$properties["webserviceurl"]."?wstoken=".$exacomp_token."&wsfunction=block_exacomp_get_user_role";
	            $xml = simplexml_load_string(file_get_contents($serverurl));
	            
	            echo "Beispiel Aufruf fuerr get_user_role (1 = Ausbilder, 0 = Auszubildender): " . $serverurl . "<br/>";
	            print_r($xml);
	            
	            // ROLE 1 == TRAINER
	            // ROLE 0 == STUDENT
	            ?>
		
			</div><!-- /ui-content -->
		</div><!-- /ui-panel-wrapper -->
	
	</div><!.. /lovevet -->

</body>
</html>