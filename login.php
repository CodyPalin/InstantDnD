<?php
session_start();
?>
<html>
  <head>
    <link rel="stylesheet" href="css/login.css">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
  </head>
  <body>
	<div id = header>
    <h1 id = h1>Login to InstantDnD</h1>
	</div>
	<div id = outerform>
		<form id = form method="post" action="handler.php">
		  <div>Username: <input type="text" name="username"></div>
		  <div>Password: <input type="password" name="password"></div>
		  <?php
		  if (isset($_SESSION['message'])) {
			echo "<div id='message'>" . $_SESSION['message'] . "</div>";
		  }
		  unset($_SESSION['message']);
		  ?>
		  <div><input type="submit" value="Login"></div>
		</form>
	</div>
	<div id=outerform
		<div id = createaccount> Don't have an account? <a href="register.php">Create one now!</a>
		and gain the ability to save what you create!
		</div>
	</div>
  </body>
</html>
