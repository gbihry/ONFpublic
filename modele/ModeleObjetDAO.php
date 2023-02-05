<?php

    require_once("Connexion.php");

    
    class ModeleObjetDAO{
        

        // INFORMATIONS NAV 

        public static function getNbArticlePanier($id, $type){
            switch ($type) {
                case 'vet':
                    $commandeid = ModeleObjetDAO::getIdVetUtilisateur($id);
                    $req = Connexion::getInstance()->prepare("SELECT count(lignecommandevet.id) FROM lignecommandevet
                    JOIN commandevet on commandevet.id = lignecommandevet.idCommandeVET
                    WHERE commandevet.id = :leId");
                    if($commandeid == false) {
                        return 0;
                    }
                    $req->bindValue(':leId',$commandeid['id'],PDO::PARAM_INT);
                    break;
                case 'epi':
                    $commandeid = ModeleObjetDAO::getIdEpiUtilisateur($id);
                    $req = Connexion::getInstance()->prepare("SELECT count(lignecommandeepi.id) FROM lignecommandeepi 
                    JOIN commandeepi on commandeepi.id = lignecommandeepi.idCommandeEPI
                    WHERE commandeepi.id = :leId");
                    if($commandeid == false) {
                        return 0;
                    }
                    $req->bindValue(':leId',$commandeid['id'],PDO::PARAM_INT);
                    break;
                default:
                    $commandeid = ModeleObjetDAO::getIdVetUtilisateur($id);
                    $commandeid2 = ModeleObjetDAO::getIdEpiUtilisateur($id);
                    $req = Connexion::getInstance()->prepare("SELECT count(lignecommandevet.id) FROM lignecommandevet
                    JOIN commandevet on commandevet.id = lignecommandevet.idCommandeVET
                    WHERE commandevet.id = :leId
                    UNION ALL
                    SELECT count(lignecommandeepi.id) FROM lignecommandeepi 
                    JOIN commandeepi on commandeepi.id = lignecommandeepi.idCommandeEPI
                    WHERE commandeepi.id = :leId2");
                    if($commandeid == false && $commandeid2 == false) {
                        return 0;
                    }
                    $req->bindValue(':leId',$commandeid['id'],PDO::PARAM_INT);
                    $req->bindValue(':leId2',$commandeid2['id'],PDO::PARAM_INT);
                    break;
            }
            $req->execute();
            $res = $req->fetchAll();
            $nombretotal = 0;
            foreach($res as $ligne){
                $nombretotal += $ligne[0];
            }
            return $nombretotal;
        }

        // INFORMATIONS UTILISATEURS

        public static function getAllUsersID() {
            $req = Connexion::getInstance()->prepare("SELECT id,utilisateur.login FROM utilisateur LIMIT 50");
            $req->execute();
            $res = $req->fetchAll();
            return $res;
        }

        public static function getResponsableCommande($id){
            $req = Connexion::getInstance()->prepare("SELECT id_responsable
            from utilisateur
            WHERE idRole = 2 and id = :id");
            $req->bindValue(':id',$id,PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetch();
            return $res;
        }

        public static function getCommanderPour($idResponsable) {
            $req = Connexion::getInstance()->prepare("SELECT email 
            from utilisateur
            JOIN role on role.id = utilisateur.idRole
            WHERE idRole = 1 and id_responsable = :idResponsable");
            $req->bindValue(':idResponsable',$idResponsable,PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetchAll();
            return $res;
        }

        public static function getCommanderPourTous() {
            $req = Connexion::getInstance()->prepare("SELECT email 
            from utilisateur
            JOIN role on role.id = utilisateur.idRole
            WHERE idRole = 1");
            $req->execute();
            $res = $req->fetchAll();
            return $res;
        }

        public static function getMetierUtilisateur($login){
            $req = Connexion::getInstance()->prepare(" SELECT idMetier FROM utilisateur WHERE utilisateur.login =:leLogin");
            $req->bindValue(':leLogin',$login,PDO::PARAM_STR);
            $req->execute();
            $res = $req->fetch();
            return $res;
        }

        public static function getIdUtilisateur($login){
            $req = Connexion::getInstance()->prepare("SELECT id FROM utilisateur WHERE utilisateur.login =:leLogin");
            $req->bindValue(':leLogin',$login,PDO::PARAM_STR);
            $req->execute();
            $res = $req->fetch();
            return $res;
        }

        public static function getEmployeur(){
            $req = Connexion::getInstance()->prepare(" SELECT * FROM employeur");
            $req->execute();
            $res = $req->fetchall();
            return $res;
        }

        public static function getTelUtilisateur($login){
            $req = Connexion::getInstance()->prepare("SELECT tel FROM utilisateur WHERE utilisateur.login =:leLogin");
            $req->bindValue(':leLogin',$login,PDO::PARAM_STR);
            $req->execute();
            $res = $req->fetch();
            return $res['tel'];
        }

        public static function getUtilisateurCommander ($etat){
            switch ($etat){
                case 1:
                    $req = Connexion::getInstance()->prepare("SELECT utilisateur.id, utilisateur.nom, utilisateur.prenom, utilisateur.email, dateCrea 
                    FROM utilisateur 
                    JOIN commandeepi ON commandeepi.idUtilisateur = utilisateur.id
                    WHERE commandeepi.terminer = 1");
                    $req->execute();
                    $res = $req->fetchAll();
                    break;
                case 0 : 
                    $req = Connexion::getInstance()->prepare("SELECT utilisateur.id, utilisateur.nom, utilisateur.prenom, utilisateur.email, dateCrea 
                    FROM utilisateur 
                    LEFT OUTER JOIN commandeepi ON commandeepi.idUtilisateur = utilisateur.id
                    WHERE dateCrea is null or terminer = 0");
                    $req->execute();
                    $res = $req->fetchAll();
                    break;
            }
            return $res;
        }


        public static function updateMdp($login, $mdpActuel,$mdpNew) {
            $verifmdp = ModeleObjetDAO::getMdp($login);
            /*
            - 8 caractères minimum (?=.{8,})
            - 1 majuscule (?=.*[A-Z])
            - 1 minuscule (?=.*[a-z])
            - 1 chiffre (?=.*\d)
            - 1 caractère spécial (?=.*[-+_!@#$%^&*., ?])

                /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[-+_!@#$%^&*., ?]).{8,}$/
            */
            if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[-+_!@#$%^&*., ?]).{8,}$/', $mdpNew)) {
                return 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial';
            }
            if(password_verify($mdpActuel,$verifmdp['password'])) {
                $newHash = password_hash($mdpNew, PASSWORD_DEFAULT);
                $req = Connexion::getInstance()->prepare("UPDATE utilisateur SET utilisateur.password = :leMdp WHERE utilisateur.login = :leLogin");
                $req->bindValue(':leMdp',$newHash,PDO::PARAM_STR);
                $req->bindValue(':leLogin',$login,PDO::PARAM_STR);
                try {
                    $req->execute();
                    return true;
                } catch (PDOException $e) {
                    return 'Erreur lors de la modification du mot de passe';
                }
            } else {
                return 'Le mot de passe actuel est incorrect';
            }
        }

        public static function getNomUtilisateur($id){
            $req = Connexion::getInstance()->prepare("SELECT utilisateur.login FROM utilisateur WHERE id =:id");
            $req->bindValue(':id',$id,PDO::PARAM_STR);
            $req->execute();
            $res = $req->fetch();
            return $res;
        }

        public static function getNbrPointUtilisateur($id){
            $req = Connexion::getInstance()->prepare("SELECT points.point FROM points WHERE idUtilisateur =:idUtilisateur");
            $req->bindValue(':idUtilisateur',$id,PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetch();
            if($res == false){
                return array("point" => 0);
            }
            return $res;
        }

        public static function getIdEpiUtilisateur($id){
            $req = Connexion::getInstance()->prepare("SELECT id FROM commandeepi WHERE idUtilisateur = :leId AND terminer = :terminer");
            $req->bindValue(':leId',$id,PDO::PARAM_INT);
            $req->bindValue(':terminer',0,PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetch();
            return $res;
        }

        public static function getIdVetUtilisateur($id){
            $req = Connexion::getInstance()->prepare("SELECT id FROM commandevet WHERE idUtilisateur = :leId AND terminer = :terminer");
            $req->bindValue(':leId',$id,PDO::PARAM_INT);
            $req->bindValue(':terminer',0,PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetch();
            return $res;
        }

        public static function getLigneCommandeEpiUtilisateur($id){
            $req = Connexion::getInstance()->prepare("SELECT lignecommandeepi.id, lignecommandeepi.idProduit, produit.fichierPhoto, produit.type, produit.nom, disponible.prix, lignecommandeepi.quantite, taille.libelle FROM lignecommandeepi
            JOIN disponible on disponible.idProduit = lignecommandeepi.idProduit
            JOIN produit on produit.id = lignecommandeepi.idProduit
            JOIN taille on taille.id = lignecommandeepi.idTaille
            JOIN commandeepi on commandeepi.id = lignecommandeepi.idCommandeEPI
            WHERE commandeepi.id = :leId");

            $commandeid = ModeleObjetDAO::getIdEpiUtilisateur($id);
            if($commandeid == false){
                return false;
            }

            $req->bindValue(':leId',$commandeid['id'],PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetchAll();
            return $res;

            /*
                Table necessaire pour la requete :
                - lignecommandeepi
                    - id
                    - idProduit (join avec disponible)
                    - quantite (quantite de produit)
                    - idCommandeEPI (join avec commandeepi)
                    - idTaille (join avec disponible)
                - disponible
                    - idProduit
                    - idTaille
                    - prix
                - commandeepi
                    - id
                    - idUtilisateur
                - produit
                    - id (join avec lignecommandeepi)
                    - nom
                    - fichierPhoto
                    - type

                Affichage :
                    - fichierPhoto
                    - nom
                    - prix
                    - quantite
                    - taille

                Requete :
                SELECT produit.fichierPhoto, produit.nom, disponible.prix, lignecommandeepi.quantite, taille.libelle FROM lignecommandeepi
                JOIN disponible on disponible.idProduit = lignecommandeepi.idProduit
                JOIN produit on produit.id = lignecommandeepi.idProduit
                JOIN taille on taille.id = lignecommandeepi.idTaille
                JOIN commandeepi on commandeepi.id = lignecommandeepi.idCommandeEPI
                WHERE commandeepi.id = :leId
            */
        }

        public static function getAllProduitCatalogue($id, $type){
            $req = Connexion::getInstance()->prepare(" SELECT referenceFournisseur,produit.id, prix, description ,nom,fichierPhoto, idType
            from produit
            join type on type.id = produit.idType
            JOIN categorie on categorie.id = type.idCategorie
            JOIN disponible on disponible.idProduit = produit.id
            JOIN concerne_categorie_metier ON categorie.id = concerne_categorie_metier.idCategorie
            WHERE type = :leType AND concerne_categorie_metier.idMetier = :idMetier");

            $req->bindValue(':leType',$type,PDO::PARAM_STR);
            $req->bindValue(':idMetier',$id,PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetchAll();
            return $res;
        }

        
        public static function getLigneCommandeVetUtilisateur($id){
            $req = Connexion::getInstance()->prepare("SELECT lignecommandevet.id,lignecommandevet.idProduit, produit.fichierPhoto, produit.type, produit.nom, disponible.prix, lignecommandevet.quantite, taille.libelle FROM lignecommandevet
            JOIN disponible on disponible.idProduit = lignecommandevet.idProduit
            JOIN produit on produit.id = lignecommandevet.idProduit
            JOIN taille on taille.id = lignecommandevet.idTaille
            JOIN commandevet on commandevet.id = lignecommandevet.idCommandeVET
            WHERE commandevet.id = :leId");

            $commandeid = ModeleObjetDAO::getIdVetUtilisateur($id);
            if($commandeid == false){
                return false;
            }

            $req->bindValue(':leId',$commandeid['id'],PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetchAll();
            return $res;
        }

        public static function getHistoriqueCommande($id){
            $query = "SELECT
                commandeepi.id,
                commandeepi.dateCrea,
                'EPI' AS origin,
                'Aucun' AS prix
            FROM
                commandeepi
            WHERE
                commandeepi.terminer = 1 AND commandeepi.idUtilisateur = :leId
            UNION
            SELECT
                commandevet.id,
                commandevet.dateCrea,
                'VET' AS origin,
                SUM(disponible.prix * lignecommandevet.quantite) AS prix
            FROM
                commandevet
            INNER JOIN lignecommandevet ON lignecommandevet.idCommandeVet = commandevet.id
            INNER JOIN disponible ON disponible.idProduit = lignecommandevet.idProduit AND disponible.idTaille = lignecommandevet.idTaille
            WHERE
                commandevet.terminer = 1 AND commandevet.idUtilisateur = :leId
            GROUP BY commandevet.id
            ORDER BY dateCrea DESC
            ";
            $req = Connexion::getInstance()->prepare($query);
            $req->bindValue(':leId',$id,PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetchAll();
            return $res;

            /*
                Table a utiliser:
                commandeepi :
                    id
                    dateCrea
                    idUtilisateur
                    terminer
                commandevet :
                    id
                    dateCrea
                    idUtilisateur
                    terminer
                lignecommandevet :
                    id
                    idCommandeVet
                    idProduit
                    idTaille
                    quantite
                disponible :
                    id
                    idProduit
                    idTaille
                    prix

                Affichage :
                    id
                    dateCrea
                    origin
                    Prix

                Requête :
                SELECT
                    commandeepi.id,
                    commandeepi.dateCrea,
                    'EPI' AS origin,
                    '0' AS prix
                FROM
                    commandeepi
                WHERE
                    commandeepi.terminer = 1 AND commandeepi.idUtilisateur = :leId
                UNION
                SELECT
                    commandevet.id,
                    commandevet.dateCrea,
                    'VET' AS origin,
                    SUM(disponible.prix * lignecommandevet.quantite) AS prix
                FROM
                    commandevet
                INNER JOIN lignecommandevet ON lignecommandevet.idCommandeVet = commandevet.id
                INNER JOIN disponible ON disponible.idProduit = lignecommandevet.idProduit AND disponible.idTaille = lignecommandevet.idTaille
                WHERE
                    commandevet.terminer = 1 AND commandevet.idUtilisateur = :leId
                GROUP BY commandevet.id
                ORDER BY dateCrea DESC


            */
        }

        public static function getHistoriqueCommandeDetail($idUtilisateur, $idCommande, $origin) {
            switch($origin) {
                case 'EPI':
                    $database = 'commandeepi';
                    $table = 'lignecommandeepi';
                    break;
                case 'VET':
                    $database = 'commandevet';
                    $table = 'lignecommandevet';
                    break;
                default:
                    return false;
            }
            $query = "SELECT
                $table.id,
                $table.idProduit,
                produit.fichierPhoto,
                produit.type,
                produit.nom,
                disponible.prix,
                $table.quantite,
                taille.libelle
            FROM
                $table
            INNER JOIN disponible ON disponible.idProduit = $table.idProduit AND disponible.idTaille = $table.idTaille
            INNER JOIN produit ON produit.id = $table.idProduit
            INNER JOIN taille ON taille.id = $table.idTaille
            INNER JOIN $database ON $database.id = $table.idCommande$origin
            WHERE
                $table.idCommande$origin = :idCommande AND $database.idUtilisateur = :idUtilisateur
            ";
            $req = Connexion::getInstance()->prepare($query);
            $req->bindValue(':idCommande',$idCommande,PDO::PARAM_INT);
            $req->bindValue(':idUtilisateur',$idUtilisateur,PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetchAll();
            return $res;
        }
        public static function getStatut($login){ 
            $req = Connexion::getInstance()->prepare("SELECT metier.statut, metier.id
                FROM metier
                JOIN utilisateur on metier.id = utilisateur.idMetier
                where utilisateur.login = :nom");
            $req->bindValue(':nom',$login,PDO::PARAM_STR);
            $req->execute();
            $res = $req->fetch();
            return $res;
            
        }

        public static function getRole($nom){
            $req = Connexion::getInstance()->prepare("SELECT libelle
            from utilisateur
            join role on utilisateur.idRole = role.id
            WHERE login = :nom");
            $req->bindValue(':nom',$nom,PDO::PARAM_STR);
            $req->execute();
            $res = $req->fetch();
            return $res;
        }

        public static function getResponsable(){
            $req = Connexion::getInstance()->prepare("select id,nom from utilisateur where idRole = 2");
            $req->execute();
            $res = $req->fetchall();
            return $res;
        }

        public static function getLesRole(){
            $req = Connexion::getInstance()->prepare("SELECT id,libelle FROM role");
            $req->execute();
            $res = $req->fetchall();
            return $res;
        }


        public static function getMetier(){
            $req = Connexion::getInstance()->prepare(" SELECT * FROM metier");
            $req->execute();
            $res = $req->fetchall();
            return $res;
        }

        //PAGE CONNEXION

        public static function getLogin($login){
            $req = Connexion::getInstance()->prepare("SELECT login FROM utilisateur WHERE login =:leLogin");
            $req->bindValue(':leLogin',$login,PDO::PARAM_STR);
            $req->execute();
            $res = $req->fetch();
            return $res;
        }

        public static function getMdp($login){
            $req = Connexion::getInstance()->prepare("SELECT password FROM utilisateur WHERE login = :leLogin");
            $req->bindValue(':leLogin',$login,PDO::PARAM_STR);
            $req->execute();
            $res = $req->fetch();
            return $res;
        }

        // INFORMATION SUR LES PRODUITS

        public static function getProduit($id,$type){
            $req = Connexion::getInstance()->prepare("SELECT referenceFournisseur,produit.id, prix, description ,nom,fichierPhoto, idType
                from produit
                join type on type.id = produit.idType
                JOIN categorie on categorie.id = type.idCategorie
                JOIN disponible on disponible.idProduit = produit.id
                WHERE categorie.id = :id and type = :type");
            $req->bindValue(':id',$id,PDO::PARAM_INT);
            $req->bindValue(':type',$type,PDO::PARAM_STR);
            $req->execute();
            $res = $req->fetchall();
            return $res;
        }

        public static function getProduitPanier($id){
            $req = Connexion::getInstance()->prepare("SELECT produit.id, prix, description ,nom,fichierPhoto
                from produit
                JOIN disponible on disponible.idProduit = produit.id
                WHERE produit.id = :id");
            $req->bindValue(':id',$id,PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetch();
            return $res;
        }

        public static function getCatalogue($id, $login, $verifVet){
            if ((ModeleObjetDAO::getRole($login)['libelle'] == 'Administrateur') || ($verifVet == true)){
                $req = Connexion::getInstance()->prepare("SELECT categorie.id,categorie.libelle
                FROM categorie");
                $req->execute();
                $res = $req->fetchall();
                return $res;
            }else{
                $req = Connexion::getInstance()->prepare("SELECT categorie.id,categorie.libelle
                FROM categorie
                JOIN concerne_categorie_metier on concerne_categorie_metier.idCategorie = categorie.id
                WHERE concerne_categorie_metier.idMetier = :id");
                $req->bindValue(':id',$id,PDO::PARAM_INT);
                $req->execute();
                $res = $req->fetchall();
                return $res;
            }
            
            /*
            Table neccessaire pour les page catalogue :
            - categorie
                - id
                - libelle
            - concerne_categorie_metier
                - idCategorie (join avec categorie.id)
                - idMetier
            
            Affichage :
                - categorie.libelle

            Requete :
                SELECT categorie.id,categorie.libelle
                FROM categorie
                JOIN concerne_categorie_metier on concerne_categorie_metier.idCategorie = categorie.id
                WHERE concerne_categorie_metier.idMetier = :id
            */
        }

        public static function getTaille($id){
            $req = Connexion::getInstance()->prepare("select libelle, taille.id from taille join disponible on disponible.idTaille = taille.id join produit on produit.id = disponible.idProduit where produit.id = :id");
            $req->bindValue(':id',$id,PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetch();
            return $res;
        }

        public static function getType($id){
            $req = Connexion::getInstance()->prepare("SELECT produit.id
            FROM produit
            join type on type.id = produit.idType
            WHERE type.id = :id");
            $req->bindValue(':id',$id,PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetch();
            return $res ['id'];
        }

        //DELETE PANIER 

        public static function deleteLigneCommande($id,$idLigne,$type){
            if($type == "EPI") {
                $idCommandeEPI = ModeleObjetDAO::getIdEpiUtilisateur($id);
                // Check si il y'a une lignecommandeepi avec une id d'un utilisateur
                if($idCommandeEPI != false) {
                    $query = Connexion::getInstance()->prepare("SELECT * FROM lignecommandeepi WHERE idCommandeEPI = :idCommandeEPI AND id = :idL");
                    $query->bindValue(':idCommandeEPI',$idCommandeEPI['id'],PDO::PARAM_INT);
                    $query->bindValue(':idL',$idLigne,PDO::PARAM_INT);
                    $query->execute();
                    $res = $query->fetch();
                    if($res != false){
                        // Si il y'a une ligne de commande on supprime la ligne
                        $query = Connexion::getInstance()->prepare("DELETE FROM lignecommandeepi WHERE id = :idL AND idCommandeEPI = :idCommandeEPI");
                        $query->bindValue(':idL',$idLigne,PDO::PARAM_INT);
                        $query->bindValue(':idCommandeEPI',$idCommandeEPI['id'],PDO::PARAM_INT);
                        $query->execute();
                    }

                    // verification si il y'a une lignecommandeepi avec une id d'un utilisateur
                    $query = Connexion::getInstance()->prepare("SELECT * FROM lignecommandeepi WHERE idCommandeEPI = :idCommandeEPI");
                    $query->bindValue(':idCommandeEPI',$idCommandeEPI['id'],PDO::PARAM_INT);
                    $query->execute();
                    $res = $query->fetch();
                    if($res == false){
                        // Si il n'y a pas de ligne de commande on supprime la commande
                        $query = Connexion::getInstance()->prepare("DELETE FROM commandeepi WHERE id = :idCommandeEPI");
                        $query->bindValue(':idCommandeEPI',$idCommandeEPI['id'],PDO::PARAM_INT);
                        $query->execute();
                    }
                }
            } elseif($type == "VET") {
                $idCommandeVet = ModeleObjetDAO::getIdVetUtilisateur($id);
                // Check si il y'a une lignecommandevetement avec une id d'un utilisateur
                if($idCommandeVet != false) {
                    $query = Connexion::getInstance()->prepare("SELECT * FROM lignecommandevet WHERE idCommandeVET = :idCommandeVET AND id = :idL");
                    $query->bindValue(':idCommandeVET',$idCommandeVet['id'],PDO::PARAM_INT);
                    $query->bindValue(':idL',$idLigne,PDO::PARAM_INT);
                    $query->execute();
                    $res = $query->fetch();
                    if($res != false){
                        // Si il y'a une ligne de commande on supprime la ligne
                        $query = Connexion::getInstance()->prepare("DELETE FROM lignecommandevet WHERE id = :idL AND idCommandeVET = :idCommandeVET");
                        $query->bindValue(':idL',$idLigne,PDO::PARAM_INT);
                        $query->bindValue(':idCommandeVET',$idCommandeVet['id'],PDO::PARAM_INT);
                        $query->execute();
                    }

                    // verification si il y'a une lignecommandevetement avec une id d'un utilisateur
                    $query = Connexion::getInstance()->prepare("SELECT * FROM lignecommandevet WHERE idCommandeVET = :idCommandeVET");
                    $query->bindValue(':idCommandeVET',$idCommandeVet['id'],PDO::PARAM_INT);
                    $query->execute();
                    $res = $query->fetch();
                    if($res == false){
                        // Si il n'y a pas de ligne de commande on supprime la commande
                        $query = Connexion::getInstance()->prepare("DELETE FROM commandevet WHERE id = :idCommandeVET");
                        $query->bindValue(':idCommandeVET',$idCommandeVet['id'],PDO::PARAM_INT);
                        $query->execute();
                    }
                    
                }
            }
        }
        
        /*
        public static function deleteCommandeTOUT($id) {
            $idCommandeEPI = ModeleObjetDAO::getIdEpiUtilisateur($id);
            $idCommandeVet = ModeleObjetDAO::getIdVetUtilisateur($id);
            if($idCommandeEPI != false) {
                $query = Connexion::getInstance()->prepare("DELETE FROM lignecommandeepi WHERE idCommandeEPI = :idCommandeEPI");
                $query->bindValue(':idCommandeEPI',$idCommandeEPI['id'],PDO::PARAM_INT);
                $query->execute();
                $query = Connexion::getInstance()->prepare("DELETE FROM commandeepi WHERE id = :idCommandeEPI AND idUtilisateur = :id");
                $query->bindValue(':idCommandeEPI',$idCommandeEPI['id'],PDO::PARAM_INT);
                $query->bindValue(':id',$id,PDO::PARAM_INT);
                $query->execute();
            }
            if($idCommandeVet != false) {
                $query = Connexion::getInstance()->prepare("DELETE FROM lignecommandevet WHERE idCommandeVET = :idCommandeVET");
                $query->bindValue(':idCommandeVET',$idCommandeVet['id'],PDO::PARAM_INT);
                $query->execute();
                $query = Connexion::getInstance()->prepare("DELETE FROM commandevet WHERE id = :idCommandeVET AND idUtilisateur = :id");
                $query->bindValue(':idCommandeVET',$idCommandeVet['id'],PDO::PARAM_INT);
                $query->bindValue(':id',$id,PDO::PARAM_INT);
                $query->execute();
            }
        }
        */

        

        //COMMANDE PANIER 
        public static function validerCommande($id, $type) {
            date_default_timezone_set('Europe/Paris');
            $prix = ModeleObjetDAO::prixTotalCommande($id,$type);
            $points = ModeleObjetDAO::getNbrPointUtilisateur($id)['point'];
            if(($points - $prix) > 0) {
                switch($type) {
                    case "epi":
                        $Commande = ModeleObjetDAO::getLigneCommandeEpiUtilisateur($id);
                        $extrafile = "EPI";
                        break;
                    case "vet":
                        $Commande = ModeleObjetDAO::getLigneCommandeVetUtilisateur($id);
                        $extrafile = "VET";
                        break;
                    default:
                        return false;
                        break;
                }
                $nomUtilisateur = ModeleObjetDAO::getNomPrenom($id);
                $nomUtilisateurSecure = ModeleObjetDAO::windowSecureFilename($nomUtilisateur);
                $filename = "Commande_".$nomUtilisateurSecure."_".date("d-m-Y")."_".date("H-i-s").".csv";
                $date = date("d/m/Y");
                $heure = date("H:i:s");
                $tmp_array_header_title = array("date" => "Date", "heure" => "Heure", "prix" => "Prix", "nomUtilisateur" => "Nom Utilisateur", "id" => "ID");
                $tmp_array_header = array("date" => $date, "heure" => $heure, "prix" => $prix, "nomUtilisateur" => $nomUtilisateur, "id" => $id);
                $tmp_array_title = array("idProduit" => "ID", "nom" => "Nom", "Taille" => "Taille", "quantite" => "quantite", "prix" => "prix");
                $tmp_array = array($tmp_array_header_title, $tmp_array_header, $tmp_array_title);
                foreach($Commande as $ligne) {
                    $tmp_array[] = array("idProduit" => $ligne['idProduit'], "nom" => $ligne['nom'], "Taille" => $ligne['libelle'], "quantite" => $ligne['quantite'], "prix" => $ligne['prix']);
                }
                $fp = fopen('commandes/'.$extrafile . '/' .$filename, 'w');
                fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF)); //Cherche un caractère par rapport à un octet, transforme les charactère en UTF 8 (changement d'encodage ascii)
                foreach ($tmp_array as $fields) {
                    fputcsv($fp, $fields, ";");
                }
                fclose($fp);
                ModeleObjetDAO::CommandeArchivage($id, $type);

                if($prix != 0) {
                $query = Connexion::getInstance()->prepare("UPDATE points SET points.point = points.point - :prix WHERE points.idUtilisateur = :id");
                $query->bindValue(':prix',$prix,PDO::PARAM_INT);
                $query->bindValue(':id',$id,PDO::PARAM_INT);
                $query->execute();
                }

                header("location:./?action=commandeReussie");

            } else {
                header("location:./?action=panier");
            }
        }

        public static function CommandeArchivage($id, $type) {
            switch($type) {
                case "epi":
                    $commandeid = ModeleObjetDAO::getIdEpiUtilisateur($id);
                    $query = Connexion::getInstance()->prepare("UPDATE commandeepi SET commandeepi.terminer = 1 WHERE commandeepi.id = :id");
                    break;
                case "vet":
                    $commandeid = ModeleObjetDAO::getIdVetUtilisateur($id);
                    $query = Connexion::getInstance()->prepare("UPDATE commandevet SET commandevet.terminer = 1 WHERE commandevet.id = :id");
                    break;
                default:
                    return;
                    break;
            }
            if($commandeid != false) { 
                $query->bindValue(':id',$commandeid['id'],PDO::PARAM_INT);
                $query->execute();
                return;
            }
        }

        public static function getNomPrenom($id) {
            $query = Connexion::getInstance()->prepare("SELECT nom, prenom FROM utilisateur WHERE id = :id");
            $query->bindValue(':id',$id,PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch();
            return $result['nom']."_".$result['prenom'];
        }

        public static function windowSecureFilename($name) {
            $name = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $name); // Enleve tout les caractères spéciaux
            $name = str_replace(' ', '_', $name); // Remplace les espaces par des _
            return $name;
        }

        public static function prixTotalCommande($id,$type){
            switch($type) {
                case "epi":
                    return 0;
                    break;
                case "vet":
                    break;
                default:
                    return 0;
                    break;
            }
            $idCommandeVet = ModeleObjetDAO::getIdVetUtilisateur($id);
            if($idCommandeVet != false) {

            $query = Connexion::getInstance()->prepare("SELECT SUM(quantite * prix) AS prixTotalCommande 
                FROM lignecommandevet 
                JOIN disponible on disponible.idProduit = lignecommandevet.idProduit AND disponible.idTaille = lignecommandevet.idTaille
                WHERE lignecommandevet.idCommandeVET = :idCommandeVET
            ");
            $query->bindValue(':idCommandeVET',$idCommandeVet['id'],PDO::PARAM_INT);
            $query->execute();
            $res = $query->fetch();
            return $res['prixTotalCommande'];
            } else {
                return 0;
            }
            
            /*
                Tableau utilisé pour le calcul du prix total de la commande :
                
                -lignecommandevet :
                    -idCommandeVet
                    -quantite
                    -idProduit
                    -idTaille
                
                -disponible :
                    -prix
                    -idProduit (join lignecommandevet)
                    -idTaille (join lignecommandevet)

                Affichage :
                    -prixTotalCommande
                
                Requete :
                    SELECT SUM(quantite * prix) AS prixTotalCommande 
                    FROM lignecommandevet 
                    JOIN disponible on disponible.idProduit = lignecommandevet.idProduit AND disponible.idTaille = lignecommandevet.idTaille
                    WHERE lignecommandevet.idCommandeVET = :idCommandeVET

            */
            
        }

        //INSERT PANIER

        public static function insertEPICommande($id, $statut){
            date_default_timezone_set('Europe/Paris');
            $idUtilisateur = $id;
            $dateActuel = date("Y-m-d H:i:s");
            $nbCommande = ModeleObjetDAO::getUtilisateurCommandeTerminer($idUtilisateur['id'], "EPI");
            if($nbCommande == false){
                $nbCommande = 0;
            }
            if($nbCommande > 0) {
                return false;
            }
            //Verification si il y'a déjà une commandeEPI avec une id d'un utilisateur
            $query = Connexion::getInstance()->prepare("SELECT * FROM commandeepi WHERE idUtilisateur = :idUtilisateur AND terminer = :terminer");
            $query->bindValue(':idUtilisateur', $idUtilisateur['id'], PDO::PARAM_STR);
            $query->bindValue(':terminer', 0, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch();
            if($result == false){
                // CREATION DU INSERT DANS LA TABLE commandeepi
                $query = Connexion::getInstance()->prepare("INSERT INTO commandeepi (datecrea, statut, idUtilisateur, terminer, idLieuLivraison) VALUES (:datecrea, :statut, :idUtilisateur, :terminer, :idLieuLivraison)");
                $query->bindValue(':datecrea', $dateActuel, PDO::PARAM_STR);
                $query->bindValue(':statut', $statut, PDO::PARAM_STR);
                $query->bindValue(':idUtilisateur', $idUtilisateur['id'], PDO::PARAM_STR);
                $query->bindValue(':terminer', 0, PDO::PARAM_INT);
                $query->bindValue(':idLieuLivraison', 0, PDO::PARAM_INT);
                $query->execute();
            }
            return true;
        }

        public static function insertVETCommande($id, $statut){
            date_default_timezone_set('Europe/Paris');
            $idUtilisateur = $id;
            $dateActuel = date("Y-m-d H:i:s");   

            //Verification si il y'a déjà une commandeVET avec une id d'un utilisateur
            $query = Connexion::getInstance()->prepare("SELECT * FROM commandevet WHERE idUtilisateur = :idUtilisateur AND terminer = :terminer");
            $query->bindValue(':idUtilisateur', $idUtilisateur['id'], PDO::PARAM_STR);
            $query->bindValue(':terminer', 0, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch();
            if($result == false){
                // CREATION DU INSERT DANS LA TABLE commandeVET
                $query = Connexion::getInstance()->prepare("INSERT INTO commandevet (datecrea, statut, idUtilisateur, terminer, idLieuLivraison) VALUES (:datecrea, :statut, :idUtilisateur, :terminer, :idLieuLivraison)");
                $query->bindValue(':datecrea', $dateActuel, PDO::PARAM_STR);
                $query->bindValue(':statut', $statut, PDO::PARAM_STR);
                $query->bindValue(':idUtilisateur', $idUtilisateur['id'], PDO::PARAM_STR);
                $query->bindValue(':terminer', 0, PDO::PARAM_INT);
                $query->bindValue(':idLieuLivraison', 0, PDO::PARAM_INT);
                $query->execute();
            }
            return true;
        }

        public static function insertLigneCommandeEPI($id, $idProduit, $quantite, $idTaille){
            // VERIFICATION SI ID PEUT AJOUTER CE PRODUIT
            /*
            Tableau utilisé pour la verification de l'ajout d'un produit dans le panier :
            - produit
                - id
                - idType
            - type
                - id
                - idCategorie
            - concerne_categorie_metier
                - idCategorie
                - idMetier
            
            Requete :
                SELECT produit.id FROM produit
                JOIN type on type.id = produit.idType
                JOIN concerne_categorie_metier on concerne_categorie_metier.idCategorie = type.idCategorie
                WHERE produit.id = :idProduit AND concerne_categorie_metier.idMetier = :idMetier
            
            */
            $idMetier = ModeleObjetDAO::getMetierUtilisateur(ModeleObjetDAO::getNomUtilisateur($id['id'])['login']);

            $query = Connexion::getInstance()->prepare("SELECT produit.id FROM produit
            JOIN type on type.id = produit.idType
            JOIN concerne_categorie_metier on concerne_categorie_metier.idCategorie = type.idCategorie
            WHERE produit.id = :idProduit AND concerne_categorie_metier.idMetier = :idMetier
            ");
            $query->bindValue(':idProduit', $idProduit, PDO::PARAM_INT);
            $query->bindValue(':idMetier', $idMetier['idMetier'], PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch();
            if($result == false){
                
                return false;
            }
            // CREATION DU INSERT DANS LA TABLE lignecommandeepi
            $idCommandeEPI = ModeleObjetDAO::getIdEpiUtilisateur($id['id']);
            $query = Connexion::getInstance()->prepare("INSERT INTO lignecommandeepi (idProduit, quantite, idCommandeEPI, idTaille) VALUES (:idProduit, :quantite, :idCommandeEPI, :idTaille)");
            $query->bindValue(':idProduit', $idProduit, PDO::PARAM_INT);
            $query->bindValue(':quantite', $quantite, PDO::PARAM_INT);
            $query->bindValue(':idCommandeEPI',$idCommandeEPI['id'], PDO::PARAM_INT);
            $query->bindValue(':idTaille', $idTaille, PDO::PARAM_INT);
            $query->execute();
            return false;
        }

        public static function insertLigneCommandeVET($id, $idProduit, $quantite, $idTaille){
            // CREATION DU INSERT DANS LA TABLE lignecommandeepi
            $idCommandeVET = ModeleObjetDAO::getIdVetUtilisateur($id['id']);
            $query = Connexion::getInstance()->prepare("INSERT INTO lignecommandevet (idProduit, quantite, idCommandeVET, idTaille) VALUES (:idProduit, :quantite, :idCommandeVET, :idTaille)");
            $query->bindValue(':idProduit', $idProduit, PDO::PARAM_INT);
            $query->bindValue(':quantite', $quantite, PDO::PARAM_INT);
            $query->bindValue(':idCommandeVET',$idCommandeVET['id'], PDO::PARAM_INT);
            $query->bindValue(':idTaille', $idTaille, PDO::PARAM_INT);
            $query->execute();
        }

        //INSERT DES PRODUITS



        //INSERT UTILISATEUR 

        public static function insertUtilisateur($login,$password,$prenom,$nom,$email,$tel,$idLieuLivraison,$id_responsable,$idRole,$idMetier,$Agence){
            $req = Connexion::getInstance()->prepare("INSERT INTO utilisateur (login,password,prenom,nom,email,tel,idLieuLivraison,id_responsable,idRole,idMetier,Agence)
            VALUES (:login, :password, :prenom,:nom,:email,:tel,:idLieuLivraison,:id_responsable,:idRole,:idMetier,:Agence)");
            $req->bindValue(':login',$email,PDO::PARAM_STR);
            $req->bindValue(':password',$password,PDO::PARAM_STR);
            $req->bindValue(':prenom',$prenom,PDO::PARAM_STR);
            $req->bindValue(':nom',$nom,PDO::PARAM_STR);
            $req->bindValue(':email',$email,PDO::PARAM_STR);
            $req->bindValue(':tel',$tel,PDO::PARAM_STR);
            $req->bindValue(':idLieuLivraison',$idLieuLivraison,PDO::PARAM_INT);
            $req->bindValue(':id_responsable',$id_responsable,PDO::PARAM_INT);
            $req->bindValue(':idRole',$idRole,PDO::PARAM_INT);
            $req->bindValue(':idMetier',$idMetier,PDO::PARAM_INT);
            $req->bindValue(':Agence',$Agence,PDO::PARAM_STR);
            $req->execute();

            ModeleObjetDAO::insertPoints(ModeleObjetDAO::getIdUtilisateur($email)['id'], 0);
        }

        public static function insertPoints($idUtilisateur, $points){
            $req = Connexion::getInstance()->prepare("SELECT points.point FROM points WHERE points.idUtilisateur = :idUtilisateur");
            $req->bindValue(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetch();
            if($res == false){
                $req = Connexion::getInstance()->prepare("INSERT INTO points (idUtilisateur, points.point) VALUES (:idUtilisateur, :pointx)");
                $req->bindValue(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
                $req->bindValue(':pointx', $points, PDO::PARAM_INT);
                $req->execute();
            } else {
                $req = Connexion::getInstance()->prepare("UPDATE points SET points.point = points.point + :pointx WHERE points.idUtilisateur = :idUtilisateur");
                $req->bindValue(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
                $req->bindValue(':pointx', $points, PDO::PARAM_INT);
                $req->execute();
            }
        }

        public static function getLieuLivraison(){
            $req = Connexion::getInstance()->prepare("SELECT * FROM lieulivraion");
            $req->execute();
            $res = $req->fetchall();
            return $res;
        }

        //fonction qui retourne la quantite dans ligne commande epi en fonction du login

        public static function getQuantiteEpi($login,$type){
            $req = Connexion::getInstance()->prepare( "SELECT sum(quantite),type.id
            from lignecommandeepi
            JOIN commandeepi on lignecommandeepi.idCommandeEPI = commandeepi.id
            JOIN utilisateur on commandeepi.idUtilisateur = utilisateur.id
            JOIN produit on lignecommandeepi.idProduit = produit.id
            join type on produit.idType = type.id
            where login = :login
            and type.id = :type
            ");
            $req->bindValue(':login', $login, PDO::PARAM_STR);
            $req->bindValue(':type', $type, PDO::PARAM_STR);
            $req->execute();
            $res = $req->fetch();
            return $res;
        }

        public static function getQuantiteEpiMax($metier,$idType){
            $req = Connexion::getInstance()->prepare("SELECT idType,quantiteMax
            from concerne 
            join metier on metier.id = concerne.idStatut
            where statut = :metier and idType = :idType");
            $req->bindValue(':metier',$metier,PDO::PARAM_STR);
            $req->bindValue(':idType',$idType,PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetch();
            return $res['quantiteMax'];
        }

        public static function getUtilisateurCommandeTerminer($id,$type) {
            switch($type){
                case 'EPI':
                    $req = Connexion::getInstance()->prepare("SELECT COUNT(id) AS nb FROM commandeepi WHERE idUtilisateur = :id AND terminer = 1"); 
                    break;
                case 'VET':
                    $req = Connexion::getInstance()->prepare("SELECT COUNT(id) AS nb FROM commandevet WHERE idUtilisateur = :id AND terminer = 1"); 
                    break;
                default:
                    return false;
                    break;
            }
            $req->bindValue(':id', $id, PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetch();
            return $res['nb'];
        }

        public static function getAgence(){
            $req =  Connexion::getInstance()->prepare("select DISTINCT agence from utilisateur");
            $req->execute();
            $res = $req->fetchall();
            return $res;
        }

        public static function getRecapCommandeEpi(){
            $req = Connexion::getInstance()->prepare("select produit.nom as produit,sum(quantite),lieulivraion.nom
            from lignecommandeepi
            JOIN commandeepi on commandeepi.id = lignecommandeepi.idCommandeEPI
            JOIN produit on lignecommandeepi.idProduit = produit.id
            JOIN utilisateur on commandeepi.idUtilisateur = utilisateur.id 
            JOIN lieulivraion on utilisateur.idLieuLivraison = lieulivraion.id 
            GROUP by produit.nom,lieulivraion.nom
            ORDER by lieulivraion.nom;");
            $req->execute();
            $res = $req->fetchall();
            return $res;
        }

        public static function getRecapCommandeVet(){
            $req = Connexion::getInstance()->prepare("select produit.nom as produit,sum(quantite),lieulivraion.nom
            from lignecommandevet
            JOIN commandevet on commandevet.id = lignecommandevet.idCommandeVET
            JOIN produit on lignecommandevet.idProduit = produit.id
            JOIN utilisateur on commandevet.idUtilisateur = utilisateur.id 
            JOIN lieulivraion on utilisateur.idLieuLivraison = lieulivraion.id 
            GROUP by produit.nom,lieulivraion.nom
            ORDER by lieulivraion.nom;");
            $req->execute();
            $res = $req->fetchall();
            return $res;
        }

} 