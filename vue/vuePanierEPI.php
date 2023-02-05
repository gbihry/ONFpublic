<div class="container-fluid text-center">
    <h1>Panier EPI</h1>
    <div class="panier">
        <?php 

        if ($ligneCommandeEPI == false){
            echo ('<p class="empty_panier"> Aucun article dans le panier </p>');
        } else {
            echo('<p class="panier_title_type">EPI</p>');

        foreach ($ligneCommandeEPI as $ligneCommandeUnique) {
            $idLigne = $ligneCommandeUnique['id'];
            $type = $ligneCommandeUnique['type'];
            $fichierPhoto = $ligneCommandeUnique['fichierPhoto'];
            $nom = $ligneCommandeUnique['nom'];
            $quantite = $ligneCommandeUnique['quantite'];
            $taille = $ligneCommandeUnique['libelle'];
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
                        <div class='supprimer'>
                            <form action='' method='post'>
                                <input type='hidden' name='type' value='EPI'>
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
                echo('
                <form action="./?action=recapPanier&type=epi" method="POST">
                    <input type="hidden" name="validePanier" value="true">
                    <input type="submit" class="btn btn-success" value="Valider le panier">
                </form>
                ');
            }
        ?> 
        </div>

    </div>
</div>