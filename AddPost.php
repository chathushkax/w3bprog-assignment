<?php // <--- do NOT put anything before this PHP tag

// this php file will have no HTML

	include('Functions.php');
	
	if (!empty($cookieUser)) {
		if (isset($_POST['postContent']) && isset($_GET['Topic'])) {
			// Trim and store POST data
			$postContent = trim($_POST['postContent']);
			$thisTopic = $_GET['Topic'];
	
			// Database connection
			$dbh = connectToDatabase();
	
			// Get the user ID based on the logged-in user's username
			$getUserIDQuery = $dbh->prepare("SELECT UserID FROM Users WHERE Username COLLATE NOCASE = ?");
			$getUserIDQuery->bindParam(1, $cookieUser, PDO::PARAM_STR);
			$getUserIDQuery->execute();
			$userID = $getUserIDQuery->fetchColumn();
	
			// Get the topic ID based on the provided topic name
			$getTopicIDQuery = $dbh->prepare("SELECT TopicID FROM Topics WHERE TopicName COLLATE NOCASE = ?");
			$getTopicIDQuery->bindParam(1, $thisTopic, PDO::PARAM_STR);
			$getTopicIDQuery->execute();
			$topicID = $getTopicIDQuery->fetchColumn();
	
			// Insert the post into the database
			$insertPostQuery = $dbh->prepare("INSERT INTO Posts (PostedBy, TopicID, PostContent, PostDate, Likes) VALUES (?, ?, ?, DATETIME('now'), 0)");
			$insertPostQuery->bindParam(1, $userID, PDO::PARAM_INT);
			$insertPostQuery->bindParam(2, $topicID, PDO::PARAM_INT);
			$insertPostQuery->bindParam(3, $postContent, PDO::PARAM_STR);
			$insertPostQuery->execute();
	
			// Redirect back to Forum.php
			redirect("Forum.php?topic=" . $thisTopic);
		} else {
			// Data not provided
			echo "Error: Missing data";
		}
	} else {
		// User is not logged in,
		redirect("Signin.php");
	}
?>