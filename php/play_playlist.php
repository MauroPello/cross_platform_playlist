<?php function play_playlist ($playlist, $start_index) {
    $fs = fopen($playlist, "r") or die("Failed to open file");
    $songs = explode("\n", stream_get_contents($fs));
    array_pop($songs);
    fclose($fs);

    $numbers = range(0, count($songs) - 1);
    $indexes = array();
    if ($start_index == -1){
        shuffle($numbers);
        $indexes = $numbers;
    }
    else{
        foreach($numbers as $number){
            array_push($indexes, ($number + $start_index) % count($songs));
        }
    }

    $ids = array();
    $platforms = array();
    $first_youtube_song = "";
    $first_spotify_song = "";
    $first_soundcloud_song = "";
    for($i = 0; $i < count($songs); $i++){
        $song = explode("*|*", $songs[$indexes[$i]]);
        $id = $song[1];
        array_push($ids, $id);
        $platform = $song[2];
        array_push($platforms, $platform);
        if ($first_youtube_song == "" && $platform == "yt"){
            $first_youtube_song = $id;
        }
        else if ($first_spotify_song == "" && $platform == "sp"){
            $first_spotify_song = $id;
        }
        else if ($first_soundcloud_song == "" && $platform == "sc"){
            $first_soundcloud_song = $id;
        }
    }
    ?>
    <body onload='start_playlist(<?php echo json_encode($platforms); ?>, <?php echo json_encode($ids); ?>)'>
    <div class="container">
    <div class="videoWrapper" style="display: none;" id='youtube_player-wrapper' >
        <iframe id='youtube_player' src="http://www.youtube.com/embed/<?php echo $first_youtube_song; ?>?html5=1&enablejsapi=1" frameborder="0"></iframe>
    </div>
    <div class="videoWrapper" style="display: none;" id='spotify_player-wrapper'>
        <iframe id='spotify_player' src="https://open.spotify.com/embed/track/<?php echo $first_spotify_song; ?>" frameborder="0" allowtransparency="true"></iframe>
    </div>
    <div class="videoWrapper" style="display: none;" id='soundcloud_player-wrapper'>
        <iframe id='soundcloud_player' src="https://w.soundcloud.com/player/?url=https://soundcloud.com/<?php echo $first_soundcloud_song; ?>" scrolling="no" frameborder="no"></iframe>
    </div>
    </div>
    <body>
<?php } ?>