<?php
    include_once "$racine/modele/ModeleObjetDAO.php";
    include_once "$racine/vue/vueEntete.php";
    if (!isset($_SESSION['autorise'])){
        header("location:./?action=login");
    }else{  
        $HistoriqueCommandeDetail = ModeleObjetDAO::getHistoriqueCommandeDetail(ModeleObjetDAO::getIdUtilisateur($_SESSION['login'])['id'], $_GET['id'], $_GET['type']);
        include_once "$racine/vue/vueHistoriqueCommandeDetail.php";
    }
    include_once "$racine/vue/vuePied.php";
