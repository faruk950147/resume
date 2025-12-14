<?php
include_once "../include/open_html.php";
include_once "../config/db.php";

$msg = "";

// Check if ID is provided
if(!isset($_GET['id']) || empty($_GET['id'])){
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// Fetch existing record
$stmt = $conn->prepare("SELECT * FROM professional_experience_section WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    $stmt->close();
    header("Location: index.php");
    exit;
}

$record = $result->fetch_assoc();
$stmt->close();

// Handle POST request
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $title       = trim($_POST['title']);
    $date        = trim($_POST['date']);
    $address     = trim($_POST['address']);
    $description = trim($_POST['description']);

    if(empty($title) || empty($date) || empty($address) || empty($description)){
        $msg = "All fields are required!";
    } else {
        $stmt = $conn->prepare("UPDATE professional_experience_section SET title = ?, date = ?, address = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $title, $date, $address, $description, $id);

        if($stmt->execute()){
            $msg = "Professional Experience Content updated successfully!";
            $stmt->close();
            header("Location: index.php");
            exit;
        } else {
            $msg = "Database Error: " . $stmt->error;
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
                            <div class="card-header"><h5>Update Professional Experience Content</h5></div>
                            <div class="card-body">

                                <?php if(!empty($msg)): ?>
                                    <div class="alert <?= strpos($msg, 'Error') !== false || strpos($msg, 'required') !== false ? 'alert-danger' : 'alert-success' ?>">
                                        <?= $msg ?>
                                    </div>
                                <?php endif; ?>

                                <form method="post">
                                    <div class="mb-3">
                                        <label>Title</label>
                                        <input type="text" name="title" class="form-control" 
                                            value="<?= htmlspecialchars($_POST['title'] ?? $record['title']) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Date</label>
                                        <input type="date" name="date" class="form-control" 
                                            value="<?= htmlspecialchars($_POST['date'] ?? $record['date']) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Address</label>
                                        <textarea name="address" class="form-control" rows="3" required><?= htmlspecialchars($_POST['address'] ?? $record['address']) ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control" rows="5" required><?= htmlspecialchars($_POST['description'] ?? $record['description']) ?></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-success form-control">Submit</button>
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
