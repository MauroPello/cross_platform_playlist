<?php function homepage ($username, $playlist) {
    $playlists = get_playlists("data/$username/");
    ?>
    <br>
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
                <button type="submit" name="button" value="View_Playlist" class="btn btn-primary center">View Playlist</button>
                <button type="submit" name="button" value="Create_Playlist" class="btn btn-primary center">Create Playlist</button>
                <button type="submit" name="button" value="Delete_Playlist" class="btn btn-primary center">Delete Playlist</button>
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
<?php } ?>