<?php require './includes/includedFiles.php'; ?>

<?php 
    $username = $_SESSION['username'];
    $user = new User($username);
   
?>

    <h5>Profile</h5>
    <div class="profile">
        <div class="update-details">
            <div class="title-container">
                <!-- <h5>Update profile</h5> -->
            </div>

            <form >
                <div class="form-group">
                    <label for="firstname">Firstname</label>
                    <input type="text" id="firstname" value="<?= $user->getFirstname() ?>" >
                </div>

                <div class="form-group">
                    <label for="lastname">Lastname</label>
                    <input type="text" id="lastname" value="<?= $user->getLastname() ?>" >
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" value="<?= $user->getUsername() ?>" >
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" value="<?= $user->getEmail() ?>" >
                </div>

                <div class="form-group">
                    <input type="submit"   value='Update' id='update-details-btn' >  
                </div>

            </form>

        </div>

        <div class="update-password">
            <div class="title-container">
                <h5>Update password</h5>
                
            </div>

            <form >
                <div class="form-group">
                    <label for="oldPassword">Old Password</label>
                    <input type="password" id="oldPassword" value="" >
                </div>

                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" id="newPassword" value="" >
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" value="" >
                </div>

                <div class="form-group">
                    <input type="submit"   value='Update' id='update-password-btn' >  
                </div>

            </form>

        </div>
        

    </div>    
            


        


