<?php

$bdd = new PDO('mysql:host=localhost:3308; dbname=espace_membre', 'salash', 'Mogwey13');

if(isset($_POST['forminscription']))
{
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $mail = htmlspecialchars($_POST['mail']);
    $mail2 = htmlspecialchars($_POST['mail2']);
    $mdp = sha1($_POST['mdp']);
    $mdp2 = sha1($_POST['mdp2']);

    if(!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2']))
    {
 
        $pseudolength = strlen($pseudo);
        if($pseudolength <= 50)
        {
            if($mail == $mail2)
            {
                if(filter_var($mail, FILTER_VALIDATE_EMAIL))
                {    
                    $reqmail = $bdd->prepare("SELECT * FROM membre WHERE mail = ?");
                    $reqmail->execute(array($mail));
                    $mailexist = $reqmail->rowCount();
                    if($mailexist == 0)    
                    {    
                        if($mdp == $mdp2)
                        {
                            $insertmbr = $bdd->prepare("INSERT INTO membres(pseudo, mail, motsdepasse) VALUES(?, ?, ?)");
                            $insertmbr->execute(array($pseudo, $mail, $mdp));
                            $erreur = "votre compte a bien été créé ! <a href=\"connexion.php\">Me connecter</a>";
                        }
                        else
                        {
                            $erreur = "Vos mdp ne correspondent pas !";
                        }
                    }
                    else
                    {
                        $erreur = "Adresse mail déja utilisée !";
                    }
                }
                else
                {
                    $erreur = "Votre adress mail n'est pas valide !";
                }
            }
            else
            {
                $erreur = "Vos adress mail ne correspondent pas !" ;
            }
               
        }
        else
        {
            $erreur = "Votre pseudo ne doit pas dépasser 50 caractéres !";
        }
    }
    else
    { 
        $erreur = "Tous les champs doivent etre complété !";
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
        <h2>Inscription</h2>
        <br /><br />
        <form method="POST" action="">
                <table>
                    <tr>
                        <td align="right">
                            <label for="pseudo">Pseudo:</label>
                        </td>
                        <td>
                            <input type="text" placeholder="Votre pseudo" id="pseudo" name="pseudo" value="<?php if(isset($pseudo)) { echo $pseudo; } ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <label for="mail">Mail:</label>
                        </td>
                        <td>
                            <input type="email" placeholder="Votre Mail" id="mail" name="mail" value="<?php if(isset($mail)) { echo $mail; } ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <label for="mail2">Confirmation du mail:</label>
                        </td>
                        <td>
                            <input type="email" placeholder="Confirmez votre meail" id="mail2" name="mail2" value="<?php if(isset($mail2)) { echo $mail2; } ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <label for="mdp">Mots de passe</label>
                        </td>
                        <td>
                            <input type="password" placeholder="Votre mots de passe " id="mdp" name="mdp" />
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <label for="mdp2">Confirmation du mots de passe</label>
                        </td>
                        <td>
                            <input type="password" placeholder="Confirmez votre mdp " id="mdp2" name="mdp2" />
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="center">
                            <br />
                            <input type="submit" name="forminscription" value="Je m'inscris" />
                        </td>
                    </tr>
                </table>
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