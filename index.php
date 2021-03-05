<?php 
require "php/functions.php";
require "php/database.php";
require "php/login_form.php";
require "php/sidebar.php";
require "php/play_playlist.php";
require "php/view_playlist.php";

ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
update_session_activity();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["rearrange_songs"]) && isset($_SESSION["username"]) && isset($_SESSION["playlist"])){
    $ids = [];
    foreach (explode("*|*", $_POST["rearrange_songs"]) as $value)
        array_push($ids, $value);

    rearrange_songs($_SESSION["username"], $_SESSION["playlist"], $ids);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <link href="css/signin.css" rel="stylesheet" type="text/css">
    <link href="css/volume-slider.css" rel="stylesheet" type="text/css">
    <link href="css/navbar-fixed-left.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/sign-in/">
    <link rel="icon" href="img/logo.ico">
    <title>All Around Playlist</title>
</head>

<body class="text-center">
<?php 
if (!isset($_SESSION["username"])) {
    if ($_SERVER["REQUEST_METHOD"] == "GET"){
        login_form();
    }
    else if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["button"])){
        $username = clean_input($_POST["username"]);
        $password = clean_input($_POST["password"]);
        if ($_POST['button'] == "Login"){
            if (check_username($username)) {
                if (check_password($username, $password)) {
                    $_SESSION['username'] = $username;
                }
                else {
                    alert("Wrong Password!");
                    login_form();
                }
            }
            else {
                alert($username . " doesn't exist!");
                login_form();
            }
        }
        else if ($_POST['button'] == "Register"){
            if (check_username($username)) {
                alert($username . " already exists!");
                login_form();
            }
            else {
                $_SESSION['username'] = $username;
                new_user($username, password_hash($password, PASSWORD_DEFAULT));
            }
        }
    }
}

if (isset($_POST["button"]) && $_POST["button"] == "Log_Out"){
    session_unset();
    session_destroy();

    header('Refresh: 0; url=index.php');
}
else if (isset($_SESSION["username"]) && (isset($_POST["playlist"]) || isset($_SESSION["playlist"])) && isset($_POST["button"])) {
    
    if ($_POST["button"] == "Create_Playlist"){
        $playlist = clean_input($_POST["playlist"]);
        if (check_playlist($_SESSION["username"], $playlist)){
            alert($playlist . ' already exists!');
        }
        else if ($playlist !== ""){
            new_playlist($_SESSION["username"], $playlist);
            $_SESSION["playlist"] = $playlist;
        }
        else {
            alert('Please enter a name!');
        }

        if  ($_SESSION["playlist"] !== ""){
            view_playlist();
        }
    }
    else if ($_POST["button"] == "Delete_Playlist"){
        $playlist = clean_input($_POST["playlist"]);
        if (check_playlist($_SESSION["username"], $playlist)){
            delete_playlist($_SESSION["username"], $playlist);
            $_SESSION["playlist"] = "";
        }
        else{
            alert($playlist . " doesn't exist!");
        }

        if  ($_SESSION["playlist"] !== ""){
            view_playlist();
        }
    }
    else if ($_POST["button"] == "Rename_Playlist"){
        $playlist = clean_input($_POST["playlist"]);
        if (check_playlist($_SESSION["username"], $playlist)){
            alert($playlist . " already exist!");
        }
        else if  ($playlist !== ""){
            rename_playlist($_SESSION["username"], $_SESSION["playlist"], $playlist);
            $_SESSION["playlist"] = $playlist;
        }
        else {
            alert('Please enter a name!');
        }

        if  ($_SESSION["playlist"] !== ""){
            view_playlist();
        }
    }
    else if (strpos($_POST["button"], "View_Playlist") !== false){
        $playlist = clean_input(explode("*|*", $_POST["button"])[1]);
        if (check_playlist($_SESSION["username"], $playlist)){
            $_SESSION["playlist"] = $playlist;
            view_playlist();
        }
        else{
            alert($playlist . " doesn't exist!");
        }
    }
    else if ($_POST["button"] == "Add"){
        $url = clean_input($_POST["url"]);
        $platform = get_platform($url);
        if (is_playlist($url)){
            $ids = get_song_ids(get_id($url, $platform), $platform);
            foreach ($ids as $id){
                $song_platform = $platform;
                $song = get_songname($id, $song_platform);
                if ($song_platform == "sp"){
                    $song_platform = "yt";
                    $id = search_ytsong($song);
                    $song = get_songname($id, $song_platform);
                }
        
                if (check_song($_SESSION["username"], $_SESSION["playlist"], $id) >= 0){
                    alert($song . " already exists!");
                }
                else{
                    if ($song !== "" && $song_platform !== "" && $id !== ""){
                        new_song($_SESSION["username"], $_SESSION["playlist"], $song, $id, $song_platform);
                    }
                    else{
                        alert('Please enter all the information required!');
                    }
                }
            }
        }
        else{
            $id = get_id($url, $platform);
            if ($id !== $url) {
                $song = get_songname($id, $platform);
                if ($platform == "sp"){
                    $platform = "yt";
                    $id = search_ytsong($song);
                    $song = get_songname($id, $platform);
                }
            }
            else{
                $platform = "yt";
                $id = search_ytsong($url);
                $song = get_songname($id, $platform);
            }

    
            if (check_song($_SESSION["username"], $_SESSION["playlist"], $id) >= 0){
                alert($song . " already exists!");
            }
            else{
                if ($song !== "" && $platform !== "" && $id !== ""){
                    new_song($_SESSION["username"], $_SESSION["playlist"], $song, $id, $platform);
                }
                else{
                    alert('Please enter all the information required!');
                }
            }
        }

        view_playlist();
    }
    else if (strpos($_POST["button"], "Delete_Song") !== false){
        $id = clean_input(explode("*|*", $_POST["button"])[1]);

        if (check_song($_SESSION["username"], $_SESSION["playlist"], $id) >= 0){
            delete_song($_SESSION["username"], $_SESSION["playlist"], $id);
        }
        else{
            $song = get_songname($id, get_platform($url));
            alert($song . " doesn't exist!");
        }

        view_playlist();
    }
    else if (strpos($_POST["button"], "Play_Song") !== false){
        $start_index = check_song($_SESSION["username"], $_SESSION["playlist"], clean_input(explode("*|*", $_POST["button"])[1]));

        play_playlist($start_index);
        view_playlist();
    }
    else if ($_POST["button"] == "Play_Random"){
        play_playlist(-1);
        view_playlist();
    }

    sidebar();
}
else if (isset($_SESSION["username"]) && !isset($_SESSION["playlist"])) { 
    sidebar();
}
else if (isset($_SESSION["username"]) && isset($_SESSION["playlist"])) { 
    sidebar();
    view_playlist();
}
?>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="http://www.youtube.com/player_api"></script>
<script src="https://www.youtube.com/iframe_api"></script>
<script src="https://connect.soundcloud.com/sdk/sdk-3.3.2.js"></script>
<script src="https://w.soundcloud.com/player/api.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type='text/javascript' src="js/scripts.js"></script>
<script type='text/javascript' src="js/table.js"></script>
</body>
</html>