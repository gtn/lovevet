
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
				<h1 class="ui-title" role="heading" aria-level="1">Eigene Beitr&auml;ge</h1>
				<a class="ui-btn-left ui-btn ui-icon-back ui-btn-icon-notext ui-shadow ui-corner-all" data-rel="back" href="trainer_studentlist.php" data-role="button" role="button">Back</a>
			
			</div>
			
			<div class="ui-content" role="main">


              
				<ul data-role="listview" data-inset="true" data-divider-theme="a">
				    <li><a href="trainer_createexample.php">Eigener Beitrag 1</a></li>
				    <li><a href="trainer_createexample.php">Eigener Beitrag 2</a></li>
				    <li><a href="trainer_createexample.php">Eigener Beitrag 3</a></li>
				</ul> 
				
				<a href="trainer_createexample.php" class="ui-shadow ui-btn ui-corner-all">Neuen Beitrag erstellen</a>

			</div><!-- /ui-content -->
			
	</div><!-- /ui-panel-wrapper -->
	
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