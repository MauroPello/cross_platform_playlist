<?php
function new_user($username, $password){
    $db = new SQLite3("data/database.sqlite");
    $username = sqlite_escape_string($username);
    $password = sqlite_escape_string($password);
    
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
    $db = new SQLite3("data/database.sqlite");
    $username = sqlite_escape_string($username);
   
    $res = $db->query(" SELECT * FROM users WHERE users.user_name == '$username' ");

    $users = [];
    while ($row = $res->fetchArray(SQLITE3_TEXT)) {
        array_push($users, [$row["user_name"], $row['password']]);
    }

    $db->close();
    if (count($users) == 0){
        return FALSE;
    }
    return TRUE;
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

function new_playlist($username, $playlist){
    $db = new SQLite3("data/database.sqlite");
    $username = sqlite_escape_string($username);
    $playlist = sqlite_escape_string($playlist);

    $db->exec(" INSERT INTO playlists (playlist_name, user_name) VALUES('$playlist', '$username') ");

    $db->close();
}

function delete_playlist($username, $playlist){
    $db = new SQLite3("data/database.sqlite");
    $username = sqlite_escape_string($username);
    $playlist = sqlite_escape_string($playlist);
    $playlist_id = get_playlist_id($username, $playlist);

    delete_all_songs($playlist_id);
    $db->exec(" DELETE FROM playlists WHERE playlists.playlist_name == '$playlist' AND playlists.user_name == '$username' ");

    $db->close();
}

function rename_playlist($username, $playlist, $name){
    $db = new SQLite3("data/database.sqlite");
    $username = sqlite_escape_string($username);
    $playlist = sqlite_escape_string($playlist);
    $name = sqlite_escape_string($name);

    $db->exec(" UPDATE playlists SET playlist_name = '$name' WHERE playlists.playlist_name == '$playlist' AND playlists.user_name == '$username' ");

    $db->close();
}

function get_playlists($username){
    $db = new SQLite3("data/database.sqlite");
    $username = sqlite_escape_string($username);

    $res = $db->query(" SELECT * FROM playlists WHERE playlists.user_name == '$username' ");

    $playlists = [];
    while ($row = $res->fetchArray(SQLITE3_TEXT)) {
        array_push($playlists, $row['playlist_name']);
    }

    $db->close();
    return $playlists;
}

function get_playlist_id($username, $playlist){
    $db = new SQLite3("data/database.sqlite");
    $username = sqlite_escape_string($username);
    $playlist = sqlite_escape_string($playlist);

    $res = $db->query(" SELECT playlists.playlist_id FROM playlists WHERE playlists.playlist_name == '$playlist' AND playlists.user_name == '$username' ");

    $id = -1;
    while ($row = $res->fetchArray(SQLITE3_TEXT)) {
        $id = intval($row["playlist_id"]);
    }

    $db->close();
    return $id;
}

function check_playlist($username, $playlist){
    $db = new SQLite3("data/database.sqlite");
    $username = sqlite_escape_string($username);
    $playlist = sqlite_escape_string($playlist);

    $res = $db->query(" SELECT * FROM playlists WHERE playlists.user_name == '$username' AND playlists.playlist_name == '$playlist' ");

    $playlists = [];
    while ($row = $res->fetchArray(SQLITE3_TEXT)) {
        array_push($playlists, $row['playlist_name']);
    }

    $db->close();
    if (count($playlists) == 0){
        return FALSE;
    }
    return TRUE;
}

function new_song($username, $playlist, $name, $id, $platform){
    $db = new SQLite3("data/database.sqlite");
    $username = sqlite_escape_string($username);
    $playlist = sqlite_escape_string($playlist);
    $name = sqlite_escape_string($name);
    $id = sqlite_escape_string($id);
    $platform = sqlite_escape_string($platform);
    $playlist_id = get_playlist_id($username, $playlist);

    $index = 0;
    $res = $db->query(" SELECT COUNT(songs.song_id) as song_count FROM songs WHERE songs.playlist_id == '$playlist_id' ");
    while ($row = $res->fetchArray(SQLITE3_TEXT)) {
        $index = intval($row["song_count"]);
    }

    $db->exec(" INSERT INTO songs VALUES( '$id' , '$playlist_id' , '$name' , '$platform' , $index) ");

    $db->close();
}

function delete_song($username, $playlist, $id){
    $db = new SQLite3("data/database.sqlite");
    $username = sqlite_escape_string($username);
    $playlist = sqlite_escape_string($playlist);
    $id = sqlite_escape_string($id);
    $playlist_id = get_playlist_id($username, $playlist);

    $db->exec(" UPDATE songs SET song_index = song_index - 1 WHERE playlist_id == '$playlist_id' AND song_index > (SELECT song_index FROM songs WHERE song_id == '$id' AND playlist_id == '$playlist_id') ");

    $db->exec(" DELETE FROM songs WHERE (songs.playlist_id == '$playlist_id' AND songs.song_id == '$id') ");

    $db->close();
}

function delete_all_songs($playlist_id){
    $db = new SQLite3("data/database.sqlite");

    $db->exec(" DELETE FROM songs WHERE songs.playlist_id == '$playlist_id' ");

    $db->close();
}

function get_songs($username, $playlist){
    $db = new SQLite3("data/database.sqlite");
    $username = sqlite_escape_string($username);
    $playlist = sqlite_escape_string($playlist);
    $playlist_id = get_playlist_id($username, $playlist);

    $res = $db->query(" SELECT * FROM songs WHERE songs.playlist_id == '$playlist_id' ORDER BY songs.song_index ASC ");

    $songs = [];
    while ($row = $res->fetchArray(SQLITE3_TEXT)) {
        array_push($songs, [$row["song_name"], $row['song_id'], $row['platform'], $row['song_index']]);
    }

    $db->close();
    return $songs;
}

function check_song($username, $playlist, $id){
    $db = new SQLite3("data/database.sqlite");
    $username = sqlite_escape_string($username);
    $playlist = sqlite_escape_string($playlist);
    $id = sqlite_escape_string($id);
    $playlist_id = get_playlist_id($username, $playlist);
    
    $res = $db->query(" SELECT songs.song_index FROM songs WHERE songs.playlist_id == '$playlist_id' AND songs.song_id == '$id' ");

    $id = -1;
    while ($row = $res->fetchArray(SQLITE3_TEXT)) {
        $id = intval($row["song_index"]);
    }

    $db->close();
    return $id;
}

function rearrange_songs($username, $playlist, $ids){
    $db = new SQLite3("data/database.sqlite");
    $username = sqlite_escape_string($username);
    $playlist = sqlite_escape_string($playlist);
    $playlist_id = get_playlist_id($username, $playlist);

    for($i = 0; $i < count($ids); $i++){
        $song_id = sqlite_escape_string($ids[$i]);
        $db->exec(" UPDATE songs SET song_index = '$i' WHERE songs.playlist_id == '$playlist_id' AND songs.song_id == '$song_id' ");
    }

    $db->close();
}

function sqlite_escape_string($string){
    return SQLite3::escapeString($string);
}
?>