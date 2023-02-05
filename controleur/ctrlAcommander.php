<?php 
    include_once "$racine/modele/ModeleObjetDAO.php";
    include_once "$racine/vue/vueEntete.php";

    
    if(isset($_SESSION['autorise']) && ModeleObjetDAO::getRole($_SESSION['login'])['libelle'] == 'Administrateur'){
        $AllUsersAcommander = ModeleObjetDAO::getUtilisateurCommander(1);
        $AllUsersNoncommander = ModeleObjetDAO::getUtilisateurCommander(0);
    } else {
        header("location:./?action=accueil");
    }

    




    include_once "$racine/vue/vueAcommander.php";
    include_once "$racine/vue/vuePied.php";

?> 