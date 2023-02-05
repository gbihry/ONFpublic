<?php

    include_once "$racine/modele/ModeleObjetDAO.php";
    include_once "$racine/vue/vueEntete.php";

    if (!isset($_SESSION['autorise'])){
        header("location:./?action=login");
    }else{  
        
        include_once "$racine/vue/vueCommandeReussi.php";
    }
    include_once "$racine/vue/vuePied.php";
?>