
<!DOCTYPE html> 
<html>
<head>
	<title>Lovevet</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8">
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
	<link rel="stylesheet" href="css/custom.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
</head>

<body>

	<div data-role="page" id="lovevet">
	
		<div class="ui-panel-wrapper">
		
			<div class="ui-header ui-bar-inherit" data-role="header" role="banner">
				<h1 class="ui-title" role="heading" aria-level="1">Teilgebiete</h1>
				<a class="ui-btn-left ui-btn ui-icon-back ui-btn-icon-notext ui-shadow ui-corner-all" data-rel="back" href="trainer_studentlist.php" data-role="button" role="button">Back</a>
			
			</div>
			
			<div class="ui-content" role="main">

			<?php 
                require_once('./curl.php');
                
                session_start();
                $exacomp_token = $_SESSION['exacomp_token'];
              
                //echo $exacomp_token;
                if(isset($exacomp_token)){
                    $curl = new curl;
                    //header('Content-Type: text/plain');
                    $userid = $_GET['u'];
                    $properties = parse_ini_file("properties.ini");		
                    
                //get topics
                    $serverurl = $properties["url"].$properties["webserviceurl"]."?wstoken=".$exacomp_token."&wsfunction=";
                    $function = "block_exacomp_get_subjects_for_user";
                    
                    $params = new stdClass();
                    $params->userid = $userid;
                    
                    $resp_xml = $curl->get($serverurl.$function, $params);
                    
                    $xml = simplexml_load_string($resp_xml);
                    $json = json_encode($xml);
                    $multiple = json_decode($json, TRUE);
                    
                    $subjects = array();
                    $current_id = 0;
                    foreach($multiple as $single){
                         foreach($single as $keys){
                             //foreach($keys as $key){
                                 foreach($keys as $attributes){
                                     foreach($attributes as $attribute){
                                         if(strcmp($attribute["@attributes"]["name"], "subjectid")==0){
                                             if(!array_key_exists($attribute["VALUE"], $subjects)){
                                                 $subjects[$attribute["VALUE"]] = new stdClass();
                                                 $subjects[$attribute["VALUE"]]->id = $attribute["VALUE"];
                                                 $current_id = $attribute["VALUE"];
                                             }
                                         }else if(strcmp($attribute["@attributes"]["name"], "title")==0){
                                              if(array_key_exists($current_id, $subjects) && $current_id>0){
                                                  $subjects[$current_id]->title = $attribute["VALUE"];
                                              }
                                         }else if(strcmp($attribute["@attributes"]["name"], "courseid")==0){
                                             if(array_key_exists($current_id, $subjects) && $current_id>0){
                                                  $subjects[$current_id]->courseid = $attribute["VALUE"];
                                              }
                                         }
                                     }
                                 }
                            //}
                         }
                     }
                    
                }
                ?>
                <ul data-role="listview" data-inset="true" data-divider-theme="a">
                <?php 
                foreach($subjects as $subject){
                    echo "<li><a href='trainer_lernfeld.php?subjectid=".$subject->id."&courseid=".$subject->courseid."&u=".$userid."'>".$subject->title."</a></li>";
                }
                ?>
                </ul>
                <!--
				<ul data-role="listview" data-inset="true" data-divider-theme="a">
				    <li><a href="trainer_lernfeld.php">L&uuml;ftungstechnik</a></li>
				    <li><a href="trainer_lernfeld.php">W&auml;rmetechnik</a></li>
				    <li><a href="trainer_lernfeld.php">Installationstechnik</a></li>
				</ul>  -->

			</div><!-- /ui-content -->
			
	</div><!-- /ui-panel-wrapper -->
	
			<div class="ui-footer ui-bar-a ui-footer-fixed slideup" data-theme="a" data-position="fixed" data-role="footer" role="contentinfo">
			    <div data-role="navbar">
			        <ul>
			            <li><a class="ui-btn-active ui-state-persist ui-link ui-btn" href="trainer_teilbereich.php">Teilbereiche</a></li>
			            <li><a class="ui-link ui-btn" href="trainer_compprofile.php?u=<?php echo $_GET['u']; ?>">Kompetenzprofil</a></li>
			            <li><a class="ui-link ui-btn" href="trainer_settings.php">Einstellungen</a></li>
			        </ul>
			    </div><!-- /navbar -->
			</div><!-- /footer -->

	
		
	
	</div><!.. /lovevet -->

</body>
</html>