<?php 

$text = trim($_GET['message']);
$textAr = explode("\n", $text);
$textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind
$random = $_GET['order'];

//"translate(" + toString(xCoord+225*i) + ", " + toString(yCoords[i] + 75*j*(i+1)) + ")"
?>




<!DOCTYPE html>
<html lang="en" style="width:100%;height:100%;">
	<head>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/0.10.2/p5.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/0.10.2/addons/p5.sound.min.js"></script>
			<link rel="stylesheet" type="text/css" href="styles.css">
			<meta charset="utf-8" />
	</head>
	<body style="width:100%;height:100%;">
		<h1> The Generated Bracket</h1>
		<script type="text/javascript">
			function closestPowOfTwo(num) { 
				return parseInt("1" + "0".repeat(Math.ceil(Math.log2(num))), 2); //When num == a power of 2, it returns itself
			}

			function spreadByes(byes, games) {
				if (byes == 1) {  //When there is only 1 bye to split among multiple games, it is given to the first of the bunch
					return("1" + "0".repeat(games-1));
				}
				else {								//this continues to split the function until there is only 1 bye left
					var f = Math.floor(byes/2); 
					var c = Math.ceil(byes/2);
					var g = games/2;
					var x = spreadByes(f, g);
					var y = spreadByes(c, g);
				}
				return(x+y)
			}

			function shuffleArray(array) { //Shuffle Array Function from the internet
				for (let i = array.length - 1; i > 0; i--) {
					const j = Math.floor(Math.random() * (i + 1));
					[array[i], array[j]] = [array[j], array[i]];
				}
			}

			//Simple Declarations
			var participants = <?php echo json_encode($textAr); ?>;
			var random = (<?php echo json_encode($random); ?> == "true");
			var bracketSize = closestPowOfTwo(participants.length);
			var byeAmount = bracketSize - participants.length; 
			var byeSpread = spreadByes(byeAmount, bracketSize/2); //The first round has an amount of games equal to half the amount of players.
			if (random) {
				shuffleArray(participants);
			}

			/*var temp = 0;
			for (i=0;i<byeSpread.length;i++) {
				if (byeSpread[i] == "1") {
					console.log(participants[temp] + " gets a bye.");
					temp++;
				}
				else if (byeSpread[i] == "0") {
					console.log(participants[temp] + " versus " + participants[temp+1] + "!");
					temp+=2;
				}
			}*/
		</script>
		<!--script src="sketch.js"></script-->
	
		<div id="svgHolder" style="width:100%;height:100%;overflow-y:scroll;" contenteditable="true">
			<svg id="svgMain" style="width:100%;height:100%;overflow-y:scroll;">
				<g width="200" height="50" transform="translate(100,50)">
					<rect x="0" y="0" rx="10" ry="10" width="200" height="50" style="fill:#00341B;stroke:#90D8E7;stroke-width:1;"></rect>
					<text id="one" x="15" y="18" fill="white">Person 1</text>
					<text id="two" x="15" y="43" fill="white">Person 2</text>
					<line x1="0" y1="25" x2="200" y2="25" style="stroke:#90D8E7;stroke-width:1" />
				</g>
			</svg>
		</div>
		
		
		<script type="text/javascript"> 
			var itm = document.getElementById("svgMain").children[0];
			//console.log(bracketSize);
			var rounds = Math.log2(bracketSize);
			var games = bracketSize/2;
			
			
			var xCoord = 100;
			var yCoord = 50; 
			var xMultiplier = 225;
			var yMultiplier = 55;
			
			//console.log(rounds);
			//console.log(games);
			
			var byeArray = []
			
			for (i=0; i<rounds; i++) {
				var temp = 0;
				for(j=0; j<games; j++) {
					
					var name1 = "_______";
					var name2 = "_______";
					var keep = true;
					if (i==0) { //Here don't create games where there are byes, and supply names for the games in round one
						if (byeSpread[j] == "1") {
							//console.log(participants[temp] + " gets a bye.");
							byeArray.push([j, participants[temp]])
							
							temp++;
							keep = false;
							
						}
						else if (byeSpread[j] == "0") {
							//console.log(participants[temp] + " versus " + participants[temp+1] + "!");
							name1 = participants[temp];
							name2 = participants[temp+1];
							temp+=2;
						}
					}
					if (i==1) { //Supply Byes for round 2
						nextBye = byeArray[temp][0];
						if (j >= nextBye/2) {
							if (nextBye%2 == 0) {
								name1 = byeArray[temp][1];
								temp++;
							}
							newBye = byeArray[temp][0];
							if (nextBye == newBye-1){
								name2 = byeArray[temp][1];
								temp++;
							}
							
						}
					}
					if (keep) {
						var cln = itm.cloneNode(true);
						var x = xCoord + xMultiplier*i;
						var y = yCoord + yMultiplier/2 * Math.pow(2, i)-1 + (yMultiplier * Math.pow(2, i) * j);

						cln.setAttribute("transform", "translate(" + x + ", " + y + ")");
						cln.setAttribute("name", i + "." + j);
						cln.querySelector('#one').innerHTML = name1;
						cln.querySelector('#two').innerHTML = name2;
						document.getElementById("svgMain").appendChild(cln);
					}
				}
				//console.log(byeArray);
				games = games/2;
			}
			itm.setAttribute("display", "none");
		</script>
	</body>
</html>


