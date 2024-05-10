<?php
session_start();
include("../connexion.php");
if (!isset( $_SESSION["chef_nam"]))
{
    header("location:../index.php");
}
$nbtache=isset($_GET["nbtache"])? $_GET["nbtache"] :5;
$page=isset($_GET["page"])? $_GET["page"] :1;
$offset=($page-1)*$nbtache;
$cherche=isset($_GET["cherche"])? $_GET["cherche"] :"";
$statu=isset($_GET["statu"])? $_GET["statu"] :"MesT";
$idChef=$_SESSION["chef_id"];
$quet="SELECT notificationn FROM chefservice WHERE idChefService=$idChef";
$notification=mysqli_fetch_array(mysqli_query($con,$quet));
$notificationn=$notification['notificationn'];
if($statu=='MesT')
{
    $query="SELECT t.idTache, T.titre, T.statu, T.duree, C.nom, c.prenom, T.dateCreation
        FROM tache T
        INNER JOIN cadre C ON T.idCadre = C.idCadre
        WHERE T.idchefService = '$idChef' &&  T.titre like '%$cherche%'
        order by dateCreation desc
        limit $nbtache
        offset $offset ";
    $querycount="SELECT count(*)
                FROM tache T
                INNER JOIN cadre C ON T.idCadre = C.idCadre
                WHERE T.idchefService = '$idChef' &&  T.titre like '%$cherche%'";
    $tachecount= mysqli_query($con,$querycount);
    $tachecount_result = mysqli_fetch_array($tachecount);
}
elseif($statu=='En cours')
{
    $query="SELECT t.idTache, T.titre, T.statu, T.duree, C.nom, c.prenom, T.dateCreation
        FROM tache T
        INNER JOIN cadre C ON T.idCadre = C.idCadre
        WHERE T.idchefService = '$idChef' && T.statu='En cours' &&  T.titre like '%$cherche%'
        order by dateCreation desc
        limit $nbtache
        offset $offset ";
    $querycount="SELECT count(*)
        FROM tache T
        INNER JOIN cadre C ON T.idCadre = C.idCadre
        WHERE T.idchefService = '$idChef' && T.statu='En cours' &&  T.titre like '%$cherche%'";
    $tachecount= mysqli_query($con,$querycount);
    $tachecount_result = mysqli_fetch_array($tachecount);
}
elseif($statu=='Non demaree')
{
    $query="SELECT t.idTache, T.titre, T.statu, T.duree, C.nom, c.prenom, T.dateCreation
        FROM tache T
        INNER JOIN cadre C ON T.idCadre = C.idCadre
        WHERE T.idchefService = '$idChef' && T.statu='Créé' &&  T.titre like '%$cherche%'
        order by dateCreation desc
        limit $nbtache
        offset $offset ";
    $querycount="SELECT count(*)
        FROM tache T
        INNER JOIN cadre C ON T.idCadre = C.idCadre
        WHERE T.idchefService = '$idChef' && T.statu='Créé' &&  T.titre like '%$cherche%'";
    $tachecount= mysqli_query($con,$querycount);
    $tachecount_result = mysqli_fetch_array($tachecount);    
}
elseif($statu=='bloqué')
{
    $query="SELECT t.idTache, T.titre, T.statu, T.duree, C.nom, c.prenom, T.dateCreation
        FROM tache T
        INNER JOIN cadre C ON T.idCadre = C.idCadre
        WHERE T.idchefService = '$idChef' && T.statu='Bloqué' &&  T.titre like '%$cherche%'
        order by dateCreation desc
        limit $nbtache
        offset $offset ";
    $querycount="SELECT count(*)
        FROM tache T
        INNER JOIN cadre C ON T.idCadre = C.idCadre
        WHERE T.idchefService = '$idChef' && T.statu='Bloqué' &&  T.titre like '%$cherche%'";
    $tachecount= mysqli_query($con,$querycount);
    $tachecount_result = mysqli_fetch_array($tachecount);
}
elseif($statu=='terminer')
{
    $query="SELECT t.idTache, T.titre, T.statu, T.duree, C.nom, c.prenom, T.dateCreation
        FROM tache T
        INNER JOIN cadre C ON T.idCadre = C.idCadre
        WHERE T.idchefService = '$idChef' && T.statu='Terminé' &&  T.titre like '%$cherche%'
        order by dateCreation desc
        limit $nbtache
        offset $offset ";
    $querycount="SELECT count(*)
        FROM tache T
        INNER JOIN cadre C ON T.idCadre = C.idCadre
        WHERE T.idchefService = '$idChef' && T.statu='Terminé' &&  T.titre like '%$cherche%'";
    $tachecount= mysqli_query($con,$querycount);
    $tachecount_result = mysqli_fetch_array($tachecount);
}
else
{
    $tachecount[0]=$notificationn;
    $tachecount_result[0]=$notificationn;
    $query="SELECT t.idTache, T.titre, T.statu, T.duree, C.nom, c.prenom, T.dateCreation
        FROM tache T
        INNER JOIN cadre C ON T.idCadre = C.idCadre
        INNER JOIN realiser R ON T.idCadre = R.idCadre
        WHERE T.idchefService = '$idChef' /*&& T.statu='bloqué'*/ &&  T.titre like '%$cherche%'
        order by dateCreation desc
        limit $notificationn";

    $notificationn=0;
    $ree="UPDATE chefservice
        SET notificationn = '$notificationn'
        WHERE idchefService = '$idChef'";
    $exree=mysqli_query($con,$ree);
    $exre=mysqli_fetch_array(mysqli_query($con,$quet));
    $notificationn=$exre['notificationn'];

}
$result=mysqli_query($con,$query);
// $tachecount= mysqli_query($con,$querycount);
// $tachecount_result = mysqli_fetch_array($tachecount);
if($tachecount_result[0]%$nbtache==0)
{
    $nbpage=$tachecount_result[0]/$nbtache;
}
else{$nbpage=floor($tachecount_result[0]/$nbtache)+1;}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef service</title>
    <link rel="icon" href="../img/logosans.png"  >
    <link rel="stylesheet" href="../css/chef.css">
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
            <p>Bienvenue  <?php echo  $_SESSION["chef_nam"];?></p>
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
                <li class="<?php if($statu=='MesT') echo 'active'?>"> <a href="chef.php?statu=MesT" >Toutes les tâches créées</a> </li>
                <li class="<?php if($statu=='En cours') echo 'active'?>"><a href="chef.php?statu=En cours">Tâches En cours</a></li>
                <li class="<?php if($statu=='Non demaree') echo 'active'?>"><a href="chef.php?statu=Non demaree">Tâches Non Démarrées </a> </li>
                <li class="<?php if($statu=='bloqué') echo 'active'?>"><a href="chef.php?statu=bloqué">Tâches Bloquées </a> </li>
                <li class="<?php if($statu=='terminer') echo 'active'?>"><a href="chef.php?statu=terminer">Tâches Terminées </a> </li>
                <li><a href="creer_tache.php"><i class="fa fa-plus" aria-hidden="true" style="color: white;"> </i> Crée une tâche</a></li>
                <li ><a href="rendement.php?cadre=service&submit=">Rendement Des Cadre</a></li>
                <li class="<?php if($statu=='notification') echo 'active'?>" ><a href="chef.php?statu=notification"> <span> <sup><?php echo $notificationn;?> </sup></span><i class="fa fa-bell" aria-hidden="true"></i> Notification</a></li>
            </ul>
        </div>
        <div class="affiche_tache">
            <div class="divtitre">
                <p class="titre">
                    <?php if($statu=='MesT') echo" Toutes les tâches créées ";
                    elseif($statu=='En cours') echo" Les Tâches En cours ";
                    elseif($statu=='Non demaree') echo" Les Tâches Non Démarrées ";
                    elseif($statu=='bloqué') echo" Les Tâches Bloquées ";
                    elseif($statu=='terminer') echo" Les Tâches Terminées ";
                    else echo" Notification ";
                    ?>
                    (
                    <?php 
                        if($tachecount_result[0]<2) 
                        {echo  $tachecount_result[0].' Tache ';}
                        else {echo  $tachecount_result[0].' Taches '; }
                    ?>)</p>
                <div class="chercher">
                    <form action="chef.php" method="get">
                        <label for="cherche">Titre :</label>
                        <input value="<?php echo $cherche; ?>" type="text" id="cherche" placeholder="Rechercher Par Titre " name="cherche">
                        <input type="hidden" name="statu" value="<?php echo $statu?>">
                        <button type="submit" name="submit"><i class="fa fa-search" ></i>Chercher</button>
                    </form>
                </div>
            </div>
            <div class="affichage">
                <table>
                    <tr>
                        <td>Titre</td>
                        <td>Responsable actuel</td>
                        <td>Durée estimée</td>
                        <td>Statut</td>
                        <td>Plus</td>
                    </tr>
                    <?php while ($tab= mysqli_fetch_array($result)) {?>
                    <tr>
                        <td><?php echo $tab["titre"]?> </td>
                        <td><?php echo $tab["nom"]." ".$tab["prenom"]?> </td>
                        <td><?php echo $tab["duree"]?> </td>
                        <td><?php echo $tab["statu"]?></td>
                        <td>
                            <a href="edit.php?idTache=<?php echo $tab["idTache"]?>"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <a onclick="return confirm('vous voulez supprimer cette tache')" href="supprimer.php?idTache=<?php echo $tab["idTache"]?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                    <?php }?>
                    <!-- <tr>
                        <td>developper un application web</td>
                        <td>othman arejdal</td>
                        <td>creer</td>
                        <td>1mois</td>
                        <td>
                            <a href=""><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <a href=""><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </td>
                    </tr> -->
                </table>
                <div class="page">
                    <ul>
                    <?php for($i=1;$i<=$nbpage;$i++){?>
                        <li  class="<?php if($page == $i) echo'pageactive';?>"> <a href="chef.php?page=<?php echo $i ?>&statu=<?php  echo $statu ?>&cherche=<?php echo $cherche ?>"> <?php echo $i ?> </a></li>
                    <?php }?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/cadre.js"></script>
</body>
</html>