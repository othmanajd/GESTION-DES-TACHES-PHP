<?php
session_start();
include("../connexion.php");
if (!isset( $_SESSION["cadre_nam"]) && !isset( $_SESSION["chef_nam"]))
{
    header("location:../index.php");
}
$idChef=$_SESSION["chef_id"];
$idTache=$_GET['idTache'];
$nbcadre=isset($_GET["nbtache"])? $_GET["nbtache"] :5;
$page=isset($_GET["page"])? $_GET["page"] :1;
$offset=($page-1)*$nbcadre;
$requet="SELECT idCadre, nom, prenom from cadre where idChefService=$idChef ";
$cadresliste=mysqli_query($con,$requet);

$quet="SELECT notificationn FROM chefservice WHERE idChefService=$idChef";
$notification=mysqli_fetch_array(mysqli_query($con,$quet));
$notificationn=$notification['notificationn'];

$query="SELECT C.nom, C.prenom, R.dateDebut, R.dateFin, R.idRealise
        FROM realiser R
        INNER JOIN cadre C ON R.idCadre = C.idCadre
        WHERE idTache=$idTache
        order by R.dateDebut desc
        limit $nbcadre
        offset $offset ";
$query1="SELECT count(*)
        FROM realiser R
        INNER JOIN cadre C ON R.idCadre = C.idCadre
        WHERE idTache=$idTache";
$historique1=mysqli_query($con,$query);
$historique=mysqli_query($con,$query);
$tachecount_result=mysqli_fetch_array(mysqli_query($con,$query1));
if($tachecount_result[0]>0)
{
$tach=mysqli_fetch_array($historique1);
$idRealise=$tach['idRealise'];}
if(isset($_POST['submit']))
{
    $dateCourante = date("Y-m-d");
    $idCadre=$_POST['cadre'];
    $req="UPDATE realiser 
            SET dateFin = '$dateCourante'
            WHERE idRealise = '$idRealise'";
    $reqq="UPDATE tache 
            SET idCadre= '$idCadre', statu='creer'
            WHERE idTache = '$idTache'";
    // $ajouter="INSERT INTO realiser (idTache, idCadre, dateDebut, dateFin) 
    //         VALUES ('$idTache', '$idCadre', '', '0000-00-00')";"
    $upd=mysqli_query($con,$req);
    $updd=mysqli_query($con,$reqq);

    $mar="SELECT creer from cadre where idCadre='$idCadre'";
    $mary=mysqli_fetch_array(mysqli_query($con,$mar));
    $creer=$mary['creer'];
    $creer++;

    $yem="UPDATE cadre 
        SET creer = '$creer'
        WHERE idCadre= '$idCadre'";
    $sof=mysqli_query($con,$yem);
}
if($tachecount_result[0]%$nbcadre==0)
{
    $nbpage=$tachecount_result[0]/$nbcadre;
}
else{$nbpage=floor($tachecount_result[0]/$nbcadre)+1;}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef service</title>
    <link rel="stylesheet" href="../css/edit_chef.css">
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
            <p>Bienvenu <?php echo $_SESSION["chef_nam"];?></p>
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
        <div class="edit">
            <div class="info_tache">
                <h1>l'historique de la tache </h1>
            </div>
            <table>
                <tr>
                    <td>Nom</td>
                    <td>Date debut</td>
                    <td>Date fin</td>
                    <td>statu</td>
                </tr>
                <?php while($hito=mysqli_fetch_array($historique)) { ?>
                <tr>
                    <td><?php echo $hito['nom']." ". $hito['prenom'];?></td>
                    <td><?php  echo $hito['dateDebut'] ;?></td>
                    <td><?php  echo $hito['dateFin'] ;?></td>
                    <td><?php 
                        if($hito['dateFin']!='0000-00-00') echo 'bloqué'; 
                        else echo 'En Cours';
                        ?></td>
                </tr>
                <?php } ?>
            </table>
            <div class="chnger_cadre">
                <h1>affecter la tache à un autre cadre</h1>
                <form action="edit.php?idTache=<?php echo $idTache;?>" method="post">
                    <label for="cadre">choisir un cadre : </label>
                    <select name="cadre" id="cadre">
                        <?php while($cad=mysqli_fetch_array($cadresliste)) { ?>
                        <option value="<?php echo $cad['idCadre'];?>"><?php echo $cad['nom']." ".$cad['prenom'];?></option>
                        <?php } ?>
                    </select>
                    <button type="submit" name="submit">affecter</button>
                </form>
            </div>
        </div>
    </div>
    <script src="../js/cadre.js"></script>
</body>
</html>