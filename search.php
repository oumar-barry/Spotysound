<?php require './includes/includedFiles.php'; ?>
    
    <div class="search-container">
        <input type="search" id="search" autofocus autocomplete="off"  placeholder='Search for an album, song or artist ...'>
        <div class="sub-menu">
            <a href="#" data-target='songs' class='active' > Songs</a>
            <a href="#" data-target='albums' >Albums</a>
            <a href="#" data-target='artists' >Artists</a>
        </div>
        <div class='sub-container'>
            <div class='songs active'> </div>
            <div class="albums"> </div>
            <div class='artists'> </div>
        </div>
            
          
    </div>
    <?= showOptionsMenu() ?>
    
    <script>
        $(document).ready(function(){
            $("#search").focus();
            $("input").on('keyup', function(){
                var term = $(this).val();
                clearTimeout(timer);

                timer = setTimeout(function(){
                    if(term != ""){
                        var target = $("a.active").data('target');
                        performSearch(term,target);
                        
                    } else {
                        $(".sub-container > div").html("");
                    }
                },500);
           
            });

            $(".sub-menu a").on('click', function(){
                $("#search").focus();
                var target = $(this).data('target');
                var term = $("#search").val();
                if(term != "") {
                    performSearch(term,target);
                }
                
            });
        });
        
        
        
        function performSearch(term,target){
            var data = {
                term: term,
                type: target
            }

            $.post("ajax/performSearch.php",data,(results) => {
                var results = JSON.parse(results);
                if(target == 'songs'){
                    $("."+target).html(results.content);

                    if(results.songIds.length != 0){
                        tmpPlaylist = results.songIds;
                    }
                } else if(target == 'albums'){
                    $("."+target).html(results.content);
                } else if(target == 'artists'){
                    $("."+target).html(results.content);
                }
            });
        }
        
    </script>
            


        






