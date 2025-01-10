<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Kiểm tra quyền truy cập
if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
    exit;
}

if (isset($_GET['madetai'])) {
    $madetai = $_GET['madetai'];

    // Lấy thông tin đề tài từ cơ sở dữ liệu
    $sql = "SELECT * FROM tbldetai WHERE MaDeTai = :madetai";
    $query = $dbh->prepare($sql);
    $query->bindParam(':madetai', $madetai, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    // Kiểm tra nếu không tìm thấy đề tài
    if (!$result) {
        $_SESSION['error'] = "Không tìm thấy thông tin đề tài!";
        header('location:detai_list.php');
        exit;
    }
}

// Cập nhật thông tin đề tài
if (isset($_POST['update'])) {
    $TenDeTai = $_POST['TenDeTai'];
    $LinhVuc = $_POST['LinhVuc'];
    $TrangThai = $_POST['TrangThai'];
    $NgayBatDau = $_POST['NgayBatDau'];
    $NgayKetThuc = $_POST['NgayKetThuc'];
    $MoTa = $_POST['MoTa'];
    $GiangVien = $_POST['GiangVien'];
    $SinhVien = implode(",", $_POST['SinhVien']); // Chuyển danh sách sinh viên sang chuỗi

    $sql = "UPDATE tbldetai 
            SET TenDeTai = :TenDeTai,
                LinhVuc = :LinhVuc,
                TrangThai = :TrangThai,
                NgayBatDau = :NgayBatDau,
                NgayKetThuc = :NgayKetThuc,
                MoTa = :MoTa,
                GiangVien = :GiangVien,
                SinhVien = :SinhVien
            WHERE MaDeTai = :madetai";
    $query = $dbh->prepare($sql);
    $query->bindParam(':TenDeTai', $TenDeTai, PDO::PARAM_STR);
    $query->bindParam(':LinhVuc', $LinhVuc, PDO::PARAM_STR);
    $query->bindParam(':TrangThai', $TrangThai, PDO::PARAM_STR);
    $query->bindParam(':NgayBatDau', $NgayBatDau, PDO::PARAM_STR);
    $query->bindParam(':NgayKetThuc', $NgayKetThuc, PDO::PARAM_STR);
    $query->bindParam(':MoTa', $MoTa, PDO::PARAM_STR);
    $query->bindParam(':GiangVien', $GiangVien, PDO::PARAM_STR);
    $query->bindParam(':SinhVien', $SinhVien, PDO::PARAM_STR);
    $query->bindParam(':madetai', $madetai, PDO::PARAM_STR);

    if ($query->execute()) {
        $_SESSION['msg'] = "Cập nhật thành công!";
    } else {
        $_SESSION['error'] = "Có lỗi xảy ra. Vui lòng thử lại.";
    }
    header('location:detai_list.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Employee | Leave Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css"/>
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="assets/plugins/google-code-prettify/prettify.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/alpha.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/custom.css" rel="stylesheet" type="text/css"/>
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>
    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <div class="page-title" style="font-size:24px;">Sửa thông tin đề tài</div>
            </div>
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <form method="post">
                            <div class="row">
                                <div class="input-field col s6">
                                    <input id="MaDeTai" name="MaDeTai" type="text" value="<?= htmlentities($result->MaDeTai) ?>" disabled>
                                    <label for="MaDeTai">Mã Đề Tài</label>
                                </div>
                                <div class="input-field col s6">
                                    <input id="TenDeTai" name="TenDeTai" type="text" value="<?= htmlentities($result->TenDeTai) ?>" required>
                                    <label for="TenDeTai">Tên Đề Tài</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s6">
                                    <input id="LinhVuc" name="LinhVuc" type="text" value="<?= htmlentities($result->LinhVuc) ?>" required>
                                    <label for="LinhVuc">Lĩnh Vực</label>
                                </div>
                                <div class="input-field col s6">
                                    <select name="TrangThai" required>
                                        <option value="Đang thực hiện" <?= $result->TrangThai == "Đang thực hiện" ? "selected" : "" ?>>Đang thực hiện</option>
                                        <option value="Hoàn thành" <?= $result->TrangThai == "Hoàn thành" ? "selected" : "" ?>>Hoàn thành</option>
                                        <option value="Tạm dừng" <?= $result->TrangThai == "Tạm dừng" ? "selected" : "" ?>>Tạm dừng</option>
                                    </select>
                                    <label for="TrangThai">Trạng Thái</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s6">
                                    <input id="NgayBatDau" name="NgayBatDau" type="date" value="<?= htmlentities($result->NgayBatDau) ?>" required>
                                    <label for="NgayBatDau">Ngày Bắt Đầu</label>
                                </div>
                                <div class="input-field col s6">
                                    <input id="NgayKetThuc" name="NgayKetThuc" type="date" value="<?= htmlentities($result->NgayKetThuc) ?>" required>
                                    <label for="NgayKetThuc">Ngày Kết Thúc</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <textarea id="MoTa" name="MoTa" class="materialize-textarea" required><?= htmlentities($result->MoTa) ?></textarea>
                                    <label for="MoTa">Mô Tả</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s6">
                                    <input id="GiangVien" name="GiangVien" type="text" value="<?= htmlentities($result->GiangVien) ?>" required>
                                    <label for="GiangVien">Giảng Viên</label>
                                </div>
                                <div class="input-field col s6">
                                    <select name="SinhVien[]" multiple>
                                        <?php
                                        $sql = "SELECT MaSV, Hoten FROM sinh_vien ORDER BY Hoten ASC";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $students = $query->fetchAll(PDO::FETCH_OBJ);
                                        $selected_students = explode(",", $result->SinhVien);
                                        foreach ($students as $student) {
                                            $selected = in_array($student->MaSV, $selected_students) ? "selected" : "";
                                            echo "<option value='{$student->MaSV}' $selected>{$student->Hoten}</option>";
                                        }
                                        ?>
                                    </select>
                                    <label for="SinhVien">Sinh Viên Tham Gia</label>
                                </div>
                            </div>
                            <div class="row">
                                <button type="submit" name="update" class="btn waves-effect waves-light">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
        <!-- Javascripts -->
        <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/alpha.min.js"></script>
    <script src="assets/js/pages/table-data.js"></script>
    <script src="assets/js/pages/ui-modals.js"></script>
    <script src="assets/plugins/google-code-prettify/prettify.js"></script>
</body>
</html>
