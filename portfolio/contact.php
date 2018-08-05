<?php
/*
    ********************************************************************************************
    CONFIGURATION
    ********************************************************************************************
*/
// destinataire est votre adresse mail. Pour envoyer à plusieurs à la fois, séparez-les par une virgule
$destinataire = 'contact@yoann-delaloy.ovh';

// copie ? (envoie une copie au visiteur)
$copie = 'oui';

// Action du formulaire (si votre page a des paramètres dans l'URL)
// si cette page est index.php?page=contact alors mettez index.php?page=contact
// sinon, laissez vide
$form_action = '';

// Messages de confirmation du mail
$message_envoye = "Votre message nous est bien parvenu !";
$message_non_envoye = "L'envoi du mail a échoué, veuillez réessayer SVP.";

// Message d'erreur du formulaire
$message_formulaire_invalide = "Vérifiez que tous les champs soient bien remplis et que l'email soit sans erreur.";

/*
 * NoSpamQuestion affiche une question pour la validation d'un formulaire ...
 * $mode, mode question ou réponse par défaut tirage au sort de question {string}
 * $answer, lors de la demande d'une réponse à la question numero tant ... {int}
 *
 * @returns array
 *
 * Ajouter une question :
 * copier/coller ces lignes et remplir le contenu entre guillemets doubles :
 *
 * $array_pictures[$j]['num'] = $j; // ne pas changer cette ligne
 * $array_pictures[$j]['question'] = "mettre ici la question (correspondant à l'image si vous utilisez une image)";
 * $array_pictures[$j]['answer'] = "mettre ici la réponse à l'énigme";
 * $j++; // ne pas oublier cette ligne dans la copie :-)
 *
 * C'est tout. Question suivante ? :-)
 *
 */
function NoSpamQuestion($mode = 'ask', $answer = 0)
{
    $array_pictures = array(); $j = 0;

    $array_pictures[$j]['num'] = $j;
    $array_pictures[$j]['question'] = "Quelle est la cinquième lettre du mot Astux";
    $array_pictures[$j]['answer'] = "x";
    $j++;

    $array_pictures[$j]['num'] = $j;
    $array_pictures[$j]['question'] = "Le soleil est-il chaud ou froid ?";
    $array_pictures[$j]['answer'] = "chaud";
    $j++;

    $array_pictures[$j]['num'] = $j;
    $array_pictures[$j]['question'] = "Ecrire 12 en lettres";
    $array_pictures[$j]['answer'] = "douze";
    $j++;

    if ($mode != 'ans')
    {
        // on est en mode 'tirer au sort', on tire une image aléatoire
        $lambda = rand(0, count($array_pictures)-1);
        return $array_pictures[$lambda];
    }
    else
    {
        // on demande une vraie réponse
        foreach($array_pictures as $i => $array)
        {
            if ($i == $answer)
            {
                return $array;
                break;
            };
        };
    }; // Fin if ($mode != 'ans')
};
?>
<!DOCTYPE html>
<html>
<head>
	<?php include 'header.php'; ?>
	<title>Yoann DELALOY Contact</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
  <style type="text/css">
    .jumbotron {
background: #358CCE;
color: #FFF;
border-radius: 0px;
}
.jumbotron-sm { padding-top: 24px;
padding-bottom: 24px; }
.jumbotron small {
color: #FFF;
}
.h1 small {
font-size: 24px;
}
  </style>
</head>
<body>
	<?php include 'menufixe.php'; ?>
	<br>
	<div class="row">
	<?php include 'menuv.php'; ?>
    <div class="col-sm-9 col-md-9 affix-content">
      <div class="container">
        <div class="col-md-12" style="background-color: lightgray; border-width:2px; border-style:outset; border-radius: 15px;  box-shadow:10px 10px 20px 0px #696969; width: 1000px;">
          <div class="page-header" style="text-align: center;">
            <h3 style="text-shadow: 5px 5px 6px #82576b;"><span></span><strong>Contact</strong></h3>
          </div>
        
<!-- Include the above in your HEAD tag-->


<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="well well-sm">
               <?php
               
    // on tire au sort une question
    $nospam = NoSpamQuestion();

/*
 * cette fonction sert à nettoyer et enregistrer un texte
 */
function Rec($text)
{
    $text = htmlspecialchars(trim($text), ENT_QUOTES);
    if (1 === get_magic_quotes_gpc())
    {
        $text = stripslashes($text);
    }

    $text = nl2br($text);
    return $text;
};

/*
 * Cette fonction sert à vérifier la syntaxe d'un email
 */
function IsEmail($email)
{
    $value = preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
    return (($value === 0) || ($value === false)) ? false : true;
}

// formulaire envoyé, on récupère tous les champs.
$nom        = (isset($_POST['nom']))        ? Rec($_POST['nom'])        : '';
$email      = (isset($_POST['email']))      ? Rec($_POST['email'])      : '';
$objet      = (isset($_POST['objet']))      ? Rec($_POST['objet'])      : '';
$message    = (isset($_POST['message']))    ? Rec($_POST['message'])    : '';
$antispam_h = (isset($_POST['antispam_h'])) ? Rec($_POST['antispam_h']) : '';
$antispam_r = (isset($_POST['antispam_r'])) ? Rec($_POST['antispam_r']) : '';

// On va vérifier les variables et l'email ...
$email = (IsEmail($email)) ? $email : ''; // soit l'email est vide si erroné, soit il vaut l'email entré
$err_formulaire = false; // sert pour remplir le formulaire en cas d'erreur si besoin

if (isset($_POST['envoi']))
{
    // On demande la vraie réponse
    $verif_nospam = NoSpamQuestion('ans', $antispam_r);

    if (strtolower($antispam_h) != strtolower($verif_nospam['answer']))
    {
        // le formulaire s'arrête ici
        echo '<p>Vous n\'avez pas répondu correctement à la question Antispam ...</p>';
    }
    else
    {
        if (($nom != '') && ($email != '') && ($objet != '') && ($message != ''))
        {
            // les 4 variables sont remplies, on génère puis envoie le mail
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'From:'.$nom.' <'.$email.'>' . "\r\n" .
                'Reply-To:'.$email. "\r\n" .
                'Content-Type: text/plain; charset="utf-8"; DelSp="Yes"; format=flowed '."\r\n" .
                'Content-Disposition: inline'. "\r\n" .
                'Content-Transfer-Encoding: 7bit'." \r\n" .
                'X-Mailer:PHP/'.phpversion();

            // envoyer une copie au visiteur ?
            if ($copie == 'oui')
            {
                $cible = $destinataire.';'.$email;
            }
            else
            {
                $cible = $destinataire;
            };

            // Remplacement de certains caractères spéciaux
            $message = str_replace("&#039;","'",$message);
            $message = str_replace("&#8217;","'",$message);
            $message = str_replace("&quot;",'"',$message);
            $message = str_replace('<br>','',$message);
            $message = str_replace('<br />','',$message);
            $message = str_replace("&lt;","<",$message);
            $message = str_replace("&gt;",">",$message);
            $message = str_replace("&amp;","&",$message);

            // Envoi du mail
            $num_emails = 0;
            $tmp = explode(';', $cible);
            foreach($tmp as $email_destinataire)
            {
                if (mail($email_destinataire, $objet, $message, $headers))
                    $num_emails++;
            }

            if ((($copie == 'oui') && ($num_emails == 2)) || (($copie == 'non') && ($num_emails == 1)))
            {
                echo '<p>'.$message_envoye.'</p>';
            }
            else
            {
                echo '<p>'.$message_non_envoye.'</p>';
            };
        }
        else
        {
            // une des 3 variables (ou plus) est vide ...
            echo '<p>'.$message_formulaire_invalide.'</p>';
            $err_formulaire = true;
        };
    };
}; // fin du if (!isset($_POST['envoi']))

if (($err_formulaire) || (!isset($_POST['envoi'])))
{
    // afficher le formulaire
    echo '
    <form id="contact" method="post" action="'.$form_action.'">
    <fieldset><legend>Vos coordonnées</legend>
        <p><label for="nom">Nom :</label><input type="text" id="nom" name="nom" value="'.stripslashes($nom).'" tabindex="1" /></p>
        <p><label for="email">Email :</label><input type="text" id="email" name="email" value="'.stripslashes($email).'" tabindex="2" /></p>
    </fieldset>

    <fieldset><legend>Votre message :</legend>
        <p><label for="objet">Objet :</label><input type="text" id="objet" name="objet" value="'.stripslashes($objet).'" tabindex="3" /></p>
        <p><label for="message">Message :</label><textarea id="message" name="message" tabindex="4" cols="30" rows="8">'.stripslashes($message).'</textarea></p>
    </fieldset>

    <fieldset><legend>Antispam</legend>
        <p><label for="antispam_h">'.$nospam['question'].'</label><input type="text" name="antispam_h" id="antispam_h" /><input type="hidden" name="antispam_r" value="'.$nospam['num'].'" /></p>
    </fieldset>

    <div style="text-align:center;"><input type="submit" name="envoi" value="Envoyer le formulaire !" /></div>
    </form>';
};
?>
            </div>
        </div>
        <div class="col-md-3">
            <form>
            <legend><span class="glyphicon glyphicon-globe"></span> Contact</legend>
            <address>
                <strong>Yoann DELALOY</strong><br>
                19 bis rue du faubourg saint jeans<br>
                Orléans, CA 45000<br>
                <abbr title="Phone">
                    P:</abbr>
                (+33) 634 436 478
            </address>
            <address>
                <strong>Yoann DELALOY Contact</strong><br>
                <a href="mailto:contact@yoann-delaloy.ovh" title="Adresse MAIL">Contact@yoann-delaloy.ovh</a>
            </address>
            </form>
        </div><br />
    </div><br />
    <br /><br /><br />
    
</div><br />

         </div><br />
       </div><br />
      <br />
	</div><br /><br />
</div><br />
	<?php include 'footer.php'; ?>
</body>
</html>
