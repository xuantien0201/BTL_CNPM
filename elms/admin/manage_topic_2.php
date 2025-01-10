<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Kiểm tra nếu người dùng chưa đăng nhập
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    // Kiểm tra xem có yêu cầu xóa không
    if (isset($_GET['del'])) {
        $madetai = $_GET['del']; // Lấy tham số del từ URL
       
        // Xóa đề tài với MaDeTai tương ứng
        $sql = "DELETE FROM tblDeTai WHERE MaDeTai=:MaDeTai";
        $query = $dbh->prepare($sql);
        $query->bindParam(':MaDeTai', $madetai, PDO::PARAM_STR);

        // Thực thi câu lệnh xóa
        if ($query->execute()) {
            $msg = "Xóa đề tài thành công";
        } else {
            $msg = "Lỗi khi xóa đề tài";
        }
    }
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>

        <!-- Title -->
        <title>Admin | Quản lý đề tài</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />

        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
        <link href="../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">


        <!-- Theme Styles -->
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
            .status-link {
    display: inline-block;
    padding: 10px 20px;
    margin: 5px;
    border-radius: 12px;
    background-color: #f0f0f0;
    color: #333;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s, color 0.3s;
}

.status-link:hover {
    background-color:rgb(17, 162, 206);
    color: #fff;
}

        </style>
    </head>

    <body>
        <?php include('includes/header.php'); ?>

        <?php include('includes/sidebar.php'); ?>
        <main class="mn-inner">

            <div class="row">
                <div class="col s12">
                    <div class="page-title">Quản lý đề tài</div>
                </div>
                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">Thông tin đề tài</span>
                            <!-- <?php if ($msg) { ?>
                                <div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div>
                            <?php } ?> -->
                            <div class="row">
                <div class="col s12">
                    <a href="manage_topic_1.php" class="status-link">Chưa bắt đầu</a>
                    <a href="manage_topic_2.php" class="status-link">Đang làm việc</a>
                    <a href="manage_topic_3.php" class="status-link">Đã hoàn thành</a>
                </div>
                            </div>
                            <table id="example" class="display responsive-table ">
                                <thead>
                                    <tr>   
                                        <th>Mã</th>
                                        <th>Tên</th>
                                        <th>Lĩnh vực</th>
                                        <th>Ngày bắt đầu</th>
                                        <th>Ngày kết thúc</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $sql = "SELECT * from tbldetai where TrangThai = 'Đang làm việc'";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $kqua) { ?>
                                            <tr>
                                                <td> <?php echo htmlentities($kqua->MaDeTai); ?></td>
                                                <td><?php echo htmlentities($kqua->TenDeTai); ?></td>
                                                <td><?php echo htmlentities($kqua->LinhVuc); ?></td>
                                                <td><?php echo htmlentities($kqua->NgayBatDau); ?></td>
                                                <td><?php echo htmlentities($kqua->NgayKetThuc); ?></td>
                                                <td>
                                                    <a href="details_topic.php?show=<?php echo htmlentities($kqua->MaDeTai); ?>">
                                                        <i class="material-icons">visibility</i>
                                                    </a>
                                                    <a href="edit_topic.php?update=<?php echo htmlentities($kqua->MaDeTai); ?>">
                                                        <i class="material-icons">mode_edit</i>
                                                    </a>
                                                    <a href="manage_topic.php?del=<?php echo htmlentities($kqua->MaDeTai); ?>"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa đề tài này không');"> <i
                                                            class="material-icons">delete_forever</i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php 
                                        }
                                    } ?>
                                </tbody>
                            </table>
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
        <script src="../assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
        <script src="../assets/js/alpha.min.js"></script>
        <script src="../assets/js/pages/table-data.js"></script>

    </body>

    </html>
<?php } ?>