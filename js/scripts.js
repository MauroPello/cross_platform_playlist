var count = 0;
var yt_player = null;
var sp_player = null;
var sc_player = null;
var songs = [];

function start_playlist(platforms, ids){
    songs.ids = ids;
    songs.platforms = platforms;
    next_song(platforms[count], ids[count]);
}

function next_song(platform, id){
    hide_all_players();
    
    if (platform == "yt"){
        if (yt_player == null){
            yt_player = new YT.Player('youtube_player', {
                playerVars: {autoplay: 1, showinfo : 0, controls: 0},
                events: { onReady: onPlayerReady }
            });

            yt_player.addEventListener("onStateChange", function(state){
                if(state.data === 0){
                    console.log("Finished playing " + id);
                    next_song(songs.platforms[count], songs.ids[count]);
                }
            });
        }
        else{
            yt_player.loadVideoById(id, 0);
            yt_player.playVideo();
        }

        var iframe = document.getElementById('youtube_player-wrapper');
        iframe.style.display = "block";
    }
    else if (platform == "sp"){
        var iframe = document.getElementById('spotify_player');
        iframe.style.display = "block";
        if (sp_player == null){
            // start spotify player
        }
        // next spotify song
    }
    else if (platform == "sc"){
        var sc_options = [];
        sc_options.show_artwork = true;
        sc_options.liking = false;
        sc_options.auto_play = true;
        
        if (sc_player == null){
            SC.initialize({
                client_id: 'YOUR_CLIENT_ID'
            });

            sc_player = SC.Widget('soundcloud_player');

            sc_player.bind(SC.Widget.Events.READY, function() {
                sc_player.load("https://soundcloud.com/" + id, sc_options);

                sc_player.bind(SC.Widget.Events.FINISH, function() {
                    console.log("Finished playing " + id);
                    next_song(songs.platforms[count], songs.ids[count]);
                });
            });
        }
        else{
            sc_player.load("https://soundcloud.com/" + id, sc_options);
        }
        
        var iframe = document.getElementById('soundcloud_player');
        iframe.style.display = "block";
    }

    count += 1;
    count = count % songs.ids.length;
}

function hide_all_players(){
    div = document.getElementById('youtube_player-wrapper');
    div.style.display = "none";
    iframe = document.getElementById('spotify_player');
    iframe.style.display = "none";
    iframe = document.getElementById('soundcloud_player');
    iframe.style.display = "none";
}

function onPlayerReady(){
    yt_player.playVideo();
}