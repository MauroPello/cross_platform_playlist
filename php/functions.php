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
    $fs = fopen($filename, "r") or die("Failed to open file");
    $songs = explode("\n", stream_get_contents($fs));
    array_pop($songs);
    fclose($fs);

    for($i = 0; $i < count($songs); $i++){
        if (explode("*|*", $songs[$i])[1] == $song_id){
            return $i;
        } 
    }
    return -1;
}

function alert($msg){
    echo '<script type="text/javascript">alert("' . $msg . '");</script>';
} 

function open_link($url){
    echo '<script type="text/javascript">window.open("' . $url . '","_self");</script>';
}

function get_playlists($user_directory){
    return array_diff(scandir($user_directory), array(".", ".."));
}

function get_songname($url){
    return $url;
}

function get_platform($url){
    if (str_contains($url, "https://www.youtube.com/watch?v=") || str_contains($url, "https://youtu.be/")){
        return "yt";
    }
    else if (str_contains($url, "https://open.spotify.com/embed/track/") || str_contains($url, "https://open.spotify.com/embed/track/") || str_contains($url, "spotify:track:")){
        return "sp";
    }
    else if (str_contains($url, "https://soundcloud.com/")){
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

function display_songs($filename){
    $fs = fopen($filename, "r") or die("Failed to open file");
    $songs = explode("\n", stream_get_contents($fs));
    array_pop($songs);
    fclose($fs);
 
    $contents = "";
    foreach($songs as $song){
        $tmp = explode("*|*", $song);
        if ($tmp[2] == "yt"){
            $contents  = $contents . '<a href="https://youtu.be/' . $tmp[1] . '" target="_blank">' . $tmp[0] . '</a><br>';
        }
        else if ($tmp[2] == "sp"){
            $contents  = $contents . '<a href="https://open.spotify.com/track/' . $tmp[1] . '" target="_blank">' . $tmp[0] . '</a><br>';
        }
        else if ($tmp[2] == "sc"){
            $contents  = $contents . '<a href="https://soundcloud.com/' . $tmp[1] . '" target="_blank">' . $tmp[0] . '</a><br>';
        }
    }

    echo '<br><p class="filecontent">' . $contents . '</p>';
}

function append_to_file($filename, $text){
    file_put_contents($filename, $text . "\n", FILE_APPEND);
} 

function delete_song($filename, $song_id){
    $fs = fopen($filename, "r") or die("Failed to open file");
    $songs = explode("\n", stream_get_contents($fs));
    fclose($fs);

    $to_write = "";
    foreach($songs as $song){
        if ($song != "" && explode("*|*", $song)[1] !== $song_id){
            $to_write = $to_write . $song . "\n";
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
} ?>