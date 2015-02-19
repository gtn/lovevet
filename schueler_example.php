
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
				<h1 class="ui-title" role="heading" aria-level="1">Aufgaben</h1>
				<a class="ui-btn-left ui-btn ui-icon-back ui-btn-icon-notext ui-shadow ui-corner-all" data-rel="back" href="trainer_studentlist.php" data-role="button" role="button">Back</a>
			
			</div>
			
			<div class="ui-content" role="main">
				<ul data-role="listview" data-inset="true">
			    <li data-role="list-divider">Aufgabe</li>
			    <li>
				<?php 
				    require_once('./curl.php');
				    
				    session_start();
                    $exacomp_token = $_SESSION['exacomp_token'];
                    $exaport_token = $_SESSION['exaport_token'];
                    //echo $exacomp_token;
                    $student_self_evaluation = 0;
                    if(isset($exacomp_token) && isset($_GET['exampleid'])){
                        $example = $_GET['exampleid'];
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
                        foreach($single as $key){
                            foreach($key as $attributes){
                                foreach($attributes as $attribute){
                                    if(strcmp($attribute["@attributes"]["name"], "title")==0)
                                        $title = $attribute["VALUE"];
                                    else if(strcmp($attribute["@attributes"]["name"], "description") == 0)
                                        $description = $attribute["VALUE"];
                                    else if(strcmp($attribute["@attributes"]["name"], "studentvalue")==0)
                                        $student_self_evaluation = $attribute["VALUE"];
                                }
                            }
                        }
                        echo '<h2>'.$title.'</h2>';
                        echo '<p>'.$description.'</p>'; 
                    }
				?>
			   	</li>
			   	
			    <li><a href="#">
			    <h2>Video.avi</h2>
			    </a>
			    </li>
			    <li><a href="#">
			    <h2>Bild.jpg</h2>
			    </a>
			    </li>
			    <li><a href="#">
			    <h2>www.exabis.at</h2>
			    </a>
			    </li>
			    </li>
			    <li data-role="list-divider">Lernprodukt</li>
			    <li>	
					<label for="file">File:</label>
					<input name="file" id="file" value="" type="file">
			    </li>
			    <li>
			    	<label for="text-basic">Weblink:</label>
					<input name="text-basic" id="text-basic" value="" type="text">
			    </li>
			    <li>
			    	<label for="text-basic">Aufwand:</label>
					<input name="text-basic" id="text-basic" value="" type="text">
				</li>
				<li>
					<label for="textarea">Kommentar:</label>
					<textarea cols="40" rows="8" name="textarea" id="textarea"></textarea>
				</li>
				 <li data-role="list-divider">Selbsteinsch&auml;tzung</li>
				 <li>
					<label for="slider-fill">Selbsteinsch&auml;tzung:</label>
					<input name="slider-fill" id="slider-fill" value="<?php echo $student_self_evaluation;?>" min="0" max="100" step="1" data-highlight="true" type="range">
				 </li>
				
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
                                
                                $resp_xml = $curl->get($serverurl.$function, $params);
                                $xml = simplexml_load_string($resp_xml);
                                $json = json_encode($xml);
                                $multiple = json_decode($json,TRUE);
                               
                                $descriptors = array();
                                $all = true;
                                $current_id = 0;
                                //TODO mehrere deskriptoren zu einem beispiel
                                foreach($multiple as $single){
                                    foreach($single as $key){
                                        foreach($key as $attributes){
                                            foreach($attributes as $attribute){
                                                if(strcmp($attribute["@attributes"]["name"], "descriptorid")==0){
                                                    $descriptors[$attribute["VALUE"]] = new stdClass();
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
								
								$count = count($descriptors);
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
                                        echo '<input name="checkbox-1a" id="checkbox-1a" type="checkbox" disabled="">';
                                    else 
                                        echo '<input name="checkbox-1a" id="checkbox-1a" checked="" type="checkbox" disabled="">';
                                    
                                    echo '<label for="checkbox-1a">'.$descriptor->title.'</label>';
                                }
                                echo '</fieldset>';
                            }
					    ?>
		        </li>
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
			            <li><a class="ui-link ui-btn" href="schueler_timeline.php">Timeline</a></li>
			        </ul>
			    </div><!-- /navbar -->
			</div><!-- /footer -->

	
		
	
	</div><!.. /lovevet -->

</body>
</html>