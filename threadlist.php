<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <title>Welcome to iDiscuss - Coding Forums</title>
</head>
<style>
    #ques {
        min-height: 433px;
    }
</style>

<body>
    <?php include "partials/_dbconnect.php" ?>
    <?php include 'partials/_header.php' ?>

    <!---------------------------------------------------------------------------------->

    <?php
    $id = $_GET['catid'];
    $sql = "SELECT * FROM `categories` WHERE `category_id`=$id";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $catname = $row['category_name'];
        $catdesc = $row['category_description'];
    };
    ?>


    <?php
    $show_Alert = false;
    $method = $_SERVER['REQUEST_METHOD'];
    // echo $method;
    if($method == "POST"){
        $th_title = $_POST['title'];
        $th_desc = $_POST['desc'];
        $sno = $_POST['sno'];

        $th_title = str_replace("<", "&lt;", $th_title);
        $th_title = str_replace(">", "&gt;", $th_title);

        $th_desc = str_replace("<", "&lt;", $th_desc);
        $th_desc = str_replace(">", "&gt;", $th_desc);


        $sql = "INSERT INTO `threads` (  `thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `timestamp`) VALUES ( '$th_title', '$th_desc', '$id', '$sno', current_timestamp())";
        $result = mysqli_query($conn, $sql);
        $show_Alert = true;
        if($show_Alert){
            echo'<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your thread has been added! Please wait for community to respond.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
        }
    }
    ?>

    <!-- Categories Container start here -->
    <div class="container my-4">
        <div class="jumbotron">
            <h1 class="display-4">Welcome to
                <?php echo $catname; ?> forums</h1>
            <p class="lead">
                <?php echo $catdesc; ?>
            </p>
            <hr class="my-4">
            <p>This is peer to peer forum. No Spam / Advertising / Self-promote in the forums is not allowed. Do not post Copyright-infringing material. Do not post "offesive" post, link or images. Do not cross post questions. Remain respectful of
                other members at all times.
            </p>
            <p class="lead">
                <a class="btn btn-success btn-lg" href="#" role="button">Learn more</a>
            </p>
        </div>
    </div>

    <!---------------------------------------------------------------------------------->
    <?php
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
        echo '<div class="container">
        <h1 class="py-2">Start a Discussion</h1>
        <form action="'. $_SERVER["REQUEST_URI"] .'" method="post">
        <div class="form-group">
        <label for="Problem_title">Problem Title</label>
        <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp" placeholder="Enter Your problem">
        <small id="smTitle" class="form-text text-muted">Keep your title as short and crisp as possible.</small>
        </div>
        <div class="form-group">
        <label for="Problem_desc">Elaborate Your Concern</label>
        <textarea class="form-control" id="desc" name="desc" rows="3"></textarea>
        </div>
        <input type="hidden" name="sno" value="'.$_SESSION["sno"].'">
        <button type="submit" class="btn btn-success">Submit</button>
        </form>
        </div>';
    }
     
    else{
        echo ' 
        <div class="container">
            <h1 class="py-2">Start a Discussion</h1>
            <p class="lead">You are not logged in. Please login to be able to start a Discussion </p>
        </div>
        ' ;
    }
        ?>
      
    <!-------------------------------------------------------------------------------------->
    <div class="container mb-5" id="ques">
        <h1 class="py-4">Browse Questions</h1>
        <?php
        $id = $_GET['catid'];
        $sql = "SELECT * FROM `threads` WHERE `thread_cat_id` =$id";
        $result = mysqli_query($conn, $sql);
        $noResult = true;
        while ($row = mysqli_fetch_assoc($result)) {
            $noResult = false;
            $title = $row['thread_title'];
            $desc = $row['thread_desc'];
            $id = $row['thread_id'];
            $thread_time = $row['timestamp'];
            $thread_user_id = $row['thread_user_id'];
            
            // echo $thread_user_id;

            $sql2 = "SELECT user_email FROM `users` WHERE sno = '$thread_user_id'";
            $result2 = mysqli_query($conn , $sql2);
            // echo var_dump($result2) ;
            $row2 = mysqli_fetch_assoc($result2); 
            echo ' <div class="media my-4 ">
                    <img class="mr-3" src="img/default_user.jpg" width="34px" alt="Generic  placeholder image">
                <div class="media-body">
                    <h5 class="mt-0 "><a class="text-dark" href="thread.php?threadid=' . $id . '">' . $title . '</a></h5>
                    ' . $desc . '
                </div>
                <p class= "font-weight-bold my-0 " > Asked by '.$row2['user_email'].' at '.$thread_time.'</p>
            </div>';
        };
        // echo var_dump($noResult);
        if ($noResult) {
            echo  '<div class="jumbotron jumbotron-fluid">
        <div class="container">
          <p class="display-4">No Threads Found</p>
          <p class="lead">Be the first person to ask the question.</p>
        </div>
      </div>';
        }
        ?>
        <!------------------------------------------------------------------------------------->

        <!-- remove later: putting this just to check html alignment for now -->

        <!-- <div class="media my-4 ">
            <img class="mr-3" src="img/default_user.jpg" width="34px" alt="Generic placeholder image">
            <div class="media-body">
                <h5 class="mt-0">Media heading</h5>
                Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
            </div>
        </div> -->

    </div>
    <!------------------------------------------------------------------------------------->
    <?php include 'partials/_footer.php' ?>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    -->
</body>

</html>