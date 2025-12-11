<?php
include_once "../include/open_html.php";
include_once "../config/db.php";

// File Upload Function
function fileUpload($image){
    $dir = '../assets/img/hero-section/';

    // Create folder if not exists
    if(!file_exists($dir)){
        mkdir($dir, 0755, true);
    }

    // Check file upload error
    if($image['error'] !== UPLOAD_ERR_OK){
        echo "File upload error!";
        return null;
    }

    // Allowed file types
    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

    if(!in_array($ext, $allowed_ext)){
        echo "Invalid file type!";
        return null;
    }

    // Check file is real image
    if(!getimagesize($image['tmp_name'])){
        echo "Uploaded file is not a valid image!";
        return null;
    }

    // Check max file size (2MB limit)
    if($image['size'] > 2 * 1024 * 1024){
        echo "File size must be under 2MB!";
        return null;
    }

    // Generate secure file name
    $newName = bin2hex(random_bytes(8)) . "." . $ext;
    $fileDir = $dir . $newName;

    if(move_uploaded_file($image['tmp_name'], $fileDir)){
        return $newName;
    }else{
        echo "File not uploaded!";
        return null;
    }
}


// Create table
$create_table = "CREATE TABLE IF NOT EXISTS hero_section (
    id INT AUTO_INCREMENT PRIMARY KEY,
    headline VARCHAR(255),
    title VARCHAR(255),
    image TEXT
)";
$conn->query($create_table);

$error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $headline = trim($_POST['headline']);
    $title = trim($_POST['title']);
    $image = $_FILES['image'];

    if(empty($headline) || empty($title)){
        $error = "All fields are required!";
    } else {

        // Upload image
        $uploadedFileName = fileUpload($image);

        if($uploadedFileName){
            $stmt = $conn->prepare("INSERT INTO hero_section (headline, title, image) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $headline, $title, $uploadedFileName);
            $stmt->execute();
            $stmt->close();

            header("Location: index.php");
            exit;
        } else {
            $error = "Image upload failed!";
        }
    }
}
?>



<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <?php include_once "../include/sidebar.php"; ?>
    <!-- End of Sidebar -->

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- Topbar -->
            <?php include_once "../include/topbar.php"; ?>
            <!-- End of Topbar -->

            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="card">
                            <div class="card-header">
                                <h5>Create Hero Content</h5>
                            </div>
                            <div class="card-body">

                                <?php if(!empty($error)): ?>
                                    <div class="alert alert-danger"><?= $error ?></div>
                                <?php endif; ?>

                                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                                    <div class="row">

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Headline</label>
                                            <input type="text" name="headline" required class="form-control" placeholder="Enter Headline">
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="title" required class="form-control" placeholder="Enter Title">
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Image</label>
                                            <input type="file" name="image" required class="form-control">
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <button type="submit" class="btn btn-success form-control">Submit</button>
                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <?php include_once "../include/footer.php"; ?>
    </div>

</div>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<?php include_once "../include/closed_html.php"; ?>
