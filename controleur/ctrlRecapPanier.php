<?php

    include_once "$racine/modele/ModeleObjetDAO.php";
    include_once "$racine/vue/vueEntete.php";

    if (!isset($_SESSION['autorise'])){
        header("location:./?action=login");
    }else{  
        switch($_GET['type']) {
            case 'vet':
                $ligneCommandeVET = ModeleObjetDAO::getLigneCommandeVetUtilisateur(ModeleObjetDAO::getIdUtilisateur($_SESSION['login'])['id']);
                $points = ModeleObjetDAO::getNbrPointUtilisateur(ModeleObjetDAO::getIdUtilisateur($_SESSION['login'])['id'])['point'];
                if($ligneCommandeVET == false){
                    header("location:./?action=panierVET");
                }
                break;
            case 'epi':
                $ligneCommandeEPI = ModeleObjetDAO::getLigneCommandeEpiUtilisateur(ModeleObjetDAO::getIdUtilisateur($_SESSION['login'])['id']);
                if($ligneCommandeEPI == false){
                    header("location:./?action=panierEPI");
                }
                break;
            default:
                header("location:./?action=accueil");
                break;
        }
        
        if(isset($_POST['validerCommande'])) {
            ModeleObjetDAO::validerCommande(ModeleObjetDAO::getIdUtilisateur($_SESSION['login'])['id'], $_GET['type']);
        }

        include_once "$racine/vue/vueRecapPanier.php";
    }
    include_once "$racine/vue/vuePied.php";
?>