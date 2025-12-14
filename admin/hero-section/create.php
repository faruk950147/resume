<?php
include_once "../include/open_html.php";
// db config
include_once "../config/db.php";
// File Upload Function
include_once "../utilities/fileUpload.php";

// Create Table if not exists
$create_table = "CREATE TABLE IF NOT EXISTS hero_section (
    id INT AUTO_INCREMENT PRIMARY KEY,
    headline VARCHAR(255),
    title VARCHAR(255),
    image TEXT
)";
$conn->query($create_table);

$msg = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $headline = trim($_POST['headline']);
    $title = trim($_POST['title']);

    if(empty($headline) || empty($title)){
        $msg = "All fields are required!";
    } elseif(!isset($_FILES['image']) || empty($_FILES['image']['name'])){
        $msg = "Image is required!";
    } else {
        $upload = fileUpload($_FILES['image']);

        if(isset($upload['error'])){
            $msg = $upload['error'];
        } else {
            $uploadedFileName = $upload['name'];

            $stmt = $conn->prepare("INSERT INTO hero_section (headline, title, image) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $headline, $title, $uploadedFileName);

            if($stmt->execute()){
                $msg = "Hero Content created successfully!";
                $stmt->close();
                // redirect after success (optional)
                header("Location: index.php");
                exit;
            } else {
                $msg = "Database Error: " . $stmt->error;
            }
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

                                <?php if(!empty($msg)): ?>
                                    <div class="alert <?= strpos($msg, 'Error') !== false || strpos($msg, 'required') !== false ? 'alert-danger' : 'alert-success' ?>">
                                        <?= $msg ?>
                                    </div>
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
