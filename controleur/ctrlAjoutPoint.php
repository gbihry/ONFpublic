<?php 
    include_once "$racine/modele/ModeleObjetDAO.php";
    include_once "$racine/vue/vueEntete.php";

    
    if(isset($_SESSION['autorise']) && ModeleObjetDAO::getRole($_SESSION['login'])['libelle'] == 'Administrateur' ||  
    ModeleObjetDAO::getRole($_SESSION['login'])['libelle'] == 'Super-Administrateur'){
        if(!empty($_POST)){
            $idUtilisateur = $_POST['user'];
            $points = $_POST['nombrepoint'];
            ModeleObjetDAO::insertPoints($idUtilisateur, $points);
        }
    } else {
        header("location:./?action=accueil");
    }
    

    $AllUsers = ModeleObjetDAO::getAllUsersID();




    include_once "$racine/vue/vueAjoutPoint.php";
    include_once "$racine/vue/vuePied.php";

?> 