</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">

			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-navbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="index.php" class="navbar-brand">Movie Database</a>
			</div>

			<div class="collapse navbar-collapse" id="main-navbar">
				<ul class="nav navbar-nav">
					<li <?php if (isset($currPage) && $currPage == 1) echo 'class="active"'; ?>><a href="index.php">Home</a></li>
					<li class="dropdown">
              			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Search Tables <span class="caret"></span></a>
              			<ul class="dropdown-menu">
                			<li><a href="movies.php">Movies</a></li>
                			<li><a href="actors.php">Actors</a></li>
                			<li><a href="directors.php">Directors</a></li>
                			<li><a href="studios.php">Studios</a></li>
              			</ul>
              		<li <?php if (isset($currPage) && $currPage == 2) echo 'class="active"'; ?>><a href="advancedsearch.php">Advanced Search</a></li>
				</ul>

				<?php if (!isset($_SESSION['username'])) { ?>
				<form action="login.php" method="post" class="navbar-form navbar-right">
					<input type="hidden" name="page" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
					<div class="form-group">
						<input type="text" name="username" placeholder="Username" class="form-control">
					</div>
					<div class="form-group">
						<input type="password" name="password" placeholder="Password" class="form-control">
					</div>
					<button type="submit" class="btn btn-success">Sign In</button>
					<a href="register.php" class="btn btn-warning" role="button">Register</a>
				</form>
				<?php } else { ?>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="user.php?id=<?php echo $_SESSION['userid']; ?>"><?php echo $_SESSION['username']; ?></a></li>
					<li><a href="logout.php?logout">Logout</a></li>
				</ul>
				<?php } ?>
			</div>

		</div>
	</nav>