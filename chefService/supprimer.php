<?php
session_start();
include("../connexion.php");
$idTache=$_GET['idTache'];
$query = "DELETE FROM realiser WHERE idTache = '$idTache'";
$query1 = "DELETE FROM tache WHERE idTache = '$idTache'";
$sup=mysqli_query($con,$query);
$supp=mysqli_query($con,$query1);
header("location:chef.php");
?>