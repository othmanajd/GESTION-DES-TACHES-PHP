<?php
include("../connexion.php");
session_start();
$idCadre=$_SESSION["cadre_id"];
$idTache=$_GET['idTache'];

$re="SELECT idChefService FROM cadre WHERE idCadre=$idCadre";
$exre=mysqli_fetch_array(mysqli_query($con,$re));
$idChefService=$exre['idChefService'];

$notiC="SELECT notificationn FROM chefservice WHERE idChefService=$idChefService";
$notificatinchef=mysqli_fetch_array(mysqli_query($con,$notiC));
$notificatinChefService=$notificatinchef['notificationn'];
$requte="SELECT titre, statu, duree ,ddescription
        from tache
        where idTache=$idTache";
$info_tache=mysqli_fetch_array(mysqli_query($con,$requte));
$re="SELECT * FROM cadre WHERE idCadre=$idCadre";
$exre=mysqli_fetch_array(mysqli_query($con,$re));
$notificationn=$exre['notificationn'];
$creer=$exre['creer'];
$EnCours=$exre['enCours'];
$bloque=$exre['bloque'];
$terminer=$exre['terminer'];
if(isset($_POST['submit']))
{
    $dateCourante = date("Y-m-d");
    if($info_tache['statu']=='Créé' && $_POST['statu']=='En cours')
    {
        $creer--;
        $EnCours++;
        $notificatinChefService++;
        $re="UPDATE tache 
            SET statu = 'En cours'
            WHERE idTache = '$idTache'";
        $ree="INSERT INTO realiser (idTache, idCadre, dateDebut, dateModification) 
            VALUES ('$idTache', '$idCadre', '$dateCourante','$dateCourante')";
        $exre=mysqli_query($con,$re);
        $exree=mysqli_query($con,$ree);
        
        $fica="UPDATE chefservice 
            SET notificationn = '$notificatinChefService'
            WHERE idChefService= '$idChefService'";
        $fication=mysqli_query($con,$fica);

        $upreq="UPDATE cadre
            SET creer= ' $creer', terminer= '$terminer', bloque= '$bloque', enCours='$EnCours'
            WHERE idCadre = '$idCadre'";
        $exupreq=mysqli_query($con,$upreq);
    }
    elseif($info_tache['statu']=='Créé' && $_POST['statu']=='Bloqué')
    {
        $creer--;
        $bloque++;
        $notificatinChefService++;
        $re="UPDATE tache 
            SET statu = 'Bloqué'
            WHERE idTache = '$idTache'";
        $ree="INSERT INTO realiser (idTache, idCadre, dateDebut, datefin, dateModification) 
            VALUES ('$idTache', '$idCadre', '$dateCourante', '$dateCourante','$dateCourante')";
        $exre=mysqli_query($con,$re);
        $exree=mysqli_query($con,$ree);

        $fica="UPDATE chefservice 
            SET notificationn = '$notificatinChefService'
            WHERE idChefService= '$idChefService'";
        $fication=mysqli_query($con,$fica);

        $upreq="UPDATE cadre
            SET creer= ' $creer', terminer= '$terminer', bloque= '$bloque', enCours='$EnCours'
            WHERE idCadre = '$idCadre'";
        $exupreq=mysqli_query($con,$upreq);

    }
    elseif($info_tache['statu']=='En cours' && $_POST['statu']=='Bloqué')
    {
        $EnCours--;
        $bloque++;
        $dateCourante = date("Y-m-d");
        $notificatinChefService++; 

        $re="UPDATE tache 
            SET statu = 'Bloqué'
            WHERE idTache = '$idTache'";
        $query="SELECT  R.idRealise
                FROM realiser R
                WHERE idTache=$idTache
                order by R.dateDebut desc
                limit 1";
        $realiser=mysqli_fetch_array(mysqli_query($con,$query));
        $idRealise=$realiser['idRealise'];
        $ree="UPDATE realiser 
            SET dateFin = '$dateCourante', dateModification='$dateCourante'
            WHERE idRealise= '$idRealise'";
        $exre=mysqli_query($con,$re);
        $exree=mysqli_query($con,$ree);

        $fica="UPDATE chefservice 
            SET notificationn = '$notificatinChefService'
            WHERE idChefService= '$idChefService'";
        $fication=mysqli_query($con,$fica);

        $upreq="UPDATE cadre
            SET creer= ' $creer', terminer= '$terminer', bloque= '$bloque', enCours='$EnCours'
            WHERE idCadre = '$idCadre'";
        $exupreq=mysqli_query($con,$upreq);
    }
    elseif($info_tache['statu']=='En cours' && $_POST['statu']=='Terminé')
    {
        $EnCours--;
        $terminer++;
        $dateCourante = date("Y-m-d");
        $notificatinChefService++; 

        $re="UPDATE tache 
            SET statu = 'Terminé'
            WHERE idTache = '$idTache'";
        $query="SELECT  R.idRealise
                FROM realiser R
                WHERE idTache=$idTache
                order by R.dateDebut desc
                limit 1";
        $realiser=mysqli_fetch_array(mysqli_query($con,$query));
        $idRealise=$realiser['idRealise'];
        $ree="UPDATE realiser 
            SET dateFin = '$dateCourante', dateModification='$dateCourante'
            WHERE idRealise= '$idRealise'";
        $exre=mysqli_query($con,$re);
        $exree=mysqli_query($con,$ree);

        $fica="UPDATE chefservice 
            SET notificationn = '$notificatinChefService'
            WHERE idChefService= '$idChefService'";
        $fication=mysqli_query($con,$fica);

        $upreq="UPDATE cadre
            SET creer= ' $creer', terminer= '$terminer', bloque= '$bloque', enCours='$EnCours'
            WHERE idCadre = '$idCadre'";
        $exupreq=mysqli_query($con,$upreq);
    }
    else
    {
        $erreur="Le statut n'est pas changé";
    }
    $info_tache=mysqli_fetch_array(mysqli_query($con,$requte));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadre</title>
    <link rel="icon" href="../img/logosans.png"  >
    <link rel="stylesheet" href="../css/editt_cadre.css">
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
                    <p><a href="../deconnection.php">Se déconnecter</a></p>
                </div>
            </p>
        </div>
    </header>
    <div class="taches">
        <div class="menu-tache">
            <ul>
                <li class="<?php if($statu=='mes') echo 'active'?>"> <a href="cadre.php?statu=mes" >Mes Tâches</a> </li>
                <li class="<?php if($statu=='EN cours') echo 'active'?>"><a href="cadre.php?statu=EN cours">Tâches En Cours</a></li>
                <li class="<?php if($statu=='creer') echo 'active'?>"><a href="cadre.php?statu=creer">Tâches Non Démarrées</a> </li>
                <li class="<?php if($statu=='terminer') echo 'active'?>"><a href="cadre.php?statu=terminer">Tâches Terminées</a> </li>
                <li ><a href="rendement.php">Mon Rendement</a></li>
                <li class="<?php if($statu=='notification') echo 'active'?>" >
                <a href="cadre.php?statu=notification"> <span> <sup><?php echo $notificationn;?></sup></span> <i class="fa fa-bell" aria-hidden="true"></i> 
                Notification</a></li>
            </ul>
        </div>
        <div class="edit">
            <h1>Plus d'informations sur la tâche</h1>
            <table>
                <tr>
                    <td>Titre</td>
                    <td>Durée estimée</td>
                    <td>Description</td>
                    <td>Statut</td>
                </tr>
                <tr>
                    <td><?php echo $info_tache['titre'] ?></td>
                    <td><?php echo $info_tache['duree'] ?></td>
                    <td><?php echo $info_tache['ddescription'] ?></td>
                    <td><?php echo $info_tache['statu'] ?></td>
                </tr>
            </table>
            <div class="change_statu">
                <h3>Changer le statut de la tâche</h3>
                <form action="edit.php?idTache=<?php echo $idTache; ?>" method="post">
                    <label for="statu">Choisir un statut :</label>
                    <select name="statu" id="statu">
                        <option value="En cours">Démarrer</option>
                        <option value="Bloqué">Bloqué</option>
                        <option value="Terminé">Terminé</option>
                    </select>
                    <button type="submit" name="submit" >Changer</button>
                </form>
                <p class="erreur">
                    <?php if(isset($erreur)) {echo $erreur;} ?>
                </p>
            </div>
        </div>
    </div>
    <script src="../js/cadre.js"></script>
</body>
</html>