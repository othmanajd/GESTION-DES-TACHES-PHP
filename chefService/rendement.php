<?php
session_start();
include("../connexion.php");
if (!isset( $_SESSION["chef_nam"]))
{
    header("location:../index.php");
}

$idChef=$_SESSION["chef_id"];
$quet="SELECT notificationn FROM chefservice WHERE idChefService=$idChef";
$notification=mysqli_fetch_array(mysqli_query($con,$quet));
$notificationn=$notification['notificationn'];

$reqcadres="SELECT * FROM cadre WHERE idChefService=$idChef";
$cadreslist=mysqli_query($con,$reqcadres);

if(isset($_GET['submit']))
{
    $idCadre=$_GET['cadre'];
    if($idCadre=="service")
    {
        $infocadre="SELECT sum(creer) as 'creer', sum(enCours) as 'enCours', sum(bloque) as 'bloque', sum(terminer) as 'terminer' 
                    from cadre
                    where idChefService= '$idChef' ";
        $infocadres=mysqli_fetch_array(mysqli_query($con,$infocadre));
    }
    else{
    $infocadre="SELECT * from cadre
            where idCadre= '$idCadre' ";
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
    <title>chef service</title>
    <link rel="icon" href="../img/logosans.png"  >
    <link rel="stylesheet" href="../css/rendementchefS.css">
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
            <p>Bienvenue <?php echo  $_SESSION["chef_nam"];?></p>
            <p class="sub-1"><i class="fa fa-angle-right"></i>
                <div class="cache">
                    <p><a href="profil.php"> Profil</a></p>
                    <p><a href="../deconnection.php">Se déconnecter</a></p>
                </div>
            </p>
        </div>
    </header>
    <div class="taches">
        <div class="menu-tache">
            <ul>
                <li > <a href="chef.php?statu=MesT" >Toutes les Tâches creer</a> </li>
                <li ><a href="chef.php?statu=En cours">En Cours</a></li>
                <li ><a href="chef.php?statu=Non demaree">Tâches Non Démarré </a> </li>
                <li ><a href="chef.php?statu=bloqué">Tâches bloque </a> </li>
                <li ><a href="chef.php?statu=terminer">Tâches terminer </a> </li>
                <li><a href="creer_tache.php">Cree une Tache</a></li>
                <li class="active"><a href="rendement.php?cadre=service&submit=">Rendement Des Cadre</a></li>
                <li class="<?php if($statu=='notification') echo 'active'?>" ><a href="chef.php?statu=notification"> <span> <sup><?php echo $notificationn;?> </sup></span><i class="fa fa-bell" aria-hidden="true"></i> Notification</a></li>
            </ul>
        </div>
        <div class="affiche_tache">
            <div class="divtitre">
                <p class="titre">Rendement</p>
                <div class="chercher">
                    <form action="rendement.php" method="get">
                        <label for="cadre">Choisir un cadre :</label>
                        <select name="cadre" id="cadre">
                            <option <?php if($_GET['cadre']=="service") echo "selected";?> value="service">Tout le Service</OPtion>
                            <?php while($tab=mysqli_fetch_array($cadreslist)){?>
                            <option <?php if($_GET['cadre']==$tab['idCadre']) echo "selected";?> value="<?php echo $tab['idCadre'];?>" ><?php echo $tab['nom']." ". $tab['prenom'];?></option>
                            <?php }?>
                        </select>
                        <button type="submit" name="submit"><i class="fa fa-search" ></i>Chercher</button>
                    </form>
                </div>
            </div>
            <script>
window.onload = function() {
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "Rendement <?php 
        if(isset($_GET['cadre']))
        {
            if($_GET['cadre']=="service") echo " de Service";
            else echo " de ".$infocadres['nom']." ".$infocadres['prenom'];
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