<?php require './includes/includedFiles.php'; ?>
    <h5>Recently Played</h5>
    <div class="recentSongs">
        <?php 
            echo getRecentPlayedSongs()["content"];
            $songIds = getRecentPlayedSongs()["songIds"];
        ?>
        

        
    </div>   
    
    <script>
        tmpPlaylist = JSON.parse('<?= json_encode($songIds) ?>');
        
    </script>
    <script>
        var swiper = new Swiper(".swiper-container", {
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: "auto",
        coverflowEffect: {
          rotate: 50,
          stretch: 0,
          depth: 100,
          modifier: 1,
          slideShadows: true,
        },
        pagination: {
            el: '.swiper-pagination',
        },
      });
    </script>
            


        






