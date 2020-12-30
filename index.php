<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogbook</title>
    <script src="jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">
    <script>

        $(document).ready(function() {

            $.ajax({
                url: 'checkaccountlogin.php',
                type: 'POST',
                async: false,
                data: {uem:localStorage.blogbook_username, ups:localStorage.blogbook_password},
                success: function(response){
                    if(response == 1) {
                        window.location.replace("startusersession.php?uem="+localStorage.blogbook_username);
                    }
                }
            });


            $("#login_button").click(function(){
                if($("#input_1").val().length == 0) {
                    create_alert("You username is missing");
                } else if($("#input_2").val().length == 0) {
                    create_alert("Your password is missing");
                } else {
                    $.ajax({
                        url: 'checkaccountlogin.php',
                        type: 'POST',
                        async: false,
                        data: {uem:$("#input_1").val(), ups:$("#input_2").val()},
                        success: function(response){
                            if(response == 1) {
                                localStorage.blogbook_username = $("#input_1").val();
                                localStorage.blogbook_password = $("#input_2").val();
                                window.location.replace("startusersession.php?uem="+localStorage.blogbook_username);
                            } else {
                                create_alert("Incorrect email or password");
                            }
                        }
                    });
                }
            });

            $(".alert_wrapper").css("display", "none");

            var alert_message_id = 0;
            function create_alert(x) {
                $(".alert_wrapper").css("display", "none");
                alert_message_id = alert_message_id + 1;
                $("body").append("<div class='alert_wrapper'>" + x + "</div>");
                $(".alert_wrapper").delay(1500).fadeOut();
            }

        });

    </script>
</head>
<body>

    <div id="login_left_panel">
        <img src="assets/logo_wide.png" id="login_logo">
        <div>
            <h4 class="form_title">Login</h4>
            <input type="text" placeholder="Username" id="input_1">
            <input type="password" placeholder="Password" id="input_2">
            <p id="change_form_p">Don't have an account? <a href="register">Register</a>.</p>
            <button id="login_button">Login</button>
        </div>
        <p>© Copyright Blogbook 2020</p>
    </div>
    <div id="login_right_panel"></div>

    <div class="alert_wrapper">
    </div>

    <script>
        function randomInt(min, max) {
            return min + Math.floor((max - min) * Math.random());
        }
        function getBgImg(){
            document.getElementById("login_right_panel").style.backgroundImage = "url('assets/LoginBg/"+randomInt(1, 9)+".jpg')";
        }
        getBgImg();
    </script>
</body>
</html>