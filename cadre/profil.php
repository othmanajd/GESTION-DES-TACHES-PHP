<?php
session_start();
include("../connexion.php");
if (!isset( $_SESSION["cadre_nam"]) && !isset( $_SESSION["chef_nam"]))
{
    header("location:../index.php");
}
$idCadre=isset($_SESSION["cadre_id"])?$_SESSION["cadre_id"]:-1;
$idChef=isset($_SESSION["chef_id"])?$_SESSION["chef_id"]:-1;
$re="SELECT notificationn FROM cadre WHERE idCadre=$idCadre";
$exre=mysqli_fetch_array(mysqli_query($con,$re));
$notificationn=$exre['notificationn'];
if(isset($_SESSION["cadre_id"]))
{
    $query="SELECT * from cadre where idCadre=$idCadre";
    $poste='Cadre';
}
$info=mysqli_fetch_array(mysqli_query($con,$query));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cadre Profil</title>
    <link rel="icon" href="../img/logosans.png"  >
    <link rel="stylesheet" href="../css/cadre_profil.css">
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
            <p>Bienvenue <?php echo $info['prenom'];?></p>
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
                <li class="<?php if($statu=='mes') echo 'active'?>"> <a href="cadre.php?statu=mes" >Mes Tâches</a> </li>
                <li class="<?php if($statu=='En cours') echo 'active'?>"><a href="cadre.php?statu=En cours">Tâches En Cours</a></li>
                <li class="<?php if($statu=='Créé') echo 'active'?>"><a href="cadre.php?statu=Créé">Tâches Non Démarrées</a> </li>
                <li class="<?php if($statu=='Terminé') echo 'active'?>"><a href="cadre.php?statu=Terminé">Tâches Terminées</a> </li>
                <li ><a href="rendement.php">Mon Rendement</a></li>
                <li class="<?php if($statu=='notification') echo 'active'?>" >
                <a href="cadre.php?statu=notification"> <span> <sup style="color:aqua;" ><?php echo $notificationn;?></sup></span> <i class="fa fa-bell" aria-hidden="true"></i> 
                Notification</a></li>
            </ul>
        </div>
    <div class="affichage_profil">
        <div class="info">
                <div class="imge">
                    <img src="../img/profil.png" alt="PROFIL">
                </div>
                <div class="perso">
                    <h3>Bienvenue <span class="name"><?php echo $info['prenom'];?></span><h3>
                    <table>
                        <tr>
                            <td><span>Nom </span></td>
                            <td><?php echo $info['nom']." ".$info['prenom'];?></td>
                        </tr>
                        <tr>
                            <td><span>Matricule </span></td>
                            <td><?php echo $info['matricul'];?></td>
                        </tr>
                        <tr>
                            <td><span >Poste </span></td>
                            <td><?php echo $poste;?></td>
                        </tr>
                        <tr></tr>
                            <td><span >N° telephone </span></td>
                            <td><?php echo $info['telephon'];?></td>
                        </tr>
                        <tr>
                            <td><span >Adresse </span></td>
                            <td><?php echo $info['adress'];?></td>
                        </tr>
                        <tr>
                            <td class="span-perso">E-mail</td>
                            <td class="span-perso"><?php echo $info['email'];?></td>
                        </tr>
                    </table>
                    
                    <button  type="submit" name="submit" ><a href="../deconnection.php">Se déconnecter</a></button>
                    
                </div>
            </div>
        </div>
    </div>
    <script src="../js/cadre.js"></script>
</body>
</html>