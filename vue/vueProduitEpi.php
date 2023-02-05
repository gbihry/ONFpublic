<div class="container-fluid text-center mt-5 produit">
    <?php 
        foreach($unProduit as $detail){
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
                <input type="number" class="form-control" name='quantity' min='0' max='<?php echo ModeleObjetDAO::getQuantiteEpiMax($unStatut['statut'],$detail['idType']);   ?>' aria-describedby="inputGroup-sizing-sm">
                
            </div>
            <div class="input-group mb-3">
                
                <?php
                if (ModeleObjetDAO::getType($detail['id']) == 1 || ModeleObjetDAO::getType($detail['id']) == 2) {
                ?>
                    <div class="input-group-prepend">
                        <span class="input-group-text" for="inputGroupSelect01">Pointure :</span>
                    </div>
                <?php
                }else{
                ?>
                <div class="input-group-prepend">
                    <span class="input-group-text" for="inputGroupSelect01">Taille :</span>
                </div>
                <?php
                }
                ?>
                <select name="taille" class="custom-select" id="inputGroupSelect01">
                <?php 
                        echo ("<option value=" . (ModeleObjetDAO::getTaille($detail['id']))['id'] .">" . (ModeleObjetDAO::getTaille($detail['id']))['libelle']. "</option>")
                        
                ?>
                </select>
            </div>
            <?php 
            if (isset($commanderPour)){
                
            ?>
                <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Commander pour :</span>
                </div>
                <select name="commanderPour" class="custom-select" id="inputGroupSelect01">
                <?php 
                foreach($commanderPour as $unSubordonnee){
                        echo ("<option value=" . $unSubordonnee['email'] .">" . $unSubordonnee['email']. "</option>");
                }
                ?>
                </select>
                
                </div>
            <?php
            }
            ?>
            
            <?php
                if(ModeleObjetDAO::getQuantiteEpi($_SESSION['login'],$detail['idType'])['sum(quantite)'] < (ModeleObjetDAO::getQuantiteEpiMax($unStatut['statut'],$detail['idType']))){
                    echo "<button type='submit' name='submit' class='btn btn-success float-right' value='" . $detail['id'] . "'>Ajouter au panier</button>";
                }
                
                
                
                echo "</form>";
                echo "</div>";
                echo "</div>";
            
        }
    ?>
</div>