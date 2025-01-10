<?php
// Bao gồm tệp cấu hình kết nối cơ sở dữ liệu
include('includes/config.php');

$error = "";
$msg = "";

// gán
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign'])) {
    $ma_de_tai = $_POST['ma_de_tai'];
    $sinh_vien_ids = $_POST['ma_sinh_vien'];

    $duplicate_students = [];
    foreach ($sinh_vien_ids as $ma_sinh_vien) {
        // Kiểm tra trùng lặp trước khi thêm
        $check_sql = "SELECT * FROM de_tai_sinh_vien WHERE ma_de_tai = ? AND ma_sinh_vien = ?";
        $stmt = $dbh->prepare($check_sql);
        $stmt->bindParam(1, $ma_de_tai);
        $stmt->bindParam(2, $ma_sinh_vien);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Ghi nhận sinh viên bị trùng
            $duplicate_students[] = $ma_sinh_vien;
        } else {
            // Thêm gán sinh viên vào đề tài
            $sql = "INSERT INTO de_tai_sinh_vien (ma_de_tai, ma_sinh_vien) VALUES (?, ?)";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(1, $ma_de_tai);
            $stmt->bindParam(2, $ma_sinh_vien);
            if (!$stmt->execute()) {
                $error = "Error: " . $stmt->errorInfo()[2];
            }
        }
    }

    if (!empty($duplicate_students)) {
        $error = "Các sinh viên sau đã được gán vào đề tài: " . implode(", ", $duplicate_students);
    } elseif (empty($error)) {
        $msg = "Sinh viên đã được gán thành công vào đề tài!";
    }
}


// Xử lý xóa sinh viên khỏi đề tài
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $ma_de_tai = $_POST['ma_de_tai'];
    $ma_sinh_vien = $_POST['ma_sinh_vien'];
    $msg = "";
    $error = "";

    $delete_sql = "DELETE FROM de_tai_sinh_vien WHERE ma_de_tai = ? AND ma_sinh_vien = ?";
    $stmt = $dbh->prepare($delete_sql);
    $stmt->bindParam(1, $ma_de_tai);
    $stmt->bindParam(2, $ma_sinh_vien);
    if ($stmt->execute()) {
        $msg = "Xóa sinh viên khỏi đề tài thành công!";
    } else {
        $error = "Error: " . $stmt->errorInfo()[2];
    }
}

// Hàm tải dữ liệu (đề tài hoặc sinh viên)
function load_data($dbh, $type)
{
    // Tạo câu SQL cho đề tài hoặc sinh viên
    $sql = $type === 'de_tai' ? "SELECT MaDeTai, TenDeTai FROM de_tai" : "SELECT MaSV, HoTen FROM sinh_vien";

    // Thực thi câu truy vấn
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    // Lấy tất cả kết quả trả về dưới dạng mảng
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Tạo các option cho dropdown
    $options = "";
    foreach ($results as $row) {
        $value = $type === 'de_tai' ? $row['MaDeTai'] : $row['MaSV'];
        $text = $type === 'de_tai' ? $row['TenDeTai'] : $row['HoTen'];
        $options .= "<option value='$value'>$text</option>";
    }
    return $options;
}

// Hàm hiển thị sinh viên tham gia đề tài
function load_gan_sinh_vien($dbh)
{
    $sql = "SELECT dt.MaDeTai, dt.TenDeTai, sv.MaSV, sv.HoTen
                FROM de_tai_sinh_vien
                JOIN de_tai dt ON de_tai_sinh_vien.ma_de_tai = dt.MaDeTai
                JOIN sinh_vien sv ON de_tai_sinh_vien.ma_sinh_vien = sv.MaSV
                ORDER BY dt.MaDeTai";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $output = "";
    $current_topic = null;
    $student_list = [];

    foreach ($results as $row) {
        if ($current_topic !== $row['MaDeTai']) {


            $current_topic = $row['MaDeTai'];
            $topic_name = $row['TenDeTai'];
            $student_list = [];

            // Hiển thị đề tài ở dòng đầu tiên
            $output .= "<tr>
                                <td>{$current_topic}</td>
                                <td>{$topic_name}</td>
                                <td></td>
                                <td></td>
                            </tr>";
        }

        // Thêm sinh viên vào danh sách sinh viên
        $student_list[] = "{$row['MaSV']} - {$row['HoTen']}";

        // Thêm sinh viên vào dòng dưới đề tài
        $output .= "<tr>
                            <td></td>
                            <td></td>
                            <td>
                                Mã sinh viên: {$row['MaSV']} - Tên sinh viên: {$row['HoTen']}
                            </td>
                            <td>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='ma_de_tai' value='{$row['MaDeTai']}'>
                                    <input type='hidden' name='ma_sinh_vien' value='{$row['MaSV']}'>
                                    <button type='submit' name='delete' class='btn btn-sm btn-danger'>Xóa</button>
                                </form>
                            </td>
                        </tr>";
    }



    return $output;
}


?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <title>Quản lý Sinh viên tham gia Đề tài</title>

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

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }

        table thead tr {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
        }

        table th,
        table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tbody tr:nth-child(odd) {
            background-color: #fff;
        }

        table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .action-buttons button {
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <div class="page-title">Quản lý Sinh viên tham gia Đề tài</div>
            </div>
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <form id="assignForm" method="POST" action="">
                            <?php if ($error) { ?>
                                <div class="error"><strong>ERROR</strong> : <?php echo htmlentities(string: $error); ?>
                                </div>
                            <?php } else if ($msg) { ?>
                                    <div class="succesful"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?></div>
                            <?php } ?>
                            <div class="row">
                                <div class="input-field col s12">
                                    <select id="deTai" name="ma_de_tai" required>
                                        <option value="" disabled selected>-- Chọn đề tài --</option>
                                        <?php echo load_data($dbh, 'de_tai'); ?>
                                    </select>
                                    <label for="deTai">Chọn Đề tài:</label>
                                </div>
                                <div class="input-field col s12">
                                    <select id="sinhVien" name="ma_sinh_vien[]" multiple required>
                                        <?php echo load_data($dbh, 'sinh_vien'); ?>
                                    </select>
                                    <label for="sinhVien">Chọn Sinh viên:</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 text-right">
                                    <button type="submit" name="assign" class="waves-effect waves-light btn indigo">Gán
                                        Sinh viên</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <h5 class="mb-4">Danh sách Sinh viên tham gia Đề tài</h5>
                        <table class="highlight">
                            <thead>
                                <tr>
                                    <th>Mã Đề tài</th>
                                    <th>Tên Đề tài</th>
                                    <th>Danh sách Sinh viên</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo load_gan_sinh_vien($dbh); ?>
                            </tbody>
                        </table>
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