<?php 
include("connexion.php");
if(isset($_POST["submit"]))
{
    $pwd=md5($_POST["pwd"]);
    $requite="UPDATE chefdivision
            SET motpass = '$pwd'";
    $exupreq=mysqli_query($con,$requite);
}
?>
<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pssword</title>
</head>
<body>
    <form method="post">
    <input type="text" name="pwd">
    <button name="submit">update</button>
    </form>
</body>
</html>