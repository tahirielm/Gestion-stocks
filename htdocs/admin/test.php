<?php
include_once '../../Classes/ClasseEtudiant.php';
include_once '../../professeurPages/Class_prof.php';
include_once '../../adminpages/Class_admin.php';
session_start();
if(isset($_SESSION['loggedin']) && $_SESSION["loggedin"] == true){
	//header("location: home.php");
	exit;
}
if($_SERVER['REQUEST_METHOD'] == "POST"){
	$link = mysqli_connect("localhost", "root", "", "pfa");
	if($link == false){
		die("FAILED LOGIN TO DATABASE");
	}
	else{
		if(isset($_POST['email']) and isset($_POST['password'])){
			$query = "SELECT * FROM admin where email = ".$_POST['email'];
			$result = mysqli_query($link, $query);
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_assoc($result);
				if($row['mdp']==$_POST['password']){
					$admin = Class_admin::construct1($row['id_admin'], $row['nom'], $row['prenom'], $row['email'], $row['mdp']);
					$_SESSION['admin'] = $admin;
					$_SESSION["loggedin"] == true
					header('location:http://localhost/MonPFA/adminpages/admin.php');
					break;
				}
			}
			$query = "SELECT * FROM etudiant where email = ".$_POST['email'];
			$result = mysqli_query($link, $query);
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_assoc($result);
				if($row['mdp']==$_POST['password']){
					$etudiant = ClasseEtudiant::construct3($row);
					$_SESSION['etudiant'] = $etudiant;
					$_SESSION["loggedin"] == true
					header('location:http://localhost/MonPFA/etudiant/profile.php');
					break;
				}
			}
			$query = "SELECT * FROM professeur where email = ".$_POST['email'];
			$result = mysqli_query($link, $query);
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_assoc($result);
				if($row['mdp']==$_POST['password']){
					$professeur = Class_prof::construct1($row['cin'], $row['nom'], $row['prenom'], $row['email'],$row['mdp'] ,$row['etat_compte']);
					$_SESSION['professeur'] = $professeur;
					$_SESSION["loggedin"] == true
					header('location:http://localhost/MonPFA/professeurPages/page_prof.php');
					break;
				}
			}
			$erreur="impossible de se connecter";
			header("location:http://localhost/MonPFA/home/login.php?erreur=$erreur");
		}
	}
}
?>