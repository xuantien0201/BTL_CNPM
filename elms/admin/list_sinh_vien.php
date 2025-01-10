<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['delete'])) {
        $masv = $_POST['masv'];

        $sql = "DELETE FROM sinh_vien WHERE MaSV = '$masv'";
        $query = $dbh->prepare($sql);
        $query->execute();

        if ($query->rowCount() > 0) {
            $_SESSION['msg'] = "Xóa sinh viên thành công."; 
            header("Location: list_sinh_vien.php"); 
            exit();
        } else {
            $_SESSION['error'] = "Không tìm thấy sinh viên. Vui lòng thử lại."; 
            header("Location: list_sinh_vien.php"); 
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị | Quản Lý Sinh Viên</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
    <link href="../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />
    <style>
        .page-title {
            font-size: 30px;
            margin-bottom: 20px;
            color: #3e3e3e;
        }

        table {
            width: 100%;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 12px;
            text-align: center;
        }

        .btn-action {
            margin: 0 5px;
            cursor: pointer;
        }

        .btn-action i {
            font-size: 18px;
        }

        label {
            font-size: 18px;
            font-weight: bold;
        }

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
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <div class="page-title">Quản Lý Sinh Viên</div>
            </div>
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <form method="POST" action="list_sinh_vien.php">
                                <?php 
                                if (isset($_SESSION['error'])) {
                                    echo "<div class='error'><strong>ERROR</strong> : " . htmlentities($_SESSION['error']) . "</div>";
                                    unset($_SESSION['error']);
                                } else if (isset($_SESSION['msg'])) {
                                    echo "<div class='succesful'><strong>SUCCESS</strong> : " . htmlentities($_SESSION['msg']) . "</div>";
                                    unset($_SESSION['msg']);
                                }
                                ?>
                                <div class="col s12 m6">
                                    <input type="text" name="search_maSV" id="search_maSV" placeholder="Nhập mã sinh viên để tìm kiếm"
                                           value="<?php echo isset($_POST['search_maSV']) ? htmlentities($_POST['search_maSV']) : ''; ?>" />
                                </div>
                                <div class="col s12 m6">
                                    <button type="submit" class="btn btn-small waves-effect waves-light">Tìm kiếm</button>
                                    <a href="add_sinh_vien.php" class="btn btn-small waves-effect waves-light green">Thêm mới</a>
                                </div>
                            </form>
                        </div>

                        <table class="display responsive-table striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mã Sinh Viên</th>
                                    <th>Họ và Tên</th>
                                    <th>Lớp</th>
                                    <th>Khoa</th>
                                    <th>Email</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM sinh_vien";
                                if (isset($_POST['search_maSV']) && !empty($_POST['search_maSV'])) {
                                    $masv = '%' . $_POST['search_maSV'] . '%';
                                    $sql = "SELECT * FROM sinh_vien WHERE MaSV LIKE '$masv'";
                                }
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;

                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) {
                                        echo "<tr>
                                            <td>{$cnt}</td>
                                            <td>{$result->MaSV}</td>
                                            <td>{$result->Hoten}</td>
                                            <td>{$result->Lop}</td>
                                            <td>{$result->Khoa}</td>
                                            <td>{$result->Email}</td>
                                            <td>
                                                <a href='edit_sinh_vien.php?masv={$result->MaSV}' class='btn btn-small waves-effect waves-light blue btn-action'>
                                                    <i class='material-icons'>mode_edit</i>
                                                </a>
                                                <form method='POST' action='list_sinh_vien.php' style='display:inline;'>
                                                    <input type='hidden' name='masv' value='{$result->MaSV}'>
                                                    <button type='submit' name='delete' class='btn btn-small waves-effect waves-light red btn-action' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\");'>
                                                        <i class='material-icons'>delete_forever</i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>";
                                        $cnt++;
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>Không tìm thấy sinh viên.</td></tr>";
                                }
                                ?>
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
    <script src="../assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/alpha.min.js"></script>
</body>
</html>
<?php } ?>
