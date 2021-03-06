
<!DOCTYPE html> 
<html>
<head>
	<title>Lovevet</title>
	<meta charset="UTF-8">
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
				<h1 class="ui-title" role="heading" aria-level="1">Max Mustermann</h1>
				<a class="ui-btn-left ui-btn ui-icon-back ui-btn-icon-notext ui-shadow ui-corner-all" data-rel="back" href="trainer_studentlist.php" data-role="button" role="button">Back</a>
			
			</div>
			
			<div class="ui-content" role="main">
            <form method="POST" action="trainer_example.php?exampleid=<?php echo $_GET['exampleid']?>&courseid=<?php echo $_GET['courseid']?>&userid=<?php echo $_GET['userid']?>&itemid=<?php echo $_GET['itemid']?>">
			
			<ul data-role="listview" data-inset="true">
			    <li data-role="list-divider">Aufgabe</li>
			    <li>
			    
			    <?php 
				    require_once('./curl.php');
				    
				    session_start();
                    $exacomp_token = $_SESSION['exacomp_token'];
                    $exaport_token = $_SESSION['exaport_token'];
                    $mdl_token = $_SESSION['mdl_token'];
                    //echo $exacomp_token;
                    //TODO: 
                    $student_self_evaluation = 0;
                    
                    if(isset($exacomp_token) && isset($_GET['exampleid'])){
                        $example = $_GET['exampleid'];
                        $courseid = $_GET['courseid'];
                        $curl = new curl;
                        $properties = parse_ini_file("properties.ini");		
                        $serverurl = $properties["url"].$properties["webserviceurl"]."?wstoken=".$exacomp_token."&wsfunction=";
                        
                        //get topics
                        $function = "block_exacomp_get_example_by_id";
                        $params = new stdClass();
                        $params->exampleid = $example;
                        
                        $resp_xml = $curl->get($serverurl.$function, $params);
                        $xml = simplexml_load_string($resp_xml);
                        $json = json_encode($xml);
                        $single = json_decode($json,TRUE);
                       
                        $title = "";
                        $description = "";
                        $task = "";
                        foreach($single as $key){
                            foreach($key as $attributes){
                                foreach($attributes as $attribute){
                                    if(strcmp($attribute["@attributes"]["name"], "title")==0)
                                        $title = $attribute["VALUE"];
                                    else if(strcmp($attribute["@attributes"]["name"], "description") == 0)
                                        $description = $attribute["VALUE"];
                                    else if(strcmp($attribute["@attributes"]["name"], "task")==0)
                                        $task = $attribute["VALUE"];
                                }
                            }
                        }
                        echo '<h2>'.$title.'</h2>';
                        echo '<p>'.$description.'</p>';
                        echo '</li>';
                        if(!empty($task)){
                             echo '<li><a href="'.$task.'"><h2>'.$task.'</h2></a></li>';   
                        }
                    }
                    if(!empty($_POST)) {
                        //GRADE ITEM
                        $curl = new curl;
                        $serverurl = $properties["url"].$properties["webserviceurl"]."?wstoken=".$exacomp_token."&wsfunction=";
                        //get topics
                        $function = "block_exacomp_grade_item";
                        $params = new stdClass();
                        $params->userid = $_GET['userid'];
                        $params->value = $_POST['teachervalue'];
                        $params->comment = $_POST['teachercomment'];
                        $params->itemid = $_GET['itemid'];
                        $params->status = (isset($_POST['status'])) ? 2 : 1;
                        if(isset($_POST['descriptors'])){
                            $comps_array = $_POST['descriptors'];
                            $comps = "";
                            foreach($comps_array as $id){
                                $comps .= $id.",";
                            }
                            $comps = substr($comps, 0, strlen($comps)-1);
                            $params->comps = $comps;
                        }
                        else{
                            $params->comps = "";
                        }
                        
                        $params->courseid = $_GET['courseid'];
                        $resp_xml = $curl->get($serverurl.$function."&moodlewsrestformat=json", $params);
                    }
                    
                    $itemid = $_GET['itemid'];
                    $userid = $_GET['userid'];
                    
                    if($itemid > 0) {
                        $curl = new curl;
                        $properties = parse_ini_file("properties.ini");
                        $serverurl = $properties["url"].$properties["webserviceurl"]."?wstoken=".$exacomp_token."&wsfunction=";
                    
                        //get topics
                        $function = "block_exacomp_get_item_for_example";
                        $params = new stdClass();
                        $params->itemid = $itemid;
                        $params->userid = $userid;
                    
                        $resp_xml = $curl->get($serverurl.$function.'&moodlewsrestformat=json', $params);
                        $item = json_decode($resp_xml,TRUE);
                    }
                    ?>
                    				<li data-role="list-divider">Lernprodukt</li>
                    				
                    				<li>
                    				    <h2>File:</h2>
                    				    <p><?php echo $item['filename']; ?> </p>
                    				    <img src="<?php echo $item['file'].'&token='.$mdl_token;?>" width="70px">
                    				    
                    				</li>
                    				<li>
                    				    <h2>Link:</h2>
                    				    <p><?php echo $item['url']; ?> </p>
                    				</li>
                    				<li>
                    				    <h2>Aufwand:</h2>
                    				    <p><?php echo $item['effort']; ?> </p>
                    				</li>
                    				<li>
                    					<h2>Schülereinschätzung:</h2>
                    					<input disabled name="slider-fill" id="slider-fill" value="<?php echo $item['studentvalue'];?>" min="0" max="100" step="1" data-highlight="true" type="range">
                    				 </li>
                    			    <li>
                    				    <h2>Kommentar:</h2>
                    				    <p><?php echo $item['studentcomment']; ?></p>
                    				</li>
                    			    <li data-role="list-divider">Beispiel gel&ouml;st</li>
                    			    <li>
                    					<label for="flip-checkbox">Beispiel gel&ouml;st:</label>
                                        <input type="checkbox" data-role="flipswitch" name="status" id="flip-checkbox" data-on-text="Ja" data-off-text="Nein" data-wrapper-class="custom-label-flipswitch" <?php if($item['status'] == 2) echo "checked";?>>
                    					<input name="teachervalue" id="slider-fill" value="<?php echo $item['teachervalue'];?>" min="0" max="100" step="1" data-highlight="true" type="range">
                    				 </li>
                    			    <li>
                    			    	<label for="textarea">Kommentar:</label>
                    					<textarea cols="40" rows="8" name="teachercomment" id="textarea"><?php echo $item['teachercomment'];?></textarea>
                    			    </li>
                    			   
			     <?php 
					        if(isset($exacomp_token) && isset($_GET['exampleid'])){
                                $example = $_GET['exampleid'];
                                $userid = $_GET['userid'];
                                $curl = new curl;
                                $properties = parse_ini_file("properties.ini");		
                                $serverurl = $properties["url"].$properties["webserviceurl"]."?wstoken=".$exacomp_token."&wsfunction=";
                                
                                //get topics
                                $function = "block_exacomp_get_descriptors_for_example";
                                $params = new stdClass();
                                $params->exampleid = $example;
                                $params->courseid = $courseid;
                                $params->userid = $userid;
                                
                                $resp_xml = $curl->get($serverurl.$function, $params);
                                $xml = simplexml_load_string($resp_xml);
                                $json = json_encode($xml);
                                $multiple = json_decode($json,TRUE);
               
                                $descriptors = array();
                                $all = true;
                                $current_id = 0;
                                foreach($multiple as $single){
									foreach($single as $keys){
										foreach($keys as $key=>$value){
											//different results from webservice
											if(strcmp($key, "KEY")==0){
												foreach($value as $attribute){
													if(strcmp($attribute["@attributes"]["name"], "descriptorid")==0){
														$descriptors[$attribute["VALUE"]] = new stdClass();
														$descriptors[$attribute["VALUE"]]->id = $attribute["VALUE"];
														$current_id = $attribute["VALUE"];
													}else if(strcmp($attribute["@attributes"]["name"], "title") == 0)
													$descriptors[$current_id]->title = $attribute["VALUE"];
													else if(strcmp($attribute["@attributes"]["name"], "evaluation")==0){
														$descriptors[$current_id]->evaluation = $attribute["VALUE"];
														if($attribute["VALUE"]==0) $all = false;
													}
												}
											}else{
												foreach($value as $attributes){
													foreach($attributes as $attribute){
														if(strcmp($attribute["@attributes"]["name"], "descriptorid")==0){
															$descriptors[$attribute["VALUE"]] = new stdClass();
															$descriptors[$attribute["VALUE"]]->id = $attribute["VALUE"];
															$current_id = $attribute["VALUE"];
														}else if(strcmp($attribute["@attributes"]["name"], "title") == 0)
														$descriptors[$current_id]->title = $attribute["VALUE"];
														else if(strcmp($attribute["@attributes"]["name"], "evaluation")==0){
															$descriptors[$current_id]->evaluation = $attribute["VALUE"];
															if($attribute["VALUE"]==0) $all = false;
														}
													}
												}
											}
										}
									}
								}
								
								$count = count($descriptors);
								
								if($count > 0){
                                    echo '<li data-role="list-divider">Kompetenzen die diesem Beispiel zugeordnet sind <span class="ui-li-count">'.$count.'</span></li>';
    			                    echo '<li>';
    			                    
    			                    ?>

                                    <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('#check-all').click(function(){
                                          $(".label_for_checkbox").addClass('ui-checkbox-on');
                                          $(".descr_cb").prop('checked', true);
                                          $(".descr_cb").prop('data-cachecal', false);
                                        });
                                      });
                                     </script>
    			                    <?php 
    			                   
    								if($all)
                                        echo '<input name="all_comps" id="check-all" onClick="check-all()" "checked="" type="checkbox">';
                                    else 
                                        echo '<input name="all_comps" id="check-all" onClick="javascript:void(0);" type="checkbox">';
                                     
                                    echo '<label for="check-all">Alle Kompetenzen erreicht</label>';
                                    echo '</li>';
                                    echo '<li>';
                                    echo '<fieldset data-role="controlgroup">';
                                    echo '<legend>Einzelkompetenzen:</legend>';
                                    
                                    foreach($descriptors as $descriptor){
										if($descriptor->evaluation == 0)
										echo '<input class="descr_cb" name="descriptors[]" value="'.$descriptor->id.'" id="checkbox'.$descriptor->id.'" type="checkbox">';
										else
										echo '<input class="descr_cb" name="descriptors[]" value="'.$descriptor->id.'" id="checkbox'.$descriptor->id.'" checked="" type="checkbox">';

										echo '<label class="label_for_checkbox" for="checkbox'.$descriptor->id.'">'.$descriptor->title.'</label>';
									}
                                    echo '</fieldset>';
								}
								else{
								     echo '<li data-role="list-divider">Diesem Beispiel sind keine Kompetenzen zugeordnet.</li>';
								}
                            }
					    ?>
			    <li class="ui-body ui-body-b">
		            <fieldset class="ui-grid-a">
		                    <div class="ui-block-a"><button type="submit" class="ui-btn ui-corner-all ui-btn-a">Cancel</button></div>
		                    <div class="ui-block-b"><button type="submit" class="ui-btn ui-corner-all ui-btn-a">Save</button></div>
		            </fieldset>
		        </li>
		        
			</ul>
			
 </form>
				
				

				
			</div><!-- /ui-content -->
			
		</div><!-- /ui-panel-wrapper -->
	
			<div class="ui-footer ui-bar-a ui-footer-fixed slideup" data-theme="a" data-position="fixed" data-role="footer" role="contentinfo">
			    <div data-role="navbar">
			        <ul>
			            <li><a class="ui-btn-active ui-state-persist ui-link ui-btn" href="trainer_teilbereich.php">Teilgebiet</a></li>
			            <li><a class="ui-link ui-btn" href="trainer_compprofile.php">Kompetenzprofil</a></li>
			             <li><a class="ui-link ui-btn" href="trainer_settings.php">Einstellungen</a></li>
			        </ul>
			    </div><!-- /navbar -->
			</div><!-- /footer -->

	
		
	
	</div><!.. /lovevet -->

</body>
</html>