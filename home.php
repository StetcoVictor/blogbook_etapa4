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
            $("#create_post_input").on("keypress", function(e){
                if(e.which == 13){
                    var inputVal = $(this).val();
                    $.ajax({
                            url: 'insertPost.php',
                            type: 'POST',
                            data: {
                                puser:localStorage.blogbook_username,
                                pcont:$("#create_post_input").val()
                            },
                            success: function(response){
                                location.reload();
                            }
                        });
                }
            });



            $(".post_comment_input").on("keypress", function(e){
                if(e.which == 13){
                    var inputVal = $(this).val();
                    var inputId = $(this).attr('id').split("_")[2];
                    $.ajax({
                            url: 'insertComment.php',
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

            $(".post_share_button").click(function(){
                window.open('http://www.facebook.com/sharer.php?s=100&p[title]="Blogbook"&p[summary]="This is a summary"&p[url]=YOUR_URL&p[images][0]=YOUR_IMAGE_TO_SHARE_OBJECT', 'newwindow', 'width=600, height=500');
            });
        });
    </script>
</head>
<body>

    <div id="navbar_wrapper">
        <div id="navbar">
            <img src="assets/logo_wide.png" id="navbar_logo">
            <div id="navbar_menu">
                <div class="navbar_item"><a href="home"><font color="#FF7755">Home</font></a></div>
                <div class="navbar_item"><a href="discover">Discover</a></div>
                <div class="navbar_item"><a href="profile">Profile</a></div>
            </div>
        </div>
    </div>

    <div id="content_area_wrapper">
        <div id="content_area">

            <div id="create_post_wrapper">
                <a href="#"><div id="create_post_avatar" style="background-image:url('https://picsum.photos/200?random=<?=$current_user['id']?>')"></div></a>
                <input type="text" id="create_post_input" placeholder="What's on your mind...">
            </div>

            <?php 
            $postitems = 0;
            foreach(getItemsFromTable('posts') as $pst) {
                if($pst['poster'] != $current_user['username']) {
                    $current_poster = getItemWhere("users", "username", $pst['poster']);
                    if(strpos($current_user['following'], $current_poster['serial_no'])) {
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
                        <a href="profile/<?=$current_commenter['serial_no']?>"><div class="post_comment_header">
                            <div class="post_comment_avatar" style="background-image:url('https://picsum.photos/200?random=<?=$current_commenter['id']?>')"></div>
                            <span class="post_comment_name">@<?=$current_commenter['username']?></span>
                        </div></a>
                        <?=$cmt['content']?>
                    </div>

                    <?php } } ?>
                </div>
            </div>
            <?php } } } ?>
            <?php if($postitems == 0){ ?>   
                <img src="assets/feed_placeholder.png" id="feed_placeholder">
            <?php } ?>
        </div>
    </div>

</body>
</html>