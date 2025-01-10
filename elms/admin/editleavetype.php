<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['update'])) {
        $masv = $_POST['masv']; 
        $hoten = $_POST['hoten'];
        $lop = $_POST['lop'];
        $khoa = $_POST['khoa'];
        $email = $_POST['email'];

        // Update query that doesn't modify the masv, only other fields
        $sql = "UPDATE sinh_vien SET Hoten=:hoten, Lop=:lop, Khoa=:khoa, Email=:email WHERE MaSV=:masv";
        $query = $dbh->prepare($sql);
        $query->bindParam(':masv', $masv, PDO::PARAM_STR);
        $query->bindParam(':hoten', $hoten, PDO::PARAM_STR);
        $query->bindParam(':lop', $lop, PDO::PARAM_STR);
        $query->bindParam(':khoa', $khoa, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $msg = "Cập nhật thông tin sinh viên thành công";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Admin | Chỉnh Sửa Thông Tin Sinh Viên</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css"/>
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css"/>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <div class="page-title">Chỉnh Sửa Thông Tin Sinh Viên</div>
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
                                <?php
                                $masv = intval($_GET['masv']); // Get student ID from URL
                                $sql = "SELECT * FROM sinh_vien WHERE MaSV=:masv";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':masv', $masv, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) { ?>
                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input id="masv" type="text" class="validate" autocomplete="off"
                                                       name="masv" value="<?php echo htmlentities($result->MaSV); ?>"
                                                       required readonly>
                                                <label for="masv">Mã Sinh Viên</label>
                                            </div>

                                            <div class="input-field col s12">
                                                <input id="hoten" type="text" class="validate" autocomplete="off"
                                                       name="hoten" value="<?php echo htmlentities($result->Hoten); ?>"
                                                       required>
                                                <label for="hoten">Họ Tên</label>
                                            </div>

                                            <div class="input-field col s12">
                                                <input id="lop" type="text" class="validate" autocomplete="off"
                                                       name="lop" value="<?php echo htmlentities($result->Lop); ?>" required>
                                                <label for="lop">Lớp</label>
                                            </div>

                                            <div class="input-field col s12">
                                                <input id="khoa" type="text" class="validate" autocomplete="off"
                                                       name="khoa" value="<?php echo htmlentities($result->Khoa); ?>" required>
                                                <label for="khoa">Khoa</label>
                                            </div>

                                            <div class="input-field col s12">
                                                <input id="email" type="email" class="validate" autocomplete="off"
                                                       name="email" value="<?php echo htmlentities($result->Email); ?>" required>
                                                <label for="email">Email</label>
                                            </div>
                                        </div>
                                <?php }} ?>

                                <div class="input-field col s12">
                                    <button type="submit" name="update" class="waves-effect waves-light btn indigo m-b-xs">
                                        Cập nhật
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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
