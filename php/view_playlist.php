<?php function view_playlist () { 
    $songs = get_songs($_SESSION["username"], $_SESSION["playlist"]);
    ?>
    <div class="custom-container">
    <div class="custom-wrapper">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="form-group">
            <div class="input-group mb-3">
                <input type="text" name="url" class="form-control" style="border-radius: .25rem!important;" placeholder="URL" aria-label="URL" aria-describedby="inputGroup-sizing-default">
                <button type="submit" style="margin: .1rem!important; display: none;" name="button" value="Add" class="btn btn-dark">Add</button>
                <button type="submit" style="margin: .1rem!important;" name="button" value="Sort" class="btn btn-dark">Sort</button>
                <button type="submit" style="margin: .1rem!important; padding: .1rem!important; width: 3vw!important; height: calc(1.5em + .75rem + 2px)!important; min-width: 20px !important;" name="button" value="Play_Random" class="btn btn-dark"><img src="img/shuffle.svg" width="100%" height="100%"></button>
            </div>
        </div>    
    
        <div class="table-responsive" style="margin-top: 2%; margin-bottom: 2%;">
        <table class="table table-sm table-hover table-dark" id="table-song" style="text-align: left; background-color: #272727;">
            <thead>
                <tr>
                <th scope="col"><b>Name</b></th>
                <th scope="col" width="30px"></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    for($i = 0; $i < count($songs); $i++){
                        echo '<tr><td><button type="submit" name="button" value="Play_Song*|*' . $songs[$i][1] . '" class="btn btn-link" style="margin: 0; padding: 0; text-align: left;">' . $songs[$i][0] . '</button><input type="hidden" name="songid*|*' . $songs[$i][1] . '"></td><td><button class="btn btn-img" name="button" value="Delete_Song*|*' . $songs[$i][1] . '" style="padding: .1rem .2rem!important;" width="2vw" height="2vw"><img src="img/close.svg" width="100%" height="100%" ></button></td></tr>';
                    }
                ?>
            </tbody>
        </table>
        </div>
    </form>
    </div>
    </div>
<?php } ?>