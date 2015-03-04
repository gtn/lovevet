
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
				<h1 class="ui-title" role="heading" aria-level="1">Eigener Beitrag</h1>
				<a class="ui-btn-left ui-btn ui-icon-back ui-btn-icon-notext ui-shadow ui-corner-all" data-rel="back" href="trainer_studentlist.php" data-role="button" role="button">Back</a>
				
			</div>
		
			<div class="ui-content" role="main">
				
				
				
				
				
				<ul data-role="listview" data-inset="true">
			    <li data-role="list-divider">Aufgabe</li>
			    <li>
			    	<label for="text-basic">Name:</label>
					<input name="text-basic" id="text-basic" value="" type="text">
			    </li>
			    <li>
					<label for="textarea">Beschreibung:</label>
					<textarea cols="40" rows="8" name="textarea" id="textarea"></textarea>
				</li>
			    <li>	
					<label for="file">File:</label>
					<input name="file" id="file" value="" type="file">
			    </li>
			    <li>
			    	<label for="text-basic">Weblink:</label>
					<input name="text-basic" id="text-basic" value="" type="text">
			    </li>
			    <li data-role="list-divider">Kompetenzen<span class="ui-li-count">3</span></li>
			    <li>
					<label for="select-choice-8" class="select">Zugeordnete Kompetenzen:</label>
					<select name="select-choice-8" id="select-choice-8" multiple="multiple" data-native-menu="false" data-icon="grid" data-iconpos="left">
					    <option>Auswahl:</option>
					    <optgroup label="Kompetenzbereich 1">
					        <option value="kompetenz1">Kompetenz 1</option>
					        <option value="Kompetenz2">Kompetenz 2</option>
					        <option value="Kompetenz3">Kompetenz  3</option>
					        <option value="Kompetenz4">Kompetenz 4</option>
					    </optgroup>
					    <optgroup label="Kompetenzbereich 2">
					        <option value="Kompetenz5">Kompetenz 5</option>
					        <option value="Kompetenz6">Kompetenz 6</option>
					        <option value="Kompetenz7">Kompetenz 7</option>
					    </optgroup>
					</select>
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
			            <li><a class="ui-link ui-btn" href="trainer_student.php">Sch&uuml;lerauswahl</a></li>
			            <li><a class="ui-btn-active ui-state-persist ui-link ui-btn" href="trainer_ownexamples.php">Eigene Beitr&auml;ge</a></li>
			            <li><a class="ui-link ui-btn" href="trainer_settings.php">Einstellungen</a></li>
			        </ul>
			    </div><!-- /navbar -->
			</div><!-- /footer -->
	
	
	</div><!.. /lovevet -->

</body>
</html>