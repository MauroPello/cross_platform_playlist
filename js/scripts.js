var count = -1;
var yt_player = null;
var sp_player = null;
var sc_player = null;
var songs = [];

function start_playlist(platforms, ids){
    songs.ids = ids;
    songs.platforms = platforms;
    count = -1;
    next();
    document.getElementById("volumeRange").addEventListener("change", function(){
        setVolume(songs.platforms[count], this.value);
    });
}

function next(){
    stop();

    mediaControllerToggle(true);
    count += 1;
    count = count % songs.ids.length;

    if (songs.platforms[count] == "yt"){
        if (yt_player == null){
            yt_player = new YT.Player('youtube_player', {
                playerVars: {autoplay: 1, showinfo : 0},
                events: { onReady: onPlayerReady }
            });

            yt_player.addEventListener("onStateChange", function(state){
                if(state.data === 0){
                    next();
                }
            });
        }
        else{
            yt_player.loadVideoById(songs.ids[count], 0);
            yt_player.playVideo();
        }

        var yt_div = document.getElementById('youtube_player');
        yt_div.style.display = "block";
    }
    else if (songs.platforms[count] == "sp"){
        if (sp_player == null){
            // start spotify player
        }
        // next spotify song
        
        var sp_div = document.getElementById('spotify_player');
        sp_div.style.display = "block";
    }
    else if (songs.platforms[count] == "sc"){
        var sc_options = [];
        sc_options.auto_play = true;
        sc_options.color = "#FF5500";
        sc_options.buying = true;
        sc_options.sharing = true;
        sc_options.download = true;
        sc_options.show_artwork = true;
        sc_options.show_playcount = true;
        sc_options.show_user = true;
        sc_options.single_active = true;
        sc_options.visual = true;
        sc_options.hide_related = true;
        sc_options.show_comments = true;
        sc_options.show_reposts = true;
        sc_options.show_teaser = true;
        
        if (sc_player == null){
            SC.initialize({
                client_id: 'TaTmd2ARXgnp20a7BQJwuZ8xGFbrYgz5'
            });

            sc_player = SC.Widget('soundcloud_player');

            sc_player.bind(SC.Widget.Events.READY, function() {
                sc_player.load("https://soundcloud.com/" + songs.ids[count], sc_options);
                document.getElementById("volumeRange").value = sc_player.getVolume();
                mediaControllerToggle(false);
                
                sc_player.bind(SC.Widget.Events.FINISH, function() {
                    next();
                });
            });
        }
        else{
            sc_player.load("https://soundcloud.com/" + songs.ids[count], sc_options);
        }
        
        var sc_div = document.getElementById('soundcloud_player');
        sc_div.style.display = "block";
    }
    
    mediaControllerToggle(false);
    var div = document.getElementById('video-player-wrapper');
    div.style.display = "block";
}

function hide_all_players(){
    var div = document.getElementById('video-player-wrapper');
    div.style.display = "none";
    var yt_div = document.getElementById('youtube_player');
    yt_div.style.display = "none";
    var sp_div = document.getElementById('spotify_player');
    sp_div.style.display = "none";
    var sc_div = document.getElementById('soundcloud_player');
    sc_div.style.display = "none";
}

function onPlayerReady(){
    yt_player.playVideo();
    document.getElementById("volumeRange").value = yt_player.getVolume();
    mediaControllerToggle(false);
}

function resume(){
    if (songs.platforms[count] == "yt"){
        yt_player.playVideo();
    }
    else if (songs.platforms[count] == "sp"){
        // play
    }
    else if (songs.platforms[count] == "sc"){
        sc_player.play();
    }
}

function pause(){
    if (songs.platforms[count] == "yt"){
        yt_player.pauseVideo();
    }
    else if (songs.platforms[count] == "sp"){
        // pause
    }
    else if (songs.platforms[count] == "sc"){
        sc_player.pause();
    }
}

function stop(){
    hide_all_players();

    try { yt_player.stopVideo(); } catch(err) {}
    try { sp_player.pause(); } catch(err) {}
    try { sc_player.pause(); } catch(err) {}
}

function previous(){
    count -= 2
    if (count < 0){ count += songs.ids.length; }
    next();
}

function setVolume(platform, volume){
    if (platform == "yt" && yt_player != null){
        yt_player.setVolume(volume);
    }
    else if (platform == "sp" && sp_player != null){
        // set volume
    }
    else if (platform == "sc" && sc_player != null){
        sc_player.setVolume(volume);
    }
}

function mediaControllerToggle(value){
    setTimeout(function(){ 
        document.getElementById("resume").disabled = value;
        document.getElementById("pause").disabled = value;
        document.getElementById("stop").disabled = value;
        document.getElementById("volumeRange").disabled = value;
        document.getElementById("next").disabled = value;
        document.getElementById("previous").disabled = value;
    }, 1000);
}