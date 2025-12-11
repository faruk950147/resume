<?php
include_once "../include/open_html.php";
include_once "../config/db.php";

// File Upload Function (Same as create.php)
function fileUpload($image){
    $dir = '../assets/img/hero-section/';

    if(!file_exists($dir)){
        mkdir($dir, 0755, true);
    }

    if($image['error'] !== UPLOAD_ERR_OK){
        return ["error" => "File upload error!"];
    }

    $allowed_ext = ['jpg','jpeg','png','webp'];
    $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

    if(!in_array($ext, $allowed_ext)){
        return ["error" => "Invalid file type!"];
    }

    if(!getimagesize($image['tmp_name'])){
        return ["error" => "Uploaded file is not a valid image!"];
    }

    if($image['size'] > 2 * 1024 * 1024){
        return ["error" => "File size must be under 2MB!"];
    }

    $newName = bin2hex(random_bytes(8)) . "." . $ext;
    $fileDir = $dir . $newName;

    if(move_uploaded_file($image['tmp_name'], $fileDir)){
        return ["name" => $newName];
    }else{
        return ["error" => "File not uploaded!"];
    }
}

// Get Hero ID
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $hero_id = $_GET['id'];
} else {
    die("Invalid Hero ID");
}

// Fetch hero
$query = $conn->query("SELECT * FROM hero_section WHERE id = $hero_id");
$hero = $query->fetch_assoc();
if(!$hero) die("Hero not found");

$msg = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $headline = trim($_POST['headline']);
    $title = trim($_POST['title']);

    if(empty($headline) || empty($title)){
        $msg = "All fields are required!";
    } else {
        $new_name = $hero['image']; // default current image

        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
            $upload = fileUpload($_FILES['image']);

            if(isset($upload['error'])){
                $msg = $upload['error'];
            } else {
                $new_name = $upload['name'];

                // Delete old image
                $old_file = "../assets/img/hero-section/" . $hero['image'];
                if(!empty($hero['image']) && file_exists($old_file)){
                    unlink($old_file);
                }
            }
        }

        // Update hero using prepared statement
        $stmt = $conn->prepare("UPDATE hero_section SET headline=?, title=?, image=? WHERE id=?");
        $stmt->bind_param("sssi", $headline, $title, $new_name, $hero_id);
        if($stmt->execute()){
            $msg = "<div class='alert alert-success'>Hero Section Updated!</div>";
            $query = $conn->query("SELECT * FROM hero_section WHERE id = $hero_id");
            $hero = $query->fetch_assoc();
        } else {
            $msg = "<div class='alert alert-danger'>Update Failed!</div>";
        }
        $stmt->close();
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
                            <div class="card-header"><h5>Update Hero Content</h5></div>
                            <div class="card-body">

                                <?php if(!empty($msg)) echo $msg; ?>

                                <form action="<?= $_SERVER['PHP_SELF'] . "?id=" . $hero_id ?>" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label">Headline</label>
                                        <input type="text" name="headline" class="form-control" value="<?= htmlspecialchars($hero['headline']) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($hero['title']) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Current Image</label><br>
                                        <?php if(!empty($hero['image'])): ?>
                                            <img src="/admin/assets/img/hero-section/<?= $hero['image'] ?>" width="150" alt="Hero Image">
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Upload New Image (optional)</label>
                                        <input type="file" name="image" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-success form-control">Update</button>
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
