<?php
include_once "../include/open_html.php";
include_once "../config/db.php";

$msg = '';
$msg_type = ''; // success or danger

// Get latest service data (only 1 row)
$result = $conn->query("SELECT * FROM services ORDER BY id DESC LIMIT 1");
$services = $result->fetch_assoc();

// Handle POST request
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $title = trim($_POST['title']);

    if(empty($title)){
        $msg = "Title field is required!";
        $msg_type = 'danger';
    } else {
        if($services){
            // UPDATE existing record
            $stmt = $conn->prepare("UPDATE services SET title=? WHERE id=?");
            $stmt->bind_param("si", $title, $services['id']);
            $action = "updated";
        } else {
            // INSERT new record
            $stmt = $conn->prepare("INSERT INTO services (title) VALUES (?)");
            $stmt->bind_param("s", $title);
            $action = "created";
        }

        if($stmt->execute()){
            $msg = "Service $action successfully!";
            $msg_type = 'success';
            $stmt->close();

            // Assign the new data to $services
            if($action == "created"){
                $services['id'] = $conn->insert_id;
            }
            $services['title'] = $title;
        } else {
            $msg = "Database Error: " . $stmt->error;
            $msg_type = 'danger';
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
                                <h5>Services Content</h5>

                                <?php if(!empty($msg)): ?>
                                    <div class="alert alert-<?= $msg_type ?> mt-2" role="alert">
                                        <?= htmlspecialchars($msg) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="card-body">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input 
                                            type="text" 
                                            name="title" 
                                            class="form-control" 
                                            placeholder="Enter Title" 
                                            id="title" 
                                            value="<?= htmlspecialchars($services['title'] ?? '') ?>" 
                                            required
                                        >
                                    </div>

                                    <button type="submit" class="btn btn-success w-100">Submit</button>
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
