function login() {
    resetAlerts()
    $("#btn-login").attr("disabled", "disabled")
    user = $("#login").serializeArray();
    user.push({name: "action", value: "login"})
    // console.log(user); return

    $.post("server/login.php", user).then(data => {
        try {
            data = JSON.parse(data)
            setTimeout(() => {
                if(data.status == "unauthorized"){
                    $(".login-fail").css("display", "block")
                    $(".login-fail").css("opacity", 1)
                    $(".login-fail").html("<strong>Failed:</strong> Try again")

                    $("#btn-login").removeAttr("disabled")
                } else if(data.status == "ok") {
                    $(".login-success").css("display", "block")
                    $(".login-success").css("opacity", 1)
                    response = data.response

                    window.location.assign(response.redirect)

                    // $("#btn-login").removeAttr("disabled")
                    // console.log(data);
                }
            }, 500);
        } catch (error) {
            $(".login-fail").html("<strong>Error:</strong> check console")
            console.log(data, error);
        }
    })
}

function resetAlerts() {
    $(".login-fail").css("display", "none")
    $(".login-fail").css("opacity", 0)
    
    $(".login-success").css("display", "none")
    $(".login-success").css("opacity", 0)
}