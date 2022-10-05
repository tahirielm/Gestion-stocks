<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $link = mysqli_connect("localhost", "root", "", "monster_energy");
    if($link == false){
        die("FAILED LOGIN TO DATABASE");
    }
    else{
        $query = "SELECT * FROM articles WHERE id = ".$_POST['id'];
        $r = mysqli_query($link, $query);
        $article = mysqli_fetch_assoc($r);
        $diff = $article['qte_stock'] - $_POST['qte_stock'];
        $query1 = "UPDATE depots SET capacite = capacite-? WHERE id = ?" ;
        $stmt1 = mysqli_prepare($link, $query1);
        mysqli_stmt_bind_param($stmt1, "ss", $diff, $article['id_d']);
        mysqli_stmt_execute($stmt1);


        $sql = "UPDATE articles SET nom = ?, prix = ?, qte_stock = ?, qte_alerte = ?, id_f = ?  WHERE id = ? ;";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ssssss", $param_nom, $param_prix, $param_qte, $param_qtea, $param_idf, $param_id);

            $param_nom = $_POST['nom'];
            $param_prix = $_POST['prix'];
            $param_qte = $_POST['qte_stock'];
            $param_qtea = $_POST['qte_alerte'];
            $param_idf = $_POST['id_f'];
            $param_id = $_POST['id'];
        
            if(mysqli_stmt_execute($stmt)){
                header("Location: articles.php");
            }
            mysqli_stmt_close($stmt);
        }
    }
}