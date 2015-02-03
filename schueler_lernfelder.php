
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
				<h1 class="ui-title" role="heading" aria-level="1">Lernfelder</h1>
				<a class="ui-btn-left ui-btn ui-icon-back ui-btn-icon-notext ui-shadow ui-corner-all" data-rel="back" href="trainer_studentlist.php" data-role="button" role="button">Back</a>
			
			</div>
			
			<div class="ui-content" role="main">

				<div data-role="collapsible">
				<h2>Installiere eine Badewanne</h2>
				    <ul data-role="listview" data-filter="true">
				        <li data-icon="alert" class="example-alert"><a href="schueler_example.php">Ich kann Badewannen aufbauen (Aufgabe abgegeben aber falsch)</a></li>
				        <li data-icon="eye"><a href="schueler_example.php">Ich kann Badewannen anschliessen (noch offen)</a></li>
				        <li data-icon="check" class="example-done"><a href="schueler_example.php">Ich kann den Duschkopf montieren (gel&ouml;st)</a></li>
				    </ul>
				</div>
				
				<div data-role="collapsible">
				<h2>Installiere einen Waschtisch</h2>
				    <ul data-role="listview" data-filter="true">
				        <li data-icon="alert" class="example-alert"><a href="schueler_example.php">Ich kann den Waschtisch aufbauen (Aufgabe abgegeben aber falsch)</a></li>
				        <li data-icon="eye"><a href="schueler_example.php">Ich kann den Waschtisch anschliessen (noch offen)</a></li>
				        <li data-icon="check" class="example-done"><a href="schueler_example.php">Ich kann den Wasserhahn montieren (gel&ouml;st)</a></li>
				    </ul>
				</div>
				


				
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