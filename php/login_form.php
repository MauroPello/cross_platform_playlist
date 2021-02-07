<?php function login_form () {?>
<div class="container center-div">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="form-signin"> 
        <img class="mb-4" src="img/logo.png" alt="" width="50%" height="50%">
        <h1 class="h3 mb-3 font-weight-normal">All Around Playlist</h1>

        <label for="inputUsername" class="sr-only">Username</label>
        <input type="username" id="inputUsername" name="username" class="form-control" placeholder="Username" required autofocus>

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>

        <button type="submit" name="button" value="Login" class="btn btn-lg btn-dark btn-block">Sign In</button>
        <button type="submit" name="button" value="Register" class="btn btn-lg btn-dark btn-block">Register</button>
        <p class="mt-5 mb-3 text-muted text-center">All Around Playlist by Mauro Pellonara &copy;2021-2022</p>
    </form>
</div>
<?php } ?>