<?php 
session_start();

$bdd = new PDO('mysql:host=localhost:3308; dbname=espace_membre', 'salash', 'Mogwey13');

if(isset($_POST['formconnexion']))
{
    $mailconnect =htmlspecialchars($_POST['mailconnect']);
    $mdpconnect =sha1($_POST['mdpconnect']);
    if(!empty($mailconnect) AND !empty($mdpconnect))
    {
        $requser = $bdd->prepare("SELECT * FROM membres WHERE mail = ? AND motsdepasse = ?");
        $requser->execute(array($mailconnect, $mdpconnect));
        $userexist = $requser->rowCount();
        if($userexist == 1)
        {
            $userinfo = $requser->fetch();
            $_SESSION['id'] = $userinfo['id'];
            $_SESSION['pseudo'] = $userinfo['pseudo'];
            $_SESSION['mail'] = $userinfo['mail'];
            header("location: profil.php?id=".$_SESSION['id']);

        }
        else
        {
            $erreur = "Mauvais mail ou mots de passe !";
        }
    }
    else
    {
        $erreur = "Tous les champs doivent étre complétés !";
    }
}


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
        <h2>Connexion</h2>
        <br /><br />
        <form method="POST" action="">
                <input type="email" name="mailconnect" placeholder="Mail" />
                <input type="password" name="mdpconnect" placeholder="Mot de passe" />
                <input type="submit" name="formconnexion" value="Se connecter !" />
        </form>
        
        <?php
        if(isset($erreur))
        {
            echo '<font color="red">'.$erreur."</font>";
        }
        ?>

    </div>

</body>
</html>