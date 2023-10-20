<?php // <--- do NOT put anything before this PHP tag

// this php file will have no HTML

	include('Functions.php');
	
	if (isset($_POST['username'], $_POST['firstname'], $_POST['surname'], $_POST['shorttag'])) {
		// Trim and store POST data
		$username = trim($_POST['username']);
		$firstname = trim($_POST['firstname']);
		$surname = trim($_POST['surname']);
		$shorttag = trim($_POST['shorttag']);
	
		// Database connection
		$dbh = connectToDatabase();
	
		// Check if the username already exists
		$checkUserQuery = $dbh->prepare("SELECT * FROM Users WHERE Username = ?");
		$checkUserQuery->bindParam(1, $username, PDO::PARAM_STR);
		$checkUserQuery->execute();
		$userExists = $checkUserQuery->fetch(PDO::FETCH_ASSOC);
	
		if ($userExists) {
			// Username already exists
			setCookieMessage("User already exists");
			redirect("HomePage.php");
		} else {
			// Prepare, bind, and execute SQL statement to insert the user
			$insertUserQuery = $dbh->prepare("INSERT INTO Users (Username, FirstName, Surname, ShortTag) VALUES (?, ?, ?, ?)");
			$insertUserQuery->bindParam(1, $username, PDO::PARAM_STR);
			$insertUserQuery->bindParam(2, $firstname, PDO::PARAM_STR);
			$insertUserQuery->bindParam(3, $surname, PDO::PARAM_STR);
			$insertUserQuery->bindParam(4, $shorttag, PDO::PARAM_STR);
			$insertUserQuery->execute();
	
			// Set a cookie message and redirect to the homepage
			setCookieMessage("User has been added");
			redirect("HomePage.php");
		}
	} else {
		// Data not provided
		echo "Error: Missing data";
	}
?>