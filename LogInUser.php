<?php // <--- do NOT put anything before this PHP tag

// this php file will have no HTML

	include('Functions.php');

	if (isset($_POST['username'])) {
		// Trim and store POST data
		$username = trim($_POST['username']);
	
		// Database connection
		$dbh = connectToDatabase();
	
		// Prepare, bind, and execute SQL command to check if the username exists (case-insensitive)
		$checkUserQuery = $dbh->prepare("SELECT * FROM Users WHERE Username COLLATE NOCASE = ?");
		$checkUserQuery->bindParam(1, $username, PDO::PARAM_STR);
		$checkUserQuery->execute();
		$userExists = $checkUserQuery->fetch(PDO::FETCH_ASSOC);
	
		if ($userExists) {
			// Username exists, set the user's username and welcome message
			setCookieUser($username);
			setCookieMessage("Welcome back, " . $username);
			redirect("HomePage.php");
		} else {
			// Username does not exist, set an error message
			setCookieMessage("Username does not exist");
			redirect("SignIn.php");
		}
	} else {
		// Data not provided
		echo "Error: Missing data";
	}
	
?>
