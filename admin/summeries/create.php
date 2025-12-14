<?php
include_once "../include/open_html.php";
include_once "../config/db.php";

$msg = "";

// Create table if not exists
$conn->query("
CREATE TABLE IF NOT EXISTS summeries_section (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    bio TEXT NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL
)
");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $title   = trim($_POST['title']);
    $bio     = trim($_POST['bio']);
    $address = trim($_POST['address']);
    $phone   = trim($_POST['phone']);
    $email   = trim($_POST['email']);

    if(empty($title) || empty($bio) || empty($address) || empty($phone) || empty($email)){
        $msg = "All fields are required!";
    } else {
        $stmt = $conn->prepare("INSERT INTO summeries_section (title, bio, address, phone, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $bio, $address, $phone, $email);

        if($stmt->execute()){
            $msg = "Summery Content created successfully!";
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
                            <div class="card-header"><h5>Create Summery Content</h5></div>
                            <div class="card-body">

                                <?php if(!empty($msg)): ?>
                                    <div class="alert <?= strpos($msg, 'Error') !== false || strpos($msg, 'required') !== false ? 'alert-danger' : 'alert-success' ?>">
                                        <?= $msg ?>
                                    </div>
                                <?php endif; ?>

                                <form method="post">
                                    <div class="mb-3">
                                        <label>Title</label>
                                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Bio</label>
                                        <textarea name="bio" class="form-control" rows="3" required><?= htmlspecialchars($_POST['bio'] ?? '') ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label>Address</label>
                                        <textarea name="address" class="form-control" rows="2" required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label>Phone</label>
                                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
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
