<div class="container-fluid text-center mt-5">
    <h1>Ajout point a un utilisateur</h1>
    <form  method="post">
        <div class="addUser_container">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" for="inputGroupSelect01">Utilisateur :</span>
                </div>
                <select name="user" class="custom-select" id="inputGroupSelect01">
                <?php 
                    foreach($AllUsers as $user){
                        echo ("<option value=" . ($user['id']).">" . ($user['login']). "</option>");
                    }     
                ?>
                </select>
            </div>
            <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Point :</span>
                </div>
                <input type="number" class="form-control" name='nombrepoint' aria-describedby="inputGroup-sizing-sm" required>
            </div>
        </div>
        
        <button type='submit' class='btn btn-success m-5'>Confirmer</button>

            
    </form>

</div>