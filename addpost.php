
<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true ){
  header("location: login.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include 'db_connect.php'; 

  $caption = $_POST['caption'];

  $postpic = $_FILES['postpic'];


  if ($postpic['error'] === UPLOAD_ERR_OK) {
    $tmpName = $postpic['tmp_name'];
    $fileData = file_get_contents($tmpName);

    // Check the file size
    $maxFileSize = 40 * 1024 * 1024; // 40MB in bytes
    if ($postpic['size'] > $maxFileSize) {
        echo "File size exceeds the limit. Please upload a smaller file.";
        exit;
    }

  $stmt = $conn->prepare("INSERT INTO `POSTS` ( `CAPTION`, `USERID`,`POSTPIC`) VALUES (?, ?,?)");
  $stmt->bind_param("sss", $caption, $_SESSION['userid'], $fileData);

  if ($stmt->execute()) {
      header("Location: index.php");
  } else {
      echo "Error inserting the record: " . $stmt->error;
  }

  $stmt->close();
} else {
  echo  $postpic['error'];
}

$conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Connect | Add Post</title>
    <link rel="stylesheet" href="style.css">

    <!-- Linking fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<header>
    <div class="header_container">
        <div class="branding">
        <a href="#"><img class="logo" src="./icons/logo.png" alt="Logo"></a> 
        </div>
        <div class="searchbar">
            <input class="search" placeholder="Search" type="search" name="" id="">
        </div>
        
        <div class="iconbar">
          <a href="addpost.php">
            <span class="tooltip" data-tooltip="Add Post">
              <i class="fas fa-plus icon tooltip-info" id="add_post"></i>
            </span>
          </a>
          <a href="classroom.php">
            <span class="tooltip" data-tooltip="My Classroom">
              <i class="fa-solid fa-users-rectangle icon tooltip-info"></i>
            </span>
          </a>
          <!-- <i class="far fa-bell icon"></i> -->
          <a href="profile.php">
          <span class="tooltip" data-tooltip="My Profile">
              <img class="profile_icon tooltip-info" src="retrived.php" alt="Profile image">
          </span>
          </a>
        </div>

 
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <button type="submit" name="logout">Logout</button>
        </form>
    </div>
</header>
    <div class="inner-container">
    <form id="upload_form" action="addpost.php" method="POST" enctype="multipart/form-data">
        <div class="post">
          <div class="post_header">
            <div class="p_inner">
              <img class="post_profile" src="retrived.php" alt="Posted Person's Profile Pic">
              <div class="p_name">
                <h3><?php echo $_SESSION['username']?></h3>
              </div>
            </div>
          </div>
          <div class="p_image">
            <input type="file" id="upload_img_input" name="postpic" accept="image/*">
          </div>
          <div class="comment_section">  
            <div class="input_box">
              <input class="inpt_c" placeholder="Add a caption..." type="text" name="caption">
            </div>
            <button type="submit" class="c_txt" id="post_btn">Post</button>
          </div>
        </div> 
      </form>
    </div>
</body>
<script src="addpost.js"></script>
</html>