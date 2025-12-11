<?php
include_once "../include/open_html.php";
include_once "../config/db.php";

$error = "";
$success = "";

// Fetch existing data
$hero = $conn->query("SELECT * FROM hero_section WHERE id=1")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $headline = $_POST['headline'];
    $title = $_POST['title'];

    // Default to existing image
    $new_image_name = $hero['image'];

    // Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($image_ext, $allowed_ext)) {
            $new_image_name = uniqid('hero_', true) . '.' . $image_ext;
            $upload_path = '../../uploads/' . $new_image_name;

            if (!move_uploaded_file($image_tmp, $upload_path)) {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid image type. Only JPG, PNG, GIF allowed.";
        }
    }

    // Update Database if no error
    if (empty($error)) {
        $stmt = $conn->prepare("UPDATE hero_section SET headline=?, title=?, image=? WHERE id=1");
        $stmt->bind_param("sss", $headline, $title, $new_image_name);

        if ($stmt->execute()) {
            $success = "Hero content updated successfully!";
            $hero['headline'] = $headline;
            $hero['title'] = $title;
            $hero['image'] = $new_image_name;
        } else {
            $error = "Database error: " . $conn->error;
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
                            <div class="card-header">
                                <h5>Update Hero Content</h5>
                            </div>
                            <div class="card-body">

                                <?php if(!empty($error)): ?>
                                    <div class="alert alert-danger"><?= $error ?></div>
                                <?php endif; ?>

                                <?php if(!empty($success)): ?>
                                    <div class="alert alert-success"><?= $success ?></div>
                                <?php endif; ?>

                                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                                    <div class="row">

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Headline</label>
                                            <input type="text" name="headline" required class="form-control" value="<?= htmlspecialchars($hero['headline']) ?>">
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="title" required class="form-control" value="<?= htmlspecialchars($hero['title']) ?>">
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Current Image</label><br>
                                            <?php if(!empty($hero['image'])): ?>
                                                <img src="../../uploads/<?= $hero['image'] ?>" width="150" alt="Hero Image">
                                            <?php endif; ?>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Change Image</label>
                                            <input type="file" name="image" class="form-control">
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <button type="submit" class="btn btn-success form-control">Update</button>
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
