var audioElement;
var currentIndex;
var newPlaylist = [];
var currentPlaylist = [];
var tmpPlaylist = [];
var shufflePlaylist = [];
var repeat = false;
var shuffle = false;
var mouseDown = false;
var username = '<?= $username ?>';
var timer;
var muted = false;
var volumeBeforeMute;

// $(window).on('scroll', function(){
//     console.log($(window).scrollTop());
//     console.log($(document).scrollTop());
//     console.log(" ================");

// })

$(document).on('keyup', function(event){
    if(event.keyCode == 39){
        
        if(audioElement.currentTime() == 0){
           nextSong(false);
        } else {
            nextSong(true);
        }

    } else if(event.keyCode == 32){
        /* if(audioElement.paused()){
            alert("pause hit ");
            playSong();
        } else {
            alert("play hit ");
            pauseSong();
        } */

    } else if(event.keyCode == 37){
        if(audioElement.currentTime() == 0){
            prevSong(false);
         } else {
             prevSong(true);
         }
    } else if(event.keyCode == 38){
        if(audioElement.currentVolume() < 1){
            audioElement.audio.volume += 0.1; 
        }
    }


});

$(document).on('click','img#more',function(){
   showMenuOptions($(this));
});


$(document).on('click',function(){
    var target = $(event.target);
    if(!target.is("img#more") && !target.is(".options select")){
        hideMenuOptions();
    } 
 });

 $(document).on('click',"#update-details-btn" ,function(e){
    e.preventDefault();
    var firstname = $("#firstname").val().trim();
    var lastname = $("#lastname").val().trim();
    var username = $("#username").val().trim();
    var email = $("#email").val().trim();

    if(true){
        var data = {firstname, lastname, username, email};
        
        $.post("ajax/updateDetails.php",data, function(response){
            response = JSON.parse(response);
            $(".profile-msg").remove();
            outPutMessage("profile",response);
            
        } );

    } else {
        alert("please fil ");
    }
 });

 $(document).on('click',"#update-password-btn" ,function(e){
    e.preventDefault();
    var oldPassword = $("#oldPassword").val().trim();
    var newPassword = $("#newPassword").val().trim();
    var confirmPassword = $("#confirmPassword").val().trim();
    
    if(true){
        var data = {oldPassword, newPassword, confirmPassword};
        
        $.post("ajax/updatePassword.php",data, function(response){
            response = JSON.parse(response);
            $(".profile-msg").remove();
            outPutMessage("password",response);
            
        } );

    } else {
        alert("please fil ");
    }
 });

 $(document).on('change','.options select',function(){
    var playlistId = $(this).val();
    var songId = $(".options #songId").val();

    var data = {
        playlistId: playlistId,
        songId: songId
    }

    $.post('ajax/addToPlaylist.php',data, (response,status,xhr) => {
      
        if(response != ''){
           alert(response);
       }

       $(this).val('');
      
    });

    hideMenuOptions();

 });

 $(window).on('scroll', function(){
    hideMenuOptions();
 });

 

 function reorderSongs(playlistId,positions){
     if(positions.indexes.length != 0){
         var data = JSON.stringify(positions);
         $.post("ajax/reorderSongs.php", {data: data, playlist: playlistId}, (response) => {
            if(response != "") alert(response);s
         });
     }
 }

 function deletePlaylist(id){
    bootbox.confirm("<h5>Are you sure to delete this playlist ?</h5>", function(result){ 
        if(result == true){
            $.post("ajax/deletePlaylist.php", {playlistId: id}, function(response){
                openPage("playlists.php?");
             });

        } 
    });

 }


 function outPutMessage(container,{msg, code}){
    console.log("coucou ");

    if(code == 0){
        var style = "success";
        var timeout = 5000000000000000;
    } else {
        var style  = "danger";
        var timeout = 1500000000;
    }

    container = container == "profile" ? $(".update-details .title-container") : $(".update-password .title-container");

    var content = $(`<h5 class='profile-msg ${style}'>${msg}</h5>`);
    container.append(content);

    setTimeout(() => {
        $(".profile-msg").fadeOut().removeClass("success danger");

    },timeout);
 }

 function logout(){
    console.log('clcick');
    $.post("ajax/logout.php", (data, status, xhr) => {
        console.log("ok les gars ");
        location.reload();
    })
 }

 function createPlaylist(){
    bootbox.prompt({
        title: "<h5>Playlist name </h5>", 
        centerVertical: false,
        callback: function(result){ 
            
            $.post("ajax/newPlaylist.php",{name: result}, (response) => {
                openPage('playlists.php?');
            });
            
            
        }
    });
 }
 
function getSongIdFromElement(button){
    var songId = button.parents('.song').attr('id');
    return songId;
}

function hideMenuOptions(){
    var menu = $(".options");
    if(menu.css('display') != 'none'){
        menu.css('display','none');
    }
}

function showMenuOptions(button){
    var songId = getSongIdFromElement(button);
    var playlistMenu = $(".options");
    $(".options #songId").val(songId);

    var scrollTop = $(window).scrollTop();
    var topOffset = $(button).offset().top;
    var leftPosition = $(button).position().left;
   
    var top = topOffset - scrollTop + 3;
    var left = leftPosition + 112;
    
    playlistMenu.css({
        'top': top + 'px',
        'left': left + 'px',
        'display': 'block'
    });


}

function playIt(songId,playlist,play){
    var target = $(event.target);
    if(!target.is("img#more")){
        if(audioElement.currentTime() > 0 && !audioElement.paused() && audioElement.currentlyPlaying.id == songId){
           pauseSong();
        } else if(audioElement.paused() && audioElement.currentlyPlaying.id == songId){
            playSong();
        } else {
            setTrack(songId,playlist,play);
        }
        
    } 
}

function playFirstSong(){
    setTrack(tmpPlaylist[0],tmpPlaylist,true);
}

function openPage(url){
    if(url.indexOf("?") == -1){
        url = url + "?";
    }
    var encodedURL = encodeURI(url+"&username="+username);
    
    $(".main-container").load(encodedURL);
    $("body").scrollTop(0);
    history.pushState(null,null,url);


}



function updateVolume(audio){
    var percentage = audio.volume * 100;
    $(".current-volume").css('width',percentage +'%');
    $("#volume img").attr("src","assets/images/icons/volume.png");
}


function formatTime(duration){
    var duration = Math.round(duration);
    var hours = Math.floor(duration / 3600);
    var mins = Math.floor((duration - (hours * 3600))/60);
    var secs = duration % 60;

    hours = hours ? hours + ":" : "";
    mins = (mins < 10) ? "0"+ mins + ":" : mins + ":";
    secs = (secs < 10) ? "0"+ secs  : secs ;
    return hours + mins + secs;
}



function updateTimeProgress(audio){
    $(".top-bar .current-time").text(formatTime(audio.currentTime));
    $(".top-bar .remaining-time").text(formatTime(audio.duration - audio.currentTime));
    var percentage = audio.currentTime / audio.duration * 100;
    $(".progress-bar .current-progress").css("width",percentage+"%");
    
}





function Audio(){
    
    this.audio = document.createElement("audio");
    this.currentlyPlaying;

    this.audio.addEventListener('ended', function(){
        if(repeat == true){
           this.currentTime = 0;
           this.play();
         } else {
            nextSong(); 
         }
        
    });
    this.audio.addEventListener('timeupdate', function(){
        if(this.duration){
            updateTimeProgress(this);
        }
    });
    
    this.audio.addEventListener('canplay', function(){
        var duration = formatTime(this.duration);
        $(".top-bar .current-time").text("00:00");
        $(".top-bar .remaining-time").text(duration);

    });


    this.setTrack = (track) => {
        this.audio.src = track.path;
        this.currentlyPlaying = track;
    }

    this.play = function(){
        this.audio.play();
    }

    this.pause = function(){
        this.audio.pause();
    }

    this.setTime = function(seconds){
        this.audio.currentTime = seconds;
    }

    this.currentTime = function(){
        return this.audio.currentTime;
    }

    this.paused = function(){
        return this.audio.paused 
    }

    this.currentVolume = function(){
        return this.audio.volume;
    }

    this.setVolume = function(volume){
        this.audio.volume = volume;
    }
    
}