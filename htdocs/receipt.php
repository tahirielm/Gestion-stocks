<?php

if(isset($_GET['id'])){
	$link = mysqli_connect("localhost", "root", "", "monster_energy");
	if($link == false){
		die("FAILED LOGIN TO DATABASE");
	}

	$sql = "SELECT * FROM commandes WHERE id = ".$_GET['id'];
	$result = mysqli_query($link, $sql);
	$commande = mysqli_fetch_assoc($result);

	$sql = "SELECT * FROM articles WHERE id = ".$commande['id_p'];
	$r = mysqli_query($link, $sql);
	$article = mysqli_fetch_assoc($r);

	$sql = "SELECT * FROM users WHERE id = ".$commande['id_c'];
	$rr = mysqli_query($link, $sql);
	$client = mysqli_fetch_assoc($rr);

	$ncommande = $commande['id'];

	$adresse = $client['adresse'];
	$email = $client['email'];
	$telephone = $client['telephone'];
	$nom = $client['nom'];
	$prenom = $client['prenom'];

	$date = $commande['date_commande'];
	$quantite = $commande['qte_commande'];

	$nom_article = $article['nom'];
	$prix = $article['prix'];
	$total = $quantite*$prix;


	/*call the FPDF library*/
	require('fpdf.php');

		
	/*A4 width : 219mm*/

	$pdf = new FPDF('P','mm','A4');

	$pdf->AddPage();
	/*output the result*/

	/*set font to arial, bold, 14pt*/
	$pdf->SetFont('Arial','B',20);

	/*Cell(width , height , text , border , end line , [align] )*/

	$pdf->Cell(71 ,10,'',0,0);
	$pdf->Cell(59 ,5,'Bon de commande',0,0);
	$pdf->Cell(59 ,10,'',0,1);

	$pdf->SetFont('Arial','B',15);
	$pdf->Cell(71 ,5,'',0,0);
	$pdf->Cell(59 ,5,'',0,0);
	$pdf->Cell(59 ,5,'Details',0,1);

	$pdf->SetFont('Arial','',10);

	$pdf->Cell(130 ,5,'Adresse : '.$adresse,0,0);
	$pdf->Cell(25 ,5,'Nom et prenom : '.$nom.' '.$prenom,0,1);

	$pdf->Cell(130 ,5,'Telephone : '.$telephone,0,0);
	$pdf->Cell(25 ,5,'Date commande : '.$date,0,1);

	$pdf->Cell(130 ,5,'Email : '.$email,0,0);
	 

	$pdf->Cell(25 ,5,'Numero de commande : '.$ncommande,0,1);



	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(189 ,10,'',0,1);



	$pdf->Cell(50 ,10,'',0,1);

	$pdf->SetFont('Arial','B',10);
	/*Heading Of the table*/
	$pdf->Cell(10 ,6,'#',1,0,'C');
	$pdf->Cell(80 ,6,'Description du produit',1,0,'C');
	$pdf->Cell(23 ,6,'Quantite',1,0,'C');
	$pdf->Cell(30 ,6,'Prix unitaire (Dh)',1,0,'C');
	$pdf->Cell(25 ,6,'Total',1,1,'C');/*end of line*/
	/*Heading Of the table end*/

	$pdf->SetFont('Arial','',10);
	$pdf->Cell(10 ,6,1,1,0);
	$pdf->Cell(80 ,6,$nom_article,1,0);
	$pdf->Cell(23 ,6,$quantite,1,0,'R');
	$pdf->Cell(30 ,6,$prix,1,0,'R');
	$pdf->Cell(25 ,6,$total,1,1,'R');
			


	$pdf->Output('D', 'RecuCommande'.$ncommande.'.pdf');
}