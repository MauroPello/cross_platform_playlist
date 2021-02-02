<?php
function checkUsername ($accounts, $username) {
    foreach ($accounts as $account){
        if (explode("*|*", $account)[0] == $username){
            return TRUE;
        } 
    }
    return FALSE;
}

function checkPassword ($accounts, $username, $password) {
    foreach ($accounts as $account){
        $tmp = explode("*|*", $account);
        if ($tmp[0] == $username && password_verify($password, $tmp[1])){
            return TRUE;
        } 
    }
    return FALSE;
}

function checkFile ($directory, $filename){
    $files = array_diff(scandir($directory), array(".", ".."));
    foreach ($files as $file){
        if ($file == $filename){
            return TRUE;
        }
    }
    return FALSE;
}

function checkSong($filename, $song_id){
    $songs = get_songs($filename);

    for($i = 0; $i < count($songs); $i++){
        if (explode("*|*", $songs[$i])[1] == $song_id){
            return $i;
        } 
    }
    return -1;
}

function alert($msg){
    echo '<div class="alert alert-danger alert-dismissible" style="z-index: 10000; margin-top: 1%; margin-left: 10%; margin-right: 10%;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' . $msg . '</div>';
}

function get_songs($filename){
    $fs = fopen($filename, "r") or die("Failed to open file");
    $songs = explode("\n", stream_get_contents($fs));
    array_pop($songs);
    fclose($fs);

    return $songs;
}

function get_accounts(){
    $fs = fopen("data/accounts.txt", "r") or die("Failed to open file");
    $accounts = explode("\n", stream_get_contents($fs));
    array_pop($accounts);
    fclose($fs);

    return $accounts;
}

function new_account($username, $password){
    $fs = fopen("data/accounts.txt", "w") or die("Failed to open file");
    fwrite($fs, $username . "*|*" . $password . "\n");
    fclose($fs);
}

function get_playlists($user_directory){
    return array_diff(scandir($user_directory), array(".", ".."));
}

function get_songname($url){
    $name = "";
    if (get_platform($url) == "yt"){
        $request_url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=" . get_id($url) . "&key=AIzaSyBaZ6kbZDxm2XQo7w10qXj_51qVAqGDLGQ";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
    
        curl_close($ch);
        $data = json_decode($response);
        $value = json_decode(json_encode($data), true);
        $name = $value['items'][0]['snippet']['title'];
    }
    else if (get_platform($url) == "sp"){
        $request_url = "https://api.spotify.com/v1/tracks/" . get_id($url) . "";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer BQDQB-x4JUTxdFLebSh1KSe6RCaT-Fw0cS4Mfd4HGR0hTj2CStU70mOgRzdUD9cRdjMFDz4o_q8RwBL9Je_4oDxQBN5aQiBzVjFcDuqgAltkAHT3bmSyNUoBDnQoV_LAhJ9DFUK_dW_RhalAJ8ul2tHxrk4N2oU5tfw1qemROmNdBg';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
    
        curl_close($ch);
        $data = json_decode($response);
        $value = json_decode(json_encode($data), true);
        $name = $value['artists'][0]["name"] . " - " . $value['name'];
    }
    else if (get_platform($url) == "sc"){
        $request_url = "https://api-widget.soundcloud.com/resolve?url=https://soundcloud.com/" . get_id($url) . "&format=json&client_id=TaTmd2ARXgnp20a7BQJwuZ8xGFbrYgz5";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
    
        curl_close($ch);
        $data = json_decode($response);
        $value = json_decode(json_encode($data), true);
        $name = $value['title'];
    }

    return $name;
}

function get_platform($url){
    if (strpos($url, "https://www.youtube.com/watch?v=") !== false || strpos($url, "https://youtu.be/") !== false){
        return "yt";
    }
    else if (strpos($url, "https://open.spotify.com/track/") !== false || strpos($url, "https://open.spotify.com/embed/track/") !== false || strpos($url, "spotify:track:" !== false)){
        return "sp";
    }
    else if (strpos($url, "https://soundcloud.com/") !== false){
        return "sc";
    }
}

function get_id($url){
    $platform = get_platform($url);

    if ($platform == "yt"){
        $url = str_replace("https://www.youtube.com/watch?v=", '', $url);
        $url = str_replace("https://youtu.be/", '', $url);
        $url = explode("&", $url)[0];
    }
    else if ($platform == "sp"){
        $url = str_replace("https://open.spotify.com/embed/track/", '', $url);
        $url = str_replace("https://open.spotify.com/track/", '', $url);
        $url = str_replace("spotify:track:", '', $url);
        $url = explode("?", $url)[0];
    }
    else if ($platform == "sc"){
        $url = str_replace("https://soundcloud.com/", '', $url);
        $url = explode("?", $url)[0];
    }

    return $url;
}

function get_url($id, $platform){
    $url = "";

    if ($platform == "yt"){
        $url = "https://youtu.be/" . $id;
    }
    else if ($platform == "sp"){
        $url = "https://open.spotify.com/track/" . $id;
    }
    else if ($platform == "sc"){
        $url = "https://soundcloud.com/" . $id;
    }

    return $url;
}

function rearrange_file($filename, $indexes){
    $songs = get_songs($filename);

    $to_write = "";
    for($i = 0; $i < count($songs); $i++){
        $to_write .= $songs[$indexes[$i]] . "\n";
    }

    $fs = fopen($filename, "w") or die("Failed to open file");
    fwrite($fs, $to_write);
    fclose($fs);
}

function append_to_file($filename, $text){
    file_put_contents($filename, $text . "\n", FILE_APPEND);
} 

function delete_song($filename, $song_id){
    $songs = get_songs($filename);

    $to_write = "";
    foreach($songs as $song){
        if ($song != "" && explode("*|*", $song)[1] !== $song_id){
            $to_write .= $song . "\n";
        } 
    }

    $fs = fopen($filename, "w") or die("Failed to open file");
    fwrite($fs, $to_write);
    fclose($fs);
}

function create_file($filename){
    $tmp = fopen($filename, "w");
    fclose($tmp);
} 

function delete_file($filename){
    unlink($filename);
}

function test_input($data) {
    $tmp = $data;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if ($tmp == $data){
        return TRUE;
    }
    return FALSE;
} 

function update_session_activity(){
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1200)) {
        session_unset();     // unset $_SESSION variable for the run-time
        session_destroy();   // destroy session data in storage
    }
    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
    } else if (time() - $_SESSION['CREATED'] > 1200) {
        session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
        $_SESSION['CREATED'] = time();  // update creation time
    } 
}
?>