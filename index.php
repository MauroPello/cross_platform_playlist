<?php 
require "php/functions.php";
require "php/login_form.php";
require "php/sidebar.php";
require "php/play_playlist.php";
require "php/view_playlist.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <link href="css/signin.css" rel="stylesheet" type="text/css">
    <link href="css/navbar-fixed-left.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/sign-in/">
    <link rel="icon" href="img/logo.ico">
    <title>All Around Playlists</title>
</head>

<body class="text-center">
<?php 
if (!isset($_COOKIE["username"])) {
    if ($_SERVER["REQUEST_METHOD"] == "GET"){
        login_form();
    }
    if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["button"])){
        $fs = fopen("data/accounts.txt", "a+") or die("Failed to open file");
        $accounts = explode("\n", stream_get_contents($fs));

        if ($_POST['button'] == "Login"){
            if (checkUsername($accounts, $_POST["username"])) {
                if (checkPassword($accounts, $_POST["username"], $_POST["password"])) {
                    setcookie("username", $_POST["username"], time()+1*24*60*60);
                    header('Refresh: 0; url=index.php');
                }
                else {
                    alert("Wrong Password!");
                    login_form();
                }
            }
            else {
                alert($_POST["username"] . " doesn't exist!");
                login_form();
            }
        }
        else if ($_POST['button'] == "Register"){
            if (checkUsername($accounts, $_POST["username"])) {
                alert($_POST["username"] . " already exists!");
                login_form();
            }
            else {
                setcookie("username", $_POST["username"], time()+1*24*60*60);
                fwrite($fs, "$current_user*|*" . password_hash($_POST["password"], PASSWORD_DEFAULT) . "\n");
                header('Refresh: 0; url=index.php');
            }
        }
        fclose($fs);
    }
}

if (isset($_POST["button"]) && $_POST["button"] == "Log_Out"){
    unset($_COOKIE['username']); 
    setcookie('username', '', time()-3600, '/'); 
    header('Refresh: 0; url=index.php');
}
else if (isset($_COOKIE["username"]) && isset($_POST["playlist"]) && isset($_POST["button"])) {
    $current_user = $_COOKIE["username"];
    $user_directory = "data/$current_user/";
    $playlist = $_POST["playlist"];
    if (str_contains($_POST["button"], "View_Playlist")){
        $playlist = explode("*|*", $_POST["button"])[1];
    }

    if ($_POST["button"] == "Create_Playlist"){
        if (checkFile($user_directory, $playlist)){
            alert($playlist . ' already exists!');
        }
        else{
            create_file($user_directory . $playlist);
        }
        view_playlist($playlist);
    }
    else if ($_POST["button"] == "Delete_Playlist"){
        if (checkFile($user_directory, $playlist)){
            delete_file($user_directory . $playlist);
        }
        else{
            alert($playlist . " doesn't exist!");
        }
    }
    else if (str_contains($_POST["button"], "View_Playlist")){
        if (checkFile($user_directory, $playlist)){
            view_playlist($playlist);
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

        view_playlist($playlist);
    }
    else if ($_POST["button"] == "Delete_Song"){
        $id = get_id($_POST["url"]);

        if (checkSong($user_directory . $playlist, $id) >= 0){
            delete_song($user_directory . $playlist, $id);
        }
        else{
            alert($song . " doesn't exist!");
        }

        view_playlist($playlist);
    }
    else if (str_contains($_POST["button"], "Play_Song")){
        $start_index = checkSong($user_directory . $playlist, explode("*|*", $_POST["button"])[1]);

        play_playlist($user_directory . $playlist, $start_index);
        view_playlist($playlist);
    }
    else if ($_POST["button"] == "Play_Random"){
        play_playlist($user_directory . $playlist, -1);
        view_playlist($playlist);
    }

    sidebar($playlist);
}
else if (isset($_COOKIE["username"]) && !isset($_POST["playlist"])) { 
    sidebar("");
} ?>

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