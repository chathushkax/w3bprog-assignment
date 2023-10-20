<?php // <--- do NOT put anything before this PHP tag

// this php file will have no HTML

	include('Functions.php');
	
	if (isset($_POST['topicName'])) {
		// Trim and store POST data
		$topicName = trim($_POST['topicName']);
	
		// Database connection
		$dbh = connectToDatabase();
	
		// Prepare, bind, and execute SQL command to check if the topic already exists (case-insensitive)
		$checkTopicQuery = $dbh->prepare("SELECT TopicID FROM Topics WHERE TopicName COLLATE NOCASE = ?");
		$checkTopicQuery->bindParam(1, $topicName, PDO::PARAM_STR);
		$checkTopicQuery->execute();
		$existingTopic = $checkTopicQuery->fetch(PDO::FETCH_ASSOC);
	
		if ($existingTopic) {
			// Topic already exists, set the cookie message and redirect to Topics.php
			setCookieMessage("Topic already exists");
			redirect("Topics.php");
		} else {
			// Get the user's ID (you need to modify this part based on your database structure)
			$userID = 1; // Replace with the actual user ID retrieval method
	
			// Set the datetime to Melbourne time
			date_default_timezone_set('Australia/Melbourne');
			$datetime = date('Y-m-d H:i:s');
	
			// Insert the new topic into the database
			$insertTopicQuery = $dbh->prepare("INSERT INTO Topics (CreatedBy, TopicName, CreatedAt) VALUES (?, ?, ?)");
			$insertTopicQuery->bindParam(1, $userID, PDO::PARAM_INT);
			$insertTopicQuery->bindParam(2, $topicName, PDO::PARAM_STR);
			$insertTopicQuery->bindParam(3, $datetime);
			$insertTopicQuery->execute();
	
			// Redirect back to Topics.php
			redirect("Topics.php");
		}
	} else {
		// Data not provided
		echo "Error: Missing data";
	}
?>