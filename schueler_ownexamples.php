
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
		
			<div class="ui-header ui-bar-inherit" data-role="header" role="banner">
				<h1 class="ui-title" role="heading" aria-level="1">Eigene Beitr&auml;ge</h1>
				<a class="ui-btn-left ui-btn ui-icon-back ui-btn-icon-notext ui-shadow ui-corner-all" data-rel="back" href="trainer_studentlist.php" data-role="button" role="button">Back</a>
			
			</div>
			
			<div class="ui-content" role="main">

			<?php
				//TODO get own examples
				require_once('./curl.php');
				
				session_start();
				$mdl_token = $_SESSION['mdl_token'];
				$exacomp_token = $_SESSION['exacomp_token'];
				$exaport_token = $_SESSION['exaport_token'];
				
				if(isset($exacomp_token)){
					$curl = new curl;
					$properties = parse_ini_file("properties.ini");		
					$serverurl = $properties["url"].$properties["webserviceurl"]."?wstoken=".$exacomp_token."&wsfunction=";
					
					//get topics
					$function = "block_exacomp_get_user_examples";
					
					$resp_xml = $curl->get($serverurl.$function);
					$xml = simplexml_load_string($resp_xml);
					$json = json_encode($xml);
					$multiple = json_decode($json,TRUE);
					
					$examples = array();
					$current_id = 0;
					foreach($multiple as $single){
                         foreach($single as $keys){
                             foreach($keys as $key=>$value){
								//different results from webservice
								if(strcmp($key, "KEY")==0){
									foreach($value as $attribute){
                                         if(strcmp($attribute["@attributes"]["name"], "exampleid")==0){
        								    if(!array_key_exists($attribute["VALUE"], $examples)){
        								        $examples[$attribute["VALUE"]] = new stdClass();
        								        $examples[$attribute["VALUE"]]->id = $attribute["VALUE"];
        								        $current_id = $attribute["VALUE"];
        								    }
                                         }else if(strcmp($attribute["@attributes"]["name"], "exampletitle")==0){
        								        $examples[$current_id]->title = $attribute["VALUE"];
        								 }else if(strcmp($attribute["@attributes"]["name"], "example_status")==0){
												$examples[$current_id]->status = $attribute["VALUE"];
										 }
									}
						         }else{
									foreach($value as $attributes){
										foreach($attributes as $attribute){
    										if(strcmp($attribute["@attributes"]["name"], "exampleid")==0){
            								    if(!array_key_exists($attribute["VALUE"], $examples)){
            								        $examples[$attribute["VALUE"]] = new stdClass();
            								        $examples[$attribute["VALUE"]]->id = $attribute["VALUE"];
            								        $current_id = $attribute["VALUE"];
            								    }
                                             }else if(strcmp($attribute["@attributes"]["name"], "exampletitle")==0){
            								        $examples[$current_id]->title = $attribute["VALUE"];
            								 }else if(strcmp($attribute["@attributes"]["name"], "example_status")==0){
												$examples[$current_id]->status = $attribute["VALUE"];
											 }
										}
									}
						         }
    					     }
						 }
					}
					
					echo '<ul data-role="listview" data-inset="true" data-divider-theme="a">';
					foreach($examples as $example){
					    echo '<li><a href="schueler_updateexample.php?exampleid='.$example->id.'&status='.$example->status.'">'.$example->title.'</a></li>';
					}
					echo '</ul>';
				}
			?>
				<a href="schueler_createexample.php" class="ui-shadow ui-btn ui-corner-all">Neuen Beitrag erstellen</a>

			</div><!-- /ui-content -->
			
	</div><!-- /ui-panel-wrapper -->
	
			<div class="ui-footer ui-bar-a ui-footer-fixed slideup" data-theme="a" data-position="fixed" data-role="footer" role="contentinfo">
			    <div data-role="navbar">
			        <ul>
			            <li><a class="ui-link ui-btn" href="trainer_student.php">Sch&uuml;lerauswahl</a></li>
			            <li><a class="ui-btn-active ui-state-persist ui-link ui-btn" href="trainer_ownexamples.php">Eigene Beitr&auml;ge</a></li>
			            <li><a class="ui-link ui-btn" href="trainer_settings.php">Einstellungen</a></li>
			        </ul>
			    </div><!-- /navbar -->
			</div><!-- /footer -->

	
		
	
	</div><!.. /lovevet -->

</body>
</html>