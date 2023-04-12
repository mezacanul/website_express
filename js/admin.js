function logout() {
    send = {
        action: "logout"
    }
    $.post("server/login.php", send).then(data => {
        try {
            data = JSON.parse(data)
            if(data.status == "ok"){
                response = data.response
                window.location.assign(response.redirect)
            }
        } catch (error) {
            alert("Error: check console")
            console.log(data, error);
        }
    })
}