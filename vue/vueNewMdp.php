<br/>
<h1 class="text-center">Nouveau Mot de passe</h1>
<br/>

<div class="row">
    <div class="col-4 mx-auto text-center">
        <?php
        if (isset($error)) {
            if($error != "") {
                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            }
        }
        ?>
        <form action="" method="POST">
            <div class="form-group">
                <input type="password" class="form-control" name="mdpActuel" placeholder="Mot de passe actuel" required /><br />
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="mdpNew" placeholder="Nouveau mot de passe" required /><br />
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="mdpNewConfirm" placeholder="Confirmer nouveau mot de passe" required /><br />
            </div>
            <input type="submit" name="valider" class="btn btn-success" value="Comfirmer" />
            <br/>
        </form>
        <br/><br/>
    </div>
</div>