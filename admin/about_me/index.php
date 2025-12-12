<?php
include_once "../include/open_html.php";
include_once "../config/db.php";

// File Upload Function
function fileUpload($image){
    $path = '../assets/img/about-me/';

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
    $name = trim($_POST['name']);

}

?>

<div id="wrapper">
    <?php include_once "../include/sidebar.php"; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include_once "../include/topbar.php"; ?>
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="card">
                            <div class="card-header">
                                <h5>Create About Content</h5>
                            </div>
                            <div class="card-body">
                                <form action="">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control" placeholder="Enter Name" id="name">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="profession" class="form-label">Profession</label>
                                            <input type="text" name="profession" class="form-control" placeholder="Enter Profession" id="profession">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" placeholder="Enter Email" id="email">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="number" name="phone" class="form-control" placeholder="Enter Phone" id="phone">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="description" class="form-label">Descrption</label>
                                            <textarea class="form-control" name="description" placeholder="Enter Description" id="description"></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Image</label>
                                            <input type="file" name="image" class="form-control" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <button type="submit" class="btn btn-success">Submit</button>
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

<?php include_once "../include/closed_html.php"; ?>
