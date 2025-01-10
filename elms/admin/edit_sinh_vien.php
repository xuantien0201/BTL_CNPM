<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['update'])) {
        $masv = $_GET['masv'];
        $hoten = $_POST['hoten'];
        $lop = $_POST['lop'];
        $khoa = $_POST['khoa'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Địa chỉ email không hợp lệ.";
        } elseif (empty($hoten) || empty($lop) || empty($khoa) || empty($password)) {
            $error = "Vui lòng điền đầy đủ thông tin.";
        } else {
            try {
                $sql = "UPDATE sinh_vien SET Hoten = :hoten, Lop = :lop, Khoa = :khoa, Email = :email, Password = :password WHERE MaSV = :masv";
                $query = $dbh->prepare($sql);
                $query->bindParam(':masv', $masv, PDO::PARAM_STR);
                $query->bindParam(':hoten', $hoten, PDO::PARAM_STR);
                $query->bindParam(':lop', $lop, PDO::PARAM_STR);
                $query->bindParam(':khoa', $khoa, PDO::PARAM_STR);
                $query->bindParam(':email', $email, PDO::PARAM_STR);
                $query->bindParam(':password', $password, PDO::PARAM_STR);
                $query->execute();
                $msg = "Cập nhật thông tin sinh viên thành công.";
            } catch (PDOException $e) {
                $error = "Có lỗi xảy ra: " . $e->getMessage();
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản trị | Chỉnh Sửa Sinh Viên</title>
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
                <div class="page-title">Chỉnh Sửa Sinh Viên</div>
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
                                    $masv = $_GET['masv'];
                                    $sql = "SELECT * FROM sinh_vien WHERE masv = :masv";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':masv', $masv, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {
                                    ?>
                                        <div class="input-field col s12">
                                            <input id="masv" readonly type="text" name="masv" value="<?php echo htmlentities($result->MaSV); ?>" required >
                                            <label for="masv">Mã Sinh Viên</label>
                                        </div>

                                        <div class="input-field col s12">
                                            <input id="hoten" type="text" name="hoten" value="<?php echo htmlentities($result->Hoten); ?>" required>
                                            <label for="hoten">Họ Tên</label>
                                        </div>

                                        <div class="input-field col s12">
                                            <input id="lop" type="text" name="lop" value="<?php echo htmlentities($result->Lop); ?>" required>
                                            <label for="lop">Lớp</label>
                                        </div>

                                        <div class="input-field col s12">
                                            <input id="khoa" type="text" name="khoa" value="<?php echo htmlentities($result->Khoa); ?>" required>
                                            <label for="khoa">Khoa</label>
                                        </div>

                                        <div class="input-field col s12">
                                            <input id="email" type="email" name="email" value="<?php echo htmlentities($result->Email); ?>" required>
                                            <label for="email">Email</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input id="password" type="password" name="password" value="<?php echo htmlentities($result->Password); ?>" required>
                                            <label for="password">Password</label>
                                        </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <div class="col s12 text-right">
                                        <button type="submit" name="update" class="waves-effect waves-light btn indigo m-b-xs">Cập Nhật</button>
                                        <button type="button" class="waves-effect waves-light btn secondary m-b-xs" onclick="location.href='list_sinh_vien.php'">Quay Lại</button>
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
