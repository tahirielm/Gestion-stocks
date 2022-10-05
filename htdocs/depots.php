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
	$sql = "DELETE FROM depots WHERE id = ".$_GET['remove'];
	if(mysqli_query($link, $sql)){
		header("Location: depots.php");
	}
	mysqli_close($link);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$sql = "INSERT INTO depots(adresse, capacite, max_capacite) VALUES(?, ?, ?) ;";
	if($stmt = mysqli_prepare($link, $sql)){
		mysqli_stmt_bind_param($stmt, "sss", $param_adresse, $param_capacite, $param_maxcapacite);
		
		$param_adresse = $_POST['adresse'];
		$param_capacite = $_POST['capacite'];
		$param_maxcapacite = $_POST['max_capacite'];
		
		if(mysqli_stmt_execute($stmt)){
			header("location: depots.php");
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
					<pre class="h2">Depots</pre>
					<button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
						Ajouter un depot
					</button>
				</div>
				
				<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Ajout d'un depot</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form action="depots.php" method="POST">
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="floatingAdresse" placeholder="Adresse" name="adresse" required>
										<label for="floatingAdresse">Adresse</label>
									</div>
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="floatingAdresse" placeholder="Capacite" name="capacite">
										<label for="floatingAdresse">Capacité initiale</label>
									</div>
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="floatingAdresse" placeholder="MaxCapacite" name="max_capacite" required>
										<label for="floatingAdresse">Capacité maximale</label>
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
								<th>Adresse</th>
								<th>Capacité/Capacité maximale</th>
								<th>Actions</th>
							</tr>
						</thead>
						<?php
						$query = "SELECT * FROM depots ; ";
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
											<form action="modifydepot.php" method="POST">
												<input type="text" class="visually-hidden" name="id" value="<?php echo $row['id'] ?>">
												<div class="formg mb-3">
													<label>Adresse</label>
													<input type="text" class="form-control" id="floatingAdresse" name="adresse" required value="<?php echo $row['adresse'] ?>">
												</div>
												<div class="form-floating mb-3">
													<input type="text" class="form-control" id="floatingAdresse" placeholder="Capacite" name="capacite" value="<?php echo $row['capacite'] ?>" disabled>
													<label for="floatingAdresse">Capacité Actuelle</label>
												</div>
												<div class="form-floating mb-3">
													<input type="text" class="form-control" id="floatingAdresse" placeholder="MaxCapacite" name="max_capacite" required value="<?php echo $row['max_capacite'] ?>">
													<label for="floatingAdresse">Capacité maximale</label>
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
								<?php if($row['capacite']>=$row['max_capacite']):?>
								<tr class="bg-danger bg-gradient">
								<?php else : ?>
								<tr>
								<?php endif ?>
									<td class="bg-light"><?php echo $row['id'] ?></td>
									<td class="bg-light"><?php echo $row['adresse'] ?></td>
									<td><?php echo $row['capacite'].'/'.$row['max_capacite'] ?></td>
									<td class="bg-light"><a class="btn btn-sm btn-outline-danger" href="?remove=<?php echo $row['id'] ?>" >Supprimer</a>
										<button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $row['id'] ?>">Modifier</button>
										<button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#depot<?php echo $row['id'] ?>" aria-expanded="false" aria-controls="depot<?php echo $row['id'] ?>" >Inventaire</button>
									</td>
								</tr>
								<tr>
				        		    <td colspan="3" class="collapse" id="depot<?php echo $row['id'] ?>" >
					    		        <table class="table table-bordered">
					    		        	<thead>
					    		        		<tr>
					    		        			<th>Id</th>
					    		        			<th>Designation</th>
					    		        			<th>PU (Dhs)</th>
					    		        			<th>Quantité en stock</th>
					    		        			<th>Valeur</th>
					    		        		</tr>
					    		        	</thead>
					    		        	<tbody>
					    		        		<?php 
					    		        		$sql = "SELECT sum(prix*qte_stock) as total FROM articles WHERE id_d = ".$row['id'];
												$valeur = mysqli_fetch_assoc(mysqli_query($link, $sql));
					    		        		
					    		        		$q = "SELECT * FROM articles WHERE id_d = ".$row['id'];
												$r = mysqli_query($link, $q);
												if (mysqli_num_rows($r) > 0) {
													while($rr = mysqli_fetch_assoc($r)):?>
														<tr>
															<td><?php echo $rr['id'] ?> </td>
															<td><?php echo $rr['nom'] ?></td>
															<td><?php echo $rr['prix'] ?></td>
															<td><?php echo $rr['qte_stock'] ?></td>
															<td><?php echo $rr['prix']*$rr['qte_stock'] .' Dhs'?></td>
														</tr>
													<?php endwhile; }?>
													<tr class="table-info">
														<td class="text-center" colspan="3"><strong>Total</strong></td>
														<td class="text-center" colspan="2"><?php echo $valeur['total'] .' Dhs' ?> </td>
													</tr>

					    		        	</tbody>
					    		        	
					    		        </table>
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