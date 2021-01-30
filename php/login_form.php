<?php function login_form () {?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="margin: 10%">
    
        <div class="form-group">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Username</span>
                </div>
                <input type="text" name="username" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="inputGroup-sizing-default">
            </div>
        </div>
            
        <div class="form-group">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Password</span>
                </div>
                <input type="password" name="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="inputGroup-sizing-default">
            </div>
        </div>

        <div class="button-div">
            <button type="submit" name="button" value="Login" class="btn btn-primary center">Log In</button>
            <button type="submit" name="button" value="Register" class="btn btn-primary center">Register</button>
        </div>
    </form>
<?php } ?>