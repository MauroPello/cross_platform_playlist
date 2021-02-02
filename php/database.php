<?php
function new_user($username, $password){
    $db = new SQLite3("data/database.sqlite");

    $db->exec(" INSERT INTO users VALUES('$username', '$password') ");

    $db->close();
}

function get_users(){
    $db = new SQLite3("data/database.sqlite");
    
    $res = $db->query(" SELECT * FROM users ");

    $users = [];
    while ($row = $res->fetchArray(SQLITE3_TEXT)) {
        array_push($users, [$row["user_name"], $row['password']]);
    }

    $db->close();
    return $users;
}

function check_username($username) {
    $users = get_users();

    foreach ($users as $user){
        if ($user[0] == $username){
            return TRUE;
        } 
    }
    return FALSE;
}

function check_password($username, $password) {
    $users = get_users();

    foreach ($users as $user){
        if ($user[0] == $username && password_verify($password, $user[1])){
            return TRUE;
        } 
    }
    return FALSE;
}

function new_playlist($user, $playlist){
    $db = new SQLite3("data/database.sqlite");

    $db->exec(" INSERT INTO playlists (playlist_name, user_name) VALUES('$playlist', '$user') ");

    $db->close();
}

function delete_playlist($user, $playlist){
    $db = new SQLite3("data/database.sqlite");
    $playlist_id = get_playlist_id($user, $playlist);

    delete_all_songs($playlist_id);
    $db->exec(" DELETE FROM playlists WHERE (playlists.playlist_name == '$playlist' AND playlists.user_name == '$user') ");

    $db->close();
}

function get_playlists($user){
    $db = new SQLite3("data/database.sqlite");
    
    $res = $db->query(" SELECT * FROM playlists WHERE user_name == '$user' ");

    $playlists = [];
    while ($row = $res->fetchArray(SQLITE3_TEXT)) {
        array_push($playlists, $row['playlist_name']);
    }

    $db->close();
    return $playlists;
}

function get_playlist_id($user, $playlist){
    $db = new SQLite3("data/database.sqlite");
    
    $query = $db->prepare(" SELECT playlists.playlist_id FROM playlists WHERE (playlists.playlist_name == :playlist_name AND playlists.user_name == :user_name) ");
    $query->bindValue(':playlist_name', $playlist, SQLITE3_TEXT);
    $query->bindValue(':user_name', $user, SQLITE3_TEXT);

    $res = $query->execute();
    $id = $res->fetchArray(SQLITE3_TEXT)["playlist_id"]; 

    $db->close();
    return $id;
}

function check_playlist($user, $playlist){
    $playlists = get_playlists($user);

    foreach ($playlists as $item){
        if ($item == $playlist){
            return TRUE;
        }
    }
    return FALSE;
}

function new_song($user, $playlist, $name, $id, $platform){
    $db = new SQLite3("data/database.sqlite");
    $playlist_id = get_playlist_id($user, $playlist);

    $res = $db->query(" SELECT MAX(songs.song_index) as max_index FROM songs WHERE (songs.playlist_id == '$playlist_id') ");
    $index = intval($res->fetchArray(SQLITE3_TEXT)["max_index"]) + 1; 

    $db->exec(" INSERT INTO songs VALUES( '$id' , '$playlist_id' , '$name' , '$platform' , $index) ");

    $db->close();
}

function delete_song($user, $playlist, $id){
    $db = new SQLite3("data/database.sqlite");
    $playlist_id = get_playlist_id($user, $playlist);
    $songs = get_songs($user, $playlist);
    $song_index = count($songs);

    foreach($songs as $song){
        if ($song[1] == $id){
            $song_index = $song[3];
        }
        if ($song[3] > $song_index){
            $song[3] = $song[3] - 1;
        }
        $index = $song[3];
        $song_id = $song[1];
        $db->exec(" UPDATE songs SET song_index = '$index' WHERE songs.playlist_id == '$playlist_id' AND songs.song_id == '$song_id' ");
    }

    $db->exec(" DELETE FROM songs WHERE (songs.playlist_id == '$playlist_id' AND songs.song_id == '$id') ");

    $db->close();
}

function delete_all_songs($playlist_id){
    $db = new SQLite3("data/database.sqlite");

    $db->exec(" DELETE FROM songs WHERE (songs.playlist_id == '$playlist_id') ");

    $db->close();
}

function get_songs($user, $playlist){
    $db = new SQLite3("data/database.sqlite");
    $playlist_id = get_playlist_id($user, $playlist);

    $res = $db->query(" SELECT * FROM songs WHERE (songs.playlist_id == '$playlist_id') ORDER BY songs.song_index ASC ");

    $songs = [];
    while ($row = $res->fetchArray(SQLITE3_TEXT)) {
        array_push($songs, [$row["song_name"], $row['song_id'], $row['platform'], $row['song_index']]);
    }

    $db->close();
    return $songs;
}

function check_song($user, $playlist, $id){
    $songs = get_songs($user, $playlist);

    for($i = 0; $i < count($songs); $i++){
        if ($songs[$i][1] == $id){
            return $i;
        } 
    }
    return -1;
}

function rearrange_songs($user, $playlist, $indexes){
    $db = new SQLite3("data/database.sqlite");
    $songs = get_songs($user, $playlist);
    $playlist_id = get_playlist_id($user, $playlist);

    for($i = 0; $i < count($songs); $i++){
        $song_id = $songs[$i][1];
        $index = $indexes[$i];
        $db->exec(" UPDATE songs SET song_index = '$index' WHERE songs.playlist_id == '$playlist_id' AND songs.song_id == '$song_id' ");
    }

    $db->close();
}
?>