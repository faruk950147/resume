<?php
include_once "../include/open_html.php";
?>

<?php
// Database
// include_once "../config/db.php";

?>



<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <?php include_once "../include/sidebar.php"; ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <?php include_once "../include/topbar.php"; ?>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Hero Section</h1>
                    <a href="create.php" class="btn btn-sm btn-primary shadow-sm">
                        Create Hero Section
                    </a>
                </div>

                <div class="row">
                    <div class="col-12">

                        <table class="table table-bordered" id="datatable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Headline</th>
                                    <th>Title</th>
                                    <th>Hero Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
          
                            </tbody>

                        </table>

                    </div>
                </div>

            </div>
            <!-- End Page Content -->

        </div>
        <!-- End Main Content -->

        <?php include_once "../include/footer.php"; ?>

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button -->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<?php include_once "../include/closed_html.php"; ?>
