<?php
include_once "../include/open_html.php";
include_once "../config/db.php";

$msg = '';

// Get latest resume data (only 1 row)
$result = $conn->query("SELECT * FROM resume ORDER BY id DESC LIMIT 1");
$resume = $result->fetch_assoc();

// Handle POST request
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $title = trim($_POST['title']);

    if(empty($title)){
        $msg = "Title field is required!";
    }

    if(empty($msg)){
        if($resume){
            // UPDATE existing record
            $stmt = $conn->prepare("UPDATE resume SET title=? WHERE id=?");
            $stmt->bind_param("si", $title, $resume['id']);
            $action = "updated";
        } else {
            // INSERT new record
            $stmt = $conn->prepare("INSERT INTO resume (title) VALUES (?)");
            $stmt->bind_param("s", $title);
            $action = "created";
        }

        if($stmt->execute()){
            $msg = "Resume $action successfully!";
            $stmt->close();

            // Refresh latest data
            $result = $conn->query("SELECT * FROM resume ORDER BY id DESC LIMIT 1");
            $resume = $result->fetch_assoc();
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

                            <div class="card-header">
                                <h5>Resume Content</h5>

                                <?php if(!empty($msg)): ?>
                                    <p style="color:<?= strpos($msg, 'Error') !== false || strpos($msg, 'required') !== false ? 'red' : 'green' ?>;">
                                        <?= $msg ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <div class="card-body">
                                <form action="" method="POST">
                                    <div class="row">

                                        <div class="col-md-12 mb-3">
                                            <label for="title" class="form-label">Title</label>
                                            <input 
                                                type="text" 
                                                name="title" 
                                                class="form-control" 
                                                placeholder="Enter Title" 
                                                id="title" 
                                                value="<?= htmlspecialchars($resume['title'] ?? '') ?>" 
                                                required
                                            >
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

<?php include_once "../include/closed_html.php"; ?>
