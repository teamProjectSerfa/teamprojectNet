<?php
require_once("includes/header.php");

//  on recupere les parties des videos et on les affichent 
$preview = new PreviewProvider($con, $userLoggedIn);
echo $preview->createPreviewVideo(null);

//  on affiche les categories des vifeos existes dans la BDD
$containers = new CategoryContainers($con, $userLoggedIn);
echo $containers->showAllCategories();
?>