<br/>
<h1 class="text-center">Login</h1>
<br/>

<div class="row">
    <div class="col-4 mx-auto text-center">
        <?php
        if (isset($error)) {
            if($error != "") {
                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            }
        }
        if(isset($msg)) {
            echo '<div class="alert alert-success" role="alert">' . $_GET['msg'] . '</div>';
        }
        ?>
        <form action="" method="POST">
            <div class="form-group">
                <input type="text" class="form-control" name="nomLogin" placeholder="Nom"/><br />
                <input type="password" class="form-control" name="mdpLogin" placeholder="Mot de passe" /><br />
            </div>
            <input type="submit" name="valider" class="btn btn-success" value="Se connecter" />
            <br/>
        </form>
        <br/><br/>
        <script>
            // localhost/OVH/?action=newmdp&msg=token
            $urlP = new URLSearchParams(window.location.search);
            if ($urlP.get('msg')) {
                RemoveParam('msg');
            }
            function RemoveParam($param) {
                var url = window.location.href;
                var urlparts = url.split('?');
                if (urlparts.length >= 2) {
                    var urlBase = urlparts.shift(); //retire le premier élément (exemple : localhost/OVH/)
                    var queryString = urlparts.join("?"); //reconstruit l'url (exemple : action=newmdp&msg=token)
                    var prefix = encodeURIComponent($param) + '='; //encode pour que ça puisse rentrer dans une url
                    var pars = queryString.split(/[&;]/g); // action=newmdp&msg=token => [action=newmdp, msg=token]
                    for (var i = pars.length; i-- > 0;)
                        if (pars[i].lastIndexOf(prefix, 0) !== -1) // si le paramètre est trouvé dans l'array
                            pars.splice(i, 1); //retire le paramètre de l'url
                    url = urlBase + '?' + pars.join('&'); //reconstruit l'url
                    window.history.pushState('', document.title, url); // change l'url sans recharger la page
                }
            }
        </script>
    </div>
</div>




