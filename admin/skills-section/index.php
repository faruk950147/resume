<?php
include_once "../include/open_html.php";
include_once "../config/db.php";

// Fetch Skill sections
$query = "SELECT * FROM skill_section ORDER BY id DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<div id="wrapper">
    <?php include_once "../include/sidebar.php"; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include_once "../include/topbar.php"; ?>

            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Skill Section</h1>
                    <a href="create.php" class="btn btn-sm btn-primary">Create Skill Section</a>
                </div>

                <table class="table table-bordered" id="datatable">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Title</th>
                            <th>Expert</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if($result->num_rows > 0){
                        $sl = 1;
                        while($row = $result->fetch_assoc()): 
                    ?>
                            <tr>
                                <td><?= $sl++ ?></td>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= htmlspecialchars($row['expert']) ?></td>
                                
                                <td>
                                    <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form method="POST" action="delete.php" style="display:inline;" onsubmit="return confirm('Are you sure to delete?');">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile;
                    } else { ?>
                        <tr><td colspan="4" class="text-center">No records found.</td></tr>
                    <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>

        <?php include_once "../include/footer.php"; ?>
    </div>
</div>

<?php include_once "../include/closed_html.php"; ?>
