<!DOCTYPE html>
<html lang="en">
	<head>
			<link rel="stylesheet" type="text/css" href="/Bracket/bracketStyles.css">
			<meta charset="utf-8" />
	</head>
	<body>
		<form action="/Bracket/bracket.php" method="get">
			<label for="names">List of names:</label><br>
			<textarea name="message" rows="10" cols="30">Person 1</textarea><br>
			<label for="order">Random order game:</label><br>
			<input type="checkbox" id="order" name="order" value="true"><br><br>
			<input type="submit" value="Submit">
		</form> 
	</body>
</html>