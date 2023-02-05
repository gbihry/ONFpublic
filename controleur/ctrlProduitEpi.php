<?php

    include_once "$racine/modele/ModeleObjetDAO.php";
    include_once "$racine/vue/vueEntete.php";
    if (!isset($_SESSION['autorise'])){
        header("location:./?action=login");
    }else{  
        
        $idCateg = $_GET["id"];
        $unProduit = ModeleObjetDAO::getProduit($_GET["id"],substr(($_GET["action"]),-3));

        $unStatut = ModeleObjetDAO::getStatut($_SESSION['login']);

        $role = ModeleObjetDAO::getRole($_SESSION['login']);
        switch($role['libelle']){
            case 'Responsable' : 
                $responsable = ModeleObjetDAO::getResponsableCommande(ModeleObjetDAO::getIdUtilisateur($_SESSION['login'])['id']);
                $commanderPour = ModeleObjetDAO::getCommanderPour($responsable['id_responsable']);
                break;
            case 'Administrateur' : 
                $commanderPour = ModeleObjetDAO::getCommanderPourTous();
                break;
        }
        
        if ((isset($_POST['quantity'])) && ($_POST['quantity'] >= 1)){

            date_default_timezone_set('Europe/Paris');

            $idUtilisateur = ModeleObjetDAO::getIdUtilisateur($_SESSION['login']);
            if(ModeleObjetDAO::insertEPICommande($idUtilisateur, $unStatut['statut']) != false) {
            
                $quantite = $_POST['quantity'];
                $taille = $_POST['taille'];
                $idProduit = $_POST['submit'];
    
                ModeleObjetDAO::insertLigneCommandeEPI($idUtilisateur, $idProduit, $quantite, $taille);

            } else {
                echo "Erreur lors de l'insertion de la commande";
            }
            
        }
        include_once "$racine/vue/vueProduitEpi.php";
        
    }
    include_once "$racine/vue/vuePied.php";

    
    
    
    
?>