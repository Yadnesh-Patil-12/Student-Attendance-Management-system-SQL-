$(function() {
    function trylogin() {
        let un = $("#username").val();  
        let pw = $("#password").val();  

        if (un && pw) {  
            $.ajax({
                url: "../ajaxhandler/loginAjax.php",
                type: "POST",
                dataType: "json",
                data: { 
                    username: un, 
                    password: pw, 
                    action: "verifyUser" 
                },
                success: function(response) {
                    console.log("Response from server:", response);

                    if (response.status === "Login Successful") {
                        window.location.href = "home.php";  // Redirect to home.php
                    } else {
                        alert(response.status);  // Show error message
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                }
            });
        }
    }

    $(document).on('keyup', function(e) {
        let un = $("#username").val();  
        let pw = $("#password").val();  

        if (un && pw) {
            $("#loginBtn").prop("disabled", false);
        } else {
            $("#loginBtn").prop("disabled", true);
        }

        if (un.length < 2) {
            $("#username").css("border-color", "red");
        } else {
            $("#username").css("border-color", "green");
        }

        if (pw.length < 3) {
            $("#password").css("border-color", "red");
        } else {
            $("#password").css("border-color", "green");
        }
    });

    $("#loginBtn").click(function(e) {
        e.preventDefault();  
        trylogin();  
    });
});
