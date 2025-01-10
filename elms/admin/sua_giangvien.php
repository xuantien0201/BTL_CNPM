<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['update'])) {
        $magiangvien = $_GET['magiangvien'];
        $tengiangvien = $_POST['tengiangvien'];
        $khoa_giangvien = $_POST['khoa_giangvien'];
        $email_giangvien = $_POST['email_giangvien'];
        $mk_giangvien = $_POST['mk_giangvien'];
        
        // SQL Query to update giangvien data
        $sql = "UPDATE tblgiangvien 
                SET tengiangvien = :tengiangvien, khoa_giangvien = :khoa_giangvien, email_giangvien = :email_giangvien, mk_giangvien = :mk_giangvien
                WHERE magiangvien = :magiangvien";
        $query = $dbh->prepare($sql);
        $query->bindParam(':magiangvien', $magiangvien, PDO::PARAM_STR);
        $query->bindParam(':tengiangvien', $tengiangvien, PDO::PARAM_STR);
        $query->bindParam(':khoa_giangvien', $khoa_giangvien, PDO::PARAM_STR);
        $query->bindParam(':email_giangvien', $email_giangvien, PDO::PARAM_STR);
        $query->bindParam(':mk_giangvien', $mk_giangvien, PDO::PARAM_STR);
        $query->execute();
        $msg = "Cập nhật thông tin giảng viên thành công.";
        header('location:ds_giangvien.php');
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản trị | Chỉnh Sửa Giảng Viên</title>
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css"/>
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />
    <style>
        .error {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }

        .succesful {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
        .page-title {
            font-size: 30px;
            margin-bottom: 20px;
            color: #3e3e3e;
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
        .input-field label {
            font-size: 18px; 
        }
        
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <div class="page-title">Chỉnh Sửa Giảng Viên</div>
            </div>

            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <form class="col s12" method="post">
                                <?php if ($error) { ?>
                                    <div class="error"><strong>ERROR</strong> : <?php echo htmlentities($error); ?></div>
                                <?php } else if ($msg) { ?>
                                    <div class="succesful"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?></div>
                                <?php } ?>
                                
                                <div class="row">
                                    <?php
                                    if (isset($_GET['magiangvien'])) {
                                        $magiangvien = $_GET['magiangvien'];
                                        $sql = "SELECT * FROM tblgiangvien WHERE magiangvien = :magiangvien";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':magiangvien', $magiangvien, PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) {
                                    ?>
                                        <div class="input-field col s12">
                                            <input id="magiangvien" readonly type="text" name="magiangvien" value="<?php echo htmlentities($result->magiangvien); ?>" required >
                                            <label for="magiangvien">Mã Giảng Viên</label>
                                        </div>

                                        <div class="input-field col s12">
                                            <input id="tengiangvien" type="text" name="tengiangvien" value="<?php echo htmlentities($result->tengiangvien); ?>" required>
                                            <label for="tengiangvien">Tên Giảng Viên</label>
                                        </div>

                                        <div class="input-field col s12">
                                            <input id="khoa_giangvien" type="text" name="khoa_giangvien" value="<?php echo htmlentities($result->khoa_giangvien); ?>" required>
                                            <label for="khoa_giangvien">Khoa Giảng Viên</label>
                                        </div>

                                        <div class="input-field col s12">
                                            <input id="email_giangvien" type="email" name="email_giangvien" value="<?php echo htmlentities($result->email_giangvien); ?>" required>
                                            <label for="email_giangvien">Email Giảng Viên</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input id="email_giangvien" type="text" name="mk_giangvien" value="<?php echo htmlentities($result->mk_giangvien); ?>" required>
                                            <label for="email_giangvien">Mật khẩu tài khoản</label>
                                        </div>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="col s12 text-right">
                                        <button type="submit" name="update" class="waves-effect waves-light btn indigo m-b-xs">Cập Nhật</button>
                                        <button type="button" class="waves-effect waves-light btn secondary m-b-xs" onclick="location.href='ds_giangvien.php'">Quay Lại</button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
