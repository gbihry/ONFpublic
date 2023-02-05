<?php
    session_start();
    include_once "$racine/modele/ModeleObjetDAO.php";
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <!-- Lien vers l'URL Bootstrap -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <script src="https://kit.fontawesome.com/f460dffe13.js" crossorigin="anonymous"></script>  
        <?php 
          if (isset(($_GET['action'])) && ($_GET['action'] == "catalogue1")) {
            echo ('<title>ONF - Catalogue EPI</title>');
          }elseif  (isset($_GET['action'])){
            echo ('<title>ONF - ' . ucfirst($_GET['action'] ). '</title>');
          }else{
            echo ('<title>ONF</title>');
          }
        ?>

    </head>
    <body>
      <nav class="nav">
        <input type="checkbox" id="nav-check">
          <div class="nav_title">
            <a href="index.php?action=accueil"><img src="images/onf.png" alt="logo ONF" class="logo"></a>
              <?php 
              if(isset($_SESSION['autorise'])) {
                echo '<div class="nav_title_item"><p><i class="fa-solid fa-user"></i>' . $_SESSION['login'] . '</p></div>';
                echo '<div class="nav_title_item"><p><i class="fa-solid fa-wrench"></i>' . ModeleObjetDAO::getStatut($_SESSION['login'])['statut'] . '</p></div>';
                echo '<div class="nav_title_item"><p><i class="fa-solid fa-ticket"></i>'.ModeleObjetDAO::getNbrPointUtilisateur(ModeleObjetDAO::getIdUtilisateur($_SESSION['login'])['id'])['point']. '</p></div>';
                echo '<div class="nav_title_item custombtn"><a href="./?action=logout"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>'.'</div>';
              }
            ?>
            </div>
            </div>
          <div class="nav_btn">
            <label for="nav-check"><i class="fa-solid fa-bars" id="nav_checker_open"></i><i class="fa-solid fa-x" id="nav_checker_close"></i></label>
          </div>
            
          <div class="nav_links">
            <?php
            if(isset($_SESSION['autorise'])) {
              $NombreElementDansLePanierEPI = ModeleObjetDAO::getNbArticlePanier(ModeleObjetDAO::getIdUtilisateur($_SESSION['login'])['id'],'epi');
              $NombreElementDansLePanierVET = ModeleObjetDAO::getNbArticlePanier(ModeleObjetDAO::getIdUtilisateur($_SESSION['login'])['id'],'vet');
              
              if(ModeleObjetDAO::getUtilisateurCommandeTerminer(ModeleObjetDAO::getIdUtilisateur($_SESSION['login'])['id'], 'EPI') + ModeleObjetDAO::getUtilisateurCommandeTerminer(ModeleObjetDAO::getIdUtilisateur($_SESSION['login'])['id'], 'VET') > 0){
              echo '<div class="nav_links_item"><a href="index.php?action=historiqueCommande"><i class="fa-solid fa-clock-rotate-left"></i>Historique</a></div>';
              }
            echo '
            <div class="nav_links_item"><a href="index.php?action=catalogueVet"><i class="fa-solid fa-book-open"></i>Catalogue VET</a></div>
            <div class="nav_links_item"><a href="index.php?action=catalogueEpi"><i class="fa-solid fa-book-open"></i>Catalogue EPI</a></div>
            <div class="nav_links_item"><a href="index.php?action=panierEPI"><i class="fa-solid fa-bag-shopping"></i>Panier EPI ('.$NombreElementDansLePanierEPI.')</a></div>
            <div class="nav_links_item"><a href="index.php?action=panierVET"><i class="fa-solid fa-bag-shopping"></i>Panier VET ('.$NombreElementDansLePanierVET.')</a></div>';
            
            } else {
              echo '<div class="nav_links_item"><a href="./?action=login"><i class="fa-solid fa-right-from-bracket"></i> Connexion</a>'.'</div>';
            }
            ?>
          </div>
      </nav>
          <?php
            if(isset($_SESSION['autorise']) && ModeleObjetDAO::getRole($_SESSION['login'])['libelle'] == 'Administrateur' || 
            isset($_SESSION['autorise']) && ModeleObjetDAO::getRole($_SESSION['login'])['libelle'] == 'Super-Administrateur'){
              include_once "$racine/vue/vueSousEntete.php";}
            ?>
          
        <div class="container-fluid">