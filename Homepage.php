<?php // <--- do NOT put anything before this PHP tag
	include('Functions.php');
	$cookieMessage = getCookieMessage();
	$cookieUser = getCookieUser();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="styles.css"> 
</head>
<body>
	<div class="container">
		<div class="row", id="header">
			<h2>CSE4IFU-Home</h2>
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
			<div class="welcome-container">
                <p class="welcome-text">
                    Welcome to our forum!
                </p>
                <div class="welcome-image"></div>
            </div>
		</div>
		<div class="row", id="footer">
			<h3>Full Name - </h3>
			<h3>Student Number - </h3>
			<h3>CSE4IFU 2023, Sem 1</h3>
		</div>
	</div>
</body>
</html>