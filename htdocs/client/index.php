<?php

session_start();
	if(!isset($_SESSION["loggedin"]) or $_SESSION['type']!='client'){
		header("location: ../login.php");
		exit;
	}

	$link = mysqli_connect("localhost", "root", "", "monster_energy");
	if($link == false){
		die("FAILED LOGIN");
	}
	else{
		$sql1 = "SELECT count(commandes.id) FROM commandes INNER JOIN users ON users.id = commandes.id_c WHERE commandes.id_c = ".$_SESSION['id'];
		$num_users = mysqli_fetch_assoc(mysqli_query($link, $sql1));
		$sql1 = "SELECT count(id) FROM users WHERE type = 'fournisseur'";
		$num_fournisseurs = mysqli_fetch_assoc(mysqli_query($link, $sql1));
		$sql1 = "SELECT count(id) FROM users WHERE type = 'client'";
		$num_clients = mysqli_fetch_assoc(mysqli_query($link, $sql1));
		
		//mysqli_close($link);
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
	
	<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-10 shadow">
		<a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="index.php">Gestion des stocks</a>
		<button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<h4 class="navbar-brand text-center w-100">Connecté en tant que <strong><?php echo $_SESSION['username']; ?> (Administrateur)</strong> </h4>
		<ul class="navbar-nav px-3">
			<li class="nav-item text-nowrap">
				<a class="btn btn-outline-danger" href="../logout.php">
					<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
						<path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0v2z"/>
						<path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
					</svg>
					Se déconnecter
				</a>
			</li>
		</ul>
	</header>

	<div class="container-fluid">
		<div class="row">
			<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
				<div class="position-sticky pt-3">
					<ul class="nav flex-column">
						<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="index.php">
								Dashboard
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="users.php">
								Utilisateurs
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="orders.php">
								Commandes
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="products.php">
								Produits
							</a>
						</li>
						
					</ul>
				</div>
			</nav>

			<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<h1 class="h2">Dashboard</h1>
				</div>
				<div class="row align-items-md-stretch justify-content-center">
					<div class="col-md-6 ">
						<div class="h-10 p-5 mb-3 text-white bg-dark bg-gradient rounded-3">
							<h2 class="text-center d-flex justify-content-center">Date et heure actuels</h2>
							<h1 class="text-center mx-5"> <?php echo date("Y-m-d H:i"); ?> </h1>
						</div>
					</div>
				</div>
				<div class="row align-items-md-stretch">
					<div class="col-md-4">
						<div class="h-10 p-5 text-white bg-primary bg-gradient rounded-3">
							<h2>Nombre de commandes effectuées</h2>
							<h1 class="text-center"> <?php echo $num_users["count(commandes.id)"]; ?> </h1>
						</div>
						<div class="row">
							<h4>Nombre de clients <?php echo $num_clients["count(id)"]; ?> </h4>
						</div>
						<div class="row">
							<h4>Nombre de fournisseurs <?php echo $num_fournisseurs["count(id)"]; ?> </h4>
						</div>
					</div>
					
				</div>
			</main>
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