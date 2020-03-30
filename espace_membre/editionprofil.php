<?php session_start();

$bdd = new PDO('mysql:host=localhost:3308; dbname=espace_membre', 'salash', 'Mogwey13');

if(isset($_SESSION['id']))
{
    $requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
    $requser->execute(array($_SESSION['id']));
    $user = $requser->fetch();

    if(isset($_POST['newpseudo']) AND !empty($_POST['newpseudo']) AND $_POST['newpseudo'] != $user['pseudo'])
    {
        $newpseudo = htmlspecialchars($_POST['newpseudo']);
        $insertpseudo = $bdd->prepare("UPDATE membres SET pseudo = ? WHERE id = ?");
        $insertpseudo->execute(array($newpseudo, $_SESSION['id']));
        header('Location: profil.php?id='.$_SESSION['id']);
    }

    if(isset($_POST['newmail']) AND !empty($_POST['newmail']) AND $_POST['newmail'] != $user['mail'])
    {
        $newmail = htmlspecialchars($_POST['newmail']);
        $insertpseudo = $bdd->prepare("UPDATE membres SET mail = ? WHERE id = ?");
        $insertpseudo->execute(array($newmail, $_SESSION['id']));
        header('Location: profil.php?id='.$_SESSION['id']);
    }

    if(isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']) AND isset($_POST['newmdp2']) AND !empty($_POST['newmdp2']))
    {
        $mdp1 = sha1($_POST['newmdp1']);
        $mdp2 = sha1($_POST['newmdp2']);

        if($mdp1 == $mdp2)
        {
            $insertmdp = $bdd->prepare("UPDATE membres SET motsdepasse = ? WHERE id = ? ");
            $insertmdp->execute(array($mdp1, $_SESSION['id']));
            header('Location: profil.php?id='.$_SESSION['id']);
        }
        else
        {
            $msg ='Vos deux mdp ne corresponde pas !';
        }            
    }

    if(isset($_FILES['avatar']) AND  !empty($_FILES['avatar']['name']))
    {
        $tailleMax = 2097152;
        $extensionsValides  = array('jpg', 'jpeg', 'gif', 'png');
        if($_FILES['avatar']['size'] <= $tailleMax)
        {
            $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
            if(in_array($extensionUpload, $extensionsValides))
            {
                $chemin = "membres/avatars/".$_SESSION['id'].".".$extensionUpload;
                $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
                if($resultat)
                {
                    $updateavatar = $bdd->prepare('UPDATE membres SET avatar = :avatar WHERE id = :id');
                    $updateavatar->execute(array(
                        'avatar' => $_SESSION['id'].".".$extensionUpload,
                        'id' => $_SESSION['id']  
                        ));
                        header('Location: profil.php?id='.$_SESSION['id']);
                }
                else
                {
                    $msg = "Erreur durant l'importation de votre photo de profil";
                }
            }
            else
            {
                $msg = " Votre photo de profil doit etre au format jpg, jpeg, gif, png !";
            }
        }
        else
        {
            $msg = "Votre photo de profil ne doit pas dépasser 2 MO";
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
            <h2>Edition de mon profil</h2>
            <div align="left">
                <form method="POST" action="" enctype="multipart/form-data">
                    <label>Pseudo :</label>
                    <input type="text" name="newpseudo" placeholder="Pseudo" value="<?php echo $user['pseudo']; ?>" > <br /> <br />
                    <label>Mail :</label>
                    <input type="text" name="newmail" placeholder="Mail" value="<?php echo $user['mail']; ?>" > <br /><br />
                    <label>Mots de passe:</label>
                    <input type="password" name="newmdp1" placeholder="Mots de passe"><br /><br />
                    <label>Confirmation de votre mots de passe :</label>
                    <input type="password" name="newmdp2" placeholder="Confirmation du mots de passe"><br /><br />
                    <label >Avatar :</label>
                    <input type="file" name="avatar"><br /><br />
                    <input type="submit" value="Mettre a jour mon profil !"><br /><br />
                </form>
                <?php if(isset($msg)) { echo $msg; } ?>
            </div>
        </div>
    </body>
</html>
<?php
}
else
{
    header("Location: connexion.php");
}
?>