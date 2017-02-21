<?php
	include("inc/config.php");
	session_start();

	$title = "Register";
	$currPage = 100;

	if (isset($_SESSION['username'])) {
		header("location: index.php");
	}

	if (isset($_POST['submit'])) {
		if ($_POST['password'] != $_POST['passwordCheck']) {
			$_SESSION['error'] = "The passwords entered were not the same.";
			header("location: register.php");
			exit();
		}

		$upcase = preg_match('@[A-Z]@', $_POST['password']);
		$lowcase = preg_match('@[a-z]@', $_POST['password']);
		$num = preg_match('@[0-9]@', $_POST['password']);

		if (!$upcase || !$lowcase || !$num || strlen($_POST['password']) < 8) {
			$_SESSION['error'] = "Password needs to contain an uppercase letter, lowercase letter and a number.";
			header("location: register.php");
			exit();
		}

		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$user = mysqli_real_escape_string($db, $_POST['username']);
		$pass = mysqli_real_escape_string($db, $_POST['password']);

		try {
			$userCheck = "SELECT uname FROM users WHERE uname = '$user'";
			$result = mysqli_query($db, $userCheck);
			$count = mysqli_num_rows($result);
			if ($count != 0) {
				mysqli_close($db);
				$_SESSION['error'] = "That username is taken.";
				header("location: register.php");
				exit();
			}

			$passHash = password_hash($pass, PASSWORD_DEFAULT);

			$insert = "INSERT INTO users(`uname`, `pass`) VALUES ('$user', '$passHash')";

			mysqli_query($db, $insert);

			$_SESSION['username'] = $_POST['username'];

			$query = "SELECT * FROM users WHERE uname = '" . $_SESSION['username'] . "'";

			$res = mysqli_query($db, $query);
			$row = mysqli_fetch_array($res);
			$_SESSION['userid'] = $row['id'];

			mysqli_close($db);
			header("location: index.php");
		}
		catch (Exception $e) {
			echo "Could not connect to the database.";
			mysqli_close($db);
			exit();
		}
	}

	include_once("inc/head.php");
	include_once("inc/nav.php");

?>
	<div class="container">
		<?php if (isset($_SESSION['error'])) { echo "<h4 class='text-danger' style='max-width:300px; margin:0 auto; padding-top: 15px;'>" . $_SESSION['error'] . "</h4>"; unset($_SESSION['error']); } ?>
		<form action="" method="post" style="max-width:300px; margin:0 auto; padding-top: 15px;">
			<div class="form-group">
				<input type="text" name="username" placeholder="Username" class="form-control" required autofocus>
			</div>
			<div class="form-group">
				<input type="password" name="password" placeholder="Password" class="form-control" required>
			</div>
			<div class="form-group">
				<input type="password" name="passwordCheck" placeholder="Repeat Password" class="form-control" required>
			</div>
			<button type="submit" name="submit" class="btn btn-success">Register</button>
		</form>
	</div>
</body>
</html>