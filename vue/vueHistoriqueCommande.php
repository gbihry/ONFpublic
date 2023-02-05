<div class="container-fluid text-center">
    <h1>Historique Commande</h1>
<?php
    echo ('
    <table class="historiquecommande">
        <tr>
            <th>Numéro de commande</th>
            <th>Date de commande</th>
            <th>Montant de la commande</th>
            <th>Type de commande</th>
            <th>Voir la commande</th>
        </tr>
    ');
foreach ($HistoriqueCommande as $key => $value) {
    echo ('
        <tr>
            <td>'.$value['origin'].'_'.$value['id'].'</td>
            <td>'.$value['dateCrea'].'</td>
            <td>'.$value['prix'].'</td>
            <td>'.$value['origin'].'</td>
            <td><a href="./?action=historiquecommandedetail&id='.$value['id'].'&type='.$value['origin'].'" class="btn btn-success"><i class="fa-regular fa-eye"></i> Voir</a></td>
        </tr>');
}
?>
    </table>
    </div>