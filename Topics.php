<?php // <--- do NOT put anything before this PHP tag
	include('Functions.php');
	$cookieMessage = getCookieMessage();
	$cookieUser = getCookieUser();

	// Database connection
	$dbh = connectToDatabase();

	// Count the number of topics in the database
	$countTopicsQuery = $dbh->query("SELECT COUNT(*) FROM Topics");
	$numTopics = $countTopicsQuery->fetchColumn();

	// Retrieve the list of topics from the database
	$getTopicsQuery = $dbh->prepare("SELECT Users.Username, Topics.CreatedAt, Topics.TopicName, Topics.TopicID FROM Topics INNER JOIN Users ON Topics.CreatedBy = Users.UserID ORDER BY Topics.TopicID DESC");
	$getTopicsQuery->execute();
	$topics = $getTopicsQuery->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
	<title>Topics</title>
	<link rel="stylesheet" type="text/css" href="styles.css"> 
	<meta charset="UTF-8">		<!-- For emojis -->
</head>
<body>
	<div class="container">
		<div class="row", id="header">
			<h2>CSE4IFU-Topics</h2>
		</div>
		<div class="row", id="nav">
			<?php
				if (!empty($cookieMessage)) {
					echo '<div class="error-message">' . $cookieMessage . '</div>';
				}
            ?>  
			<ul>
				<?php
				// Get the current page's filename
				$current_page = basename($_SERVER['PHP_SELF']);

				if ($cookieUser === "") {
					// User is not logged in, show Sign Up and Sign In links
					echo '<li ' . ($current_page == 'SignUp.php' ? 'class="highlight-nav-text"' : '') . '><a href="SignUp.php">Sign Up</a></li>';
					echo '<li ' . ($current_page == 'SignIn.php' ? 'class="highlight-nav-text"' : '') . '><a href="SignIn.php">Sign In</a></li>';
				} else {
					// User is logged in, show Sign Out and user's information
					echo '<li ' . ($current_page == 'LogOutUser.php' ? 'class="highlight-nav-text"' : '') . '><a href="LogOutUser.php">Sign Out</a></li>';
					echo '<li ' . ($current_page == 'UserProfile.php' ? 'class="highlight-nav-text"' : '') . '><a href="UserProfile.php">' . $cookieUser . '</a></li>';
				}

				// Add the "highlight" class to the "Home" link if it's the current page
				echo '<li ' . ($current_page == 'HomePage.php' ? 'class="highlight-nav-text"' : '') . '><a href="HomePage.php">Home</a></li>';

				echo '<li ' . ($current_page == 'Topics.php' ? 'class="highlight-nav-text"' : '') . '><a href="Topics.php">Topics</a></li>';
				?>
			</ul>
		</div>
		<div class="row", id="content">
			<h3>Topics</h3>
            <table>
                <tr>
                    <th>Created by User</th>
                    <th>Topic Name</th>
                    <th>Date Created</th>
                </tr>
                <?php foreach ($topics as $topic) : ?>
                    <tr>
                        <td><?php echo $topic['Username']; ?></td>
                        <td><a href="ViewTopic.php?topicID=<?php echo $topic['TopicID']; ?>"><?php echo $topic['TopicName']; ?></a></td>
                        <td><?php echo $topic['CreatedAt']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <h3>Create a new topic</h3>
            <?php if ($cookieUser === "") : ?>
                <p>You must be logged in to create a topic.</p>
            <?php else : ?>
                <form action="AddTopic.php" method="POST">
                    <label for="topicName">Topic Name:</label>
                    <input type="text" id="topicName" name="topicName" required>
                    <br><br>
                    <input type="submit" value="Create Topic">
                </form>
            <?php endif; ?>
		</div>
		<div class="row", id="footer">
			<h3>Full Name - </h3>
			<h3>Student Number - </h3>
			<h3>CSE4IFU 2023, Sem 1</h3>
		</div>
	</div>
</body>
</html>