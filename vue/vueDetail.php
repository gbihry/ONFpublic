<div class="container-fluid text-center mt-5 produit">
    <?php 
        foreach($unProduit as $detail){
            echo "<div class='main-produit'>";
            echo "<img class='img-produit' src='images/".ModeleObjetDAO::getImage($detail['idImage'])['nom']. "'>";
            echo "<h1>".$detail['nom']."</h1>";
            echo "</div>";
            echo "<div class='main-desc'>";
            echo "<p>" .$detail['description'] ."</p>";
            echo "<h3>".$detail['prix']." points</h3>";
            echo "<button type='button' class='btn btn-outline-success m-5'>Ajouter au panier</button>";
            echo "</div>";
        }
    
    ?>
</div>