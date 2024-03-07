$(function(){
    
    $("#login").on("click",(e) => {
        e.preventDefault();
        $(".register-section").slideUp("slow", () => {
            $(".login-section").slideDown("fast");
        })
    })

    $("#register").on("click",(e) => {
        e.preventDefault();
        $(".login-section").slideUp("slow", () => {
            $(".register-section").slideDown("fast");
        })
    })



})




