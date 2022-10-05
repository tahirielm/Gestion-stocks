<?php

session_start();
if(!isset($_SESSION["loggedin"])){
	header("location: login.php");
	exit;
}
$link = mysqli_connect("localhost", "root", "", "monster_energy");
if($link == false){
	die("FAILED LOGIN TO DATABASE");
}

if(isset($_GET['remove'])){
	$sql = "DELETE FROM articles WHERE id = ".$_GET['remove'];
	if(mysqli_query($link, $sql)){
		header("Location: articles.php");
	}
	mysqli_close($link);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$sql = "INSERT INTO articles(nom, prix, qte_stock, qte_alerte, id_f, id_d) VALUES(?, ?, ?, ?, ?, ?) ;";
	if($stmt = mysqli_prepare($link, $sql)){
		mysqli_stmt_bind_param($stmt, "ssssss", $param_nom, $param_prix, $param_qte, $param_qtea, $param_idf, $param_idd);
		
		$param_nom = $_POST['nom'];
		$param_prix = $_POST['prix'];
		$param_qte = $_POST['qte_stock'];
		$param_qtea = $_POST['qte_alerte'];
		$param_idf = $_POST['id_f'];
		$param_idd = $_POST['id_d'];
		
		if(mysqli_stmt_execute($stmt)){
			header("location: articles.php");
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
					<pre class="h2">Articles</pre>
					<button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
						Ajouter un article
					</button>
				</div>
				
				<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Ajout d'un article</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form action="articles.php" method="POST">
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="floatingNom" placeholder="Designation" name="nom" required>
										<label for="floatingNom">Designation</label>
									</div>
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="floatingPrice" placeholder="Capacite" name="prix" required>
										<label for="floatingPrice">Prix unitaire</label>
									</div>
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="floatingQte" placeholder="Quantite" name="qte_stock" required>
										<label for="floatingQte">Quantite en stock</label>
									</div>
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="floatingQtea" placeholder="QuantiteAlerte" name="qte_alerte" required>
										<label for="floatingQtea">Quantite d'alerte</label>
									</div>
									<div class="form-control">
										<h5>Fournisseur</h5>											
										<select class="form-select" aria-label="Default select example" name="id_f" id="fournisseur">
										<?php
											$sql = "SELECT id,nom,prenom FROM users WHERE type = 'fournisseur' ;";
											$res = mysqli_query($link, $sql);
											if (mysqli_num_rows($res) > 0) {
												while($r = mysqli_fetch_assoc($res)):?>
													<option value=<?php echo "'". $r['id'] ."'" ?> > <?php echo $r['nom'].' '.$r['prenom'] ?> </option>
												<?php endwhile;
											}
										?>		
										</select>
									</div>
									<div class="form-control">
										<h5>Dépot</h5>											
										<select class="form-select" aria-label="Default select example" name="id_d" id="fournisseur">
										<?php
											$sql = "SELECT * FROM depots WHERE capacite < max_capacite ;";
											$res = mysqli_query($link, $sql);
											if (mysqli_num_rows($res) > 0) {
												while($r = mysqli_fetch_assoc($res)):?>
													<option value=<?php echo "'". $r['id'] ."'" ?> > <?php echo $r['adresse'] .' ('. $r['capacite'].'/'. $r['max_capacite'].')'  ?> </option>
												<?php endwhile;
											}
										?>		
										</select>
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
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Id</th>
								<th>Designation</th>
								<th>Dépot</th>
								<th>Prix unitaire (DH)</th>
								<th>Quantite en stock</th>
								<th>Quantite d'alerte</th>
								<th>Fournisseur</th>
								<th>Actions</th>
							</tr>
						</thead>
						<?php
						$query = "SELECT * FROM articles ; ";
						$result = mysqli_query($link, $query);
						if (mysqli_num_rows($result) > 0) {
							while($row = mysqli_fetch_assoc($result)):?>
							<div class="modal fade" id="exampleModal<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalLabel">Modification d'un depot</h5>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body">
											<form action="modifyarticle.php" method="POST">
												<input type="text" class="visually-hidden" name="id" value="<?php echo $row['id'] ?>">
												<div class="form-floating mb-3">
													<input type="text" class="form-control" id="floatingNom" placeholder="Designation" name="nom" value="<?php echo $row['nom'] ?>" required>
													<label for="floatingNom">Designation</label>
												</div>
												<div class="form-floating mb-3">
													<input type="text" class="form-control" id="floatingPrice" placeholder="Capacite" name="prix" value="<?php echo $row['prix'] ?>" required>
													<label for="floatingPrice">Prix unitaire</label>
												</div>
												<div class="form-floating mb-3">
													<input type="text" class="form-control" id="floatingQte" placeholder="Quantite" name="qte_stock" required value="<?php echo $row['qte_stock'] ?>">
													<label for="floatingQte">Quantite en stock</label>
												</div>
												<div class="form-floating mb-3">
													<input type="text" class="form-control" id="floatingQtea" placeholder="QuantiteAlerte" name="qte_alerte" required value="<?php echo $row['qte_alerte'] ?>">
													<label for="floatingQtea">Quantite d'alerte</label>
												</div>
												<div class="form-control">
													<h5>Fournisseur</h5>
													<select class="form-select" aria-label="Default select example" name="id_f" id="fournisseur">
													<?php
														$sql = "SELECT id,nom,prenom FROM users WHERE type = 'fournisseur' ;";
														$res = mysqli_query($link, $sql);
														if (mysqli_num_rows($res) > 0) {
															while($r = mysqli_fetch_assoc($res)):?>
																<option value=<?php echo "'". $r['id'] ."'" ?> > <?php echo $r['nom'].' '.$r['prenom'] ?> </option>
															<?php endwhile;
														}
													?>	
													</select>
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
								<?php if($row['qte_stock']<=$row['qte_alerte']):?>
								<tr class="bg-danger">
								<?php else : ?>
								<tr>
								<?php endif ?>
									<td class="bg-light"><?php echo $row['id'] ?></td>
									<td class="bg-light"><?php echo $row['nom'] ?></td>
									<?php 
										$sqll = "SELECT adresse FROM depots WHERE id = ".$row['id_d'];
										$rr = mysqli_fetch_assoc(mysqli_query($link, $sqll));
									?>
									<td class="bg-light"><?php echo $rr['adresse']?></td>
									<td class="bg-light"><?php echo $row['prix'] ?></td>
									<td><?php echo $row['qte_stock'] ?></td>
									<td class="bg-light"><?php echo $row['qte_alerte'] ?></td>
									<?php 
										$sqll = "SELECT nom, prenom FROM users WHERE id = ".$row['id_f'];
										$rr = mysqli_fetch_assoc(mysqli_query($link, $sqll));
									?>
									<td class="bg-light"><?php echo $rr['nom'].' '.$rr['prenom'] ?></td>
									<td class="bg-light"><a class="btn btn-sm btn-outline-danger" href="?remove=<?php echo $row['id'] ?>" >Supprimer</a>
										<button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $row['id'] ?>">Modifier</button>
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