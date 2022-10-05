<?php

session_start();
if(!isset($_SESSION["loggedin"]) or $_SESSION['type']!='admin'){
	header("location: ../login.php");
	exit;
}

if(isset($_GET['remove'])){
	$link = mysqli_connect("localhost", "root", "", "monster_energy");
	if($link == false){
		die("FAILED LOGIN");
	}
	else{
		$sql = "DELETE FROM products WHERE id = ".$_GET['remove'];
		if(mysqli_query($link, $sql)){
			header("Location: products.php");
		} else{
			echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
		}
		mysqli_close($link);
	}
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$link = mysqli_connect("localhost", "root", "", "monster_energy");
	if($link == false){
		die("FAILED LOGIN");
	}
	else{
		$sql = "INSERT INTO products (nom, prix, qte, id_f) VALUES (?, ?, ?, ?)";
		if($stmt = mysqli_prepare($link, $sql)){
			mysqli_stmt_bind_param($stmt, "ssss", $param_nom, $param_prix, $param_qte, $param_idf);

			$param_nom = $_POST['nom'];
			$param_prix = $_POST['prix'];
			$param_qte = $_POST['qte'];
			$param_idf = $_POST['id_f'];

			if(mysqli_stmt_execute($stmt)){
				header("location: products.php");
			}

			mysqli_stmt_close($stmt);
		}
	}
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
					<h1 class="h2">Produits</h1>
					<button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
						Ajouter un produit
					</button>
				</div>

				<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Ajout d'un Produit</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form action="products.php" method="POST">
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="floatingEmail" placeholder="Nom" name="nom" required>
										<label for="floatingInput">Nom du produit</label>
									</div>
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="floatingPrix" placeholder="Prix" name="prix" required>
										<label for="floatingPrix">Prix (DH)</label>
									</div>
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="floatingQte" placeholder="Qte" name="qte" required>
										<label for="floatingQte">Quantité</label>
									</div>
									<select class="form-select" aria-label="Default select example" name="id_f" id="fournisseurs">
									<?php 
									$link = mysqli_connect("localhost", "root", "", "monster_energy");
									if($link == false){
										die("FAILED LOGIN");
									}
									else{
										$query = "SELECT id,username FROM users WHERE type = 'fournisseur'";
										$result = mysqli_query($link, $query);
										if (mysqli_num_rows($result) > 0) {
											while($row = mysqli_fetch_assoc($result)):?>
													<option value=<?php echo "'". $row['id'] ."'" ?> > <?php echo $row['username'] ?> </option>
											<?php endwhile;
										}
									}
									?>
									</select>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Quitter</button>
									<button class="btn btn-primary" type="submit" >Enregistrer</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-striped table-sm">
						<thead>
							<tr>
								<th>Nom du produit</th>
								<th>Prix unitaire(DH)</th>
								<th>Quantité en stock</th>
								<th>Nom du fournisseur</th>
								<th>Actions</th>
							</tr>
						</thead>
						<?php
						$link = mysqli_connect("localhost", "root", "", "monster_energy");
						if($link == false){
							die("FAILED LOGIN");
						}
						else{
							$query = "SELECT * FROM products";
							$result = mysqli_query($link, $query);
							if (mysqli_num_rows($result) > 0) {
								while($row = mysqli_fetch_assoc($result)):?>
									<?php 
									$sql = "SELECT users.username FROM users INNER JOIN products ON users.id = products.id_f WHERE products.id_f = ?;";
									$stmt = mysqli_prepare($link, $sql) ;
									mysqli_stmt_bind_param($stmt, "s", $param_idf);
									$param_idf = $row['id_f'];
									mysqli_stmt_execute($stmt);
									mysqli_stmt_store_result($stmt);
									mysqli_stmt_bind_result($stmt, $nom_fournisser);
									mysqli_stmt_fetch($stmt);
									;?>
									<tbody>
										<tr>
											<td><?php echo $row['nom'] ?></td>
											<td><?php echo $row['prix'] ?></td>
											<td><?php echo $row['qte'] ?></td>
											<td><?php echo $nom_fournisser ?></td>
											<td><a class="btn btn-sm btn-outline-danger" href="?remove=<?php echo $row['id'] ?>" >Supprimer</a>
											</tbody>
										<?php endwhile;
									}
								}
								?>
							</table>
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