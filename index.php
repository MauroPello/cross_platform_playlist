<?php 
require "php/functions.php";
require "php/login_form.php";
require "php/home_page.php";
require "php/play_playlist.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>All Around Playlists</title>
</head>
<body>
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

<div class="container">
    <?php 
    $current_user = ""; 

    if ($current_user == "") {
        if ($_SERVER["REQUEST_METHOD"] == "GET"){
            $current_user = "";
            
            login_form();
        }
        if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["button"])){
            $fs = fopen("data/accounts.txt", "a+") or die("Failed to open file");
            $accounts = explode("\n", stream_get_contents($fs));

            if ($_POST['button'] == "Login"){
                if (checkUsername($accounts, $_POST["username"])) {
                    if (checkPassword($accounts, $_POST["username"], $_POST["password"])) {
                        $current_user = $_POST["username"];
                        alert($current_user . " logged in successfully!");
                    }
                    else {
                        alert("Wrong Password!");
                        open_link("http://localhost:8080");
                    }
                }
                else {
                    alert($_POST["username"] . " doesn't exist!");
                    open_link("http://localhost:8080");
                }
            }
            else if ($_POST['button'] == "Register"){
                if (checkUsername($accounts, $_POST["username"])) {
                    alert($_POST["username"] . " already exists!");
                    open_link("http://localhost:8080");
                }
                else {
                    $current_user = $_POST["username"];
                    fwrite($fs, "$current_user*|*" . password_hash($_POST["password"], PASSWORD_DEFAULT) . "\n");
                    alert($current_user . " successfully registered!");
                }
            }
            fclose($fs);
        }
    }


    if (isset($_POST["username"]) && isset($_POST["playlist"]) && isset($_POST["button"])) {
        $current_user = $_POST["username"];
        $user_directory = "data/$current_user/";
        $playlist = $_POST["playlist"];


        if ($_POST["button"] == "Create_Playlist"){
            if (checkFile($user_directory, $playlist)){
                alert($playlist . " already exists!");
            }
            else{
                create_file($user_directory . $playlist);
            }
    
            homepage($current_user, $playlist);
        }
        else if ($_POST["button"] == "Delete_Playlist"){
            if (checkFile($user_directory, $playlist)){
                delete_file($user_directory . $playlist);
            }
            else{
                alert($playlist . " doesn't exist!");
            }

            homepage($current_user, $playlist);
        }
        else if ($_POST["button"] == "View_Playlist"){
            homepage($current_user, $playlist);
            
            if (checkFile($user_directory, $playlist)){
                display_songs($user_directory . $playlist);
            }
            else{
                alert($playlist . " doesn't exist!");
            }
        }
        else if ($_POST["button"] == "Add_Song"){
            $song = get_songname($_POST["url"]);
            $platform = get_platform($_POST["url"]);
            $id = get_id($_POST["url"]);

            if (checkSong($user_directory . $playlist, $song) >= 0){
                alert($song . " already exists!");
            }
            else{
                append_to_file($user_directory . $playlist, $song . "*|*" . $id . "*|*" . $platform);
            }

            homepage($current_user, $playlist);
            display_songs($user_directory . $playlist);
        }
        else if ($_POST["button"] == "Delete_Song"){
            $id = get_id($_POST["url"]);

            if (checkSong($user_directory . $playlist, $id) >= 0){
                delete_song($user_directory . $playlist, $id);
            }
            else{
                alert($song . " doesn't exist!");
            }

            homepage($current_user, $playlist);
            display_songs($user_directory . $playlist);
        }
        else if ($_POST["button"] == "Play"){
            $start_index = checkSong($user_directory . $playlist, get_id($_POST["url"]));
            if ($start_index == -1){$start_index = 0;}

            homepage($current_user, $playlist);
            play_playlist($user_directory . $playlist, $start_index);
            display_songs($user_directory . $playlist);
        }
        else if ($_POST["button"] == "Play_Random"){
            homepage($current_user, $playlist);
            play_playlist($user_directory . $playlist, -1);
            display_songs($user_directory . $playlist);
        }
        else{
            homepage($current_user, $playlist);
        }
    }
    else if ($current_user !== "" && !isset($_POST["playlist"])) { 
        homepage($current_user, "");
    }
    else {
        open_link("http://localhost:8080");
    } ?>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="http://www.youtube.com/player_api"></script>
<script src="https://www.youtube.com/iframe_api"></script>
<script src="https://connect.soundcloud.com/sdk/sdk-3.3.2.js"></script>
<script src="https://w.soundcloud.com/player/api.js"></script>
<script type='text/javascript' src="js/scripts.js"></script>
</body>
</html>