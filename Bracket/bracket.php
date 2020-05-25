<?php 

$json;

if ($_GET['message'] != null) {
	$text = trim($_GET['message']);
	$textAr = explode("\n", $text);
	$textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind
	$random = $_GET['order'];
}
else {
	echo "ha";
	if ($_POST['json'] != null) {
		$json = $_POST['json'];
	}
}



?>




<!DOCTYPE html>
<html lang="en" style="width:100%;height:100%;">
	<head>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/0.10.2/p5.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/0.10.2/addons/p5.sound.min.js"></script>
			<link rel="stylesheet" type="text/css" href="/Bracket/bracketStyles.css">
			<meta charset="utf-8" />
	</head>
	<body style="width:100%;height:100%;">
		
		<h1 id="heading">The Generated Bracket</h1>
		
		<script type="text/javascript" name="backend"> //Back end
			var participants = <?php echo json_encode($textAr); ?>;
			var random = (<?php echo json_encode($random); ?> == "true");
			<?php 
				if ($json != "") {
					echo "var json = '" . $_POST['json'] . "';"; 
				}
			?>
		</script>
	
		<div id="svgHolder" > <!-- contenteditable="true" overflow-y:scroll; -->
			<svg id="svgMain">
				<g id="box" width="200" height="50" transform="translate(100,50)">
					<rect x="0" y="0" rx="10" ry="10" width="200" height="50" style="fill:#00341B;stroke:#90D8E7;stroke-width:1;"></rect>
					<text id="one" x="15" y="18" fill="white">Person 1</text>
					<text id="two" x="15" y="43" fill="white">Person 2</text>
					<line x1="0" y1="25" x2="200" y2="25" style="stroke:#90D8E7;stroke-width:1" />
				</g>
				<polyline id="connector" points="0,40 40,40 40,80 80,80"/>
			</svg>
		</div>
		
		<div id="footer">
			<div id="mainFooter">
				<button type="button" id="winnerButton" disabled>
					Declare winner
				</button>
				<button type="button" id="swapButton" disabled> 
					Swap players
				</button>
				<button type="button" id="cancelButton" onclick="cancel()" disabled>
					Cancel
				</button>
				<button type="button" id="loadButton" onclick="load()">
					Load
				</button>
				
				<button type="button" id="saveButton" onclick="save()">
					Save
				</button>
			</div>
			<div id="sideFooter">
				<div id="winnerDiv" style="height:auto;width:auto;display:none;">
					<button id="winButton1"></button>
					<button id="winButton2"></button>
				</div>

				<div id="swapDiv" style="height:100%;display:none;height:auto;width:auto;">
					<button id="swapButton1" data-pos="top"></button>
					<button id="swapButton2" data-pos="bot"></button>
				</div>
			</div>
			
		</div>
		
		<script type="text/javascript" name="frontend"> //Front End
			
		</script>
		
		
		<script src="/Bracket/backend.js"></script>
		<script src="/Bracket/frontend.js"></script>
		<script src="/Bracket/features.js"></script>
		<script type="text/javascript">
			<?php 
				if ($json != "") {
					echo "createBracket(JSON.parse(json));" ; 
				}
			?>
			
		</script>
	</body>
</html>


