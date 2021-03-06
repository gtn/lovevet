
<!DOCTYPE html>
<html>
<head>
<title>Lovevet</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"
	href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
<link rel="stylesheet" href="css/custom.css" />
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script
	src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
</head>

<body>

	<div data-role="page" id="lovevet">

		<div class="ui-panel-wrapper">

			<div class="ui-header ui-bar-inherit" data-role="header"
				role="banner">
				<h1 class="ui-title" role="heading" aria-level="1">Kompetenzprofil</h1>
				<a
					class="ui-btn-left ui-btn ui-icon-back ui-btn-icon-notext ui-shadow ui-corner-all"
					data-rel="back" href="trainer_studentlist.php" data-role="button"
					role="button">Back</a>

			</div>

			<div class="ui-content" role="main">


				<!-- ><img src="images/comp.jpg" />  -->
				<?php 
				require_once('./curl.php');

				session_start();
				$exacomp_token = $_SESSION['exacomp_token'];


				//echo $exacomp_token;
				if(isset($exacomp_token)){
				    $curl = new curl;
				    //header('Content-Type: text/plain');

				    $properties = parse_ini_file("properties.ini");

				    //get topics
				    $serverurl = $properties["url"].$properties["webserviceurl"]."?wstoken=".$exacomp_token."&wsfunction=";
				    $function = "block_exacomp_get_user_profile";

				    $params = new stdClass();
				    $params->userid = 0;

				    $resp = $curl->get($serverurl.$function."&moodlewsrestformat=json", $params);

				    $resp = json_decode($resp);
				    foreach($resp as $r) {
				        echo $r->title . ": ";
				        echo "<progress value='".$r->reachedcomps."' max='".$r->totalcomps."'></progress>";
				        echo "<br/>";
				    }
				}
				?>



			</div>
			<!-- /ui-content -->

		</div>
		<!-- /ui-panel-wrapper -->

		<div class="ui-footer ui-bar-a ui-footer-fixed slideup" data-theme="a"
			data-position="fixed" data-role="footer" role="contentinfo">
			<div data-role="navbar">
				<ul>
					<li><a class="ui-link ui-btn" href="schueler_examples.php">Teilgebiete</a>
					</li>
					<li><a class="ui-btn-active ui-state-persist ui-link ui-btn"
						href="schueler_compprofile.php">Kompetenzprofil</a></li>
					<li><a class="ui-link ui-btn" href="schueler_settings.php">Einstellungen</a>
					</li>
				</ul>
			</div>
			<!-- /navbar -->
		</div>
		<!-- /footer -->




	</div>
	<!.. /lovevet -->

</body>
</html>
