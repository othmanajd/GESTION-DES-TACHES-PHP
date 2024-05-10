<?php
session_start();
include("connexion.php");
if(isset($_POST["submit"]))
{
    if (isset($_POST['submit']))
        {
            $email= $_POST['email'];
            $motpass= md5($_POST['motpass']);

            $query1="select * from cadre where email='$email' && motpass='$motpass'";
            $result1=mysqli_query($con,$query1);

            $query2="select * from  chefservice where email='$email' && motpass='$motpass'";
            $result2=mysqli_query($con,$query2);

            $query3="select * from  chefdivision where email='$email' && motpass='$motpass'";
            $result3=mysqli_query($con,$query3);

            if(mysqli_num_rows($result1)>0)
            {
                // $_SESSION['name']=$matricule;
                $tab= mysqli_fetch_array($result1);
                $_SESSION["cadre_nam"]=$tab['prenom'];
                $_SESSION["cadre_id"]=$tab['idCadre'];
                header("location:cadre/cadre.php");
            }
            elseif(mysqli_num_rows($result2)>0)
            {
                $tab= mysqli_fetch_array($result2);
                $_SESSION["chef_nam"]=$tab['prenom'];
                $_SESSION["chef_id"]=$tab['idChefService'];
                header("location:chefService/chef.php");
            }
            elseif(mysqli_num_rows($result3)>0)
            {
                $tab= mysqli_fetch_array($result3);
                $_SESSION["chefdivision_nam"]=$tab['prenom'];
                $_SESSION["chefdivision_id"]=$tab['idChefDivision'];
                header("location:chefDivision/chef.php?division=division&submit=");
            }
            else
            {
                $erreur="Mot de passe ou E-mail est incorrect";
            }
            }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="icon" href="img/logosans.png"  >
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <?php if(isset($erreur)) echo'<p class="erreur">'.$erreur.'</p>';?>
    <div class="form">
        <form method="post">
            <div class="logo">
                <img class="mins" src="img/logosans.png" alt="logo">
                <img src="img/logoMorocsans.png" alt="logoMOROC">
            </div>
            <p class="con">Connexion</p>
            <label for="email">E-mail</label>
            <input type="text" placeholder=" Entrer votre E-mail" id="email" name="email" required>
            <br>
            <label for="motpasss" >Mot de passe</label>
            <input type="password" id="motpass" name="motpass" placeholder="  Entre votre mot de passe" required >
            <br>
            <button name="submit">Se connecter</button>
            <p><a href="">E-mail ou mot de passe oublié ?</a></p>
        </form>
    </div>
</body>
</html>