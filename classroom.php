<?php
session_start();

if (isset($_POST['logout'])) {
    // Perform logout actions
    session_unset();
    session_destroy();
    header("location: login.php");
    exit;
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}

// Include the database connection file
include 'db_connect.php';

// Retrieve posts from the database
$sql = "SELECT * FROM `CLASSPOSTS`";
$result = $conn->query($sql);

// Array to store the retrieved posts
$posts = array();

// Fetch each post and add it to the posts array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userid = $_SESSION['userid'];

        // Retrieve the profile picture and username of the user who added the post
        $profileQuery = $conn->prepare("SELECT `PROFILEPIC`,`USERNAME` FROM `USERS` WHERE `ID` = ?");
        $profileQuery->bind_param("s", $userid);
        $profileQuery->execute();
        $profileResult = $profileQuery->get_result();

        if ($profileResult->num_rows > 0) {
            $profileRow = $profileResult->fetch_assoc();
            $username = $profileRow['USERNAME'];
            $profilePic = $profileRow['PROFILEPIC'];
        } 
        $post = array(
            'username' => $username,
            'userid' => $userid,
            'profilePic' => $profilePic,
            'postPic' => $row['Image'],
            'caption' => $row['Caption'],
            'postId' => $row['ID']
        );
        $posts[] = $post;
    }
}

// Close the database connection
$conn->close();

$userUname=$_SESSION['username'];
$repUname="Ullas";
if($userUname===$repUname){
    $_SESSION['isrep']=true;
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <!-- Linking fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <title>Class Connect | Classroom</title>
</head>
<body>
    
<header>
    <div class="header_container">
        <div class="branding">
        <a href="#"><img class="logo" src="./icons/logo.png" alt="Logo"></a> 
        </div>
       
        <div class="iconbar">

        <div class="iconbar">

        <div class="icon home">
        <span>
            <a href="index.php">
            <i class="fas fa-home"></i>
            </a>
        </span>
        <div class="tooltip">
                Home
        </div>
        </div>

        <div class="icon addclasspost <?php echo $isRep ? 'visible' : ''; ?>">
        <span>
            <a href="addclasspost.php">
            <i class="fas fa-plus" id="add_post"></i>
            </a>
        </span>
        <div class="tooltip">
                Add Post
        </div>
        </div>

        <div class="icon upload <?php echo $isRep ? 'visible' : ''; ?>">
        
        <span>
            <a href="upload.php">
            <i class="fa-solid fa-upload icon"></i>
            </a>
        </span>
        <div class="tooltip">
                Upload Notes
        </div>
        </div>

        <div class="icon notes">
        
        <span>
            <a href="notes.php">
            <i class="fa-solid fa-book icon"></i>
            </a>
        </span>
        <div class="tooltip">
                Notes
        </div>
        </div>

        <div class="icon profile">
        
        <span>
            <a href="profile.php">
            <i class="fas fa-user"></i>
            </a>
        </span>
        <div class="tooltip">
                Profile
        </div>
        </div>

        <div class="icon ">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <span>
            <button type="submit" name="logout"><i class="fa-solid fa-right-from-bracket"></i></button>
            </span>
            <div class="tooltip">
                Logout
            </div>
        
        </form>
        </div>
    </div>
</header>

<section class="main-container">

<div class="inner-container">
    <div class="left-setion">
        <div class="post_list">
        <?php
        foreach ($posts as $post) {
        
        echo '<div class="post">' .
        '<div class="post_header">' .
        '<div class="p_inner">' .
        '<img class="post_profile" src="data:image/jpeg;base64,' . base64_encode($profilePic) . '" alt="Posted Person\'s Profile Pic">' .
        '<p class="p_name">' . $post['username'] . '</p>' .
        '</div>' .
        '<i class="fa-solid fa-ellipsis-vertical threedots"></i>' .
        '</div>' .
        '<div class="p_image">' .
        '<img class="pp_full" src="data:image/jpeg;base64,' . base64_encode($post['postPic']) . '">' .
        '</div>' .
        '<div class="reaction_icon">' .
        '<div class="left_i">' .
        '<i class="far fa-comment"></i>' .
        '<i class="far fa-thumbs-up"></i>' .
        '</div>' .
        '</div>' .
        '<div class="comment_section">
              <div class="input_box">
                <input type="text" class="inpt_c comment-input" data-postid="'.$post['postId'].'" placeholder="Add a comment...">
              </div>
              <div class="c_txt">
                <button class="post-comment-btn" data-postid="'.$post['postId'] .'">Post</button>
              </div>
            </div>' .
        '</div>';
        }
        ?>
    </div>
</div>

</section>


<script src="script.js"></script>
</body>
</html>

