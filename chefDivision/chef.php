<?php
session_start();
include("../connexion.php");
$idChefD=$_SESSION["chefdivision_id"];
$reu="SELECT * from service
    where idChefDivision='$idChefD' ";
$services=mysqli_query($con,$reu);
if(isset($_GET['submit']))
{
    $idService=$_GET['division'];
    if( $idService=="division")
    {
        $infocadre="SELECT sum(creer) as creer, sum(enCours) as enCours, sum(bloque) as bloque, sum(terminer) as terminer from cadre c
        INNER JOIN chefservice cs ON cs.idChefService=c.idChefService
        INNER JOIN service s ON s.idService=cs.idService
        INNER JOIN chefdivision cf ON cf.idChefDivision=s.idChefDivision
        where cf.idChefDivision= '$idChefD' ";
        $infocadres=mysqli_fetch_array(mysqli_query($con,$infocadre));
    }
    else{
        $infocadre="SELECT s.nom as nom, sum(creer) as creer, sum(enCours) as enCours, sum(bloque) as bloque, sum(terminer) as terminer from cadre c
        INNER JOIN chefservice cs ON cs.idChefService=c.idChefService
        INNER JOIN service s ON s.idService=cs.idService
        where s.idService= ' $idService' ";
    $infocadres=mysqli_fetch_array(mysqli_query($con,$infocadre));
    }
    $dataPoints = array( 
        array("y" =>$infocadres["creer"], "label" => "Non Démarrées"),
        array("y" =>$infocadres["enCours"], "label" => "En cours"),
        array("y" =>$infocadres["bloque"], "label" => "Bloquées"),
        array("y" =>$infocadres["terminer"], "label" => "Terminées")
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Division</title>
    <link rel="icon" href="../img/logosans.png"  >
    <link rel="stylesheet" href="../css/chefDivision.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo">
            <img class="mins" src="../img/logosans.png" alt="logo">
        </div>
        <div class="profil">
            <img  src="../img/profilsans.png" alt="PROFIL">
            <p>Bienvenue  <?php echo  $_SESSION["chefdivision_nam"];?></p>
            <p class="sub-1"><i class="fa fa-angle-right"></i>
                <div class="cache">
                    <p><a href="profil.php"> Profil</a></p>
                    <p><a href="../deconnection.php">Se déconnecter</a></p>
                </div>
            </p>
        </div>
    </header>
    <div class="affichge">
        <div class="chercher">
            <p class="titre">Rendement</p>
            <form action="chef.php" method="get">
                <label for="division">choisir un service :</label>
                <select name="division" id="division">
                    <option value="division">Toute la division</option>
                    <?php while($service=mysqli_fetch_array($services)){?>
                        <option value="<?php echo $service['idService'];?>"><?php echo $service['nom'];?></option>
                    <?php }?>
                </select>
                <button type="submit" name="submit"><i class="fa fa-search" ></i>Chercher</button>
            </form>
        </div>
        <div class="rend">
        <script>
window.onload = function() {
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "Rendement <?php 
        if(isset($_GET['division']))
        {
            if($_GET['division']=="division") echo " de la Division";
            else echo " de service ".$infocadres['nom'];
        }
        ?>"
	},
    subtitles: [{
		text: 'Le <?php echo date("d/m/Y")?>  à  <?php date_default_timezone_set('Africa/Casablanca');echo  date('H:i:s');?>'
	}],
	axisY: {
		title: "nombers des taches"
	},
	data: [{
		type: "column",
		yValueFormatString: "#,##0.## tonnes",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>  

        </div>
    </div>
    <script src="../js/cadre.js"></script>   
</body>
</html>