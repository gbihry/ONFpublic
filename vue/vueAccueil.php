<div class="container text-center">
    <br/>
    <h1>Découvrez ONF</h1>
    <?php
        if (!isset($_SESSION['autorise'])){
            echo ("<p class='text-muted'>Veuillez vous connecter pour pouvoir utiliser toutes les fonctionnalités</p>");
        }else{
            echo ("<p class='text-muted'>Vous êtes connecté(e)s, vous pouvez désormais utiliser toutes les fonctionnalités</p>");
        }
    ?>
    
</div>            
