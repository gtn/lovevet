
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
				<h1 class="ui-title" role="heading" aria-level="1">Eigenen Beitrag erstellen</h1>
				<a class="ui-btn-left ui-btn ui-icon-back ui-btn-icon-notext ui-shadow ui-corner-all" data-rel="back" href="trainer_studentlist.php" data-role="button" role="button">Back</a>
			
			</div>
			
			<div class="ui-content" role="main">

				
				<ul data-role="listview" data-inset="true">
			   
			    <li>
			    <label for="text-basic">Name:</label>
					<input name="text-basic" id="text-basic" value="" type="text">
			    </li>
			    <li>
				    <label for="textarea">Beschreibung:</label>
					<textarea cols="40" rows="8" name="textarea" id="textarea"></textarea>
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
				<li data-role="list-divider">Kompetenz</li>
				<li>
					<label for="select-choice-8" class="select">Erreichte Kompetenzen:</label>
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
				<li data-role="list-divider">Selbsteinsch&auml;tzung</li>
				 <li>
					<label for="slider-fill">Selbsteinsch&auml;tzung:</label>
					<input name="slider-fill" id="slider-fill" value="0" min="0" max="100" step="1" data-highlight="true" type="range">
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