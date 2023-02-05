<div class="catalogue">
    <div class="text-center">
        <p class="catalogue_title_type"> Catalogue EPI</p>
    </div>
    <div class="form-check text-center">
        <form action="./?action=catalogueEpi" method ="POST">
        <?php
            if (isset($_POST['valideProduit']) != true){
        ?>
            <div>
                <input class="form-check-input" type="checkbox" id="flexCheckDefault" required>
                <label class="form-check-label" for="validerProduit">Voir tout les produits</label>
            </div>
            <input type="submit" name="valideProduit" class="btn btn-success" value="Valider" />
        <?php
            }else{
        ?>
            <div>
                <input class="form-check-input" type="checkbox" id="flexCheckDefault" name='validerCatgorie' required>
                <label class="form-check-label" for="validerCategorie">Voir toutes les catégories</label>
            </div>
            <input type="submit" name="valideCategorie" class="btn btn-success" value="Valider" />
        <?php
            }
        ?>
        </form>
    </div>
    <div class="contenue">
        
    <?php 
        if (isset($_POST['valideProduit']) == true){
    ?>
            <div class="container-fluid text-center mt-5 produit">
            <?php
            foreach($allProducts as $detail){
                echo "<div class ='unProduit'>";
                echo "<div class='main-produit'>";
                echo "<img class='img-produit' src='images/".($detail['fichierPhoto'])."'>";
                echo "<h1>".$detail['nom']."</h1>";
                echo "</div>";
                echo "<div class='main-desc'>";
                echo "<p>" .$detail['description'] ."</p>";
                echo "<form method='POST' class='form-group'>";
                ?>
                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-default">Quantité :</span>
                    </div>
                    <input type="number" class="form-control" name='quantity' min='0' max='<?php echo ( ModeleObjetDAO::getQuantiteEpiMax($unStatut['statut'],$detail['idType'])); ?>' aria-describedby="inputGroup-sizing-sm">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" for="inputGroupSelect01">Taille :</span>
                    </div>
                    <select name="taille" class="custom-select" id="inputGroupSelect01">
                    <?php 
                            echo ("<option value=" . (ModeleObjetDAO::getTaille($detail['id']))['id'] .">" . (ModeleObjetDAO::getTaille($detail['id']))['libelle']. "</option>")
                            
                    ?>
                    </select>
                </div>
                <?php
                    if(ModeleObjetDAO::getQuantiteEpi($_SESSION['login'],$detail['idType'])['sum(quantite)'] < (ModeleObjetDAO::getQuantiteEpiMax($unStatut['statut'],$detail['idType']))){
                        echo "<button type='submit' name='submit' class='btn btn-success float-right' value='" . $detail['id'] . "'>Ajouter au panier</button>";
                    }
                    
                    
                    
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                
            }
        }else{
            foreach($catalogue as $uneCategorie){
                echo "<div class='tuile'>
                        <p>" . $uneCategorie['libelle'] . "</p>
                        <a href='./?action=produitEpi&id=".$uneCategorie['id']."'><img src='images/categorie/".$uneCategorie['libelle'].'.jpg' . "'></a>
                    </div>";
            }
        }
        
        
    ?>
    </div>
</div>