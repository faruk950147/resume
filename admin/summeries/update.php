<?php
include_once "../include/open_html.php";
include_once "../config/db.php";

$msg = "";

// Get Summaries ID safely
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $summeries_id = $_GET['id'];
} else {
    die("<div class='alert alert-danger'>Invalid Summaries ID</div>");
}

// Fetch existing summaries
$stmt = $conn->prepare("SELECT * FROM summeries_section WHERE id = ?");
$stmt->bind_param("i", $summeries_id);
$stmt->execute();
$result = $stmt->get_result();
$summeries = $result->fetch_assoc();
$stmt->close();

if (!$summeries) die("<div class='alert alert-danger'>Summaries not found</div>");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title   = trim($_POST['title']);
    $bio     = trim($_POST['bio']);
    $address = trim($_POST['address']);
    $phone   = trim($_POST['phone']);
    $email   = trim($_POST['email']);

    if (empty($title) || empty($bio) || empty($address) || empty($phone) || empty($email)) {
        $msg = "All fields are required!";
    } else {
        $stmt = $conn->prepare("
            UPDATE summeries_section
            SET title = ?, bio = ?, address = ?, phone = ?, email = ?
            WHERE id = ?
        ");
        $stmt->bind_param("sssssi", $title, $bio, $address, $phone, $email, $summeries_id);

        if ($stmt->execute()) {
            $msg = "Summaries updated successfully!";
            // Refresh $summeries array
            $summeries['title']   = $title;
            $summeries['bio']     = $bio;
            $summeries['address'] = $address;
            $summeries['phone']   = $phone;
            $summeries['email']   = $email;
        } else {
            $msg = "Update Failed!";
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
                                <h5>Update Summaries Section</h5>
                            </div>
                            <div class="card-body">

                                <?php if (!empty($msg)) : ?>
                                    <div class="alert <?= strpos($msg, 'Failed') !== false || strpos($msg, 'required') !== false ? 'alert-danger' : 'alert-success' ?>">
                                        <?= htmlspecialchars($msg) ?>
                                    </div>
                                <?php endif; ?>

                                <form action="?id=<?= $summeries_id ?>" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="title" class="form-control"
                                               value="<?= htmlspecialchars($summeries['title']) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Bio</label>
                                        <textarea name="bio" class="form-control" rows="3" required><?= htmlspecialchars($summeries['bio']) ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" class="form-control" rows="2" required><?= htmlspecialchars($summeries['address']) ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control"
                                               value="<?= htmlspecialchars($summeries['phone']) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control"
                                               value="<?= htmlspecialchars($summeries['email']) ?>" required>
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

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<?php include_once "../include/closed_html.php"; ?>
