<?php

session_start();
	if(!isset($_SESSION["loggedin"])){
		header("location: login.php");
		exit;
	}

	$link = mysqli_connect("localhost", "root", "", "monster_energy");
	if($link == false){
		die("FAILED LOGIN");
	}
	else{
		$sql = "SELECT sum(prix*qte_stock) FROM articles";
		$valeur = mysqli_fetch_assoc(mysqli_query($link, $sql));

		$sql1 = "SELECT count(id) FROM users WHERE type = 'fournisseur' OR type = 'client' ;";
		$num_users = mysqli_fetch_assoc(mysqli_query($link, $sql1));
		$sql1 = "SELECT count(id) FROM users WHERE type = 'fournisseur'";
		$num_fournisseurs = mysqli_fetch_assoc(mysqli_query($link, $sql1));
		$sql1 = "SELECT count(id) FROM users WHERE type = 'client'";
		$num_clients = mysqli_fetch_assoc(mysqli_query($link, $sql1));

		$sql2 = "SELECT count(id) FROM articles";
		$num_products = mysqli_fetch_assoc(mysqli_query($link, $sql2));
		$sql2 = "SELECT sum(qte_stock) FROM articles";
		$qte = mysqli_fetch_assoc(mysqli_query($link, $sql2));
		$sql2 = "SELECT count(id) FROM articles WHERE qte_stock <= qte_alerte ;";
		$alerte = mysqli_fetch_assoc(mysqli_query($link, $sql2));
		
		$sql3 = "SELECT count(id) FROM commandes";
		$num_commandes = mysqli_fetch_assoc(mysqli_query($link, $sql3));
		$sql3 = "SELECT count(id) FROM commandes WHERE valide = 'oui'";
		$num_commandes_valides = mysqli_fetch_assoc(mysqli_query($link, $sql3));
		$sql3 = "SELECT count(id) FROM commandes WHERE valide = 'non'";
		$num_commandes_effectues = mysqli_fetch_assoc(mysqli_query($link, $sql3));
		
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
	
	<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-10 shadow">
		<?php include("navbar.html") ?>
	</header>

	<div class="container-fluid">
		<div class="row">
			<?php include("leftnav.html"); ?>

			<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<pre class="h2">Tableau de bord		 <?php echo date("Y-m-d H") ."h"; ?></pre>
				</div>
				<div class="row align-items-md-stretch justify-content-center">
					<div class="col-md-4 ">
						<div class="h-10 p-5 mb-3 text-white bg-success bg-gradient rounded-3">
							<h2 class="text-center d-flex justify-content-center">Valeur du stock</h2>
							<h1 class="text-center mx-5"> <?php echo $valeur["sum(prix*qte_stock)"].' Dhs' ?> </h1>
						</div>
					</div>
				</div>
				<div class="row align-items-md-stretch">
					<div class="col-md-4">
						<div class="h-10 p-5 text-white bg-primary bg-gradient rounded-3">
							<h2>Nombre d'utilisateurs</h2>
							<h1 class="text-center"> <?php echo $num_users["count(id)"]; ?> </h1>
						</div>
						<div class="row">
							<h4>Nombre de clients <?php echo $num_clients["count(id)"]; ?> </h4>
						</div>
						<div class="row">
							<h4>Nombre de fournisseurs <?php echo $num_fournisseurs["count(id)"]; ?> </h4>
						</div>
					</div>
					<div class="col-md-4">
						<div class="h-10 p-5 text-white bg-primary bg-gradient rounded-3">
							<h2>Nombre de produits</h2>
							<h1 class="text-center"> <?php echo $num_products["count(id)"]; ?> </h1>
						</div>
						<div class="row">
							<h4>Total d'articles <?php echo $qte["sum(qte_stock)"]; ?> </h4>
							<h4 style="color: red">Nombre de produits en alerte <?php echo $alerte["count(id)"]; ?> </h4>
						</div>
					</div>
					<div class="col-md-4">
						<div class="h-10 p-5 text-white bg-primary bg-gradient rounded-3">
							<h2>Nombre de commandes</h2>
							<h1 class="text-center"> <?php echo $num_commandes["count(id)"]; ?> </h1>
						</div>
						<div class="row">
							<h4>Commandes en attente <?php echo $num_commandes_effectues["count(id)"]; ?> </h4>
						</div>
						<div class="row">
							<h4>Commandes validés <?php echo $num_commandes_valides["count(id)"]; ?> </h4>
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