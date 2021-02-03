<?php function view_playlist () { 
    $songs = get_songs($_SESSION["username"], $_SESSION["playlist"]);
    ?>
    <div class="custom-container">
    <div class="custom-wrapper">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="table-responsive" style="margin-top: 2%; margin-bottom: 2%;">
        <table class="table table-sm table-hover table-dark" id="table-song" style="text-align: left; background-color: #272727;">
            <thead>
                <tr>
                <th scope="col"><b>#</b></th>
                <th scope="col"><b>Name</b></th>
                <th scope="col"><b>Direct Link</b></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    for($i = 0; $i < count($songs); $i++){
                        echo '<tr><td><b>' . $i . '</b></td><td><button type="submit" name="button" value="Play_Song*|*' . $songs[$i][1] . '" class="btn btn-link" style="margin: 0; padding: 0; text-align: left;">' . $songs[$i][0] . '</button><input type="hidden" name="songid*|*' . $songs[$i][1] . '"></td><td><a href="' . get_url($songs[$i][1], $songs[$i][2]) . '" class="song-url" target="_blank">' . get_url($songs[$i][1], $songs[$i][2]) . '</a></td></tr>';
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
                <input type="text" name="url" class="form-control" placeholder="Enter a valid URL..." aria-label="URL" aria-describedby="inputGroup-sizing-default">
            </div>

            <div class="button-div">
                <button type="submit" name="button" value="Add_Song" class="btn btn-dark">Add Song</button>
                <button type="submit" name="button" value="Delete_Song" class="btn btn-dark">Delete Song</button>
                <button type="submit" name="button" value="Sort" class="btn btn-dark">Sort</button>
                <button type="submit" name="button" value="Play_Random" class="btn btn-dark">Shuffle Play</button>
            </div>
        </div>
    </form>
    </div>
    </div>
<?php } ?>