
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

  $uid=$_SESSION['userid'];

  $stmt = $conn->prepare("INSERT INTO `CLASSPOSTS` ( `CAPTION`,`IMAGE`,`USERID`) VALUES (?, ?,?)");
  $stmt->bind_param("sss", $caption,$fileData,$uid);

  if ($stmt->execute()) {
      header("Location: classroom.php");
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
    <title>Class Connect | Add CLass Post</title>
    <link rel="stylesheet" href="style.css">
    <!-- Linking fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
<header>
    <div class="header_container">
        <div class="branding">
        <a href="about.php"><img class="logo" src="./icons/logo.png" alt="Logo"></a> 
        </div>

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

        <div class="icon upload">
        
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

        <!-- <div class="icon profile">
        
        <span>
            <a href="profile.php">
            <i class="fas fa-user"></i>
            </a>
        </span>
        <div class="tooltip">
                Profile
        </div>
        </div> -->

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
    <div class="inner-container">
    <form id="upload_form" action="addclasspost.php" method="POST" enctype="multipart/form-data">
        <div class="post">
          <div class="post_header">
            <div class="p_inner">
              <img class="post_profile" src="retrived.php?id=<?php echo $_SESSION['userid']; ?>" alt="Posted Person's Profile Pic">
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
