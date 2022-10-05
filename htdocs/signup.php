<?php

$email = $username = $password = $confirm_password = "";
$ve = $vu = $vp = $vcp = true;

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$link = mysqli_connect("localhost", "root", "", "monster_energy");
	if($link === false){
		die("FAILED LOGIN TO DATABASE ");
	}
	$sql = "INSERT INTO admin (username, password) VALUES (?, ?)";
	if($stmt = mysqli_prepare($link, $sql)){
		mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
		
		$param_username = $_POST['username'];
		$param_password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
		if(mysqli_stmt_execute($stmt)){
			header("location: login.php");
		}
		mysqli_stmt_close($stmt);
	}
	mysqli_close($link);
}
?>

<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	
	
	<title>Gestion stocks</title>
</head>
<body>
	<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
		<div class="container-fluid">
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarCollapse"s>
				<ul class="navbar-nav me-auto mb-2 mb-md-0">
					<li class="nav-item">
						<a class="nav-link" href="index.html">Accueil</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="index.html#aboutus">A propos de nous</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="index.html#contactus">Nous contacter</a>
					</li>
				</ul>
				<form class="d-flex">
					<a class="btn btn-outline-success " href="signup.php">
						<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
							<path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
							<path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
						</svg>
						S'enregistrer
					</a>
					<a>‎ ‎<a>
						<a class="btn btn-outline-success" href="login.php">
							<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
								<path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
								<path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
							</svg>
							Se Connecter
						</a>
					</form>
				</div>
			</div>
		</nav>
		
		
		<div class="container col-xl-10 col-xxl-8 px-4 py-5">
			<div class="row align-items-center g-lg-5 py-5">
				<div class="col-lg-7 text-center text-lg-start">
					<h1 class="display-4 fw-bold lh-1 mb-3">S'enregistrer</h1>
				</div>
				<div class="col-md-10 mx-auto col-lg-5">
					
					<form class="p-4 p-md-5 border rounded-3 bg-light shadow-lg" action="signup.php" method="POST">
						<div class="form-floating mb-3">
							<input type="text" class="form-control" id="floatingInput" placeholder="Username" name="username">
							<label for="floatingInput">Username</label>
						</div>
						<div class="form-floating mb-3">
							<input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
							<label for="floatingPassword">Password</label>
						</div>
						<button class="w-100 btn btn-lg btn-primary" type="submit">S'enregistrer</button>
						<hr class="my-4">
						<small class="text-muted">Vos informations sont securisés</small>
					</form>
				</div>
			</div>
		</div>
		
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
		
		<footer class="footer mt-auto py-3 bg-light text-center">
			<div class="container">
				<span class="text-muted">Copyright<sup>(C)</sup> All rights reserved 2021</span>
			</div>
		</footer>
		
	</body>
	</html>