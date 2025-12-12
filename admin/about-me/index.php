<?php
include_once "../include/open_html.php";
include_once "../config/db.php";
// File Upload Function
include_once "../utilities/fileUpload.php";
$error = "";
$success = "";

// Fetch the latest record (if any)
$result = $conn->query("SELECT * FROM about_me ORDER BY id DESC LIMIT 1");
$about_me = $result->fetch_assoc();

// Handle POST request for insert or update
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = trim($_POST['name']);
    $profession = trim($_POST['profession']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $description = trim($_POST['description']);

    // Keep old image if new not selected
    $new_name = $about_me['image'] ?? '';

    // Handle new image upload
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        $upload = fileUpload($_FILES['image']); // Make sure fileUpload returns ['name'] or ['error']

        if(isset($upload['error'])){
            $error = $upload['error'];
        } else {
            $new_name = $upload['name'];

            // Delete old image if exists
            if(!empty($about_me['image'])){
                $old_file = "../assets/media/" . $about_me['image'];
                if(file_exists($old_file)){
                    unlink($old_file);
                }
            }
        }
    }

    if(empty($error)){
        if($about_me){ 
            // Update existing record using prepared statement
            $stmt = $conn->prepare("UPDATE about_me SET name=?, profession=?, email=?, phone=?, description=?, image=? WHERE id=?");
            $stmt->bind_param("ssssssi", $name, $profession, $email, $phone, $description, $new_name, $about_me['id']);
            $action = "updated";
        } else { 
            // Insert new record using prepared statement
            $stmt = $conn->prepare("INSERT INTO about_me (name, profession, email, phone, description, image) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $profession, $email, $phone, $description, $new_name);
            $action = "created";
        }

        if($stmt->execute()){
            $success = "About Me $action successfully!";
            $stmt->close();

            // Refresh $about_me
            $result = $conn->query("SELECT * FROM about_me ORDER BY id DESC LIMIT 1");
            $about_me = $result->fetch_assoc();
        } else {
            $error = "Database Error: " . $stmt->error;
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
                                <h5>About Content</h5>
                                <?php if($success): ?>
                                    <p style="color:green;"><?= $success ?></p>
                                <?php endif; ?>
                                <?php if($error): ?>
                                    <p style="color:red;"><?= $error ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control" placeholder="Enter Name" id="name" 
                                                   value="<?= htmlspecialchars($about_me['name'] ?? '') ?>" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="profession" class="form-label">Profession</label>
                                            <input type="text" name="profession" class="form-control" placeholder="Enter Profession" id="profession" 
                                                   value="<?= htmlspecialchars($about_me['profession'] ?? '') ?>" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" placeholder="Enter Email" id="email" 
                                                   value="<?= htmlspecialchars($about_me['email'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" name="phone" class="form-control" placeholder="Enter Phone" id="phone" 
                                                   value="<?= htmlspecialchars($about_me['phone'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" name="description" placeholder="Enter Description" id="description" required><?= htmlspecialchars($about_me['description'] ?? '') ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Current Image</label><br>
                                            <?php if(!empty($about_me['image'])): ?>
                                                <img src="../assets/media/<?= $about_me['image'] ?>" 
                                                    width="100" height="100"
                                                    style="border:1px solid #ddd; padding:3px;">
                                            <?php else: ?>
                                                <p>No image uploaded.</p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Image</label>
                                            <input type="file" name="image" class="form-control">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <button type="submit" class="btn btn-success form-conrol">Submit</button>
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
