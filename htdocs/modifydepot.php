<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $link = mysqli_connect("localhost", "root", "", "monster_energy");
    if($link == false){
	    die("FAILED LOGIN TO DATABASE");
    }
    else{
        $sql = "UPDATE depots SET adresse = ?, max_capacite = ?  WHERE id = ? ;";
	    if($stmt = mysqli_prepare($link, $sql)){
	    	mysqli_stmt_bind_param($stmt, "sss", $param_adresse, $param_max, $param_id);

	    	$param_adresse = $_POST['adresse'];
            $param_max = $_POST['max_capacite'];
            $param_id = $_POST['id'];
		
	    	if(mysqli_stmt_execute($stmt)){
                header("Location: depots.php");
	    	}
	    	mysqli_stmt_close($stmt);
        }
    }
}