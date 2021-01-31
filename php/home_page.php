<?php function homepage ($username, $playlist) {
    $playlists = get_playlists("data/$username/");
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="./">All Around Playlist</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
        <li class="nav-item <?php if ($_SERVER["REQUEST_METHOD"] == "GET"){ echo 'active';} ?>">
            <a class="nav-link" href="./">Log In<?php if ($_SERVER["REQUEST_METHOD"] == "GET"){ echo '<span class="sr-only">(current)</span>';} ?></a>
        </li>
        <li class="nav-item <?php if (isset($_POST["username"])){ echo 'active';} ?>">
            <a class="nav-link" href="#">Playlists</a>
        </li>
        </ul>
        <span class="nav-item nav-link">
            <?php if (isset($_POST["username"])){ echo "Current User: " . $_POST["username"]; }?>
        </span>
    </div>
    </nav>
    <br>
    <div class="container">
    <h3 style="text-align: center"><p class="label label-default">Pick a Playlist</p></h3>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input style="display: none;" type="text" name="username" value="<?php echo $username; ?>" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="inputGroup-sizing-default" readonly>
        
        <div class="form-group">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span for="playlist" class="input-group-text" id="inputGroup-sizing-default">Playlist</span>
                </div>
                <input type="text" name="playlist" list="playlists" class="form-control" value="<?php echo $playlist; ?>" placeholder="Playlist" autocomplete="off" aria-label="Playlist" aria-describedby="inputGroup-sizing-default">
                <datalist id="playlists">
                    <?php
                        foreach($playlists as $item){
                            echo '<option value="' . $item . '">' . $item . '</option>';
                        }
                    ?>
                </datalist>
            </div>
        
            <div class="button-div">
                <button type="submit" name="button" value="View_Playlist" class="btn btn-primary">View Playlist</button>
                <button type="submit" name="button" value="Create_Playlist" class="btn btn-primary">Create Playlist</button>
                <button type="submit" name="button" value="Delete_Playlist" class="btn btn-primary">Delete Playlist</button>
            </div>
        </div>
        
        <br>
        <h3 style="text-align: center"><p class="label label-default">Manage your Songs</p></h3>

        <div class="form-group">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">URL</span>
                </div>
                <input type="text" name="url" class="form-control" placeholder="URL" aria-label="URL" aria-describedby="inputGroup-sizing-default">
            </div>

            <div class="button-div">
                <button type="submit" name="button" value="Add_Song" class="btn btn-primary">Add Song</button>
                <button type="submit" name="button" value="Delete_Song" class="btn btn-primary">Delete Song</button>
                <button type="submit" name="button" value="Play" class="btn btn-primary">Play</button>
                <button type="submit" name="button" value="Play_Random" class="btn btn-primary">Shuffle Play</button>
            </div>
        </div>
    </form>
    </div>
<?php } ?>