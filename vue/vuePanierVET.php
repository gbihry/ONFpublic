<div class="container-fluid text-center">
    <h1>Panier VET</h1>
    <div class="panier">
        <?php 

        if ($ligneCommandeVET == false){
            echo ('<p class="empty_panier"> Aucun article dans le panier </p>');
        } else {
            echo('<p class="panier_title_type">VET</p>');

        $prixTotal = 0;

        foreach ($ligneCommandeVET as $ligneCommandeUnique) {
            $idLigne = $ligneCommandeUnique['id'];
            $type = $ligneCommandeUnique['type'];
            $fichierPhoto = $ligneCommandeUnique['fichierPhoto'];
            $nom = $ligneCommandeUnique['nom'];
            $quantite = $ligneCommandeUnique['quantite'];
            $prix = $ligneCommandeUnique['prix'];
            $taille = $ligneCommandeUnique['libelle'];
            $prixTotal += $ligneCommandeUnique['prix'] * $ligneCommandeUnique['quantite'];
                echo ("<div class='content'>
                        <div class='image'>
                            <img src='images/" . $fichierPhoto . "' alt=''>
                        </div>
                        <div class='libelle'>
                            <p class='panier_title'>Description produit</p>
                            <p> " . $nom . "</p>
                        </div>
                        <div class'prix'>
                            <p class='panier_title'>Prix</p>
                            <p> " . $prix ."</p>
                        </div>
                        <div class'quantite'>
                            <p class='panier_title'>Quantite</p>
                            <p> " .  $quantite."</p>
                        </div>
                        <div class='taille'>
                            <p class='panier_title'>Taille</p>
                            <p> " .$taille . "</p>
                        </div>
                        <div class='supprimer'>
                            <form action='' method='post'>
                                <input type='hidden' name='type' value='VET'>
                                <input type='hidden' name='idLigne' value='".$idLigne."'>
                                <button type='submit'>Supprimer</button>
                            </form>
                        </div>
                    </div> 
                ");
        }
        ?>
        <div class='valide_panier'>
        <?php
                echo("
                <div class='prixTotal'>
                    <p>Prix total : <span class='prix_total_span'>".$prixTotal ." <i class='fa-solid fa-ticket'></i></span></p>
                </div> 
                ");

                echo('
                <form action="./?action=recapPanier&type=vet" method="POST">
                    <input type="hidden" name="validePanier" value="true">
                    <input type="submit" class="btn btn-success" value="Valider le panier">
                </form>
                ');
        }
        ?> 
        </div>

    </div>
</div>