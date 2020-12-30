<?php

    include "config.php";
    include "functions.php";
    session_start();
    $current_user = getItemWhere("users", "username", $_SESSION['usermail']);

    if(!isset($_GET['u'])) {
        header("Location: profile/".$current_user['serial_no']);
    }

    $profile_user = getItemWhere("users", "serial_no", $_GET['u']);

    if(!$current_user['username']) {
        header("Location: destroyusersession.php");
    }



?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogbook</title>
    <script src="../jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/master.css">
    <script>

        $(document).ready(function() {
            $("#logout_button").on("click", function() {
                localStorage.blogbook_username = "";
                localStorage.blogbook_password = "";
                window.location.replace("../destroyusersession.php");
            });


            $(".post_comment_input").on("keypress", function(e){
                if(e.which == 13){
                    var inputVal = $(this).val();
                    var inputId = $(this).attr('id').split("_")[2];
                    $.ajax({
                            url: '../insertComment.php',
                            type: 'POST',
                            data: {
                                cuser:localStorage.blogbook_username,
                                ccont:inputVal,
                                cid:inputId
                            },
                            success: function(response){
                                location.reload();
                            }
                        });
                }
            });

            $(".profile_follow_button").click(function(){
                var cuser = $(this).attr('id').split("_")[1];
                $.ajax({
                    url: '../followUser.php',
                    type: 'POST',
                    data: {
                        fuser:localStorage.blogbook_username,
                        ftarget:cuser,
                    },
                    success: function(response){
                        location.reload();
                    }
                });
            });

            $(".profile_unfollow_button").click(function(){
                var cuser = $(this).attr('id').split("_")[1];
                $.ajax({
                    url: '../unfollowUser.php',
                    type: 'POST',
                    data: {
                        fuser:localStorage.blogbook_username,
                        ftarget:cuser,
                    },
                    success: function(response){
                        location.reload();
                    }
                });
            });

            $(".post_share_button").click(function(){
                window.open('http://www.facebook.com/sharer.php?s=100&p[title]="Blogbook"&p[summary]="This is a summary"&p[url]=YOUR_URL&p[images][0]=YOUR_IMAGE_TO_SHARE_OBJECT', 'newwindow', 'width=600, height=500');
            });


        });

    </script>
</head>
<body>

    <div id="navbar_wrapper">
        <div id="navbar">
            <img src="../assets/logo_wide.png" id="navbar_logo">
            <div id="navbar_menu">
                <div class="navbar_item"><a href="../home">Home</a></div>
                <div class="navbar_item"><a href="../discover">Discover</a></div>
                <div class="navbar_item"><a href="../profile"><font color="#FF7755">Profile</font></a></div>
            </div>
        </div>
    </div>

    <div id="content_area_wrapper">
        <div id="content_area">

            <div id="profile_area">
                <div id="profile_avatar" style="background-image:url('https://picsum.photos/200?random=<?=$current_user['id']?>')"></div>
                <div id="profile_description">
                    <div id="profile_name">
                        <span>@<?=$profile_user['username']?></span>
                        <?php
                            if($profile_user['serial_no']==$current_user['serial_no']) {
                        ?>
                        <div id="logout_button">
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="sign-out-alt" class="svg-inline--fa fa-sign-out-alt fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#AAAAAA" d="M497 273L329 441c-15 15-41 4.5-41-17v-96H152c-13.3 0-24-10.7-24-24v-96c0-13.3 10.7-24 24-24h136V88c0-21.4 25.9-32 41-17l168 168c9.3 9.4 9.3 24.6 0 34zM192 436v-40c0-6.6-5.4-12-12-12H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h84c6.6 0 12-5.4 12-12V76c0-6.6-5.4-12-12-12H96c-53 0-96 43-96 96v192c0 53 43 96 96 96h84c6.6 0 12-5.4 12-12z"></path></svg>
                        </div>
                        <?php } else if(!strpos($current_user['following'], $profile_user['serial_no'])) { ?>
                            <button id="follow_<?=$profile_user['serial_no']?>" class="profile_follow_button">Follow</button>
                        <?php } else { ?>
                            <button id="unfollow_<?=$profile_user['serial_no']?>" class="profile_unfollow_button">Unfollow</button>
                        <?php } ?>
                    </div>
                    <span id="profile_following"><b>
                        <?php echo sizeOf(explode(",", $profile_user['followers']))-2; ?>
                    </b> followers and <b>
                        <?php echo sizeOf(explode(",", $profile_user['following']))-2; ?>
                    </b> following</span>
                    <span id="profile_posts"><b>
                        <?php echo sizeOf(getMultipleWhere("posts", "poster", $profile_user['username'])); ?>
                    </b> published posts</span>
                </div>
            </div>

            <?php 
            $postitems = 0;
            foreach(getItemsFromTable('posts') as $pst) {
                if($pst['poster'] == $profile_user['username']) {
                    $current_poster = getItemWhere("users", "username", $pst['poster']);
                    $postitems = $postitems + 1;
            ?>

            <div class="post_wrapper">
                <div class="post_header">
                <a href="profile/<?=$current_poster['serial_no']?>"><div class="post_avatar" style="background-image:url('https://picsum.photos/200?random=<?=$current_poster['id']?>')"></div></a>
                    <div class="post_details">
                        <a href="profile/<?=$current_poster['serial_no']?>"><span class="post_name">@<?=$pst['poster']?></span></a>
                        <span class="post_date">Posted on <?=$pst['date']?></span>
                    </div>
                    <div class="post_share_button">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 155.139 155.139" style="enable-background:new 0 0 155.139 155.139;" xml:space="preserve">
                        <path id="f_1_" style="fill:#ffffff;" d="M89.584,155.139V84.378h23.742l3.562-27.585H89.584V39.184 c0-7.984,2.208-13.425,13.67-13.425l14.595-0.006V1.08C115.325,0.752,106.661,0,96.577,0C75.52,0,61.104,12.853,61.104,36.452 v20.341H37.29v27.585h23.814v70.761H89.584z"/>
                    </svg>
                    Share
                </div>
                </div>
                <div class="post_container">
                <?=$pst['content']?>
                </div>
                <div class="post_comment_section">
                    <input id="post_input_<?=$pst['serial_no']?>" type="text" class="post_comment_input" placeholder="Add a comment">
                    <?php 

                    foreach(getItemsFromTable('comments', "desc") as $cmt) {
                        if($cmt['post'] == $pst['serial_no']) {
                            $current_commenter = getItemWhere("users", "username", $cmt['poster']);

                    ?>
                
                    <div class="post_comment_wrapper">
                        <a href="../profile/<?=$current_commenter['serial_no']?>"><div class="post_comment_header">
                            <div class="post_comment_avatar" style="background-image:url('https://picsum.photos/200?random=<?=$current_commenter['id']?>')"></div>
                            <span class="post_comment_name">@<?=$current_commenter['username']?></span>
                        </div></a>
                        <?=$cmt['content']?>
                    </div>

                    <?php } } ?>
                </div>
            </div>
            <?php } } ?>
            <?php if($postitems == 0){ ?>   
                <img src="../assets/feed_placeholder.png" id="feed_placeholder">
            <?php } ?>
        </div>
    </div>

</body>
</html>