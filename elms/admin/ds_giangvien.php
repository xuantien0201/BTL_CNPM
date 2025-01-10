<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_GET['del'])) {
        $magv = $_GET['del'];
        $sql = "delete from  tblgiangvien  WHERE magiangvien=:magiangvien";
        $query = $dbh->prepare($sql);
        $query->bindParam(':magiangvien', $magv, PDO::PARAM_STR);
        $query->execute();
        $msg = "Xóa giảng viên thành công";

    }

    ?>

<!DOCTYPE html>
    <html lang="vi">

    <head>

        <!-- Title -->
        <title>Admin | Quản lý giảng viên</title>

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
        </style>
    </head>

    <body>
        <?php include('includes/header.php'); ?>

        <?php include('includes/sidebar.php'); ?>
        <main class="mn-inner">
            <div class="row">
                <div class="col s12">
                    <div class="page-title">Quản lí giảng viên</div>
                </div>
                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">Thông tin giảng viên</span>
                            <div class="row">
                                
    <div class="col s6 right-align">
        <label for="searchMaGiangVien">Tìm kiếm theo mã giảng viên:</label>
        <input type="text" id="searchMaGiangVien" placeholder="Nhập mã giảng viên" />
    </div>
</div>

                            <?php if ($msg) { ?>
                                <div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div>
                            <?php } ?>
                            <table id="example" class="display responsive-table ">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã giảng viên</th>
                                        <th>Tên giảng viên</th>
                                        <th>Khoa</th>
                                        <th>Thông tin liên hệ</th>
                                        <th>Tác vụ</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $sql = "SELECT * from tblgiangvien";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) { ?>
                                            <tr>
                                                <td> <?php echo htmlentities($cnt); ?></td>
                                                <td><?php echo htmlentities($result->magiangvien); ?></td>
                                                <td><?php echo htmlentities($result->tengiangvien); ?></td>
                                                <td><?php echo htmlentities($result->khoa_giangvien); ?></td>
                                                <td><?php echo htmlentities($result->email_giangvien); ?></td>
                                                <td><a href="sua_giangvien.php?magiangvien=<?php echo htmlentities($result->magiangvien); ?>"><i
                                                            class="material-icons">mode_edit</i></a><a
                                                        href="ds_giangvien.php?del=<?php echo htmlentities($result->magiangvien); ?>"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa');"> <i
                                                            class="material-icons">delete_forever</i></a></td>
                                            </tr>
                                            <?php $cnt++;
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
        <!-- <script src="../assets/plugins/datatables/js/jquery.dataTables.min.js"></script> -->
        <script src="../assets/js/alpha.min.js"></script>
        <script src="../assets/js/pages/table-data.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
    // Lấy input tìm kiếm và bảng
    const searchInput = document.getElementById("searchMaGiangVien");
    const table = document.getElementById("example");
    const rows = table.getElementsByTagName("tr");

    // Lắng nghe sự kiện nhập (keyup) trên ô tìm kiếm
    searchInput.addEventListener("keyup", function () {
        const filter = searchInput.value.toUpperCase(); // Lấy giá trị nhập vào và chuyển thành chữ in hoa
        for (let i = 1; i < rows.length; i++) { // Bỏ qua hàng đầu tiên (tiêu đề bảng)
            const cells = rows[i].getElementsByTagName("td");
            if (cells.length > 0) {
                const magiangvien = cells[1].textContent || cells[1].innerText; // Cột "Mã giảng viên"
                if (magiangvien.toUpperCase().indexOf(filter) > -1) {
                    rows[i].style.display = ""; // Hiển thị hàng
                } else {
                    rows[i].style.display = "none"; // Ẩn hàng
                }
            }
        }
    });
});

        </script>

    </body>

    </html>
<?php } ?>