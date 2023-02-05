<?php 
    include_once "$racine/modele/ModeleObjetDAO.php";
    include "$racine/vue/vueEntete.php";
    

    if(isset($_SESSION['autorise']) && ModeleObjetDAO::getRole($_SESSION['login'])['libelle'] == 'Administrateur'){
        
        $RecapEpi = ModeleObjetDAO::getRecapCommandeEpi();

        $RecapVet = ModeleObjetDAO::getRecapCommandeVet();
        
        include "$racine/vue/vueRecapCommande.php";
    } else {
        header("location:./?action=accueil");
    }
    include "$racine/vue/vuePied.php";
?>