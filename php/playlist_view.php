<?php function view_playlist ($username, $playlist) {?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="margin: 10%">
       
        <div class="form-group">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Username</span>
                </div>
                <input type="text" name="username" value="<?php echo $username; ?>" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="inputGroup-sizing-default" readonly>
            </div>
        </div>
            
        <div class="form-group">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Playlist</span>
                </div>
                <input type="text" name="playlist" value="<?php echo $playlist; ?>" class="form-control" placeholder="Playlist" aria-label="Playlist" aria-describedby="inputGroup-sizing-default" readonly>
            </div>
        </div>

        <div class="form-group">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Song</span>
                </div>
                <input type="text" name="song" class="form-control" placeholder="Song" aria-label="Song" aria-describedby="inputGroup-sizing-default">
            </div>
        </div>

        <div class="form-group">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">URL</span>
                </div>
                <input type="text" name="url" class="form-control" placeholder="URL" aria-label="URL" aria-describedby="inputGroup-sizing-default">
            </div>
        </div>

        <div class="form-group">
            <div class="input-group mb-3">
                <div class="form-check form-check-inline" style="margin: auto;">
                    <input class="form-check-input" type="radio" name="platform" id="inlineRadio1" value="yt" checked>
                    <label class="form-check-label" for="inlineRadio1">Youtube</label>
                </div>
                <div class="form-check form-check-inline" style="margin: auto;">
                    <input class="form-check-input" type="radio" name="platform" id="inlineRadio2" value="sp">
                    <label class="form-check-label" for="inlineRadio2">Spotify</label>
                </div>
                <div class="form-check form-check-inline" style="margin: auto;">
                    <input class="form-check-input" type="radio" name="platform" id="inlineRadio3" value="sc">
                    <label class="form-check-label" for="inlineRadio3">SoundCloud</label>
                </div>
            </div>
        </div>

        <div class="button-div">
            <button type="submit" name="button" value="Add_Song" class="btn btn-primary">Add Song</button>
            <button type="submit" name="button" value="Delete_Song" class="btn btn-primary">Delete Song</button>
            <button type="submit" name="button" value="Play" class="btn btn-primary">Play</button>
            <button type="submit" name="button" value="Play_Random" class="btn btn-primary">Shuffle Play</button>
            <button type="submit" name="button" value="" class="btn btn-primary">Go Back</button>
        </div>
    </form>
<?php } ?>