var svgMain = document.getElementById("svgMain");
var gs = svgMain.querySelectorAll("g");
for (i=0;i<gs.length;i++) {
	gs[i].onclick = clickedOnBox;
}

var mainFooter = document.getElementById("mainFooter");

//Declare a winner feature

var winnerDiv = document.getElementById("winnerDiv");
var winnerButton = document.getElementById("winnerButton");
winnerButton.onclick = winnerFunc;

var winButton1 = document.getElementById("winButton1");
var winButton2 = document.getElementById("winButton2");
winButton1.onclick = winnerChosen;
winButton2.onclick = winnerChosen;

var spot;
var previousSpot;
var previousTopOrBottom;

var name1;
var name2;

function winnerFunc() {
	if (name1 != emptyLine && name2 != emptyLine) {
		winnerDiv.style.display = "inline";
		swapDiv.style.display = "none";
		winButton1.innerHTML = name1;
		winButton2.innerHTML = name2;
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
	var pos = "bot";
	if (currentY%2 == 0) {
		pos = "top";
	}
	
	setName(name, nextX + "." + nextY, pos)

	resetStuff();
}


//Swap players feature

var swapDiv = document.getElementById("swapDiv");
var swapButton = document.getElementById("swapButton");
swapButton.onclick = swapFunc;

var swapButton1 = document.getElementById("swapButton1");
var swapButton2 = document.getElementById("swapButton2");
swapButton1.onclick = swapChosen;
swapButton2.onclick = swapChosen;


var firstName = "";
var firstSpot;
var firstPos;


function swapFunc() {
	if (name1 != emptyLine || name2 != emptyLine) {
		swapDiv.style.display = "inline";
		winnerDiv.style.display = "none";
		swapButton1.innerHTML = name1;
		swapButton2.innerHTML = name2;
		
	}
	else {
		alert("Update earlier games first");
	}
}

function swapChosen() {
	if (firstName == "") {
		firstName = this.innerHTML;
		firstSpot = spot;
		firstPos = this.dataset.pos;
		alert("Choose next person to swap with " + firstName);
		resetStuff();
	}
	else {
		console.log(firstName + " " + firstSpot + " " + firstPos);
		console.log(this.innerHTML + " " + spot + " " + this.dataset.pos);
		setName(this.innerHTML, firstSpot, firstPos);
		setName(firstName, spot, this.dataset.pos);
		firstName = "";
		firstSpot = "";
		firstPos = "";
		resetStuff()
	}
	
}

//Both

function clickedOnBox() {
	spot = this.attributes["name"].value;
	winnerButton.disabled = false;
	swapButton.disabled = false;

	name1 = this.querySelector("#one").innerHTML;
	name2 = this.querySelector("#two").innerHTML;
}

function resetStuff() {
	name1 = "";
	name2 = "";
	spot = "";
	winnerDiv.style.display = "none";
	winButton1.innerHTML = name1;
	winButton2.innerHTML = name2;
	winnerButton.disabled = true;
	
	swapDiv.style.display = "none";
	swapButton.disabled = true;
}

function setName(name, spot, pos) {
	var theebox = document.querySelector('g[name="'+ spot.trim() +'"]');	
	var texts = theebox.querySelectorAll("text"); //This breaks at the last game!!!
	var text = texts[1];

	if (pos == "top") {
		text = texts[0];
	}

	text.innerHTML = name;
}

function cancel() {
	resetStuff()
}