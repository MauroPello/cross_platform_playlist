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

function checkSong($filename, $songname){
    $fs = fopen($filename, "r") or die("Failed to open file");
    $songs = explode("\n", stream_get_contents($fs));
    array_pop($songs);
    fclose($fs);

    foreach($songs as $song){
        $tmp = explode("*|*", $song);
        if ($tmp[0] == $songname){
            return TRUE;
        } 
    }
    return FALSE;
}

function alert($msg){
    echo '<script type="text/javascript">alert("' . $msg . '");</script>';
} 

function display_filenames($path){
    echo '<h4 style="text-align: center;">All the Playlists</h4>';
    echo '<p class="filecontent">' . implode("\n", array_diff(scandir($path), array(".", ".."))) . '</p>';
}

function display_songs($filename, $header){
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

    echo '<br><h4 style="text-align: center;">' . $header . '</h4>';
    echo '<p class="filecontent">' . $contents . '</p>';
}

function append_to_file($filename, $text){
    file_put_contents($filename, $text . "\n", FILE_APPEND);
} 

function delete_song($filename, $songname){
    $fs = fopen($filename, "r") or die("Failed to open file");
    $songs = explode("\n", stream_get_contents($fs));
    fclose($fs);

    $to_write = "";
    foreach($songs as $song){
        if (explode("*|*", $song)[0] != $songname && $song != ""){
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