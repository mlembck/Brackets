var svgMain = document.getElementById("svgMain");
var gs = svgMain.querySelectorAll("g");

function addOnclicks() {
	gs = svgMain.querySelectorAll("g");
	for (i=0;i<gs.length;i++) {
		gs[i].onclick = clickedOnBox;
	}
}

addOnclicks();

var mainFooter = document.getElementById("mainFooter");

//Declare a winner feature

var winnerDiv = document.getElementById("winnerDiv");
var winnerButton = document.getElementById("winnerButton");
winnerButton.onclick = winnerFunc;

var winButton1 = document.getElementById("winButton1");
var winButton2 = document.getElementById("winButton2");
winButton1.onclick = winnerChosen;
winButton2.onclick = winnerChosen;

var cancelButton = document.getElementById("cancelButton");

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

var cupPongBracket = '[[[0,"Sasha Small","Henry Reed"],[1,"Riley Dewey","Jerry Karalis"],[2,"Eric Rosenbaum","Bo Gold"],[3,"Chase Sullivan","Jordan Kaliner"],[4,"Shainee Kotay","Dan Snitzer"],[5,"Caroline Milgram","Lindsey Davis"],[6,"Luke Grayum","Lilia Becker"],[7,"Mercy Olukanni","Sana Bohkari"],[8,"Hayley Gibson","Jack Robinson"],[9,"Declan Buckley","Elle Chirstine"],[10,"Max Tirjan","Kyle Mezrow"],[11,"Max Brandt","Karly Terlevich"],[12,"Tom Kim","Michael Lembck"],[13,"Jimmy Fallon","Liv Hamilton"],[14,"Dean Maner","Ian Lissack"],[15,"Maxwell Orr","Bella Willing"]],[[0,"",""],[1,"",""],[2,"",""],[3,"",""],[4,"",""],[5,"",""],[6,"Michael Lembck",""],[7,"",""]],[[0,"",""],[1,"",""],[2,"",""],[3,"",""]],[[0,"",""],[1,"",""]],[[0,"",""]]]';


function swapFunc() {
	if (name1 != emptyLine || name2 != emptyLine) {
		swapDiv.style.display = "inline";
		winnerDiv.style.display = "none";
		swapButton1.innerHTML = name1;
		swapButton2.innerHTML = name2;
		if (name1 == emptyLine) {
			swapButton1.style.display = "none";
		}
		if (name2 == emptyLine) {
			swapButton2.style.display = "none";
		}
		
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
	console.log("here");
	cancelButton.disabled = false;
	
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
	cancelButton.disabled = true;
	
	swapButton1.style.display = "inline";
	swapButton2.style.display = "inline";
}

function setName(name, spot, pos) {
	var spotArray = spot.split(".");
	var round = parseInt(spotArray[0]);
	var game = parseInt(spotArray[1]);
	
	
	var theebox = document.querySelector('g[name="'+ spot.trim() +'"]');	
	var texts = theebox.querySelectorAll("text"); //This breaks at the last game!!!
	var text = texts[1];
	
	console.log(theCurrentBracket[round][game]);

	if (pos == "top") {
		text = texts[0];
		theCurrentBracket[round][game][1] = name;
	}
	else {
		theCurrentBracket[round][game][2] = name;
	}

	text.innerHTML = name;
	
	console.log(theCurrentBracket[round][game]);
}

function cancel() {
	cancelButton.disabled = true;
	resetStuff()
}

function load() {
	createBracket(JSON.parse(cupPongBracket));
	document.getElementById("heading").innerHTML = "Cup Pong Tournament";
}

var hidden = false;

document.addEventListener('keydown', function(event) {
	//console.log(event.keyCode);
	//console.log(event.keyCode);
    if(event.keyCode == 67) {
        if (hidden) {
			hidden = false;
			document.getElementById("mainFooter").style.display = "inline";
		}
		else {
			hidden = true;
			document.getElementById("mainFooter").style.display = "none";
		}
    }
});

function save() {
	var str = JSON.stringify(theCurrentBracket).replace(/(\r\n|\n|\r)/gm,"");
	copyStringToClipboard(str);
	
	const form = document.createElement('form');
	form.action = '/Bracket/bracket.php';
	form.method = 'post';

	const json = document.createElement('input');
	json.type = 'hidden';
	json.name = 'json';
	json.value = str;
	form.appendChild(json);

	// repeat for guest

	document.body.appendChild(form);
	form.submit();
}

function copyStringToClipboard(str) { //from internet
   // Create new element
   var el = document.createElement('textarea');
   // Set value (string to be copied)
   el.value = str;
   // Set non-editable to avoid focus and move outside of view
   el.setAttribute('readonly', '');
   el.style = {position: 'absolute', left: '-9999px'};
   document.body.appendChild(el);
   // Select text inside element
   el.select();
   // Copy text to clipboard
   document.execCommand('copy');
   // Remove temporary element
   document.body.removeChild(el);
}
