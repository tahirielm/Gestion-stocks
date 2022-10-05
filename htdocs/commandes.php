<?php

session_start();
$link = mysqli_connect("localhost", "root", "", "monster_energy");
if($link == false){
	die("FAILED LOGIN TO DATABASE");
}

if(!isset($_SESSION["loggedin"])){
	header("location: login.php");
	exit;
}

if(isset($_GET['remove'])){
	$sql = "DELETE FROM commandes WHERE id = ".$_GET['remove'];
	if(mysqli_query($link, $sql)){
		header("Location: commandes.php");
	} else{
		echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
	}
	mysqli_close($link);
}

if (isset($_GET['valider'])) {
	$sql = "SELECT * FROM commandes WHERE id = ".$_GET['valider'];
	$result = mysqli_query($link, $sql);
	$commande = mysqli_fetch_assoc($result);

	$sql = "SELECT * FROM articles WHERE id = ".$commande['id_p'];
	$r = mysqli_query($link, $sql);
	$article = mysqli_fetch_assoc($r);
	if ($commande['qte_commande']<=$article['qte_stock']) {
		$query = "UPDATE articles SET qte_stock = qte_stock-? WHERE id = ?";
		$stmt = mysqli_prepare($link, $query);
		mysqli_stmt_bind_param($stmt, "ss", $commande['qte_commande'], $commande['id_p']);
		mysqli_stmt_execute($stmt);

		$query2 = "UPDATE depots SET capacite = capacite-? WHERE id = ?";
		$stmt2 = mysqli_prepare($link, $query2);
		mysqli_stmt_bind_param($stmt2, "ss", $commande['qte_commande'], $article['id_d']);
		mysqli_stmt_execute($stmt2);

		$query3 = "UPDATE commandes SET valide = 'oui' WHERE id =".$_GET['valider'];
		$stmt3 = mysqli_prepare($link, $query3);
		mysqli_stmt_execute($stmt3);

		header("Location: commandes.php");
	}
}


if($_SERVER["REQUEST_METHOD"] == "POST"){
	$sql = "INSERT INTO commandes (id_c, id_p, qte_commande) VALUES (?, ?, ?)";
	if($stmt = mysqli_prepare($link, $sql)){
		mysqli_stmt_bind_param($stmt, "sss", $param_idc, $param_idp, $param_qte);

		$param_idc = $_POST['id_c'];
		$param_idp = $_POST['id_p']; 
		$param_qte = $_POST['qte'];

		if(mysqli_stmt_execute($stmt)){
			header("location: commandes.php");
		}

		mysqli_stmt_close($stmt);
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
		<?php include("navbar.html") ?>
	</header>

	<div class="container-fluid">
		<div class="row">
			<?php include("leftnav.html") ?>

			<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<pre class="h2">Commandes</pre>
					<button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
						Ajouter une commande
					</button>
				</div>

				<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Ajout d'une commande</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form action="commandes.php" method="POST">
									<h5>Client</h5>
									<select class="form-select" aria-label="Default select example" name="id_c" id="clients">
										<?php
										$query = "SELECT id,nom,prenom FROM users WHERE type = 'client'";
										$result = mysqli_query($link, $query);
										if (mysqli_num_rows($result) > 0) {
											while($row = mysqli_fetch_assoc($result)):?>
												<option value=<?php echo "'". $row['id'] ."'" ?> > <?php echo $row['nom'].' '.$row['prenom'] ?> </option>
											<?php endwhile;
										}
										?>	
									</select>
									<br>
									<h5>Produit</h5>
									<select class="form-select" aria-label="Default select example" name="id_p" id="products">
										<?php
										$query = "SELECT id,nom FROM articles";
										$result = mysqli_query($link, $query);
										if (mysqli_num_rows($result) > 0) {
											while($row = mysqli_fetch_assoc($result)):?>
												<option value=<?php echo "'". $row['id'] ."'" ?> > <?php echo $row['nom'] ?> </option>
											<?php endwhile;
										}
										?>	
									</select>
									<br>
									<div class="form-floating mb-3">
										<input type="number" min="0" class="form-control" id="floatingQte" placeholder="Qte" name="qte" required>
										<label for="floatingQte">Quantité</label>
									</div>
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
					<table class="table table-hover table-sm">
						<thead>
							<tr>
								<th>Id</th>
								<th>Client</th>
								<th>Article</th>
								<th>P.U</th>
								<th>Qte</th>
								<th>Total</th>
								<th>Validé</th>
								<th>Date</th>
								<th>Actions</th>
							</tr>
						</thead>
						<?php
						$query = "SELECT * FROM commandes";
						$result = mysqli_query($link, $query);
						if (mysqli_num_rows($result) > 0) {
							while($row = mysqli_fetch_assoc($result)):?>
								<div class="modal fade" id="exampleModal<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Modification d'une commande</h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
												<form action="modifycommande.php" method="POST">
													<input type="text" class="visually-hidden" name="id" value="<?php echo $row['id'] ?>">
													<h5>Client</h5>
													<select class="form-select" aria-label="Default select example" name="id_c" id="clients">
														<?php
														$sql1 = "SELECT id,nom,prenom FROM users WHERE type = 'client'";
														$re1 = mysqli_query($link, $sql1);
														if (mysqli_num_rows($re1) > 0) {
															while($r1 = mysqli_fetch_assoc($re1)):?>
																<option value=<?php echo "'". $r1['id'] ."'" ?> > <?php echo $r1['nom'].' '.$r1['prenom'] ?> </option>
															<?php endwhile;
														}
														?>	
													</select>
													<br>
													<h5>Produit</h5>
													<select class="form-select" aria-label="Default select example" name="id_p" id="products">
														<?php
														$sql2 = "SELECT id,nom,qte_stock FROM articles";
														$re2 = mysqli_query($link, $sql2);
														if (mysqli_num_rows($re2) > 0) {
															while($r2 = mysqli_fetch_assoc($re2)):?>
																<option value="<?php echo $r2['id'] ?>"> <?php echo $r2['nom'] ?> </option>
															<?php endwhile;
														}
														?>	
													</select>
													<br>
													<div class="form-floating mb-3">
														<input type="number" class="form-control" id="floatingQte" placeholder="Qte" name="qte" value="<?php echo $row['qte_commande'] ?>" required  min="0" max="<?php echo $r2['qte_stock'] ?>">
														<label for="floatingQte">Quantité</label>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Quitter</button>
													<button class="btn btn-primary" type="submit" >Enregistrer</button>
												</div>
											</form>
										</div>
									</div>
								</div>
								<tbody>
									<tr>
										<td><?php echo $row['id'] ?></td>
										<?php 
										$sql = "SELECT nom, prenom FROM users INNER JOIN commandes ON users.id = commandes.id_c WHERE commandes.id_c = ".$row['id_c'];
										$stmt = mysqli_prepare($link, $sql);
										mysqli_stmt_execute($stmt);
										mysqli_stmt_store_result($stmt);
										mysqli_stmt_bind_result($stmt, $nom, $prenom);
										mysqli_stmt_fetch($stmt);
										;?>
										<td><?php echo $nom.' '.$prenom ?></td>
										<?php 
										$sql1 = "SELECT nom, prix FROM articles INNER JOIN commandes ON articles.id = commandes.id_p WHERE commandes.id_p = ".$row['id_p'];
										$re1 = mysqli_query($link, $sql1);
										if (mysqli_num_rows($re1) > 0) {
											$r1 = mysqli_fetch_assoc($re1);
											$nom_article = $r1['nom'];
											$prix_article = $r1['prix'];
										}
										;?>
										<td><?php echo $nom_article ?></td>
										<td><?php echo $prix_article.' Dhs' ?></td>
										<td><?php echo $row['qte_commande'] ?></td>
										<td><?php echo $row['qte_commande']*$prix_article .' Dhs' ?></td>
										<?php 
										if($row['valide'] == 'oui'){
											echo "<td> Oui </td>";
										}
										else if ($row['valide'] == 'non') :?>
											<td>
												<a class="btn btn-sm btn-outline-primary" href="?valider=<?php echo $row['id'] ?>">VALIDER</a>
											</td>
										<?php endif ?>
										<td><?php echo $row['date_commande'] ?></td>
										<td><a class="btn btn-sm btn-outline-danger" href="?remove=<?php echo $row['id'] ?>" >Supprimer</a>
											<?php if($row['valide'] == 'non') : ?>
											<button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $row['id'] ?>">Modifier</button>
											<?php endif ?>
											<?php if($row['valide'] == 'oui') : ?>
											<a type="button" href="receipt.php?id=<?php echo $row['id'] ?>" class="btn btn-sm btn-outline-primary">Telecharger recu</a>
											<?php endif ?>
										</td>
									</tr>
									</tbody>
								<?php endwhile;
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