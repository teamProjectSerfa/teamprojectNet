<?php
require_once("includes/header.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");

$user = new User($con, $userLoggedIn);

$detailsMessage = "";
$passwordMessage = "";

if(isset($_POST["saveDetailsButton"])) {
    $account = new Account($con);

    $firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
    $lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);
    $email = FormSanitizer::sanitizeFormEmail($_POST["email"]);

    if($account->updateDetails($firstName, $lastName, $email, $userLoggedIn)) {
        $detailsMessage = "<div class='alertSuccess'>
                                Informations modifié avec succès !
                            </div>";
    }
    else {
        $errorMessage = $account->getFirstError();

        $detailsMessage = "<div class='alertError'>
                                $errorMessage
                            </div>";
    }
}

if(isset($_POST["savePasswordButton"])) {
    $account = new Account($con);

    $oldPassword = FormSanitizer::sanitizeFormPassword($_POST["oldPassword"]); 
    $newPassword = FormSanitizer::sanitizeFormPassword($_POST["newPassword"]);
    $newPassword2 = FormSanitizer::sanitizeFormPassword($_POST["newPassword2"]);

    if($account->updatePassword($oldPassword, $newPassword, $newPassword2, $userLoggedIn)) {
        $passwordMessage = "<div class='alertSuccess'>
                                Mot de passe modifié avec succès !
                            </div>";
    }
    else {
        $errorMessage = $account->getFirstError();

        $passwordMessage = "<div class='alertError'>
                                $errorMessage
                            </div>";
    }
}
?>
<div class="settingsContainer column">

    <div class="formSection">

        <form method="POST">

            <h2>Informations d'utilisateur</h2>
            
            <?php

            $firstName = isset($_POST["firstName"]) ? $_POST["firstName"] : $user->getFirstName();
            $lastName = isset($_POST["lastName"]) ? $_POST["lastName"] : $user->getLastName();
            $email = isset($_POST["email"]) ? $_POST["email"] : $user->getEmail();
            ?>

            <input type="text" name="firstName" placeholder="Saisir Votre nom" value="<?php echo $firstName; ?>">
            <input type="text" name="lastName" placeholder="Saisir Votre prénom" value="<?php echo $lastName; ?>">
            <input type="email" name="email" placeholder="Saisir Votre email" value="<?php echo $email; ?>">

            <div class="message">
                <?php echo $detailsMessage; ?>
            </div>
            
            <input type="submit" name="saveDetailsButton" value="Valider">


        </form>

    </div>

    <div class="formSection">

        <form method="POST">

            <h2>Modifier votre mot de passe</h2>

            <input type="password" name="oldPassword" placeholder="Mot de passe actuel">
            <input type="password" name="newPassword" placeholder="Entrer votre nouveau mot de passe">
            <input type="password" name="newPassword2" placeholder="Confirmer votre nouveau mot de passe">

            <div class="message">
                <?php echo $passwordMessage; ?>
            </div>

            <input type="submit" name="savePasswordButton" value="Enregistrer">


        </form>

    </div>