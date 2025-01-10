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
        $password = ($_POST['password']);


        $sql = "INSERT INTO sinh_vien (masv, hoten, lop, khoa, email, password) VALUES ('$masv','$hoten','$lop','$khoa','$email','$password')";
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta charset="UTF-8">
        <title>Thêm Sinh Viên</title>

        <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
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
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            }

            .succesful {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #5cb85c;
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            }

            .page-title {
                font-size: 30px;
                margin-bottom: 20px;
                color: #3e3e3e;
            }

            label {
                font-size: 18px;
                font-weight: bold;
            }
        </style>
    </head>

    <body>
        <?php include('includes/header.php'); ?>
        <?php include('includes/sidebar.php'); ?>

        <main class="mn-inner">
            <div class="row">
                <div class="col s12">
                    <div class="page-title">Thêm Sinh Viên</div>
                </div>
                <div class="col s12">
                    <div class="card">
                        <div class="card-content">
                            <form method="POST">
                                <?php if ($error) { ?>
                                    <div class="error"><strong>ERROR</strong> : <?php echo htmlentities($error); ?></div>
                                <?php } else if ($msg) { ?>
                                        <div class="succesful"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?></div>
                                <?php } ?>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="masv" type="text" name="masv" required>
                                        <label for="masv">Mã Sinh Viên</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="hoten" type="text" name="hoten" required>
                                        <label for="hoten">Họ Tên</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="lop" type="text" name="lop" required>
                                        <label for="lop">Lớp</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="khoa" type="text" name="khoa" required>
                                        <label for="khoa">Khoa</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="email" type="email" name="email" required>
                                        <label for="email">Email</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <label for="password">Password</label>
                                        <input id="password" name="password" type="password" autocomplete="off" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12 text-right">
                                        <button type="submit" name="add"
                                            class="waves-effect waves-light btn indigo">Thêm</button>
                                        <button type="button" class="waves-effect waves-light btn secondary"
                                            onclick="location.href='list_sinh_vien.php'">Hủy</button>
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