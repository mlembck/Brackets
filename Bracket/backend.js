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

if (participants != null || json == null) {
	//Simple Declarations
	bracketSize = closestPowOfTwo(participants.length);
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

	var rounds = Math.log2(bracketSize);
	var games = bracketSize/2;

	var bracketToBeMade = [];
	var byeArray = []
	var byesLeft = byeAmount;
	var gamesThisRound = games;
	var emptyLine = "";


	for (i=0; i<rounds; i++) { //Loops through every round
		var temp = 0;
		var currentBracket = [];
		for(j=0; j<gamesThisRound; j++) { //Loops through every game per round
			var name1 = emptyLine;
			var name2 = emptyLine;
			var keep = true;
			if (i==0) { //Here don't create games where there are byes, and supply names for the games in round one
				if (byeSpread[j] == "1") {
					currentBracket.push([j, "bye"]);
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
				currentBracket.push([j, name1, name2]);
			}
		}
		bracketToBeMade.push(currentBracket);
		gamesThisRound = gamesThisRound/2;
	}

}
//console.log(JSON.parse(JSON.stringify(bracketToBeMade)));

/*

bracketToBeMade = [
					[	[0, "bye"],
						[1, "Person 1", "Person 7"],
						[2, "Person 2", "Person 8"],
						[3, "Person 3", "Person 9"],
						[4, "bye"],
						[5, "Person 4", "Person 10"],
						[6, "Person 5", "Person 11"],
						[7, "Person 6", "Person 12"]
					],
					[	[0, "Person 13", "_______"],
						[1, "_______", "Person 9"],
						[2, "Person 14", "Person 10"],
						[3, "_______", "_______"]
					],



				  ]

*/