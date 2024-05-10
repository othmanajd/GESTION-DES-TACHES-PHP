<?php
session_start();
include("../connexion.php");
if (!isset( $_SESSION["cadre_nam"]) && !isset( $_SESSION["chef_nam"]))
{
    header("location:../index.php");
}
$idCadre=isset($_SESSION["cadre_id"])?$_SESSION["cadre_id"]:-1;
$idChef=isset($_SESSION["chef_id"])?$_SESSION["chef_id"]:-1;
$quet="SELECT notificationn FROM chefservice WHERE idChefService=$idChef";
$notification=mysqli_fetch_array(mysqli_query($con,$quet));
$notificationn=$notification['notificationn'];
if(isset($_SESSION["cadre_id"]))
{
    $query="SELECT * from cadre where idCadre=$idCadre";
    $poste='cadre';
}
else
{
    $query="SELECT * from chefservice where idChefService=$idChef";
    $poste='Chef service';
}
$info=mysqli_fetch_array(mysqli_query($con,$query));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cadre</title>
    <link rel="stylesheet" href="../css/chef_profil.css">
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
            <p>Bienvenu <?php echo $info['prenom'];?></p>
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
                <li class="<?php if($statu=='MesT') echo 'active'?>"> <a href="chef.php?statu=MesT" >Toutes les Tâches creer</a> </li>
                <li class="<?php if($statu=='En cours') echo 'active'?>"><a href="chef.php?statu=En cours">En Cours</a></li>
                <li class="<?php if($statu=='Non demaree') echo 'active'?>"><a href="chef.php?statu=Non demaree">Tâches Non Démarré </a> </li>
                <li class="<?php if($statu=='bloqué') echo 'active'?>"><a href="chef.php?statu=bloqué">Tâches bloque </a> </li>
                <li class="<?php if($statu=='terminer') echo 'active'?>"><a href="chef.php?statu=terminer">Tâches terminer </a> </li>
                <li><a href="creer_tache.php">Cree une Tache</a></li>
                <li ><a href="rendement.php?cadre=service&submit=">Rendement Des Cadre</a></li>
                <li class="<?php if($statu=='notification') echo 'active'?>" ><a href="chef.php?statu=notification"> <span> <sup><?php echo $notificationn;?> </sup></span><i class="fa fa-bell" aria-hidden="true"></i> Notification</a></li>
            </ul>
        </div>
        <div class="affichage_profil">
        <div class="info">
                <div class="imge">
                    <img src="../img/profil.png" alt="PROFIL">
                </div>
                <div class="perso">
                    <h3>Bienvenu <span class="name"><?php echo $info['prenom'];?></span><h3>
                    <table>
                        <tr>
                            <td><span>Nom </span></td>
                            <td><?php echo $info['nom']." ".$info['prenom'];?></td>
                        </tr>
                        <tr>
                            <td><span>matricule </span></td>
                            <td><?php echo $info['matricul'];?></td>
                        </tr>
                        <tr>
                            <td><span >poste </span></td>
                            <td><?php echo $poste;?></td>
                        </tr>
                        <tr></tr>
                            <td><span >N° telephone </span></td>
                            <td><?php echo $info['telephon'];?></td>
                        </tr>
                        <tr>
                            <td><span >adress </span></td>
                            <td><?php echo $info['adress'];?></td>
                        </tr>
                        <tr>
                            <td class="span-perso">email</td>
                            <td class="span-perso"><?php echo $info['email'];?></td>
                        </tr>
                    </table>
                    
                    <button  type="submit" name="submit" ><a href="../deconnection.php">se deconnecter</a></button>
                    
                </div>
            </div>
        </div>
    </div>
    <script src="../js/cadre.js"></script>
</body>
</html>