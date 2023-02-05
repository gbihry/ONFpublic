<div class="container-fluid text-center">
    <h1 class="recapCommandeEpi">Récap commande EPI</h1>
    <table class="recapCommandeEpi">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantite</th>
                <th>Lieu de livraison</th>
            </tr>
        </thead>
        <tbody>
            
                <?php foreach($RecapEpi as $uneCommandeEpi){?>
                    <tr>
                        <td><?php echo $uneCommandeEpi['produit'] ;?></td>
                        <td><?php echo $uneCommandeEpi['sum(quantite)'] ;?></td>
                        <td><?php echo $uneCommandeEpi['nom'] ;?></td>
                    </tr>
                <?php   } ?>
            
            
        </tbody>
    </table>

    <h1 class="recapCommandeEpi">Récap commande VET</h1>
    <table class="recapCommandeEpi">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantite</th>
                <th>Lieu de livraison</th>
            </tr>
        </thead>
        <tbody>
            
                <?php foreach($RecapVet as $uneCommandeVet){?>
                    <tr>
                        <td><?php echo $uneCommandeVet['produit'] ;?></td>
                        <td><?php echo $uneCommandeVet['sum(quantite)'] ;?></td>
                        <td><?php echo $uneCommandeVet['nom'] ;?></td>
                    </tr>
                <?php   } ?>
            
            
        </tbody>
    </table>


    
</div>
        

