<div class="menu">
            <div class="logo" onclick="openPage('index.php')" >
                <img src="assets/images/logo.png" alt="logo"> 
                <span id="slotify">Spotysound</span>
            </div>
            <div class="links">
                <div class="link" onclick="openPage('search.php')">
                    <input type="search" placeholder="Make a search">

                </div>

                <div class="link">
                    <span><i class="fa-solid fa-house"></i></span>
                    <span onclick="openPage('browse.php')" >Browse</span>
                </div>

                <div class="link">
                    <span><i class="fa-solid fa-music"></i></span>
                    <span onclick="openPage('playlists.php')" >Playlist</span>
                </div>

                <div class="link">
                    <span><i class="fa-solid fa-clock-rotate-left"></i></span>
                    <span onclick="openPage('recent.php')"  >Recently Played</span>
                </div>
                
                <div class="link">
                    <span><i class="fa-solid fa-gear"></i></span>
                    <span onclick="openPage('settings.php')" >Settings</span>
                </div>

                <div class="link">
                    <span><i class="fa-solid fa-right-from-bracket"></i></span>
                    <span onclick="logout()" >Logout</span>
                </div>


            </div>
        </div>