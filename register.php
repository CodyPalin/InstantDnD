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
		<form id = form method="post" action="register-handler.php">
		  <div>Username: <input type="text" name="username"></div>
		  <div>Password: <input type="password" name="password"></div>
		  <div>Confirm Password: <input type="password" name="confirm_password"></div>
		  <?php
		  if (isset($_SESSION['message'])) {
			echo "<div id='message'>" . $_SESSION['message'] . "</div>";
		  }
		  unset($_SESSION['message']);
		  ?>
		  <div><input type="submit" value="register"></div>
		</form>
	</div>
	<div id=outerform
		<div id = createaccount> 
		Already have an account? <a href="login.php">Login!</a>
		</div>
	</div>
  </body>
</html>
