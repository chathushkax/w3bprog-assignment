<?php // <--- do NOT put anything before this PHP tag
	include('Functions.php');
	$cookieMessage = getCookieMessage();
	$cookieUser = getCookieUser();

	if (isset($_GET['topic'])) {
		$thisTopic = $_GET['topic'];
	} else {
		// Handle the case when the topic is not provided in the URL
		// You may redirect or display an error message
	}
	
	$dbh = connectToDatabase();
	
	// Get the topicID based on the provided topic name
	$getTopicIDQuery = $dbh->prepare("SELECT TopicID FROM Topics WHERE TopicName COLLATE NOCASE = ?");
	$getTopicIDQuery->bindParam(1, $thisTopic, PDO::PARAM_STR);
	$getTopicIDQuery->execute();
	$topicID = $getTopicIDQuery->fetchColumn();
	
	// Count the number of posts for this topic
	$countPostsQuery = $dbh->prepare("SELECT COUNT(*) FROM Posts WHERE TopicID = ?");
	$countPostsQuery->bindParam(1, $topicID, PDO::PARAM_INT);
	$countPostsQuery->execute();
	$numPosts = $countPostsQuery->fetchColumn();
	
	// Get the posts for the topic
	$getPostsQuery = $dbh->prepare("SELECT Users.Username, Posts.PostContent, Posts.PostDate, Posts.Likes FROM Posts INNER JOIN Users ON Posts.PostedBy = Users.UserID WHERE Posts.TopicID = ?");
	$getPostsQuery->bindParam(1, $topicID, PDO::PARAM_INT);
	$getPostsQuery->execute();
	$posts = $getPostsQuery->fetchAll(PDO::FETCH_ASSOC);
	
	
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $thisTopic; ?></title>
	<link rel="stylesheet" type="text/css" href="styles.css"> 
	<meta charset="UTF-8">		<!-- For emojis -->
</head>
<body>
	<div class="container">
		<div class="row", id="header">
			<h2><?php echo $thisTopic; ?></h2>
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
			<h3>Forum</h3>
            <table>
                <tr>
                    <th>User</th>
                    <th>Post</th>
                    <th>Date</th>
                    <th>Likes</th>
                </tr>
                <?php foreach ($posts as $post) : ?>
                    <tr>
                        <td><?php echo $post['Username']; ?></td>
                        <td><?php echo $post['PostContent']; ?></td>
                        <td><?php echo $post['PostDate']; ?></td>
                        <td><?php echo $post['Likes']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <h3>Create a new topic</h3>
            <?php if ($cookieUser === "") : ?>
                <p>You must be logged in to create a post.</p>
            <?php else : ?>
                <form method="POST" action="AddPost.php?Topic=<?php echo $thisTopic; ?>">
                    <label for="postContent">Post Content:</label>
                    <textarea id="postContent" name="postContent" required></textarea>
                    <br><br>
                    <input type="submit" value="Create Post">
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