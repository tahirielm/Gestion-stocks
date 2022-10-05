<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $link = mysqli_connect("localhost", "root", "", "monster_energy");
    if($link == false){
	    die("FAILED LOGIN TO DATABASE");
    }
    else{
        $sql = "UPDATE users SET nom = ? , prenom = ? , adresse = ? , telephone = ? , email = ? WHERE id = ? ;";
	    if($stmt = mysqli_prepare($link, $sql)){
	    	mysqli_stmt_bind_param($stmt, "ssssss", $param_nom, $param_prenom, $param_adresse, $param_telephone, $param_email, $param_id);

            $param_nom = $_POST['nom'];
	    	$param_prenom = $_POST['prenom'];
	    	$param_adresse = $_POST['adresse'];
	    	$param_telephone = $_POST['telephone'];
	    	$param_email = $_POST['email'];
            $param_id = $_POST['id'];
		
	    	if(mysqli_stmt_execute($stmt)){
                if($_POST['type'] == 'client'){
	    		    header("Location: clients.php");
                }
                if($_POST['type'] == 'fournisseur'){
	    		    header("Location: fournisseurs.php");
                }
	    	}
	    	mysqli_stmt_close($stmt);
        }
    }
}