<?php
include_once "../include/open_html.php";
include_once "../config/db.php";

// ========================
// DELETE FUNCTION (POST)
// ========================
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id']) && is_numeric($_POST['delete_id'])){
    $id = $_POST['delete_id'];

    // Step 1: get image
    $stmt = $conn->prepare("SELECT image FROM hero_section WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();

    // Step 2: delete image file
    if(!empty($image)){
        $path = "../assets/img/hero-section/" . $image;
        if(file_exists($path)){
            unlink($path);
        }
    }

    // Step 3: delete record
    $stmt = $conn->prepare("DELETE FROM hero_section WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php?msg=deleted");
    exit;
}

// Fetch hero sections
$query = "SELECT * FROM hero_section ORDER BY id DESC";
$result = $conn->query($query);
?>

<div id="wrapper">
    <?php include_once "../include/sidebar.php"; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include_once "../include/topbar.php"; ?>

            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Hero Section</h1>
                    <a href="create.php" class="btn btn-sm btn-primary">Create Hero Section</a>
                </div>

                <?php if(isset($_GET['msg']) && $_GET['msg'] == "deleted"): ?>
                    <div class="alert alert-success">Hero Section deleted successfully.</div>
                <?php endif; ?>

                <table class="table table-bordered" id="datatable">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Headline</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if($result->num_rows > 0){
                        $sl = 1;
                        while($row = $result->fetch_assoc()){ ?>
                            <tr>
                                <td><?= $sl++ ?></td>
                                <td><?= htmlspecialchars($row['headline']) ?></td>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td>
                                    <?php if(!empty($row['image'])): ?>
                                        <img src="../assets/img/hero-section/<?= $row['image'] ?>" width="100">
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- DELETE POST FORM -->
                                    <form method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this?');">
                                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr><td colspan="5" class="text-center">No records found.</td></tr>
                    <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>

        <?php include_once "../include/footer.php"; ?>
    </div>
</div>

<?php include_once "../include/closed_html.php"; ?>
