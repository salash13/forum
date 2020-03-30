<?php session_start();

$bdd = new PDO('mysql:host=localhost:3308; dbname=espace_membre', 'salash', 'Mogwey13');

if(isset($_GET['id']) AND $_GET['id'] > 0)
{
    $getid = intval($_GET['id']);
    $requser = $bdd->prepare('SELECT * FROM membres WHERE id = ? ');
    $requser->execute(array($getid));
    $userinfo = $requser->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
        <div align="center">
            <h2>Bonjours <?php echo $userinfo['pseudo']; ?></h2>
            <br /><br />
            <?php
            if(!empty($userinfo['avatar']))
            {
            ?>
            <img src="membres/avatars/<?php echo $userinfo['avatar']; ?>" width="150"> 
            <?php  
            }
            ?>
            <br /><br />
            Pseudo = <?php echo $userinfo['pseudo']; ?>
            <br />
            Mail = <?php echo $userinfo['mail']; ?>
            <?php
            if(isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id']);
            {
            ?>
                <a href="editionprofil.php">Editer mon profil></a>
                <a href="deconnexion.php">Se déconnecter></a>
            <?php
            }
            ?>
        </div>
    </body>
</html>

<?php
}
?>