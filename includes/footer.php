
        
        </div>
        <!-- Get 10 random songs when the page loads  -->
        <?php 
            $songIds = json_encode(getRandomSongs());
        ?>
        

        <div class="nowPlayingBar">
            <div class="left-track">
                <span class="track-image">
                    <img src="">
                </span>
                <div class="artist">
                    <span class="artist-name"></span>
                    <span class="song-title"></span>
                </div>
            </div>

            <div class="main-track">

                <div class="top-bar">
                    <span class="current-time"></span>
                    <span class="progress-bar">
                        <div class="current-progress"></div>
                    </span>
                    <span class="remaining-time">0:00</span>
                </div>
                
                <div class="track-controls">
                    <button id="loop" onclick="repeatSong()">
                        <img src="assets/images/icons/repeat.png" alt="repeat icon">
                    </button>

                    <button id="prev" onclick="prevSong()">
                        <img src="assets/images/icons/previous.png" alt="previous icon ">
                    </button>

                    <button id="play" onclick="playSong()" >
                        <img src="assets/images/icons/play.png" alt="play icon ">
                    </button>

                    <button id="pause" onclick="pauseSong()" style="display: none;" >
                        <img src="assets/images/icons/pause.png" alt="pause icon ">
                    </button>

                    <button id="next" onclick="nextSong()">
                        <img src="assets/images/icons/next.png" alt="next icon ">
                    </button>

                    <button id="shuffle" onclick="setShuffle()">
                        <img src="assets/images/icons/shuffle.png" alt="shuffle icon ">
                    </button>
                </div>

                
            </div>
            <div class="right-track">
                <button id="volume" onClick="mute()" >
                    <img src="assets/images/icons/volume.png" alt="volume icon">
                </button>
                <div class="volume-progress">
                    <div class="current-volume"></div>
                </div>
            </div>
        </div>
    </div>
</div>

    



    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'></script>

    <script src="assets/js/script.js"></script>

    

    <script>
        $(document).ready(() => {
            
            audioElement = new Audio();
            newPlaylist = JSON.parse('<?= $songIds ?>');
            setTrack(newPlaylist[0],newPlaylist,false);
            updateVolume(audioElement.audio);

            $(".progress-bar, .volume-progress").on('mousedown',function(){
                mouseDown = true;
            });

            $(".progress-bar").on('mousemove',function(e){
                if(mouseDown){
                    playFromoffset(e,this);
                }
            });

            $(".progress-bar").on('mouseup',function(e){
                playFromoffset(e,this);
                mouseDown = false;
            });

            $(".volume-progress").on('mousemove',function(e){
                if(mouseDown){
                    setVolumeFromOffset(e,this);
                }
            });

            $(".volume-progress").on('mouseup',function(e){
                setVolumeFromOffset(e,this);
                mouseDown = false;
            });

            $(".main-track, .right-track").on("mouseleave",function(){
                if(mouseDown){
                    mouseDown = false;
                }
            });

          
            

            


            
            
        });

        $(document).on('click','.sub-menu a', function(){
            var target = $(this).data('target');
            $(".sub-menu a").removeClass('active');
            $(this).addClass('active');
            $(".sub-container > div").removeClass('active');
            $("."+target).addClass('active');
        })


        function setTrack(songId,newPlaylist,play){
            if(newPlaylist != currentPlaylist){
                currentPlaylist = newPlaylist;
                shufflePlaylist = currentPlaylist.slice();
                
            }


            $.post('ajax/getSong.php',{id:songId}, (data) => {
               var track = JSON.parse(data);

               $(".left-track .song-title").text(track.title);
               
               $.post("ajax/getAlbum.php",{id: track.album}, (data) => {
                    var album = JSON.parse(data);
                    $(".left-track img").attr('src',album.artworkPath);
                    
                    $(".left-track img").attr('onclick',"openPage('album.php?id="+album.id+"')");

               });

               $.post("ajax/getArtist.php",{id: track.artist}, (data) => {
                    var artist = JSON.parse(data);
                    $(".left-track .artist-name").text(artist.name);
                    $(".left-track .artist-name").attr('onclick',"openPage('artist.php?id="+artist.id+"')");

               });
                

                audioElement.setTrack(track);
                currentIndex = newPlaylist.indexOf(audioElement.currentlyPlaying.id);
                
                if(play){
                    playSong();
                }
            });

            if($(".song").length != 0){
                $(".song").removeClass('active');
                $("#"+songId).addClass('active')
               
            }


        }

        
        function pauseSong(){
            $("#play").show();
            $("#pause").hide();
            audioElement.pause();

        }

        function playSong(){
            if(audioElement.audio.currentTime == 0){
                
                var songId = audioElement.currentlyPlaying.id;
                $.post("ajax/updatePlays.php",{id: songId});
                $.post("ajax/recentlyPlayed.php",{id: songId});
                
            } 
           
            $("#play").hide();
            $("#pause").show();
            audioElement.play();

        }

     

        function nextSong(play = true){
            
            if(currentIndex >= currentPlaylist.length - 1){
                currentIndex = 0;
            } else {
                currentIndex++;
            }

            var trackToPlay =  shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
            
            setTrack(trackToPlay,currentPlaylist,play);
        }

        function prevSong(play = true){
            if(repeat == true){
               audioElement.setTime(0);
                return;
            }

            if(currentIndex <= 0){
                currentIndex = currentPlaylist.length - 1;
            } else {
                currentIndex--;
            }

            var trackToPlay = currentPlaylist[currentIndex];
            setTrack(trackToPlay,currentPlaylist,play);


        }

        function repeatSong(){
            repeat = !repeat;
            var icon = repeat ? "assets/images/icons/repeat.png" : "assets/images/icons/repeat-active.png";
            
           $("#loop img").attr('src',icon);
           
        }

        function setShuffle(){
            shuffle = !shuffle;
            
            if(shuffle){
                shuffleArray(shufflePlaylist);
                currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
                var icon = "assets/images/icons/shuffle-active.png";
            } else {
                currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
                var icon = "assets/images/icons/shuffle.png";
            }

            $("#shuffle img").attr('src',icon);

            
        }

        function mute(){
            muted = !muted;
            var icon = muted ? "assets/images/icons/volume-mute.png" : "assets/images/icons/volume.png";
            $("#volume img").attr("src",icon);
            

            if(muted){
                volumeBeforeMute = audioElement.currentVolume();
                audioElement.setVolume(0);
                $(".current-volume").css('width','0%');

            } else {
                var percentage = volumeBeforeMute * 100;
                $(".current-volume").css('width', percentage + '0%');
                audioElement.setVolume(volumeBeforeMute);
            }
            console.log(volumeBeforeMute);

        }

        

        function shuffleArray(a) {
            var j, x, i;
            for (i = a.length - 1; i > 0; i--) {
                j = Math.floor(Math.random() * (i + 1));
                x = a[i];
                a[i] = a[j];
                a[j] = x;
            }
            return a;
        }

        function playFromoffset(mouse,progressBar){
            var percentage = mouse.offsetX / $(progressBar).width() * 100;
            var seconds = audioElement.audio.duration * (percentage / 100);
            audioElement.setTime(seconds);
            
        }

        function setVolumeFromOffset(mouse,volumeBar){
            var volume = mouse.offsetX / $(volumeBar).width();
            var percentage = volume * 100;
            if(volume >= 0 && volume <= 1){
                audioElement.audio.volume = volume;
                $(".current-volume").css('width',percentage +'%');
                $("#volume img").attr("src","assets/images/icons/volume.png");
            }
        }






















    </script>



</body>

</html>