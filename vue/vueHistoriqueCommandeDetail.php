<div class="container-fluid text-center">
<h1>Historique Commande : <span class="produitvet_prix"><?php echo($_GET['type'].'_'.$_GET['id']); ?></span></h1>
<div class="panier">
    <?php 

    if ($HistoriqueCommandeDetail == false){
        echo ('<p class="empty_panier"> Erreur </p>');
    } else {
        $prixTotal = 0;
        foreach ($HistoriqueCommandeDetail as $HistoriqueCommandeDetailUnique) {
            $idLigne = $HistoriqueCommandeDetailUnique['id'];
            $type = $HistoriqueCommandeDetailUnique['type'];
            $fichierPhoto = $HistoriqueCommandeDetailUnique['fichierPhoto'];
            $nom = $HistoriqueCommandeDetailUnique['nom'];
            $quantite = $HistoriqueCommandeDetailUnique['quantite'];
            $taille = $HistoriqueCommandeDetailUnique['libelle'];
            $HistoriqueCommandeDetailUnique['prix'] ? $prix = $HistoriqueCommandeDetailUnique['prix'] : $prix = 0;
            $prixTotal += $prix * $HistoriqueCommandeDetailUnique['quantite'];
                echo ("<div class='content'>
                        <div class='image'>
                            <img src='images/" . $fichierPhoto . "' alt=''>
                        </div>
                        <div class='libelle'>
                            <p class='panier_title'>Description produit</p>
                            <p> " . $nom . "</p>
                        </div>
                        <div class'quantite'>
                            <p class='panier_title'>Quantite:</p>
                            <p> " .  $quantite."</p>
                        </div>
                        <div class='taille'>
                            <p class='panier_title'>Taille:</p>
                            <p> " .$taille . "</p>
                        </div>
                        ");
                if($_GET['type'] == 'VET') {
                    echo (" <div class='prix'>
                                <p class='panier_title'>Prix:</p>
                                <p> " .$prix . "</p>
                            </div>");
                }
                echo "</div> ";
        }
        if($_GET['type'] == 'VET') {
            echo (  "<div class='valide_panier'><div class='prixTotal'>
                        <p>Prix total : <span class='prix_total_span'>".$prixTotal ." <i class='fa-solid fa-ticket'></i></span></p>
                    </div></div>");
        }
    }
    ?>