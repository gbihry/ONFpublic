<?php 

     include_once "$racine/modele/ModeleObjetDAO.php";
     include_once "$racine/vue/vueEntete.php";
     if (!isset($_SESSION['autorise'])){
          header("location:./?action=login");
     }else{  
          $unProduit = ModeleObjetDAO::getDetail($_GET["id"]);
          include_once "$racine/vue/vueDetail.php";
     }
     include_once "$racine/vue/vuePied.php";

?>