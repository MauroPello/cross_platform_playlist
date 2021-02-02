<?php 
require "php/functions.php";
require "php/database.php";
require "php/login_form.php";
require "php/sidebar.php";
require "php/play_playlist.php";
require "php/view_playlist.php";

session_start();
update_session_activity(); 
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
    <title>All Around Playlists</title>
</head>

<body class="text-center">
<?php 
if (!isset($_SESSION["username"])) {
    if ($_SERVER["REQUEST_METHOD"] == "GET"){
        login_form();
    }
    else if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["button"])){
        if ($_POST['button'] == "Login"){
            if (check_username($_POST["username"])) {
                if (check_password($_POST["username"], $_POST["password"])) {
                    $_SESSION['username'] = $_POST["username"];
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
            if (check_username($_POST["username"])) {
                alert($_POST["username"] . " already exists!");
                login_form();
            }
            else if (test_input($_POST["username"]) && test_input($_POST["password"])){
                $_SESSION['username'] = $_POST["username"];
                new_user($_POST['username'], password_hash($_POST["password"], PASSWORD_DEFAULT));
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
        if (check_playlist($_SESSION["username"], $_POST["playlist"])){
            alert($_POST["playlist"] . ' already exists!');
        }
        else if (test_input($_POST["playlist"])){
            new_playlist($_SESSION["username"], $_POST["playlist"]);
        }

        $_SESSION["playlist"] = $_POST["playlist"];
        view_playlist();
    }
    else if ($_POST["button"] == "Delete_Playlist"){
        if (check_playlist($_SESSION["username"], $_POST["playlist"]) && test_input($_POST["playlist"])){
            delete_playlist($_SESSION["username"], $_POST["playlist"]);
        }
        else{
            alert($_POST["playlist"] . " doesn't exist!");
        }
    }
    else if (strpos($_POST["button"], "View_Playlist") !== false){
        $playlist = explode("*|*", $_POST["button"])[1];
        if (check_playlist($_SESSION["username"], $playlist)){
            $_SESSION["playlist"] = $playlist;
            view_playlist();
        }
        else{
            alert($playlist . " doesn't exist!");
        }
    }
    else if ($_POST["button"] == "Add_Song"){
        $song = get_songname($_POST["url"]);
        $platform = get_platform($_POST["url"]);
        $id = get_id($_POST["url"]);

        if (check_song($_SESSION["username"], $_SESSION["playlist"], $id) >= 0){
            alert($song . " already exists!");
        }
        else{
            new_song($_SESSION["username"], $_SESSION["playlist"], $song, $id, $platform);
        }

        view_playlist();
    }
    else if ($_POST["button"] == "Delete_Song"){
        $id = get_id($_POST["url"]);

        if (check_song($_SESSION["username"], $_SESSION["playlist"], $id) >= 0){
            delete_song($_SESSION["username"], $_SESSION["playlist"], $id);
        }
        else{
            alert($song . " doesn't exist!");
        }

        view_playlist();
    }
    else if (strpos($_POST["button"], "Play_Song") !== false){
        $start_index = check_song($_SESSION["username"], $_SESSION["playlist"], explode("*|*", $_POST["button"])[1]);

        play_playlist($start_index);
        view_playlist();
    }
    else if ($_POST["button"] == "Play_Random"){
        play_playlist(-1);
        view_playlist();
    }
    else if ($_POST["button"] == "Sort"){
        $indexes = [];
        $flag = false;
        foreach ($_POST as $key => $value){
            if (strpos($key, "index") !== false){
                if (in_array(intval($value), $indexes)){
                    $flag = $flag || true;
                }
                else{
                    array_push($indexes, intval($value));
                }
            }
        }
        if ($flag) {
            alert("Duplicate Indexes!");
            view_playlist();
        }
        else{
            rearrange_songs($_SESSION["username"], $_SESSION["playlist"], $indexes);
            view_playlist();
        }
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
<script type='text/javascript' src="js/scripts.js"></script>
</body>
</html>