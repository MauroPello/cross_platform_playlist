<?php function view_playlist ($username, $playlist) { 
    $songs = get_songs("data/$username/$playlist"); 
    ?>
    <div class="custom-container">
    <div class="custom-wrapper">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input style="display: none;" type="text" name="username" value="<?php echo $username; ?>" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="inputGroup-sizing-default" required readonly>
        <input style="display: none;" type="text" name="playlist" value="<?php echo $playlist; ?>" class="form-control" placeholder="Playlist" aria-label="PLaylist" aria-describedby="inputGroup-sizing-default" required readonly>

        <div class="table-responsive" style="margin-top: 2%; margin-bottom: 2%;">
        <table class="table table-sm table-hover table-dark" style="text-align: left; background-color: #272727;">
            <thead>
                <tr>
                <th scope="col"><a style="font-weight: bold;">Name (click any to start playing)</a></th>
                <th scope="col"><a style="font-weight: bold;">Direct Link</a></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach($songs as $song){
                        $tmp = explode("*|*", $song);
                        echo '<tr><th scope="row"><button type="submit" name="button" value="Play_Song*|*' . $tmp[1] . '" class="btn btn-link" style="margin: 0;padding: 0;">' . $tmp[0] . '</button><td><a href="' . get_url($tmp[1], $tmp[2]) . '" target="_blank">' . get_url($tmp[1], $tmp[2]) . '</a></td></th></tr>';
                    }
                ?>
            </tbody>
        </table>
        </div>
        
        <div class="form-group">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">URL</span>
                </div>
                <input type="text" name="url" class="form-control" placeholder="URL" aria-label="URL" aria-describedby="inputGroup-sizing-default">
            </div>

            <div class="button-div">
                <button type="submit" name="button" value="Add_Song" class="btn btn-dark">Add Song</button>
                <button type="submit" name="button" value="Delete_Song" class="btn btn-dark">Delete Song</button>
                <button type="submit" name="button" value="Play_Random" class="btn btn-dark">Shuffle Play</button>
            </div>
        </div>
    </form>
    </div>
    </div>
<?php } ?>