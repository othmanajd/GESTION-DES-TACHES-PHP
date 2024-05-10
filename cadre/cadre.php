<?php
session_start();
include("../connexion.php");
if (!isset( $_SESSION["cadre_nam"]))
{
    header("location:../index.php");
}
$nbtache=isset($_GET["nbtache"])? $_GET["nbtache"] :5;
$page=isset($_GET["page"])? $_GET["page"] :1;
$offset=($page-1)*$nbtache;
$idCadre=$_SESSION["cadre_id"];
$cherche=isset($_GET["cherche"])? $_GET["cherche"] :"";
$statu=isset($_GET["statu"])? $_GET["statu"] :"mes";

$re="SELECT notificationn FROM cadre WHERE idCadre=$idCadre";
$exre=mysqli_fetch_array(mysqli_query($con,$re));
$notificationn=$exre['notificationn'];

if($statu=='mes')
{
    $query="SELECT idTache, titre, statu, duree, dateDebut 
            from tache
            where idCadre='$idCadre'  && titre like '%$cherche%'
            order by dateCreation desc
            limit $nbtache
            offset $offset ";
    $querycount="SELECT count(*)
                FROM tache 
                where idCadre='$idCadre'  && titre like '%$cherche%'";
    $tachecountt=mysqli_fetch_array(mysqli_query($con,$querycount));
    $tachecount[0]=$tachecountt[0];
}
elseif($statu=='En cours')
{
    $query="SELECT idTache,titre, statu, duree, dateDebut 
            from tache 
            where idCadre='$idCadre'  && titre like '%$cherche%' && statu='En cours'
            order by dateCreation desc
            limit $nbtache
            offset $offset ";
    $querycount="SELECT count(*)
                FROM tache 
                WHERE idCadre = '$idCadre' && statu='En cours' &&  titre like '%$cherche%'";
    $tachecountt=mysqli_fetch_array(mysqli_query($con,$querycount));
    $tachecount[0]=$tachecountt[0];
}
elseif($statu=='Créé')
{
    $query="SELECT idTache, titre, statu, duree, dateDebut
        FROM tache 
        WHERE idCadre = '$idCadre' && statu='Créé' &&  titre like '%$cherche%'
        order by dateCreation desc
        limit $nbtache
        offset $offset ";
    $querycount="SELECT count(*)
        FROM tache 
        WHERE idCadre = '$idCadre' && statu='Créé' &&  titre like '%$cherche%'";
    $tachecountt=mysqli_fetch_array(mysqli_query($con,$querycount));
    $tachecount[0]=$tachecountt[0];
}
elseif($statu=='Terminé')
{
    $query="SELECT idTache, titre, statu, duree, dateDebut
        FROM tache 
        WHERE idCadre = '$idCadre' && statu='Terminé' &&  titre like '%$cherche%'
        order by dateCreation desc
        limit $nbtache
        offset $offset ";
    $querycount="SELECT count(*)
        FROM tache 
        WHERE idCadre = '$idCadre' && statu='Terminé' &&  titre like '%$cherche%'";
    $tachecountt=mysqli_fetch_array(mysqli_query($con,$querycount));
    $tachecount[0]=$tachecountt[0];
}
else
{
    $tachecount[0]=$notificationn;
    $query="SELECT idTache, titre, statu, duree, dateDebut 
        FROM tache 
        WHERE idCadre = '$idCadre' && statu='Créé' &&  titre like '%$cherche%'
        order by dateCreation desc
        limit $notificationn";

    $notificationn=0;
    $ree="UPDATE cadre 
        SET notificationn = '$notificationn'
        WHERE idCadre= '$idCadre'";

    $exree=mysqli_query($con,$ree);
    $exre=mysqli_fetch_array(mysqli_query($con,$re));
    $notificationn=$exre['notificationn'];

}
$result=mysqli_query($con,$query);
if($tachecount[0]%$nbtache==0)
{
    $nbpage=$tachecount[0]/$nbtache;
}
else{$nbpage=floor($tachecount[0]/$nbtache)+1;}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadre</title>
    <link rel="icon" href="../img/logosans.png"  >
    <link rel="stylesheet" href="../css/cadre.css">
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
                    <p><a href="profil.php">Profil</a></p>
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
                <a href="cadre.php?statu=notification"> <span> <sup><?php echo $notificationn;?></sup></span> <i class="fa fa-bell" aria-hidden="true"></i> 
                Notification</a></li>
            </ul>
        </div>
        <div class="affiche_tache">
            <div class="divtitre">
                <p class="titre">
                    <?php if($statu=='mes') echo" Mes Tâches ";
                    elseif($statu=='En cours') echo" Les Tâches En Cours ";
                    elseif($statu=='Créé') echo" Les Tâches Non Démarrées ";
                    elseif($statu=='Terminé') echo" Tâches Terminées ";
                    else echo" Notification ";
                    ?>
                    ( 
                    <?php 
                        if($tachecount[0]<2) 
                        {echo  $tachecount[0].' Tâche ';}
                        else {echo  $tachecount[0].' Tâches '; }
                    ?>)</p>
                </p>
                <div class="chercher">
                <form action="cadre.php" method="get">
                        <label for="cherche">Titre :</label>
                        <input value="<?php echo $cherche; ?>" type="text" id="cherche" placeholder="Recherche Par Titre" name="cherche">
                        <input type="hidden" name="statu" value="<?php echo $statu?>">
                        <button type="submit" name="submit"><i class="fa fa-search" ></i>Chercher</button>
                </form>
                </div>
            </div>
            <div class="affichage">
                <table>
                    <tr>
                        <td>Titre</td>
                        <td> Date de Début</td>
                        <td>Durée estimée</td>
                        <td>Statut</td>
                        <td>Plus</td>
                    </tr>
                    <?php while ($tab= mysqli_fetch_array($result)) {?>
                    <tr>
                        <td><?php echo $tab["titre"];?> </td>
                        <td><?php echo $tab["dateDebut"];?> </td>
                        <td><?php echo $tab["duree"];?> </td>
                        <td><?php echo $tab["statu"];?></td>
                        <td><a href="edit.php?idTache=<?php echo $tab["idTache"];?>"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></td>
                    </tr>
                    <?php }?>
                    <!-- <tr>
                        <td>developper un application web</td>
                        <td>othman arejdal</td>
                        <td>creer</td>
                        <td>1mois</td>
                        <td>
                            <a href=""><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                        </td>
                    </tr> -->
                </table>
                <div class="page">
                    <ul>
                    <?php for($i=1;$i<=$nbpage;$i++){?>
                        <li class="<?php if($page == $i) echo'pageactive';?>"><a href="cadre.php?page=<?php echo $i ?>&statu=<?php  echo $statu ?>&cherche=<?php echo $cherche ?>"> <?php echo $i ?> </a></li>
                    <?php }?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/cadre.js"></script>
</body>
</html>