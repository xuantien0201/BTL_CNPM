<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['add'])) {
        $masv = $_POST['masv'];
        $hoten = $_POST['hoten'];
        $lop = $_POST['lop'];
        $khoa = $_POST['khoa'];
        $email = $_POST['email'];

        // Adjust the query to insert the new fields into the database
        $sql = "INSERT INTO tblstudents(MaSV, Hoten, Lop, Khoa, Email) VALUES(:masv, :hoten, :lop, :khoa, :email)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':masv', $masv, PDO::PARAM_STR);
        $query->bindParam(':hoten', $hoten, PDO::PARAM_STR);
        $query->bindParam(':lop', $lop, PDO::PARAM_STR);
        $query->bindParam(':khoa', $khoa, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if ($lastInsertId) {
            $msg = "Student information added successfully";
        } else {
            $error = "Something went wrong. Please try again";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title -->
    <title>Admin | Add Student Information</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }

        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>
    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <div class="page-title">Add Student Information</div>
            </div>
            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <form class="col s12" method="post">
                                <?php if ($error) { ?>
                                    <div class="errorWrap"><strong>ERROR</strong> : <?php echo htmlentities($error); ?></div>
                                <?php } else if ($msg) { ?>
                                    <div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?></div>
                                <?php } ?>
                                <div class="row">
                                    <!-- MaSV Input -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="masv" class="control-label">Mã Sinh Viên</label>
                                            <input id="masv" type="text" name="masv" required>
                                        </div>
                                    </div>

                                    <!-- HoTen Input -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hoten" class="control-label">Họ Tên</label>
                                            <input id="hoten" type="text" name="hoten" required>
                                        </div>
                                    </div>

                                    <!-- Lop Input -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lop" class="control-label">Lớp</label>
                                            <input id="lop" type="text" name="lop" required>
                                        </div>
                                    </div>

                                    <!-- Khoa Input -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="khoa" class="control-label">Khoa</label>
                                            <input id="khoa" type="text" name="khoa" required>
                                        </div>
                                    </div>

                                    <!-- Email Input -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="control-label">Email</label>
                                            <input id="email" type="email" name="email" required>
                                        </div>
                                    </div>

                                    <!-- Submit and Cancel Buttons -->
                                    <div class="col s12 text-right justify-content-center d-flex">
                                        <button type="submit" name="add" class="waves-effect waves-light btn indigo m-b-xs">
                                            Add
                                        </button>
                                        <button type="button" class="waves-effect waves-light btn secondary m-b-xs"
                                            onclick="location.href='index.php'">Cancel</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    </div>
    <div class="left-sidebar-hover"></div>

    <!-- Javascripts -->
    <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="../assets/js/alpha.min.js"></script>
    <script src="../assets/js/pages/form_elements.js"></script>

</body>

</html>
<?php } ?>
