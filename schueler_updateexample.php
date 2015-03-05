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
				<h1 class="ui-title" role="heading" aria-level="1">Eigener Beitrag</h1>
				<a class="ui-btn-left ui-btn ui-icon-back ui-btn-icon-notext ui-shadow ui-corner-all" data-rel="back" href="trainer_studentlist.php" data-role="button" role="button">Back</a>
				
			</div>
		
			<div class="ui-content" role="main">
				
				<?php 
                require_once('./curl.php');
                
                session_start();
                $exacomp_token = $_SESSION['exacomp_token'];
                $exaport_token = $_SESSION['exaport_token'];
                $mdl_token=$_SESSION['mdl_token'];
				
				$example_title = "";
				$example_description = "";
				$example_task = "";
				$example_externaltask = "";
                //echo $exacomp_token;
                if(isset($exacomp_token) && isset($_GET['exampleid'])){
                    (isset($_GET['save'])&& $_GET['save']==1)?$save=1:$save=0;
					(isset($_GET['status']))?$status=$_GET['status']:$status=-1;
                    $exampleid = $_GET['exampleid'];
                    
					$disabled = "";
					if($status==0 || $status==-1)
						$disabled = 'disabled=""';
						
                    $curl = new curl;
                        
                    $properties = parse_ini_file("properties.ini");		
                    $serverurl = $properties["url"].$properties["webserviceurl"]."?wstoken=".$exacomp_token."&wsfunction=";
                        
                    if($save == 1){
                        //name, description & competencies for example
                        if(isset($_POST['name']) && isset($_POST['description'])){
                            
                            $name = $_POST['name'];
                            $description = $_POST['description'];
                            
                            $task = $_POST['task'];
                            
                            $comps = "";
                            if(isset($_POST['comps_select'])){
                                $comps_array = $_POST['comps_select'];
                                foreach($comps_array as $id){
                                    $comps .= $id.",";
                                }
                                $comps = substr($comps, 0, strlen($comps)-1);
                            }
                            
                            $function = "block_exacomp_update_example";
                            $params = new stdClass();
                            $params->exampleid = $exampleid;
                            $params->name = $name;
                            $params->description = $description;
                            $params->task = $task;
                            $params->comps = $comps;
                            $params->filename = 0;

                            $resp_xml = $curl->get($serverurl.$function, $params);
					
                            $xml = simplexml_load_string($resp_xml);
                            $json = json_encode($xml);
                            $single = json_decode($json,TRUE);
                            /*foreach($single as $key){
                                foreach($key as $attribute){
                                   if(strcmp($attribute["@attributes"]["name"], "exampleid")==0){
                                        $id = $attribute["VALUE"];
                                    }
                                }
                            }*/
                        }
                        
                    }	
					$function = "block_exacomp_get_example_by_id";
					$params = new stdClass();
					$params->exampleid = $exampleid;
					
					$resp_xml = $curl->get($serverurl.$function, $params);
					$xml = simplexml_load_string($resp_xml);
					$json = json_encode($xml);
					$single = json_decode($json,TRUE);
				   
					foreach($single as $key){
						foreach($key as $attributes){
							foreach($attributes as $attribute){
								if(strcmp($attribute["@attributes"]["name"], "title")==0)
									$example_title = $attribute["VALUE"];
								else if(strcmp($attribute["@attributes"]["name"], "description") == 0)
									$example_description = $attribute["VALUE"];
								else if(strcmp($attribute["@attributes"]["name"], "task")==0)
									$example_task = $attribute["VALUE"];
								else if(strcmp($attribute["@attributes"]["name"], "externaltask")==0)
									$example_externaltask = $attribute["VALUE"];
							}
						}
					}
					
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
                        foreach($single as $keys){
                            foreach($keys as $key=>$value){
								//different results from webservice
								if(strcmp($key, "KEY")==0){
									foreach($value as $attribute){
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
                                }else{
                                    foreach($value as $attributes){
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
                                }
                            }
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
                                foreach($keys as $key=>$value){
    								//different results from webservice
    								if(strcmp($key, "KEY")==0){
    									foreach($value as $attribute){
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
                                    }else{
                                        foreach($value as $attributes){
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
				<form action="schueler_updateexample.php?save=1&exampleid=<?php echo $exampleid?>"; method = "post">
				
				<ul data-role="listview" data-inset="true">
			    <li data-role="list-divider">Aufgabe</li>
			    <li>
			    	<label for="name">Name:</label>
					<input name="name" id="text-basic" <?php echo $disabled; ?> value="<?php echo $example_title; ?>" type="text">
			    </li>
			    <li>
					<label for="description">Beschreibung:</label>
					<textarea cols="40" rows="8" name="description" <?php echo $disabled; ?> id="textarea"><?php echo $example_description; ?></textarea>
				</li>
			    <li>
			    	<label for="task">Weblink:</label>
					<input name="task" id="text-basic" <?php echo $disabled; ?> value="<?php echo $example_task;?>" type="text">
			    </li>
			    <li data-role="list-divider">Kompetenzen<span class="ui-li-count">3</span></li>
			    <li>
					<label for="comps_select" class="select">Zugeordnete Kompetenzen:</label>
					<?php
					//get topics
						$function = "block_exacomp_get_descriptors_for_example";
						$params = new stdClass();
						$params->exampleid = $exampleid;
						$params->courseid = 0;
						$params->userid = 0;
						
						$resp_xml = $curl->get($serverurl.$function, $params);
						$xml = simplexml_load_string($resp_xml);
						$json = json_encode($xml);
						$multiple = json_decode($json,TRUE);
					   
						$descriptors_act = array();
						$all = true;
						$current_id = 0;
						foreach($multiple as $single){
							foreach($single as $keys){
								foreach($keys as $key=>$value){
									//different results from webservice
									if(strcmp($key, "KEY")==0){
										foreach($value as $attribute){
											if(strcmp($attribute["@attributes"]["name"], "descriptorid")==0){
												$descriptors_act[$attribute["VALUE"]] = new stdClass();
												$descriptors_act[$attribute["VALUE"]]->id = $attribute["VALUE"];
												$current_id = $attribute["VALUE"];
											}else if(strcmp($attribute["@attributes"]["name"], "title") == 0)
												$descriptors_act[$current_id]->title = $attribute["VALUE"];
										}
									}else{
										foreach($value as $attributes){
											foreach($attributes as $attribute){
												if(strcmp($attribute["@attributes"]["name"], "descriptorid")==0){
													$descriptors_act[$attribute["VALUE"]] = new stdClass();
													$descriptors_act[$attribute["VALUE"]]->id = $attribute["VALUE"];
													$current_id = $attribute["VALUE"];
												}else if(strcmp($attribute["@attributes"]["name"], "title") == 0)
													$descriptors_act[$current_id]->title = $attribute["VALUE"];
											}
										}
									}
								}
							}
						}
						
						$count = count($descriptors_act);
						
						if($count > 0){
							echo '<li data-role="list-divider">Kompetenzen die diesem Beispiel zugeordnet sind <span class="ui-li-count">'.$count.'</span></li>';
						
							echo '<li>';
							echo '<fieldset data-role="controlgroup">';
							echo '<legend>Einzelkompetenzen:</legend>';
							
						   foreach($descriptors_act as $descriptor){
								echo '<input name="checkbox'.$descriptor->id.'" id="checkbox'.$descriptor->id.'" checked="" type="checkbox" disabled="">';

								echo '<label for="checkbox'.$descriptor->id.'">'.$descriptor->title.'</label>';
							}
							echo '</fieldset>';
						}
						else{
							 echo '<li data-role="list-divider">Diesem Beispiel sind keine Kompetenzen zugeordnet.</li>';
						}
					?>
					
					
					<select name="comps_select[]" <?php echo $disabled; ?> id="comps_select" multiple="multiple" data-native-menu="false" data-icon="grid" data-iconpos="left">
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
				<li class="ui-body ui-body-b">
		            <fieldset class="ui-grid-a">
		                    <div class="ui-block-a"><button type="submit" class="ui-btn ui-corner-all ui-btn-a">Cancel</button></div>
		                    <div class="ui-block-b"><button type="submit" class="ui-btn ui-corner-all ui-btn-a">Update</button></div>
		            </fieldset>
		        </li>
			</ul>
			
				
	
	
			</div><!-- /ui-content -->
		</div><!-- /ui-panel-wrapper -->
		</form>
		
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