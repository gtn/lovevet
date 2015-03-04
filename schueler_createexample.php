
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
				<h1 class="ui-title" role="heading" aria-level="1">Eigenen Beitrag</h1>
				<a class="ui-btn-left ui-btn ui-icon-back ui-btn-icon-notext ui-shadow ui-corner-all" data-rel="back" href="trainer_studentlist.php" data-role="button" role="button">Back</a>
			
			</div>
			
			<div class="ui-content" role="main">

				<?php 
                require_once('./curl.php');
                
                session_start();
                $exacomp_token = $_SESSION['exacomp_token'];
                $exaport_token = $_SESSION['exaport_token'];
                //echo $exacomp_token;
                if(isset($exacomp_token)){
                    (isset($_GET['save'])&& $_GET['save']==1)?$save=1:$save=0;
                    $curl = new curl;
                        
                    $properties = parse_ini_file("properties.ini");		
                    $serverurl = $properties["url"].$properties["webserviceurl"]."?wstoken=".$exacomp_token."&wsfunction=";
                        
                    if($save == 1){
                        //name, description & competencies for example
                        if(isset($_POST['name']) && isset($_POST['description']) && isset($_POST['comps_select'])){
                            $name = $_POST['name'];
                            $description = $_POST['description'];
                            $comps_array = $_POST['comps_select'];
                            $task = $_POST['task'];
                            
                            $comps = "";
                            foreach($comps_array as $id){
                                $comps .= $id.",";
                            }
                            $comps = substr($comps, 0, strlen($comps)-1);
                            
                            $function = "block_exacomp_create_example";
                            $params = new stdClass();
                            $params->name = $name;
                            $params->description = $description;
                            $params->task = $task;
                            $params->comps = $comps;

                            $resp_xml = $curl->get($serverurl.$function, $params);
                            $xml = simplexml_load_string($resp_xml);
                            $json = json_encode($xml);
                            $single = json_decode($json,TRUE);
                            
                            foreach($single as $key){
                                foreach($key as $attribute){
                                   if(strcmp($attribute["@attributes"]["name"], "exampleid")==0){
                                        $id = $attribute["VALUE"];
                                    }
                                }
                            }
                           
                            //example wurde erstellt, auf example seite weiterleiten?
                            echo '<script type="text/javascript">
           							window.location = "schueler_example.php?exampleid='.$id.'&courseid=0"
      							  </script>';
                        }
                        
                        //TODO CreateItem
                        //kommentar
                        //weblink
                        //aufwand
                        //selbsteinschätzung
                        
                    }else{
                        //get topics
                        $function = "block_exacomp_get_competencies_for_upload";
                        $params = new stdClass();
                        $params->userid = 0;
                        
                        $resp_xml = $curl->get($serverurl.$function, $params);
                       
                        $xml = simplexml_load_string($resp_xml);
                        $json = json_encode($xml);
                        $multiple = json_decode($json,TRUE);
                       
                        $subjects = array();
                        $current_id = 0;
                        foreach($multiple as $single){
                            foreach($single as $key){
                                //foreach($keys as $key){
                                    foreach($key as $attributes){
                                        foreach($attributes as $attribute){
                                            if(strcmp($attribute["@attributes"]["name"], "subjectid")==0){
                                                if(!in_array($attribute["VALUE"], $subjects)){
                                                    $subjects[$attribute["VALUE"]] = new stdClass();
                                                    $subjects[$attribute["VALUE"]]->id = $attribute["VALUE"];
                                                    $subjects[$attribute["VALUE"]]->topics = array();
                                                    $current_id = $attribute["VALUE"];
                                                }
                                            }else if(strcmp($attribute["@attributes"]["name"], "subjecttitle")==0){
                                                 if(array_key_exists($current_id, $subjects) && $current_id>0){
                                                     $subjects[$current_id]->title = $attribute["VALUE"];
                                                 }
                                            }else if(strcmp($attribute["@attributes"]["name"], "topics")==0){
                                                if(array_key_exists($current_id, $subjects) && $current_id>0){
                                                     $subjects[$current_id]->topics_multiple = $attribute["MULTIPLE"];
                                                 }
                                            }
                                        }
                                    }
                                //}
                            }
                        }
                        
                        foreach($subjects as $subject){
                            foreach($subject->topics_multiple as $keys){
                                foreach($keys as $key){
                                    foreach($key as $attributes){
                                        foreach($attributes as $attribute){
                                            if(strcmp($attribute["@attributes"]["name"], "topicid")==0){
                                                if(!in_array($attribute["VALUE"], $subjects[$subject->id]->topics)){
                                                    $subjects[$subject->id]->topics[$attribute["VALUE"]] = new stdClass();
                                                    $subjects[$subject->id]->topics[$attribute["VALUE"]]->id = $attribute["VALUE"];
                                                    $subjects[$subject->id]->topics[$attribute["VALUE"]]->descriptors = array();
                                                    $current_id = $attribute["VALUE"];
                                                }
                                            }else if(strcmp($attribute["@attributes"]["name"], "topictitle")==0){
                                                 if(array_key_exists($current_id, $subjects[$subject->id]->topics) && $current_id>0){
                                                     $subjects[$subject->id]->topics[$current_id]->title = $attribute["VALUE"];
                                                 }
                                            }else if(strcmp($attribute["@attributes"]["name"], "descriptors")==0){
                                                if(array_key_exists($current_id,  $subjects[$subject->id]->topics) && $current_id>0){
                                                     $subjects[$subject->id]->topics[$current_id]->descriptors_multiple = $attribute["MULTIPLE"];
                                                 }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        
                        foreach($subjects as $subject){
                            foreach($subject->topics as $topic){
                                foreach($topic->descriptors_multiple as $keys){
                                    foreach($keys as $key){
                                        foreach($key as $attributes){
                                            foreach($attributes as $attribute){
                                                if(strcmp($attribute["@attributes"]["name"], "descriptorid")==0){
                                                    if(!in_array($attribute["VALUE"],  $subjects[$subject->id]->topics[$topic->id]->descriptors)){
                                                        $subjects[$subject->id]->topics[$topic->id]->descriptors[$attribute["VALUE"]] = new stdClass();
                                                        $subjects[$subject->id]->topics[$topic->id]->descriptors[$attribute["VALUE"]]->id = $attribute["VALUE"];
                                                    
                                                        $current_id = $attribute["VALUE"];
                                                    }
                                                }else if(strcmp($attribute["@attributes"]["name"], "descriptortitle")==0){
                                                     if(array_key_exists($current_id, $subjects[$subject->id]->topics[$topic->id]->descriptors) && $current_id>0){
                                                         $subjects[$subject->id]->topics[$topic->id]->descriptors[$current_id]->title = $attribute["VALUE"];
                                                     }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                ?>
	
				<form action="schueler_createexample.php?save=1"; method = "post">
				<ul data-role="listview" data-inset="true">
			   
			    <li>
			    <label for="name">Name:</label>
					<input name="name" id="name" value="" type="text">
			    </li>
			    <li>
				    <label for="description">Beschreibung:</label>
					<textarea cols="40" rows="8" name="description" id="description"></textarea>
			    </li>
			    <li>
				    <label for="task">Task:</label>
					<textarea cols="40" rows="8" name="task" id="task"></textarea>
			    </li>
			    <!--<li data-role="list-divider">Lernprodukt</li>
			    <li>	
					<label for="file">File:</label>
					<input name="file" id="file" value="" type="file">
			    </li>
			    <li>
			    	<label for="weblink">Weblink:</label>
					<input name="weblink" id="weblink" value="" type="text">
			    </li>
			    <li>
			    	<label for="effort">Aufwand:</label>
					<input name="effort" id="effort" value="" type="text">
				</li>
				<li>
					<label for="comment">Kommentar:</label>
					<textarea cols="40" rows="8" name="comment" id="comment"></textarea>
				</li>
				-->
				<li data-role="list-divider">Kompetenz</li>
				<li>
					<label for="comps_select" class="select">Erreichbare Kompetenzen:</label>
					<select name="comps_select[]" id="comps_select" multiple="multiple" data-native-menu="false" data-icon="grid" data-iconpos="left">
					    <option>Auswahl:</option>
					    <?php
					        foreach($subjects as $subject){
					            foreach($subject->topics as $topic){
					                echo ' <optgroup label="'.$topic->title.'">';
					                foreach($topic->descriptors as $descriptor){
					                    echo '<option value="'.$descriptor->id.'">'.$descriptor->title.'</option>';
					                }
					                echo '</optgroup>';
					            }
					        }
					    ?>
					</select>
				</li>
				<!--
				<li data-role="list-divider">Selbsteinsch&auml;tzung</li>
				 <li>
					<label for="self_eval">Selbsteinsch&auml;tzung:</label>
					<input name="self_eval" id="self_eval" value="0" min="0" max="100" step="1" data-highlight="true" type="range">
				 </li>
				 -->
				<li class="ui-body ui-body-b">
		            <fieldset class="ui-grid-a">
		                    <div class="ui-block-a"><button type="submit" class="ui-btn ui-corner-all ui-btn-a">Cancel</button></div>
		                    <div class="ui-block-b"><button type="submit" class="ui-btn ui-corner-all ui-btn-a">Save</button></div>
		            </fieldset>
		        </li>
			</ul>
				
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

	</form>
		
	
	</div><!.. /lovevet -->

</body>
</html>