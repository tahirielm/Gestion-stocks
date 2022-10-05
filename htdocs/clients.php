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
	$sql = "DELETE FROM users WHERE id = ".$_GET['remove'];
	if(mysqli_query($link, $sql)){
		header("Location: clients.php");
	}
	mysqli_close($link);
}

if(isset($_GET['fournisseur'])){
	$sql = "UPDATE users SET type = 'fournisseur' WHERE id = ".$_GET['fournisseur'];
	if(mysqli_query($link, $sql)){
		header("Location: clients.php");
	}
	mysqli_close($link);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$sql = "INSERT INTO users (nom, prenom, adresse, telephone, email, type) VALUES (?, ?, ?, ?, ?, 'client') ;";
	if($stmt = mysqli_prepare($link, $sql)){
		mysqli_stmt_bind_param($stmt, "sssss", $param_nom, $param_prenom, $param_adresse, $param_telephone, $param_email);
		
		$param_nom = $_POST['nom'];
		$param_prenom = $_POST['prenom'];
		$param_adresse = $_POST['adresse'];
		$param_telephone = $_POST['telephone'];
		$param_email = $_POST['email'];
		
		if(mysqli_stmt_execute($stmt)){
			header("location: clients.php");
		}
		mysqli_stmt_close($stmt);
	}
}
//$keys=array("nom","prenom","adresse","telephone", "email");
//$row = array_fill_keys($keys, "");
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
					<pre class="h2">Clients</pre>
					<button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
						Ajouter un client
					</button>
				</div>
				
				<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Ajout d'un client</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form action="clients.php" method="POST">
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="floatingNom" placeholder="Nom" name="nom" required>
										<label for="floatingNom">Nom</label>
									</div>
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="floatingPrenom" placeholder="Prenom" name="prenom" required>
										<label for="floatingPrenom">Prenom</label>
									</div>
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="floatingAdresse" placeholder="Adresse" name="adresse" required>
										<label for="floatingAdresse">Adresse</label>
									</div>
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="floatingTel" placeholder="Telephone" name="telephone" required>
										<label for="floatingTel">Telephone</label>
									</div>
									<div class="form-floating mb-3">
										<input type="email" class="form-control" id="floatingEmail" placeholder="Email" name="email" required>
										<label for="floatingInput">Email</label>
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
								<th>Nom et prénom</th>
								<th>Adresse</th>
								<th>Telephone</th>
								<th>Email</th>
								<th>Actions</th>
							</tr>
						</thead>
						<?php
						$query = "SELECT * FROM users WHERE type = 'client' ; ";
						$result = mysqli_query($link, $query);
						if (mysqli_num_rows($result) > 0) {
							while($row = mysqli_fetch_assoc($result)):?>
							<div class="modal fade" id="exampleModal<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalLabel">Modification d'un client</h5>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body">
											<form action="modify.php" method="POST">
												<input type="text" class="visually-hidden" name="id" value="<?php echo $row['id'] ?>">	
												<input type="text" class="visually-hidden" name="type" value="client">
												<div class="form mb-3">
													<label>Nom</label>
													<input type="text" class="form-control" id="floatingNom" name="nom" required value="<?php echo $row['nom'] ?>">
												</div>
												<div class="form mb-3">
													<label>Prenom</label>
													<input type="text" class="form-control" id="floatingPrenom" name="prenom" required value="<?php echo $row['prenom'] ?>">
												</div>
												<div class="formg mb-3">
													<label>Adresse</label>
													<input type="text" class="form-control" id="floatingAdresse" name="adresse" required value="<?php echo $row['adresse'] ?>">
												</div>
												<div class="form mb-3">
													<label>Telephone</label>
													<input type="text" class="form-control" id="floatingTel" name="telephone" required value="<?php echo $row['telephone'] ?>">
												</div>
												<div class="form mb-3">
													<label>Email</label>
													<input type="email" class="form-control" id="floatingEmail" name="email" required value="<?php echo $row['email'] ?>">
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
									<td><?php echo $row['nom'] . " " . $row['prenom'] ?></td>
									<td><?php echo $row['adresse'] ?></td>
									<td><?php echo $row['telephone'] ?></td>
									<td><?php echo $row['email'] ?></td>
									<td><a class="btn btn-sm btn-outline-danger" href="?remove=<?php echo $row['id'] ?>" >Supprimer</a>
										<a class="btn btn-sm btn-outline-primary" href="?fournisseur=<?php echo $row['id'] ?>" >Rendre fournisseur</a>
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