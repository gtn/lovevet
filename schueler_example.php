
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

		<?php 
		require_once('./curl.php');
		$itemid = 0;
		if(isset($_GET['itemid']) && $_GET['itemid'] != -1)
		    $itemid = $_GET['itemid'];
		
		session_start();
		$mdl_token = $_SESSION['mdl_token'];
		$exacomp_token = $_SESSION['exacomp_token'];
		$exaport_token = $_SESSION['exaport_token'];
		
		if(isset($_POST['url'])) {
		    $curl = new curl;
		    $properties = parse_ini_file("properties.ini");
		
		    /// UPLOAD PARAMETERS
		    //Note: check "Maximum uploaded file size" in your Moodle "Site Policies".
		    $imagepath = './image_to_upload.jpg';
		    $filepath = '/'; //put the file to the root of your private file area. //OPTIONAL
		    /// UPLOAD IMAGE - Moodle 2.1 and later
		    $params = array('file_box' => "@".$imagepath,'filepath' => $filepath, 'token' => $mdl_token);
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_HEADER, 0);
		    curl_setopt($ch, CURLOPT_VERBOSE, 0);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
		    curl_setopt($ch, CURLOPT_URL, $properties["url"] . 'webservice/upload.php');
		    curl_setopt($ch, CURLOPT_POST, true);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		    $response = curl_exec($ch);
		
		    $jsonresponse = json_decode($response);
		
		    $curl = new curl;
		    $serverurl = $properties["url"].$properties["webserviceurl"]."?wstoken=".$exacomp_token."&wsfunction=";
		    //get topics
		    $function = "block_exacomp_submit_example";
		    $params = new stdClass();
		    $params->exampleid = $_GET['exampleid'];
		    $params->studentvalue = $_POST['studentvalue'];
		    $params->url = $_POST['url'];
		    $params->effort = $_POST['effort'];
		    $params->filename = $jsonresponse[0]->filename;
		    $params->studentcomment = $_POST['studentcomment'];
		    $params->title = 'dummytitle';
		    $params->itemid = $itemid;
		    $params->courseid = $_GET['courseid'];
		
		    $resp_xml = $curl->get($serverurl.$function."&moodlewsrestformat=json", $params);
			print_r($resp_xml);
		    $resp = json_decode($resp_xml);
		    $itemid = $resp->itemid;
		}
		?>
		<div class="ui-panel-wrapper">
		
			<div class="ui-header ui-bar-inherit" data-role="header" role="banner">
				<h1 class="ui-title" role="heading" aria-level="1">Aufgaben</h1>
				<a class="ui-btn-left ui-btn ui-icon-back ui-btn-icon-notext ui-shadow ui-corner-all" data-rel="back" href="trainer_studentlist.php" data-role="button" role="button">Back</a>
			
			</div>
			<form method="POST" action="schueler_example.php?exampleid=<?php echo $_GET['exampleid']?>&courseid=<?php echo $_GET['courseid']?>&itemid=<?php echo $itemid?>">
			<div class="ui-content" role="main">
				<ul data-role="listview" data-inset="true">
			    <li data-role="list-divider">Aufgabe</li>
			    <li>
				<?php 
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
						$externaltask = "";
                        foreach($single as $key){
                            foreach($key as $attributes){
                                foreach($attributes as $attribute){
                                    if(strcmp($attribute["@attributes"]["name"], "title")==0)
                                        $title = $attribute["VALUE"];
                                    else if(strcmp($attribute["@attributes"]["name"], "description") == 0)
                                        $description = $attribute["VALUE"];
                                    else if(strcmp($attribute["@attributes"]["name"], "task")==0)
                                        $task = $attribute["VALUE"];
									else if(strcmp($attribute["@attributes"]["name"], "externaltask"==0))
										$externaltask = $attribute["VALUE"];
                                }
                            }
                        }
                        echo '<h2>'.$title.'</h2>';
                        echo '<p>'.$description.'</p>';
                        echo '</li>';
                        if(!empty($task)){
                             echo '<li><a href="'.$task.'"><h2>'.$task.'</h2></a></li>';   
                        }
						if(!empty($externaltask) && !is_array($externaltask)){
                             echo '<li><a href="'.$externaltask.'"><h2>'.$externaltask.'</h2></a></li>';   
                        }
                    }
				?>
				
				<?php if($itemid == 0) { ?>
			    </li>
			    <li data-role="list-divider">Lernprodukt</li>
			    <li>	
					<label for="file">File:</label>
					<input name="file" id="file" value="" type="file">
			    </li>
			    <li>
			    	<label for="text-basic">Weblink:</label>
					<input name="url" id="text-basic" value="" type="text">
			    </li>
			    <li>
			    	<label for="text-basic">Aufwand:</label>
					<input name="effort" id="text-basic" value="" type="text">
				</li>
				<li>
					<label for="textarea">Kommentar:</label>
					<textarea cols="40" rows="8" name="studentcomment" id="textarea"></textarea>
				</li>
				 <li data-role="list-divider">Selbsteinsch&auml;tzung</li>
				 <li>
					<label for="slider-fill">Selbsteinsch&auml;tzung:</label>
					<input name="studentvalue" id="slider-fill" value="<?php echo $student_self_evaluation;?>" min="0" max="100" step="1" data-highlight="true" type="range">
				 </li>
				<?php } else {
				        $userid = 0;
				    
				        $curl = new curl;
				        $properties = parse_ini_file("properties.ini");
				        $serverurl = $properties["url"].$properties["webserviceurl"]."?wstoken=".$exacomp_token."&wsfunction=";
				        //get topics
				        $function = "block_exacomp_get_item_for_example";
				        $params = new stdClass();
				        $params->userid = $userid;
				        $params->itemid = $itemid;
				    
				        $resp_xml = $curl->get($serverurl.$function."&moodlewsrestformat=json", $params);
                        $resp = json_decode($resp_xml);				    
				?>
				</li>
			    <li data-role="list-divider">Lernprodukt</li>
			    <li>	
					<label for="file">File:</label>
					<input name="file" id="file" value="" type="file">
					<img src="<?php echo $resp->file.'&token='.$mdl_token;?>" width="70px">
			    </li>
			    <li>
			    	<label for="text-basic">Weblink:</label>
					<input name="url" id="text-basic" value="<?php echo $resp->url; ?>" type="text">
			    </li>
			    <li>
			    	<label for="text-basic">Aufwand:</label>
					<input name="effort" id="text-basic" value="<?php echo $resp->effort; ?>" type="text">
				</li>
				<li>
					<label for="textarea">Kommentar:</label>
					<textarea cols="40" rows="8" name="studentcomment" id="textarea"><?php echo $resp->studentcomment; ?></textarea>
				</li>
				<li>
					<label for="textarea">Status:</label>
					<input disabled name="effort" id="text-basic" value="<?php 
					if($resp->status == 0) echo "Noch offen";
					elseif($resp->status == 1) echo "Abgegeben, Überarbeitung erfolderlich";
					elseif($resp->status == 2) echo "Gelöst"; ?>" type="text">
				</li>
				 <li data-role="list-divider">Selbsteinsch&auml;tzung</li>
				 <li>
					<label for="slider-fill">Selbsteinsch&auml;tzung:</label>
					<input name="studentvalue" id="slider-fill" min="0" max="100" step="1" data-highlight="true" type="range" value="<?php echo $resp->studentvalue;?>">
				 </li>
				 <?php if($resp->status > 0) {?>
				 <li data-role="list-divider">Lehrerbeurteilung</li>
				 <li>
					<label for="slider-fill">Lehrereinsch&auml;tzung:</label>
					<input disabled name="studentvalue" id="slider-fill" min="0" max="100" step="1" data-highlight="true" type="range" value="<?php echo $resp->teachervalue;?>">
				 </li>
				 <li>
					<label for="slider-fill">Lehrerkommentar:</label>
					<textarea disabled cols="40" rows="8" name="studentcomment" id="textarea"><?php echo $resp->teachercomment; ?></textarea>
				 </li>
				<?php } } ?>
					    <?php 
					        if(isset($exacomp_token) && isset($_GET['exampleid'])){
                                $example = $_GET['exampleid'];
                                $curl = new curl;
                                $properties = parse_ini_file("properties.ini");		
                                $serverurl = $properties["url"].$properties["webserviceurl"]."?wstoken=".$exacomp_token."&wsfunction=";
                                
                                //get topics
                                $function = "block_exacomp_get_descriptors_for_example";
                                $params = new stdClass();
                                $params->exampleid = $example;
                                $params->courseid = $courseid;
                                $params->userid = 0;
                                
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
    			                    
    								if($all)
                                        echo '<input name="checkbox-1aa" id="checkbox-1aa" checked="" type="checkbox" disabled="">';
                                    else 
                                        echo '<input name="checkbox-1aa" id="checkbox-1aa" type="checkbox" disabled="">';
                                     
                                    echo '<label for="checkbox-1aa">Alle Kompetenzen erreicht</label>';
                                    echo '</li>';
                                    echo '<li>';
                                    echo '<fieldset data-role="controlgroup">';
                                    echo '<legend>Einzelkompetenzen:</legend>';
                                    
                                   foreach($descriptors as $descriptor){
										if($descriptor->evaluation == 0)
										echo '<input name="checkbox'.$descriptor->id.'" id="checkbox'.$descriptor->id.'" type="checkbox" disabled="">';
										else
										echo '<input name="checkbox'.$descriptor->id.'" id="checkbox'.$descriptor->id.'" checked="" type="checkbox" disabled="">';

										echo '<label for="checkbox'.$descriptor->id.'">'.$descriptor->title.'</label>';
									}
                                    echo '</fieldset>';
								}
								else{
								     echo '<li data-role="list-divider">Diesem Beispiel sind keine Kompetenzen zugeordnet.</li>';
								}
                            }
					    ?>
		        </li>
		        
		        <?php if($resp->status != 0) { ?>
		            <script type="text/javascript">
		            $(":input").prop("disabled", true);
		            $("#new").prop("disabled", false);
		            $('#new').click(function( event ) {
		            	  event.preventDefault();
		            	   window.location = "/lovevet_dynamisch/schueler_example.php?exampleid=<?php echo $_GET['exampleid']?>&courseid=<?php echo $_GET['courseid']?>";
		            	});
		            </script>
		            <li class="ui-body ui-body-b">
		            <fieldset class="ui-grid-a">
		                    <div><button id="new" class="ui-btn ui-corner-all ui-btn-a">Erneut abgeben</button></div>
		            </fieldset>
		        </li>
		        <?php } else { ?>
				<li class="ui-body ui-body-b">
		            <fieldset class="ui-grid-a">
		                    <div class="ui-block-a"><button type="submit" class="ui-btn ui-corner-all ui-btn-a">Cancel</button></div>
		                    <div class="ui-block-b"><button type="submit" class="ui-btn ui-corner-all ui-btn-a">Save</button></div>
		            </fieldset>
		        </li>
		        <?php } ?>
			</ul>
	
			</form>	
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