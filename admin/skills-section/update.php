<?php
include_once "../include/open_html.php";
include_once "../config/db.php";

$msg = "";

// Get skill ID
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $skill_id = $_GET['id'];
} else {
    die("<div class='alert alert-danger'>Invalid Skill ID</div>");
}

// Fetch skill record
$stmt = $conn->prepare("SELECT * FROM skill_section WHERE id = ?");
$stmt->bind_param("i", $skill_id);
$stmt->execute();
$result = $stmt->get_result();
$skill = $result->fetch_assoc();
$stmt->close();

if(!$skill){
    die("<div class='alert alert-danger'>Skill not found</div>");
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $title = trim($_POST['title']);
    $expert = trim($_POST['expert']);

    if(empty($title) || empty($expert)){
        $msg = "<div class='alert alert-danger'>All fields are required!</div>";
    } else {
        $stmt = $conn->prepare("UPDATE skill_section SET title = ?, expert = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $expert, $skill_id);

        if($stmt->execute()){
            $msg = "<div class='alert alert-success'>Skill Section Updated Successfully!</div>";
            // Refresh skill data
            $skill['title'] = $title;
            $skill['expert'] = $expert;
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
                            <div class="card-header">
                                <h5>Update Skill Section</h5>
                            </div>
                            <div class="card-body">

                                <?= $msg ?>

                                <form action="?id=<?= $skill_id ?>" method="post">
                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="title" class="form-control"
                                               value="<?= htmlspecialchars($skill['title']) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Expert</label>
                                        <input type="text" name="expert" class="form-control"
                                               value="<?= htmlspecialchars($skill['expert']) ?>" required>
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

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<?php include_once "../include/closed_html.php"; ?>
