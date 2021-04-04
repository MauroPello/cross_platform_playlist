<?php
function get_songname($id, $platform){
    $name = "Name not Found";
    if ($platform == "yt"){
        $request_url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=$id&key=";
        $yt_keys = ["AIzaSyBaZ6kbZDxm2XQo7w10qXj_51qVAqGDLGQ", "AIzaSyDssmgRGF29D1ipsz-s_wispDJS0nRi2qM"];
        foreach($yt_keys as $key){
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $request_url . $key);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
        
            curl_close($ch);
            $data = json_decode($response);
            $value = json_decode(json_encode($data), true);
            if(!isset($value['error'])){
                $name = $value['items'][0]['snippet']['title'];
                break;
            }
        }
    }
    else if ($platform == "sp"){
        $request_url = "https://api.spotify.com/v1/tracks/$id";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . get_new_spotify_token();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
    
        curl_close($ch);
        $data = json_decode($response);
        $value = json_decode(json_encode($data), true);
        $name = $value['artists'][0]["name"] . " - " . $value['name'];
    }
    else if ($platform == "sc"){
        $request_url = "https://api-widget.soundcloud.com/resolve?url=https://soundcloud.com/$id&format=json&client_id=TaTmd2ARXgnp20a7BQJwuZ8xGFbrYgz5";
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

function get_new_spotify_token(){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=refresh_token&refresh_token=AQDAieTwvYzjkQGqCm21LyGYvHLQ2fLfhcDmz7cBA70cAoE9l6rsaqHjnt1yJlmlA0v9SJ0iojIPy72-w-Q4To-r-5OXoyPDkGLzxPuxI5nOnBs9VGavqx0UdYGFbUxhd00");
    $headers = array();
    $headers[] = 'Authorization: Basic NDk4ZDk5YzI0YjY3NGJhYmJmNzYwMzQzOGJiNjk2Y2Y6NWMxZGE5NjA2OWU0NGE2MGJkZGNkMWJhOGJiMmJkOTA=';
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);

    curl_close($ch);
    $data = json_decode($response);
    $value = json_decode(json_encode($data), true);
    return $value['access_token'];
}

function search_ytsong($name){
    $name = urlencode($name);
    $request_url = "https://youtube.googleapis.com/youtube/v3/search?part=snippet&maxResults=1&q=$name&safeSearch=none&key=";
    $yt_keys = ["AIzaSyBaZ6kbZDxm2XQo7w10qXj_51qVAqGDLGQ", "AIzaSyDssmgRGF29D1ipsz-s_wispDJS0nRi2qM"];
    foreach($yt_keys as $key){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $request_url . $key);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
    
        curl_close($ch);
        $data = json_decode($response);
        $value = json_decode(json_encode($data), true);
        if(!isset($value['error'])){
            return $value['items'][0]['id']['videoId'];
        }
    }
}

function is_playlist($url){
    if (strpos($url, "https://www.youtube.com/playlist?list=") !== false){
        return TRUE;
    }
    else if (strpos($url, "https://open.spotify.com/playlist/") !== false || strpos($url, "https://open.spotify.com/embed/playlist/") !== false || strpos($url, "spotify:playlist:") !== false){
        return TRUE;
    }
    else if (strpos(get_id($url, get_platform($url)), "/sets/") !== false){
        return TRUE;
    }
    return FALSE;
}

function get_song_ids($id, $platform){
    $ids = array();
    
    if ($platform == "yt"){
        $next_page = "";
        while (TRUE){
            $request_url = "https://youtube.googleapis.com/youtube/v3/playlistItems?part=snippet&pageToken=$next_page&maxResults=50&playlistId=$id&key=AIzaSyBaZ6kbZDxm2XQo7w10qXj_51qVAqGDLGQ";
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
            foreach ($value['items'] as $item){
                array_push($ids, $item['snippet']['resourceId']['videoId']);
            }
            if (!isset($value['nextPageToken'])){
                break;
            }
            $next_page = $value['nextPageToken'];
        }
    }
    else if ($platform == "sp"){
        $request_url = "https://api.spotify.com/v1/playlists/$id/tracks";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . get_new_spotify_token();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
    
        curl_close($ch);
        $data = json_decode($response);
        $value = json_decode(json_encode($data), true);
        foreach ($value['items'] as $item){
            array_push($ids, $item['track']['id']);
        }
    }
    else if ($platform == "sc"){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api-widget.soundcloud.com/resolve?url=https://soundcloud.com/$id&client_id=TaTmd2ARXgnp20a7BQJwuZ8xGFbrYgz5");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response);
        $value = json_decode(json_encode($data), true);
        foreach ($value['tracks'] as $track){
            if (isset($track['permalink_url'])){
                array_push($ids, get_id($track['permalink_url'], $platform));
            }
            else{
                $ch = curl_init();
                $track_id = $track['id'];
                curl_setopt($ch, CURLOPT_URL, "https://api-widget.soundcloud.com/tracks/$track_id?client_id=TaTmd2ARXgnp20a7BQJwuZ8xGFbrYgz5");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($ch);
                curl_close($ch);
                $data = json_decode($response);
                $value = json_decode(json_encode($data), true);
                array_push($ids, get_id($value['permalink_url'], $platform));
            }
        }
    }

    return $ids;
}

function get_platform($url){
    if (strpos($url, "https://www.youtube.com/") !== false || strpos($url, "https://youtu.be/") !== false){
        return "yt";
    }
    else if (strpos($url, "https://open.spotify.com/") !== false || strpos($url, "spotify:") !== false){
        return "sp";
    }
    else if (strpos($url, "https://soundcloud.com/") !== false){
        return "sc";
    }
}

function get_id($url, $platform){
    if ($platform == "yt"){
        $url = str_replace("https://www.youtube.com/watch?v=", '', $url);
        $url = str_replace("https://www.youtube.com/playlist?list=", '', $url);
        $url = str_replace("https://youtu.be/", '', $url);
        $url = explode("&", $url)[0];
    }
    else if ($platform == "sp"){
        $url = str_replace("https://open.spotify.com/embed/track/", '', $url);
        $url = str_replace("https://open.spotify.com/track/", '', $url);
        $url = str_replace("spotify:track:", '', $url);
        $url = str_replace("https://open.spotify.com/embed/playlist/", '', $url);
        $url = str_replace("https://open.spotify.com/playlist/", '', $url);
        $url = str_replace("spotify:playlist:", '', $url);
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

function alert($msg){
    echo '<div class="alert alert-danger alert-dismissible" style="z-index: 10000; margin-top: 1%; margin-left: 10%; margin-right: 10%;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' . $msg . '</div>';
}

function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
} 

function update_session_activity(){
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3000)) {
        session_unset();     // unset $_SESSION variable for the run-time
        session_destroy();   // destroy session data in storage
        
        header('Refresh: 0; url=index.php');
    }
    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
    } else if (time() - $_SESSION['CREATED'] > 3000) {
        session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
        $_SESSION['CREATED'] = time();  // update creation time
    } 
}

function encrypt($plaintext){
    return openssl_encrypt($plaintext, "AES-128-CTR", "PORCODIOMADONNACAGNALAIDA", 0, "69696969696969");
}

function decrypt($ciphertext){
    return openssl_decrypt($ciphertext, "AES-128-CTR", "PORCODIOMADONNACAGNALAIDA", 0, "69696969696969");
}
?>