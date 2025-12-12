<?php
include_once "../include/open_html.php";
include_once "../config/db.php";

// Create Table if not exists
$create_table = "CREATE TABLE IF NOT EXISTS skill_section (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    expert VARCHAR(255)
)";
$conn->query($create_table);

$error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $title = trim($_POST['title']);
    $expert = trim($_POST['expert']);

    if(empty($title) || empty($expert)){
        $error = "All fields are required!";
    } else {
        $stmt = $conn->prepare("INSERT INTO skill_section (title, expert) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $expert);
        $stmt->execute();
        $stmt->close();

        header("Location: index.php");
        exit;
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
                            <div class="card-header"><h5>Create Skill Content</h5></div>
                            <div class="card-body">

                                <?php if(!empty($error)): ?>
                                    <div class="alert alert-danger"><?= $error ?></div>
                                <?php endif; ?>

                                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="title" class="form-control" placeholder="Enter Title" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Expert</label>
                                        <input type="text" name="expert" class="form-control" placeholder="Enter Expert" required>
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
