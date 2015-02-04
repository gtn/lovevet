<?php     
session_start();
?>
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
				<h1 class="ui-title" role="heading" aria-level="1">Auszubildende</h1>
				
			</div>
		
			<div class="ui-content" role="main">
		
				<?php                       
				require_once('./curl.php');
				
				$exaport_token = $_SESSION['exaport_token'];
				
				$curl = new curl;
				
				$properties = parse_ini_file("properties.ini");
				
				//get courses
				$serverurl = $properties["url"].$properties["webserviceurl"]."?wstoken=".$exaport_token."&wsfunction=";
				$function = "block_exaport_get_external_trainer_students";
				$resp_xml = $curl->get($serverurl.$function);
				 
				$xml = simplexml_load_string($resp_xml);
				$json = json_encode($xml);
				$multiple = json_decode($json,TRUE);

				$students = array();
				
				foreach($multiple as $single){
				    foreach($single as $keys){
				        foreach($keys as $key=>$value){
				            //different results from webservice
				            if(strcmp($key, "KEY")==0){
				                $student = new stdClass();
				                
				                foreach($value as $attribute){
    				                if(strcmp($attribute["@attributes"]["name"], "name")==0){
    				                        $student->name = $attribute["VALUE"];
    				                }
    				                if(strcmp($attribute["@attributes"]["name"], "userid")==0){
    				                    $student->id = $attribute["VALUE"];
    				                }
				                }
				                $students[] = $student;
				                
				            }else{
				                foreach($value as $attributes){
				                    $student = new stdClass();
				                    
				                    foreach($attributes as $attribute){
    				                    if(strcmp($attribute["@attributes"]["name"], "name")==0){
        				                        $student->name = $attribute["VALUE"];
        				                }
        				                if(strcmp($attribute["@attributes"]["name"], "userid")==0){
        				                    $student->id = $attribute["VALUE"];
        				                }
				                    }
				                    $students[] = $student;
				                    
				                }
				            }
				        }
				    }
				}
				?>
				<ul data-role="listview" data-count-theme="b" data-inset="true">
				    <?php 
				        foreach($students as $student)
				            echo '<li><a href="trainer_teilbereich.php?u='.$student->id.'">'.$student->name.' <span class="ui-li-count">0</span></a></li>';
				    ?>
				</ul>
	
	
			</div><!-- /ui-content -->
		</div><!-- /ui-panel-wrapper -->
		
		
		<div class="ui-footer ui-bar-a ui-footer-fixed slideup" data-theme="a" data-position="fixed" data-role="footer" role="contentinfo">
			    <div data-role="navbar">
			        <ul>
			            <li><a class="ui-btn-active ui-state-persist ui-link ui-btn" href="trainer_student.php">Sch&uuml;ler</a></li>
			            <li><a class="ui-link ui-btn" href="trainer_createexample.php">Firmen interne Beispiele</a></li>
			        </ul>
			    </div><!-- /navbar -->
			</div><!-- /footer -->
	
	
	</div><!.. /lovevet -->

</body>
</html>