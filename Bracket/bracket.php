<?php 

$text = trim($_GET['message']);
$textAr = explode("\n", $text);
$textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind
$random = $_GET['order'];

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
		
		<h1>The Generated Bracket</h1>
		
		<script type="text/javascript" name="backend"> //Back end
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
			var byeSpread;
			if (byeAmount == 0) {
				byeSpread = "0".repeat(bracketSize/2)
			}
			else {
				byeSpread = spreadByes(byeAmount, bracketSize/2); //The first round has an amount of games equal to half the amount of players.
			}
			
			if (random) {
				shuffleArray(participants);
			}
			
		</script>
	
		<div id="svgHolder" > <!-- contenteditable="true" overflow-y:scroll; -->
			<svg id="svgMain">
				<g id="box" width="200" height="50" transform="translate(100,50)">
					<rect x="0" y="0" rx="10" ry="10" width="200" height="50" style="fill:#00341B;stroke:#90D8E7;stroke-width:1;"></rect>
					<text id="one" x="15" y="18" fill="white">Person 1</text>
					<text id="two" x="15" y="43" fill="white">Person 2</text>
					<line x1="0" y1="25" x2="200" y2="25" style="stroke:#90D8E7;stroke-width:1" />
				</g>
				<polyline id="line" points="0,40 40,40 40,80 80,80"/>
			</svg>
		</div>
		
		<div id="footer">
			<div id="mainFooter">
				<button type="button" id="winnerButton" disabled>
					Declare winner
				</button>
				<!--button type="button" id="swap" onclick="swapFunc" disabled> 
					Swap players
				</button-->
			</div>
			
			<div id="winnerDiv" style="border:solid black 2px;height:100%;display:none;">
				<p>Declare a winner!</p>
				<button id="button1"></button>
				<button id="button2"></button>
			</div>
		</div>
		
		<script type="text/javascript" name="frontend"> //Front End
			var box = document.getElementById("svgMain").children[0];
			var line = document.getElementById("svgMain").children[1];
			
			var emptyLine = " "
			
			var rounds = Math.log2(bracketSize);
			var games = bracketSize/2;
			
			var width = parseInt(box.getAttribute("width"));
			var height = parseInt(box.getAttribute("height"));
			
			var xMargin = 100;
			var yMargin = 50; 
			var xMultiplier = 225;
			var yMultiplier = 55;
			
			var farthestRight = xMargin + xMultiplier * (rounds);
			var farthestDown = yMargin + yMultiplier/2 * (Math.pow(2, 0)-1) + (yMultiplier * (Math.pow(2, 0)) * (games));
			
			var byeArray = [] //Participants who get byes are placed here with the id of their game [[id, "name"], [id, "name"], ... ]
			var byesLeft = byeAmount;
			var gamesThisRound = games
			
			function genXCoord(i) {
				return (xMargin + xMultiplier*i);
			}
			
			function genYCoord(i, j) {
				return(yMargin + yMultiplier/2 * (Math.pow(2, i)-1) + (yMultiplier * Math.pow(2, i) * j));
			}
			
			function genPoints(x, y, i, j) {
				var nexti = i + 1;
				var nextj = Math.floor(j/2);
				
				var x1 = x + width;
				var y1 = y + height/2;
				
				var x4 = genXCoord(nexti);
				var y4 = genYCoord(nexti, nextj) + height/2;
				
				var midX = (x1+x4)/2;
				
				return (x1 + "," + y1 + " " + midX + "," + y1 + " " + midX + "," + y4 + " " + x4 + "," + y4);
			}
			
			for (i=0; i<rounds; i++) {
				var temp = 0;
				
				for(j=0; j<gamesThisRound; j++) {
					var name1 = emptyLine;
					var name2 = emptyLine;
					var keep = true;
					if (i==0) { //Here don't create games where there are byes, and supply names for the games in round one
						if (byeSpread[j] == "1") {
							byeArray.push([j, participants[temp]])
							temp++;
							keep = false;
							
						}
						else if (byeSpread[j] == "0") {
							name1 = participants[temp];
							name2 = participants[temp+1];
							temp+=2;
						}
					}
					else if (i==1 && byesLeft > 0) { //Supply Byes for round 2 but only if there are byes left to be placed
						nextBye = byeArray[temp][0];
						if (j >= nextBye/2) {
							if (nextBye%2 == 0) {
								name1 = byeArray[temp][1];
								temp++;
								byesLeft--;
							}
							if (byeArray[temp]) {
								newBye = byeArray[temp][0];
								if (nextBye == newBye-1){
									name2 = byeArray[temp][1];
									temp++;
								}
							}
							
						}
					}
					if (keep) {
						var newBox = box.cloneNode(true);
						var x = genXCoord(i);
						var y = genYCoord(i,j);
						
						newBox.setAttribute("transform", "translate(" + x + ", " + y + ")");
						newBox.setAttribute("name", i + "." + j);
						newBox.querySelector('#one').innerHTML = name1;
						newBox.querySelector('#two').innerHTML = name2;
						document.getElementById("svgMain").appendChild(newBox);
						
						if (i != rounds-1) {
							//console.log(i);
							var newLine = line.cloneNode(true);
							var points = genPoints(x, y, i, j);

							newLine.setAttribute("points", points);
							newLine.setAttribute("id", i + "." + j);
							document.getElementById("svgMain").appendChild(newLine);
						}
						
					}
				}
				gamesThisRound = gamesThisRound/2;
			}
			box.remove()//box.setAttribute("display", "none");
			line.remove()//line.setAttribute("display", "none");
			
			document.getElementById("svgMain").setAttribute("viewBox", "0 0 " + farthestRight + " " + farthestDown);
		</script>
		
		<script type="text/javascript" name="features">
			var svgMain = document.getElementById("svgMain");
			var gs = svgMain.querySelectorAll("g");
			for (i=0;i<gs.length;i++) {
				gs[i].onclick = clickedOnBox;
			}
			
			var mainFooter = document.getElementById("mainFooter");
			var winnerDiv = document.getElementById("winnerDiv");
			var winnerButton = document.getElementById("winnerButton");
			winnerButton.onclick = winnerFunc;
			
			var button1 = document.getElementById("button1");
			var button2 = document.getElementById("button2");
			button1.onclick = winnerChosen;
			button2.onclick = winnerChosen;
			
			var spot;
			
			var name1;
			var name2;
		
			function clickedOnBox() {
				spot = this.attributes["name"].value
				winnerButton.disabled = false;
				
				name1 = this.querySelector("#one").innerHTML;
				name2 = this.querySelector("#two").innerHTML;
			}
			
			function winnerFunc() {
				if (name1 != emptyLine && name2 != emptyLine) {
					winnerDiv.style.display = "inline";
					button1.innerHTML = name1;
					button2.innerHTML = name2;
				}
				else {
					alert("Update earlier games first");
				}
			}
			
			function winnerChosen() {
				var spotArray = spot.split(".");
				var currentX = parseInt(spotArray[0]);
				var currentY = parseInt(spotArray[1]);
				var nextX = currentX + 1;
				var nextY = Math.floor(currentY/2);
				var name = this.innerHTML;
				var top = false;
				if (currentY%2 == 0) {
					top = true;
				}
				
				var nextBox = document.getElementsByName(nextX + "." + nextY)[0];
				
				var texts = nextBox.querySelectorAll("text"); //This breaks at the last game!!!
				var text = texts[1];
				
				if (top) {
					text = texts[0];
				}
				
				text.innerHTML = name;
				
				resetStuff();
			}
			
			function resetStuff() {
				name1 = "";
				name2 = "";
				spot = "";
				winnerDiv.style.display = "none";
				button1.innerHTML = name1;
				button2.innerHTML = name2;
				winnerButton.disabled = true;
			}
			
		</script>
	</body>
</html>


