<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $link = mysqli_connect("localhost", "root", "", "monster_energy");
    if($link == false){
	    die("FAILED LOGIN TO DATABASE");
    }
    else{
        $sql = "UPDATE commandes SET id_c = ?, id_p = ?, qte_commande = ?  WHERE id = ? ;";
	    if($stmt = mysqli_prepare($link, $sql)){
	    	mysqli_stmt_bind_param($stmt, "ssss", $param_idc, $param_idp, $param_qte, $param_id);

	    	$param_idc = $_POST['id_c'];
            $param_idp = $_POST['id_p'];
            $param_qte = $_POST['qte'];
            $param_id = $_POST['id'];
		
	    	if(mysqli_stmt_execute($stmt)){
                header("Location: commandes.php");
	    	}
	    	mysqli_stmt_close($stmt);
        }
    }
}