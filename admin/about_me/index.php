<?php
include_once "../include/open_html.php";
// db config
include_once "../config/db.php";

// File Upload Function
include_once "../utilities/fileUpload.php";

// Create Table if not exists
$create_table = "CREATE TABLE IF NOT EXISTS about_me (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    profession VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(15),
    description TEXT,
    image TEXT
)";
$conn->query($create_table);

$error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = trim($_POST['name']);
    $profession = trim($_POST['profession']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $description = trim($_POST['description']);
    
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
