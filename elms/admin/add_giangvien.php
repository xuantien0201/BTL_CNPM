<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['add'])) {
        $magv = $_POST['magiangvien'];
        $tengv = $_POST['tengiangvien'];
        $khoagv = $_POST['khoa_giangvien'];
        $emailgv = $_POST['email_giangvien'];
        $mk = '123456';

        $sql = "INSERT INTO tblgiangvien (magiangvien, tengiangvien, khoa_giangvien, email_giangvien, mk_giangvien) VALUES ('$magv','$tengv','$khoagv','$emailgv','$mk')";
        $query = $dbh->query($sql);

        if ($query) {
            $msg = "Thêm thành công";
        } else {
            $error = "Lỗi! Thử lại";
        }
    }
    ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Giảng Viên</title>
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <div class="page-title">Thêm Giảng Viên</div>
            </div>
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <form method="POST">
                            <!-- Hiển thị thông báo lỗi hoặc thành công -->
                            <?php if ($error) { ?>
                                <div class="error"><strong>ERROR</strong> : <?php echo htmlentities($error); ?></div>
                            <?php } else if ($msg) { ?>
                                <div class="succesful"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?></div>
                            <?php } ?>

                            <!-- Form nhập thông tin giảng viên -->
                            <div class="row">
                                <div class="input-field col s6">
                                    <input id="magiangvien" type="text" name="magiangvien" required>
                                    <label for="magiangvien">Mã Giảng Viên</label>
                                </div>
                                <div class="input-field col s6">
                                    <input id="tengiangvien" type="text" name="tengiangvien" required>
                                    <label for="tengiangvien">Tên Giảng Viên</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s6">
                                    <input id="khoa_giangvien" type="text" name="khoa_giangvien" required>
                                    <label for="khoa_giangvien">Khoa</label>
                                </div>
                                <div class="input-field col s6">
                                    <input id="email_giangvien" type="email" name="email_giangvien" required>
                                    <label for="email_giangvien">Email</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 text-right">
                                    <button type="submit" name="add" class="waves-effect waves-light btn indigo">Thêm</button>
                                    <button type="button" class="waves-effect waves-light btn secondary" onclick="location.href='ds_giangvien.php'">Hủy</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="../assets/js/alpha.min.js"></script>
</body>
</html>
<?php } ?>