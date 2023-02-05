<?php

include_once "$racine/modele/ModeleObjetDAO.php";
include "$racine/vue/vueEntete.php";

$error = "";

$valider = filter_input(INPUT_POST, 'valider');

if($valider) {
    $motDePasseActuel_INPUT= filter_input(INPUT_POST, 'mdpActuel');
    $nouveauMotDePasse_INPUT = filter_input(INPUT_POST, 'mdpNew');
    $nouveauMotDePasse2_INPUT = filter_input(INPUT_POST, 'mdpNewConfirm');

    if($nouveauMotDePasse_INPUT == $nouveauMotDePasse2_INPUT && $_SESSION['login'] != null && $_SESSION['wait'] == 'newmdp') {
        $try = ModeleObjetDAO::updateMdp($_SESSION['login'], $motDePasseActuel_INPUT, $nouveauMotDePasse_INPUT);
        if($try === true) {
            session_destroy();
            header("location:./?action=login&msg=" . urlencode('Mot de passe modifié, veuillez vous reconnecter.'));
        } else {
            $error = $try;
        }
    } else {
        $error = "Les mots de passe ne correspondent pas";
    }
}

// Affichage des vues
include "$racine/vue/vueNewMdp.php";
include "$racine/vue/vuePied.php";

