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
        setCookie(songs.platforms[count] + "_volume", this.value, 30);
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
                switch(state.data){
                    case 0:
                        next();
                        break;
                    case 1:
                        document.getElementById("toggle").children[0].src = "img/pause.svg";
                        break;
                    case 2:
                        document.getElementById("toggle").children[0].src = "img/play.svg";
                        break;
                }
            });
        }
        else{
            yt_player.loadVideoById(songs.ids[count], 0);
            yt_player.playVideo();
            setTimeout(function(){ 
                setVolume("yt", getVolumeFromCookie("yt"));
            }, 2000);
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
                client_id: 'ENTER_ID_HERE'
            });

            sc_player = SC.Widget('soundcloud_player');

            sc_player.bind(SC.Widget.Events.READY, function() {
                sc_player.load("https://soundcloud.com/" + songs.ids[count], sc_options);
                setTimeout(function(){ 
                    setVolume("sc", getVolumeFromCookie("sc"));
                }, 2000);
                mediaControllerToggle(false);
                
                sc_player.bind(SC.Widget.Events.PLAY, function() {
                    document.getElementById("toggle").children[0].src = "img/pause.svg";
                });

                sc_player.bind(SC.Widget.Events.PAUSE, function() {
                    document.getElementById("toggle").children[0].src = "img/play.svg";
                });

                sc_player.bind(SC.Widget.Events.FINISH, function() {
                    next();
                });
            });
        }
        else{
            sc_player.load("https://soundcloud.com/" + songs.ids[count], sc_options);
            setTimeout(function(){ 
                setVolume("sc", getVolumeFromCookie("sc"));
            }, 2000);
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
    setTimeout(function(){ 
        setVolume("yt", getVolumeFromCookie("yt"));
    }, 1000);
    mediaControllerToggle(false);
}

function toggle(){
    icon = document.getElementById("toggle").children[0];
    if (songs.platforms[count] == "yt"){
        if (yt_player.getPlayerState() == 1){
            yt_player.pauseVideo();
            icon.src = "img/play.svg";
        }
        else{
            yt_player.playVideo();
            icon.src = "img/pause.svg";
        }
    }
    else if (songs.platforms[count] == "sp"){
        // pause
    }
    else if (songs.platforms[count] == "sc"){
        sc_player.isPaused(function(paused) {
            if (paused){
                sc_player.play();
                icon.src = "img/pause.svg";
            }
            else{
                sc_player.pause();
                icon.src = "img/play.svg";
            }
        });
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
    document.getElementById("volumeRange").value = volume;
}

function getVolumeFromCookie(platform){
    var volume = 0;
    if (platform == "yt"){
        volume = getCookie("yt_volume");
    }
    else if (platform == "sp"){
        volume = getCookie("sp_volume");
    }
    else if (platform == "sc"){
        volume = getCookie("sc_volume");
    }
    return volume;
}

function mediaControllerToggle(value){
    setTimeout(function(){ 
        document.getElementById("toggle").disabled = value;
        document.getElementById("stop").disabled = value;
        document.getElementById("volumeRange").disabled = value;
        document.getElementById("next").disabled = value;
        document.getElementById("previous").disabled = value;
    }, 1000);
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}