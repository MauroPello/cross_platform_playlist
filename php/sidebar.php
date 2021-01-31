<?php function sidebar ($username, $playlist) {
    $playlists = get_playlists("data/$username/");
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-left">
        <div class="collapse navbar-collapse vertical-center" id="navbarTogglerDemo03">
            <img class="mb-4 text-center" src="logo.png" alt="" width="50%" height="50%">
            <a class="navbar-brand" href="./" style="margin-bottom: 15%; font-weight: bold; font-size: 1.5vw;">All Around Playlist</a>
            <span class="nav-item nav-link" style="margin-bottom: 5%; font-weight: bold; font-size: 1vw;">
                <?php echo "User: " . $_POST["username"]; ?>
            </span>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="width: 80%;">
                <input style="display: none;" type="text" name="username" value="<?php echo $username; ?>" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="inputGroup-sizing-default" required readonly>
            
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
                        <button type="submit" name="button" value="Create_Playlist" class="btn btn-secondary">Create New</button>
                        <button type="submit" name="button" value="Delete_Playlist" class="btn btn-secondary">Delete</button>
                    </div>
                </div>
                <div class="table-responsive" style="margin-top: 10%;">
                <table class="table table-sm table-hover table-dark">
                    <thead>
                        <tr>
                        <th scope="col"><a style="font-weight: bold; font-size: 1vw;">Your Playlists</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach($playlists as $item){
                                echo '<tr><th scope="row"><button type="submit" name="button" value="View_Playlist*|*' . $item . '" class="btn btn-link" style="display: flex;margin:auto;">' . $item . '</button></th></tr>';
                            }
                        ?>
                    </tbody>
                </table>
                </div>
            </form>
        </div>
    </nav>
<?php } ?>