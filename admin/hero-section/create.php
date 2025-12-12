<?php
include_once "../include/open_html.php";
include_once "../config/db.php";

// File Upload Function
function fileUpload($image){
    $path = '../assets/img/hero-section/';

    // Create folder if not exists
    if(!file_exists($path)){
        mkdir($path, 0755, true);
    }

    // Check for upload error
    if($image['error'] !== UPLOAD_ERR_OK){
        return ["error" => "File upload error!"];
    }

    // Allowed image extensions
    $allowed_ext = ['jpg','jpeg','png','webp'];
    $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

    if(!in_array($ext, $allowed_ext)){
        return ["error" => "Invalid file type! Only JPG, PNG, WEBP allowed."];
    }

    // Check if uploaded file is valid image
    if(!getimagesize($image['tmp_name'])){
        return ["error" => "File is not a valid image!"];
    }

    // Size check (2MB limit)
    if($image['size'] > 2 * 1024 * 1024){
        return ["error" => "Image must be under 2MB!"];
    }

    // Generate unique filename
    $newName = bin2hex(random_bytes(16)) . "." . $ext;
    $file_path = $path . $newName;

    // Move uploaded file
    if(move_uploaded_file($image['tmp_name'], $file_path)){
        return ["name" => $newName];
    } else {
        return ["error" => "Failed to upload image!"];
    }
}

// Create Table if not exists
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

    if(empty($headline) || empty($title)){
        $error = "All fields are required!";
    } elseif(!isset($_FILES['image']) || empty($_FILES['image']['name'])){
        $error = "Image is required!";
    } else {
        $upload = fileUpload($_FILES['image']);

        if(isset($upload['error'])){
            $error = $upload['error'];
        } else {
            $uploadedFileName = $upload['name'];

            $stmt = $conn->prepare("INSERT INTO hero_section (headline, title, image) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $headline, $title, $uploadedFileName);
            $stmt->execute();
            $stmt->close();

            header("Location: index.php");
            exit;
        }
    }
}
?>


<div id="wrapper">
    <?php include_once "../include/sidebar.php"; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include_once "../include/topbar.php"; ?>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="card">
                            <div class="card-header"><h5>Create Hero Content</h5></div>
                            <div class="card-body">

                                <?php if(!empty($error)): ?>
                                    <div class="alert alert-danger"><?= $error ?></div>
                                <?php endif; ?>

                                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label">Headline</label>
                                        <input type="text" name="headline" class="form-control" placeholder="Enter Headline" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="title" class="form-control" placeholder="Enter Title" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Image</label>
                                        <input type="file" name="image" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-success form-control">Submit</button>
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

<a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
<?php include_once "../include/closed_html.php"; ?>
