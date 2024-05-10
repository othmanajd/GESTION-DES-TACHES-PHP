<?php
session_start();
include("../connexion.php");
if (!isset( $_SESSION["cadre_nam"]))
{
    header("location:../index.php");
}
$idCadre=$_SESSION["cadre_id"];

$re="SELECT notificationn FROM cadre WHERE idCadre=$idCadre";
$exre=mysqli_fetch_array(mysqli_query($con,$re));
$notificationn=$exre['notificationn'];
$infocadre="SELECT * from cadre
            where idCadre= '$idCadre' ";
    $infocadres=mysqli_fetch_array(mysqli_query($con,$infocadre));
    $dataPoints = array( 
        array("y" =>$infocadres["creer"], "label" => "Non Démarrées"),
        array("y" =>$infocadres["enCours"], "label" => "En cours"),
        array("y" =>$infocadres["bloque"], "label" => "Bloquées"),
        array("y" =>$infocadres["terminer"], "label" => "Terminées")
    );
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rendement de cadre</title>
    <link rel="icon" href="../img/logosans.png"  >
    <link rel="stylesheet" href="../css/rendementCadre.css">
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
            <p>Bienvenue  <?php echo  $_SESSION["cadre_nam"];?></p>
            <p class="sub-1"><i class="fa fa-angle-right"></i>
                <div class="cache">
                    <p><a href="profil.php"> Profil</a></p>
                    <p><a href="../deconnection.php">se deconnecter</a></p>
                </div>
            </p>
        </div>
    </header>
    <div class="taches">
        <div class="menu-tache">
            <ul>
                <li > <a href="cadre.php?statu=mes" >Mes Tâches</a> </li>
                <li ><a href="cadre.php?statu=En cours">Tâches En Cours</a></li>
                <li ><a href="cadre.php?statu=Créé">Tâches Non Démarrées</a> </li>
                <li ><a href="cadre.php?statu=Terminé">Tâches Terminées</a> </li>
                <li class="active"><a href="rendement.php">Mon Rendement</a></li>
                <li class="<?php if($statu=='notification') echo 'active'?>" >
                <a href="cadre.php?statu=notification"> <span> <sup><?php echo $notificationn;?></sup></span> <i class="fa fa-bell" aria-hidden="true"></i> 
                Notification</a></li>
            </ul>
        </div>
        <div class="affiche_tache">
        <script>
window.onload = function() {
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title: {
		text: "Le Rendement de <?php echo $infocadres["nom"]." ".$infocadres["prenom"]?>"
	},
	subtitles: [{
		text: 'Le <?php echo date("d/m/Y")?>  à  <?php date_default_timezone_set('Africa/Casablanca');echo  date('H:i:s');?>'
	}],
	data: [{
		type: "pie",
		yValueFormatString: "#,##0.00\" Tâches\"",
		indexLabel: "{label} ({y})",
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