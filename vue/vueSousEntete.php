<div class="linenav">
    <?php
    if (isset($_SESSION['autorise']) && ModeleObjetDAO::getRole($_SESSION['login'])['libelle'] == 'Super-Administrateur'){
    ?>
    <div class="linenav_item" data-navname="stat">
        <a href="./?action=recapCommande"><i class="fa-solid fa-chart-simple"></i> Récapitulatif </a>
    </div>
    <?php
    }
    ?>
    <div class="linenav_item"   data-navname="stat">
        <a href="./?action=aCommander"><i class="fa-solid fa-chart-simple"></i> Commandes </a>
    </div>
    <div class="linenav_item" data-navname="ajoutUtilisateur">
        <a href="./?action=ajoutUtilisateur"><i class="fa-solid fa-user-plus"></i> Add User</a>
    </div>
    <div class="linenav_item" data-navname="ajoutPoint">
        <a href="./?action=ajoutPoint"><i class='fa-solid fa-ticket'></i> Add Point</a>
    </div>
</div>
<script>
    urlp = new URLSearchParams(window.location.search); // on récupère l'url de la page
    if (urlp.has('action')) { // si l'url contient l'attribut action
        var action = urlp.get('action'); // on récupère la valeur de l'attribut action
        var nav = document.querySelector('[data-navname="' + action + '"]'); // on récupère l'élément qui a l'attribut data-navname = action
        nav ? nav.classList.add('toggle') : null; // si nav existe, on ajoute la classe toggle
    }
</script>