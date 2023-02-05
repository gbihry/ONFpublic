<?php
    include_once "$racine/modele/ModeleObjetDAO.php";
    include_once "$racine/vue/vueEntete.php";
    if (!isset($_SESSION['autorise'])){
        header("location:./?action=login");
    }else{  
        $HistoriqueCommande = ModeleObjetDAO::getHistoriqueCommande(ModeleObjetDAO::getIdUtilisateur($_SESSION['login'])['id']);
        include_once "$racine/vue/vueHistoriqueCommande.php";
    }
    include_once "$racine/vue/vuePied.php";
