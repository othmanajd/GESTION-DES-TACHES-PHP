<?php
include("../connexion.php");
session_start();
if (!isset( $_SESSION["chef_nam"]))
{
    header("location:../index.php");
}
$idChef=$_SESSION["chef_id"];
$requet="SELECT * FROM cadre WHERE idChefService=$idChef";
$cadres=mysqli_query($con,$requet);
$quet="SELECT notificationn FROM chefservice WHERE idChefService=$idChef";
$notification=mysqli_fetch_array(mysqli_query($con,$quet));
$notificationnchef=$notification['notificationn'];
if (isset($_POST['submit']))
{
    $dateCourante = date("Y-m-d");
    $titre= $_POST['titre'];
    $duree= $_POST['duree'];
    $date= $_POST['date'];
    $Descrition= $_POST['Descrition'];
    $idCadre=$_POST['cadre']; 

    $query="INSERT INTO tache (titre, statu, duree, dateDebut, idCadre,idChefService,ddescription, dateCreation)
            VALUES ('$titre', 'Créé', '$duree', '$date', '$idCadre', '$idChef', '$Descrition','$dateCourante')";
    $result=mysqli_query($con,$query);

    $reusit="Votre tâche est bien affectée";

    $re="SELECT notificationn FROM cadre WHERE idCadre=$idCadre";
    $exre=mysqli_fetch_array(mysqli_query($con,$re));
    $notificationn=$exre['notificationn'];
    $notificationn++;
    $ree="UPDATE cadre 
        SET notificationn = '$notificationn'
        WHERE idCadre= '$idCadre'";
    $exree=mysqli_query($con,$ree);

    $mar="SELECT creer from cadre where idCadre='$idCadre'";
    $mary=mysqli_fetch_array(mysqli_query($con,$mar));
    $creer=$mary['creer'];
    $creer++;

    $yem="UPDATE cadre 
        SET creer = '$creer'
        WHERE idCadre= '$idCadre'";
    $sof=mysqli_query($con,$yem);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef service</title>
    <link rel="icon" href="../img/logosans.png"  >
    <link rel="stylesheet" href="../css/creer_taches.css">
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
                <li > <a href="chef.php?statu=MesT" >Toutes les tâches créées</a> </li>
                <li ><a href="chef.php?statu=En cours">Tâches En cours</a></li>
                <li ><a href="chef.php?statu=Non demaree">Tâches Non Démarrées </a> </li>
                <li><a href="chef.php?statu=bloqué">Tâches Bloquées </a> </li>
                <li ><a href="chef.php?statu=terminer">Tâches Terminées </a> </li>
                <li class="active"><a href="creer_tache.php"><i class="fa fa-plus" aria-hidden="true"> </i> Crée une tâche</a></li>
                <li ><a href="rendement.php?cadre=service&submit=">Rendement Des Cadre</a></li>
                <li class="<?php if($statu=='notification') echo 'active'?>" ><a href="chef.php?statu=notification"> <span> <sup><?php echo $notificationnchef;?> </sup></span><i class="fa fa-bell" aria-hidden="true"></i> Notification</a></li>
            </ul>
        </div>
        <div class="creer_tache">
            <?php if(isset($reusit)) echo'<p class="reusit">'.$reusit.'</p>';?>
            <form action="creer_tache.php" method="post">
                <h1>Créer une tâche</h1>
                <label for="titre">Indiquer le titre de la tâche</label>
                <input type="text" name="titre" id="titre" required >
                <label for="duree">Estimer la durée de la tâche</label>
                <input type="text" id="duree" name="duree" required >
                <label for="date">Date de début</label>
                <input type="date" name="date" id="date" required >
                <label for="Descrition">Description de la tâche</label>
                <textarea id="Descrition" name="Descrition"  rows="4" cols="50" required ></textarea>
                <label for="cadre">Affecter cette tâche à un cadre</label>
                <select name="cadre" id="cadre" required >
                    <?php while($cad=mysqli_fetch_array($cadres)){?>
                    <option value="<?php echo $cad['idCadre'];?>"><?php echo $cad['nom']." ".$cad['prenom'] ;?></option>
                    <?php }?>
                </select>
                <button name="submit" >Affecter</button>
            </form>
        </div>
    </div>
    <script src="../js/cadre.js"></script>
</body>
</html>