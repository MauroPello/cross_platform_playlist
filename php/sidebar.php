<?php function sidebar ($username, $playlist) {
    $playlists = get_playlists("data/$username/");
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-left">
        <div class="collapse navbar-collapse vertical-center" id="navbarTogglerDemo03" style="display: flex;">
            <img class="mb-4 text-center" src="img/logo.png" alt="" width="50%" height="50%">
            <a class="nav-item" href="./" style="margin-bottom: 15%; font-weight: bold; width: 80%; font-size: 1.25rem; color: white;">All Around Playlist</a>
            <span class="nav-item" style="margin-bottom: 5%; font-weight: bold; width: 80%;">
                <?php echo "User: " . $_POST["username"]; ?>
            </span>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="width: 80%;">
                <input style="display: none;" type="text" name="username" value="<?php echo $username; ?>" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="inputGroup-sizing-default" required readonly>
            
                <div class="form-group">
                    <div class="input-group mb-3">
                        <input style="border-radius: .25rem!important;" type="text" name="playlist" list="playlists" class="form-control" value="<?php echo $playlist; ?>" placeholder="Playlist" autocomplete="off" aria-label="Playlist" aria-describedby="inputGroup-sizing-default">
                        <datalist id="playlists">
                            <?php
                                foreach($playlists as $item){
                                    echo '<option value="' . $item . '">' . $item . '</option>';
                                }
                            ?>
                        </datalist>
                    </div>
                
                    <div class="button-div">
                        <button style="padding: .2rem .5rem;" type="submit" name="button" value="Create_Playlist" class="btn btn-secondary">Create New</button>
                        <button style="padding: .2rem .5rem;" type="submit" name="button" value="Delete_Playlist" class="btn btn-secondary">Delete</button>
                    </div>
                </div>

                <div class="table-responsive" style="margin-top: 10%;">
                <table class="table table-sm table-hover table-dark">
                    <thead>
                        <tr>
                        <th scope="col"><b>Your Playlists</a></th>
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