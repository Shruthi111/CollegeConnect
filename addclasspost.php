
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

  $stmt = $conn->prepare("INSERT INTO `CLASSPOSTS` ( `CAPTION`,`IMAGE`) VALUES (?, ?)");
  $stmt->bind_param("ss", $caption,$fileData);

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
</head>
<body>
    <div class="inner-container">
    <form id="upload_form" action="addclasspost.php" method="POST" enctype="multipart/form-data">
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