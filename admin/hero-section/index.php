<?php
include_once "../include/open_html.php";
include_once "../config/db.php";



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
                                    <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </a>
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
