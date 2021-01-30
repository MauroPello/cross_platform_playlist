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
<br>
<h2 style="text-align: center"><a class="label label-default" href="./">Login Page</a></h2>

<div class="container">
    <?php 

    require "php/functions.php";
    require "php/login_form.php";
    require "php/home_page.php";
    require "php/playlist_view.php";
    require "php/play_playlist.php";

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
                        $msg = $current_user . " logged in successfully!";
                    }
                    else {
                        $msg =  "Wrong Password!";
                        login_form();
                    }
                }
                else {
                    $msg =  $_POST["username"] . " doesn't exist!";
                    login_form();
                }
            }
            else if ($_POST['button'] == "Register"){
                if (checkUsername($accounts, $_POST["username"])) {
                    $msg =  $_POST["username"] . " already exists!";
                    login_form();
                }
                else {
                    $current_user = $_POST["username"];
                    fwrite($fs, "$current_user*|*" . password_hash($_POST["password"], PASSWORD_DEFAULT) . "\n");
                    $msg =  $current_user . " successfully registered!";
                }
            }
            fclose($fs);
            alert($msg);
        }
    }


    if (isset($_POST["username"]) && isset($_POST["playlist"]) && isset($_POST["button"])) {
        $current_user = $_POST["username"];
        $directory = "data/$current_user/";
        $filename = $_POST["playlist"];

        if ($_POST["button"] == "Create_Playlist"){
            if (checkFile($directory, $filename)){
                alert($filename . " already exists!");
            }
            else{
                create_file($directory . $filename);
            }

            homepage($current_user);
            display_filenames($directory);
        }
        else if ($_POST["button"] == "Delete_Playlist"){
            if (checkFile($directory, $filename)){
                delete_file($directory . $filename);
            }
            else{
                alert($filename . " doesn't exist!");
            }

            homepage($current_user);
            display_filenames($directory);
        }
        else if ($_POST["button"] == "View_Playlist"){
            if (checkFile($directory, $filename)){
                view_playlist($current_user, $filename);
                display_songs($directory . $filename, "Playlist: " . $filename);
            }
            else{
                alert($filename . " doesn't exist!");
                homepage($current_user);
                display_filenames($directory);
            }
        }
        else if ($_POST["button"] == "Add_Song"){
            $song = $_POST["song"];
            $platform = $_POST["platform"];

            if ($platform == "yt"){
                $url = str_replace("https://www.youtube.com/watch?v=", '', $_POST["url"]);
                $url = str_replace("https://youtu.be/", '', $url);
                $url = explode("&", $url)[0];
            }
            else if ($platform == "sp"){
                $url = str_replace("https://open.spotify.com/embed/track/", '', $_POST["url"]);
                $url = str_replace("https://open.spotify.com/track/", '', $url);
                $url = str_replace("spotify:track:", '', $url);
                $url = explode("?", $url)[0];
            }
            else if ($platform == "sc"){
                $url = str_replace("https://soundcloud.com/", '', $_POST["url"]);
                $url = explode("?", $url)[0];
            }

            if (checkSong($directory . $filename, $song)){
                alert($song . " already exists!");
            }
            else{
                append_to_file($directory . $filename, $song . "*|*" . $url . "*|*" . $platform);
            }

            view_playlist($current_user, $filename);
            display_songs($directory . $filename, "Playlist: " . $filename);
        }
        else if ($_POST["button"] == "Delete_Song"){
            $song = $_POST["song"];

            if (checkSong($directory . $filename, $song)){
                delete_song($directory . $filename, $song);
            }
            else{
                alert($song . " doesn't exist!");
            }

            view_playlist($current_user, $filename);
            display_songs($directory . $filename, "Playlist: " . $filename);
        }
        else if ($_POST["button"] == "Play"){
            view_playlist($current_user, $filename);
            play_playlist($directory . $filename, 2);
            display_songs($directory . $filename, "Playlist: " . $filename);
        }
        else if ($_POST["button"] == "Play_Random"){
            view_playlist($current_user, $filename);
            play_playlist($directory . $filename, -1);
            display_songs($directory . $filename, "Playlist: " . $filename);
        }
        else{
            homepage($current_user);
            display_filenames($directory);
        }
    }
    else if ($current_user !== "" && !isset($_POST["playlist"])) { 
        homepage($current_user);

        display_filenames("data/$current_user/");
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