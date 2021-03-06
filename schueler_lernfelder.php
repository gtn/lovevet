
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
				<h1 class="ui-title" role="heading" aria-level="1">Lernfelder</h1>
				<a class="ui-btn-left ui-btn ui-icon-back ui-btn-icon-notext ui-shadow ui-corner-all" data-rel="back" href="trainer_studentlist.php" data-role="button" role="button">Back</a>
			
			</div>
			
			<div class="ui-content" role="main">
            <?php 
                require_once('./curl.php');
                
                session_start();
                $exacomp_token = $_SESSION['exacomp_token'];
                $exaport_token = $_SESSION['exaport_token'];
                //echo $exacomp_token;
                if(isset($exacomp_token) && isset($_GET['subjectid'])){
                    $subjectid = $_GET['subjectid'];
                    $courseid = $_GET['courseid'];
                    $curl = new curl;
                    
                    $properties = parse_ini_file("properties.ini");		
                    $serverurl = $properties["url"].$properties["webserviceurl"]."?wstoken=".$exacomp_token."&wsfunction=";
                    
                    //get topics
                    $function = "block_exacomp_get_examples_for_subject";
                    $params = new stdClass();
                    $params->subjectid = $subjectid;
                    $params->courseid = $courseid;
                    $params->userid = 0;
                    
                    $resp_xml = $curl->get($serverurl.$function, $params);
                   
                    $xml = simplexml_load_string($resp_xml);
                    $json = json_encode($xml);
                    $multiple = json_decode($json,TRUE);
                   
                    $topics = array();
                    $current_id = 0;
                    foreach($multiple as $single){
                        foreach($single as $keys){
                            foreach($keys as $key){
                                foreach($key as $attributes){
                                    foreach($attributes as $attribute){
                                        if(strcmp($attribute["@attributes"]["name"], "topicid")==0){
                                            if(!in_array($attribute["VALUE"], $topics)){
                                                $topics[$attribute["VALUE"]] = new stdClass();
                                                $topics[$attribute["VALUE"]]->id = $attribute["VALUE"];
                                                $topics[$attribute["VALUE"]]->examples = array();
                                                $current_id = $attribute["VALUE"];
                                            }
                                        }else if(strcmp($attribute["@attributes"]["name"], "title")==0){
                                             if(array_key_exists($current_id, $topics) && $current_id>0){
                                                 $topics[$current_id]->title = $attribute["VALUE"];
                                             }
                                        }else if(strcmp($attribute["@attributes"]["name"], "examples")==0){
                                            if(array_key_exists($current_id, $topics) && $current_id>0){
                                                 $topics[$current_id]->examples_multiple = $attribute["MULTIPLE"];
                                             }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    foreach($topics as $topic){
                        foreach($topic->examples_multiple as $keys){
                            foreach($keys as $key){
                                foreach($key as $attributes){
                                    foreach($attributes as $attribute){
                                        if(strcmp($attribute["@attributes"]["name"], "exampleid")==0){
                                            if(!in_array($attribute["VALUE"], $topics[$topic->id]->examples)){
                                                $topics[$topic->id]->examples[$attribute["VALUE"]] = new stdClass();
                                                $topics[$topic->id]->examples[$attribute["VALUE"]]->id = $attribute["VALUE"];
                                            
                                                $current_id = $attribute["VALUE"];
                                            }
                                        }else if(strcmp($attribute["@attributes"]["name"], "example_title")==0){
                                             if(array_key_exists($current_id, $topics[$topic->id]->examples) && $current_id>0){
                                                 $topics[$topic->id]->examples[$current_id]->title = $attribute["VALUE"];
                                             }
                                        }else if(strcmp($attribute["@attributes"]["name"], "example_item")==0){
                                             if(array_key_exists($current_id, $topics[$topic->id]->examples) && $current_id>0){
                                                 $topics[$topic->id]->examples[$current_id]->item = $attribute["VALUE"];
                                             }
                                        }else if(strcmp($attribute["@attributes"]["name"], "example_status")==0){
                                             if(array_key_exists($current_id, $topics[$topic->id]->examples) && $current_id>0){
                                                 $topics[$topic->id]->examples[$current_id]->status = $attribute["VALUE"];
                                             }
                                        }
                                    }
                                }
                            }
                        }
                    }
                   
                   
                    foreach($topics as $topic){
                        echo '<div data-role="collapsible">';
                        echo '<h2>'.$topic->title.'</h2>';
                        echo '<ul data-role="listview">';
                        
                        foreach($topic->examples as $example){
                            $status = $example->status;
                            if($status == -1)
                                echo '<li data-icon="eye"><a href="schueler_example.php?exampleid='.$example->id.'&itemid='.$example->item.'&courseid='.$courseid.'">'.$example->title.' (noch offen)</a></li>';
                            elseif($status == 0)
								echo '<li data-icon="eye"><a href="schueler_example.php?exampleid='.$example->id.'&itemid='.$example->item.'&courseid='.$courseid.'">'.$example->title.' (warte auf Bewertung)</a></li>';
							elseif($status == 2)
                                echo '<li data-icon="check" class="example-done"><a href="schueler_example.php?exampleid='.$example->id.'&itemid='.$example->item.'&courseid='.$courseid.'">'.$example->title.' (gelöst)</a></li>';
                            elseif($status == 1)
                                echo '<li data-icon="alert" class="example-alert"><a href="schueler_example.php?exampleid='.$example->id.'&itemid='.$example->item.'&courseid='.$courseid.'">'.$example->title.' (Abgegeben, Überarbeitung erforderlich)</a></li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                    }
                }
                ?>
				<a href="schueler_createexample.php" class="ui-shadow ui-btn ui-corner-all">Eigener Beitrag</a>
			</div><!-- /ui-content -->
			
		</div><!-- /ui-panel-wrapper -->
	
			<div class="ui-footer ui-bar-a ui-footer-fixed slideup" data-theme="a" data-position="fixed" data-role="footer" role="contentinfo">
			    <div data-role="navbar">
			        <ul>
			            <li><a class="ui-btn-active ui-state-persist  ui-link ui-btn" href="schueler_examples.php">Teilgebiete</a></li>
			            <li><a class="ui-link ui-btn" href="schueler_compprofile.php">Kompetenzprofil</a></li>
			            <li><a class="ui-link ui-btn" href="schueler_settings.php">Einstellungen</a></li>
			        </ul>
			    </div><!-- /navbar -->
			</div><!-- /footer -->

	
		
	
	</div><!.. /lovevet -->

</body>
</html>