
<div class="container-fluid text-center">
    <h1 class="Acommander">Utilisateurs n'ayant pas commandé</h1>
<?php
    echo ('
    <table class="historiquecommande">
        <tr>
            <th>Id utilisateurs</th>
            <th>Nom et prénom</th>
            <th>Mail</th>
            <th>Date de création panier</th>
        </tr>
    ');
    foreach ($AllUsersNoncommander as $key => $value) {
        echo ('
            <tr>
                <td>'.$value['id'].'</td>
                <td>'.$value['nom'].'_'.$value['prenom'].'</td>
                <td>'.$value['email'].'</td>
                <td>'.$value['dateCrea'].'</td>
            </tr>');
        }
    ?>
    </table>
    <h1 class="Acommander">Utilisateurs ayant commandé</h1>
    <?php
    echo ('
    <table class="historiquecommande">
        <tr>
            <th>Id utilisateurs</th>
            <th>Nom et prénom</th>
            <th>Mail</th>
            <th>Date de création</th>
        </tr>
    ');
    foreach ($AllUsersAcommander as $key => $value) {
        echo ('
            <tr>
                <td>'.$value['id'].'</td>
                <td>'.$value['nom'].'_'.$value['prenom'].'</td>
                <td>'.$value['email'].'</td>
                <td>'.$value['dateCrea'].'</td>
            </tr>
            ');
        }
    ?>
    </table>
    
</div>