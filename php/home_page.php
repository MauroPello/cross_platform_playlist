<?php function homepage ($username) {?>
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
                <input type="text" name="playlist" class="form-control" placeholder="Playlist" aria-label="Playlist" aria-describedby="inputGroup-sizing-default">
            </div>
        </div>
        
        <div class="button-div">
            <button type="submit" name="button" value="View_Playlist" class="btn btn-primary center">View Playlist</button>
            <button type="submit" name="button" value="Create_Playlist" class="btn btn-primary center">Create Playlist</button>
            <button type="submit" name="button" value="Delete_Playlist" class="btn btn-primary center">Delete Playlist</button>
        </div>
    </form>
<?php } ?>