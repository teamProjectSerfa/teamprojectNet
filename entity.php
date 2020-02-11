<?php
require_once("includes/header.php");

if(!isset($_GET["id"])) {
    ErrorMessage::show("No ID passed into page");
}
$entityId = $_GET["id"];
$entity = new Entity($con, $entityId);


// crée une vue pour l'utilisateur connectée
$preview = new PreviewProvider($con, $userLoggedIn);
echo $preview->createPreviewVideo($entity);


// on crée une season pour l'entité
$seasonProvider = new SeasonProvider($con, $userLoggedIn);
echo $seasonProvider->create($entity);

//  génerer une vue pour les films ou les séries simulaires de meme genre rechercher
$categoryContainers = new CategoryContainers($con, $userLoggedIn);
echo $categoryContainers->showCategory($entity->getCategoryId(), "You might also like");
?>