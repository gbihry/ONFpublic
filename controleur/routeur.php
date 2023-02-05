<?php

class Routeur{
    
    //Attributs
    private static $lesActions = array(
        'defaut' => 'ctrlAccueil.php',
        'login' => 'ctrlLogin.php',
        'logout' => 'ctrlLogout.php',
        'catalogueVet' => 'ctrlCatalogueVet.php',
        'catalogueEpi' => 'ctrlCatalogueEpi.php',
        'produitVet' => 'ctrlProduitVet.php',
        'produitEpi' => 'ctrlProduitEpi.php',
        'panierEPI' => 'ctrlPanierEPI.php',
        'panierVET' => 'ctrlPanierVET.php',
        'historiqueCommande' => 'ctrlHistoriqueCommande.php',
        'ajoutUtilisateur' => 'ctrlAjoutUtilisateur.php',
        'historiquecommandedetail' => 'ctrlHistoriqueCommandeDetail.php',
        'ajoutPoint' => 'ctrlAjoutPoint.php',
        'aCommander' => 'ctrlAcommander.php',
        'recapPanier' => 'ctrlRecapPanier.php',
        'commandeReussie' => 'ctrlCommandeReussi.php',
        'newmdp' => 'ctrlNewMdp.php',
        'detail' => 'ctrlDetailObjet.php',
        'recapCommande' => 'ctrlRecapCommande.php'
    );   
    
        
    //Fonction qui retourne le fichier controleur à utiliser
    public static function getControleur($action){
        $controleur = self::$lesActions["defaut"];

        //Permet de vérifier que l'action existe et renvoie le nom du contrôleur PHP    
        if (array_key_exists ( $action , self::$lesActions )){
            $controleur = self::$lesActions[$action];
        }

        return $controleur;
    }
}