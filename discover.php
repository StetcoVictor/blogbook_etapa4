<?php

  include "config.php";
  include "functions.php";
  session_start();
  $current_user = getItemWhere("users", "username", $_SESSION['usermail']);
  if(!$current_user['username']) {
    header("Location: destroyusersession.php");
  }

?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogbook</title>
    <script src="jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/master.css">
    <script>
        $(document).ready(function(){
            $(".post_follow_button").click(function(){
                var postId = $(this).attr('id').split("_")[1];
                $.ajax({
                    url: 'followUser.php',
                    type: 'POST',
                    data: {
                        fuser:localStorage.blogbook_username,
                        ftarget:postId,
                    },
                    success: function(response){
                        $("#post_"+postId).remove();
                        if($('.post_wrapper').length == 0) {
                            $("#feed_placeholder_auto").css("display", "block");
                        }
                    }
                });
            });
        });
    </script>
</head>
<body>

    <div id="navbar_wrapper">
        <div id="navbar">
            <img src="assets/logo_wide.png" id="navbar_logo">
            <div id="navbar_menu">
                <div class="navbar_item"><a href="home">Home</a></div>
                <div class="navbar_item"><a href="discover"><font color="#FF7755">Discover</font></a></div>
                <div class="navbar_item"><a href="profile">Profile</a></div>
            </div>
        </div>
    </div>

    <div id="content_area_wrapper">
        <div id="content_area">


            <?php
            $postitems = 0;
            foreach(getItemsFromTable('posts') as $pst) {
                if($pst['poster'] != $current_user['username']) {
                    $current_poster = getItemWhere("users", "username", $pst['poster']);
                    if(!strpos($current_user['following'], $current_poster['serial_no'])) {
                        $postitems = $postitems + 1;
            ?>

            <div class="post_wrapper" id="post_<?=$current_poster['serial_no']?>">
                <div class="post_header">
                <a href="profile/<?=$current_poster['serial_no']?>"><div class="post_avatar" style="background-image:url('https://picsum.photos/200?random=<?=$current_poster['id']?>')"></div></a>
                    <div class="post_details">
                        <a href="profile/<?=$current_poster['serial_no']?>"><span class="post_name">@<?=$pst['poster']?></span></a>
                        <span class="post_date">Posted on <?=$pst['date']?></span>
                    </div>
                    <div id="follow_<?=$current_poster['serial_no']?>" class="post_follow_button">+ Follow</div>
                </div>
                <div class="post_container">
                <?=$pst['content']?>
                </div>
                <div class="post_comment_section">
                    <?php 

                    foreach(getItemsFromTable('comments', "desc") as $cmt) {
                        if($cmt['post'] == $pst['serial_no']) {
                            $current_commenter = getItemWhere("users", "username", $cmt['poster']);

                    ?>
                
                    <div class="post_comment_wrapper">
                        <a href="profile/<?=$current_commenter['serial_no']?>"><div class="post_comment_header">
                            <div class="post_comment_avatar" style="background-image:url('https://picsum.photos/200?random=<?=$current_commenter['id']?>')"></div>
                            <span class="post_comment_name">@<?=$current_commenter['username']?></span>
                        </div></a>
                        <?=$cmt['content']?>
                    </div>

                    <?php } } ?>
                </div>
            </div><br>
            <?php } } } ?>
            <?php if($postitems == 0){ ?>
                <img src="assets/feed_placeholder.png" id="feed_placeholder">
            <?php } ?>

            <img src="assets/feed_placeholder.png" id="feed_placeholder_auto">
            
        </div>
    </div>

</body>
</html>