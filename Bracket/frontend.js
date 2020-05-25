var box = document.getElementById("svgMain").children[0];
var line = document.getElementById("svgMain").children[1];

var width = parseInt(box.getAttribute("width"));
var height = parseInt(box.getAttribute("height"));

var xMargin = 100;
var yMargin = 50; 
var xMultiplier = 225;
var yMultiplier = 55;

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

var firstTime = true;
var theCurrentBracket;


function createBracket(bracket) {
	theCurrentBracket = bracket;
	
	var rounds = bracket.length;
	var games = bracket[0].length;
	
	resetSvgMain();
	
	//onsole.log(bracket.length + " < - Bracket Length");
	for (i=0; i<bracket.length; i++) { //Loops through every round
		var temp = 0;
		var currentBracket = bracket[i];
		
		//console.log(i + " " + currentBracket);
		for(j=0; j<currentBracket.length; j++) { //Loops through every game per round
			var currentGame = currentBracket[j];
			
			if (currentGame[1] != "bye") {
				var name1 = currentGame[1];
				var name2 = currentGame[2];
				var newBox = box.cloneNode(true);
				var x = genXCoord(i);
				var y = genYCoord(i,j);

				newBox.setAttribute("transform", "translate(" + x + ", " + y + ")");
				newBox.setAttribute("name", i + "." + j);
				newBox.setAttribute("id", "box" + i + "." + j);
				newBox.querySelector('#one').innerHTML = name1;
				newBox.querySelector('#two').innerHTML = name2;
				document.getElementById("svgMain").appendChild(newBox);

				if (i != rounds-1) {
					var newLine = line.cloneNode(true);
					var points = genPoints(x, y, i, j);

					newLine.setAttribute("points", points);
					newLine.setAttribute("id", "line" + i + "." + j);
					document.getElementById("svgMain").appendChild(newLine);
				}

			}
		}
	}
	
	box.setAttribute("display", "none")//box.remove();
	line.setAttribute("display", "none")//line.remove();
	
	var farthestRight = xMargin + xMultiplier * (rounds);
	var farthestDown = yMargin + yMultiplier/2 * (Math.pow(2, 0)-1) + (yMultiplier * (Math.pow(2, 0)) * (games));
	
	document.getElementById("svgMain").setAttribute("viewBox", "0 0 " + farthestRight + " " + farthestDown);
	
	if (firstTime) {
		firstTime = false;
	}
	else {
		addOnclicks();
	}
}

function resetSvgMain() {
	console.log("in prep");
	box.setAttribute("display", "inline")
	line.setAttribute("display", "inline")
	
	var children = document.getElementById("svgMain").children;
	//console.log(children);
	for (i=children.length-1;i>0;i--) {
		if (children[i].id != "box" && children[i].id != "connector") {
			//console.log("Removing: " + children[i].id);
			children[i].remove();
			
		}
		else {
			console.log("not removing: " + children[i].id);
		}
		
	}
}


if (json == null) {
	createBracket(bracketToBeMade);
}

//resetSvgMain();



