
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

	


	
			<ul data-role="listview" data-inset="true">
			    <li data-role="list-divider">Aufgabe</li>
			    <li>
			    
			    <?php 
				    require_once('./curl.php');
				    
				    session_start();
                    $exacomp_token = $_SESSION['exacomp_token'];
                    $exaport_token = $_SESSION['exaport_token'];
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
                    
                    //TODO item auslesen und anzeigen
				?>
				
			    <li>
				    <h2>Kommentar:</h2>
				    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. </p>
				</li>
			    <li data-role="list-divider">Beispiel gel&ouml;st</li>
			    <li>
					<label for="slider-fill">Beispiel gel&ouml;st:</label>
					<input name="slider-fill" id="slider-fill" value="0" min="0" max="100" step="1" data-highlight="true" type="range">
				 </li>
			    <li>
			    	<label for="textarea">Kommentar:</label>
					<textarea cols="40" rows="8" name="textarea" id="textarea"></textarea>
			    </li>
			     <?php 
					        if(isset($exacomp_token) && isset($_GET['exampleid'])){
                                $example = $_GET['exampleid'];
                                $userid = $_GET['u'];
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
								
								if($count > 0){
                                    echo '<li data-role="list-divider">Kompetenzen die diesem Beispiel zugeordnet sind <span class="ui-li-count">'.$count.'</span></li>';
    			                    echo '<li>';
    			                    
    								if($all)
                                        echo '<input name="checkbox-1aa" id="checkbox-1aa" checked="" type="checkbox">';
                                    else 
                                        echo '<input name="checkbox-1aa" id="checkbox-1aa" type="checkbox">';
                                     
                                    echo '<label for="checkbox-1aa">Alle Kompetenzen erreicht</label>';
                                    echo '</li>';
                                    echo '<li>';
                                    echo '<fieldset data-role="controlgroup">';
                                    echo '<legend>Einzelkompetenzen:</legend>';
                                    
                                    foreach($descriptors as $descriptor){
                                        if($descriptor->evaluation == 0)
                                            echo '<input name="checkbox-1a" id="checkbox-1a" type="checkbox">';
                                        else 
                                            echo '<input name="checkbox-1a" id="checkbox-1a" checked="" type="checkbox">';
                                        
                                        echo '<label for="checkbox-1a">'.$descriptor->title.'</label>';
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
			

				
				

				
			</div><!-- /ui-content -->
			
		</div><!-- /ui-panel-wrapper -->
	
			<div class="ui-footer ui-bar-a ui-footer-fixed slideup" data-theme="a" data-position="fixed" data-role="footer" role="contentinfo">
			    <div data-role="navbar">
			        <ul>
			            <li><a class="ui-btn-active ui-state-persist ui-link ui-btn" href="trainer_teilbereich.php">Teilgebiet</a></li>
			            <li><a class="ui-link ui-btn" href="trainer_compprofile.php">Kompetenzprofil</a></li>
			            <li><a class="ui-link ui-btn" href="trainer_report.php">Berichtsheft</a></li>
			        </ul>
			    </div><!-- /navbar -->
			</div><!-- /footer -->

	
		
	
	</div><!.. /lovevet -->

</body>
</html>