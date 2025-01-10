<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['emplogin'])==0)
    {   
header('location:index.php');
}
else{

 ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        
        <!-- Title -->
        <title>Employee | Leave History</title>
        
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

            
        <!-- Theme Styles -->
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
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.button-group {
    display: flex;
    gap: 10px; /* Adjust the gap between buttons */
}

.button-group a {
    margin: 0; /* Remove any default margin */
}
        </style>
    </head>
    <body>
       <?php include('includes/headersv.php');?>
            
       <?php include('includes/sidebarsv.php');?>
            <main class="mn-inner">
                <div class="row">
                    <div class="col s12">
                        <div class="page-title">Số dự án</div>
                    </div>
                   
                    <div class="col s12 m12 l12">
                        <div class="card">
                            <div class="card-content">
                                <span class="card-title">Số dự án</span>
                                <!-- <?php if($msg){?><div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div><?php }?> -->
                                <table id="example" class="display responsive-table ">
                                    <thead>
                                        <tr>
                                            <th>Mã đề tài</th>
                                            <th>Tên đề tài</th>
                                            <th>Lĩnh vực</th>
                                            <th>Ngày bắt đầu</th>
                                            <th>Ngày kết thúc</th>
                                            <th>Trạng thái</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                 
                                    <tbody>
                                    <?php
                                    
            if (isset($_GET['detai'])) {
                
                $detaiString = urldecode($_GET['detai']); // Giải mã chuỗi
                $detaiArray = explode(",", $detaiString); // Chuyển chuỗi thành mảng\

                // Kiểm tra nếu mảng không rỗng
                if (!empty($detaiArray) && count($detaiArray) > 0) {
                    // Tạo danh sách các placeholder tương ứng với số lượng phần tử trong mảng
                    $placeholders = rtrim(str_repeat('?,', count($detaiArray)), ',');
            
                    // Truy vấn thông tin chi tiết các đề tài
                    $sql = "SELECT *
                            FROM tbldetai
                            WHERE MaDeTai IN ($placeholders)";
                    $query = $dbh->prepare($sql);
            
                    // Gắn các giá trị từ `$detaiArray` vào các placeholder
                    $query->execute($detaiArray);
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
            
                    // Hiển thị kết quả nếu có dữ liệu
                    if ($query->rowCount() > 0) {
                        foreach ($results as $result) {
                            ?>
                            <tr>
                                <td><?php echo htmlentities($result->MaDeTai); ?></td>
                                <td><?php echo htmlentities($result->TenDeTai); ?></td>
                                <td><?php echo htmlentities($result->LinhVuc); ?></td>
                                <td><?php echo htmlentities($result->NgayBatDau); ?></td>
                                <td><?php echo htmlentities($result->NgayKetThuc); ?></td>
                                <td>
                                    <?php
                                    $stats = $result->TrangThai;
                                    if ($stats == "Chưa bắt đầu") {
                                        echo '<span style="color: green">Chưa bắt đầu</span>';
                                    } elseif ($stats == "Đang làm việc") {
                                        echo '<span style="color: red">Đang hoạt động</span>';
                                    } elseif ($stats == "Đã hoàn thành") {
                                        echo '<span style="color: blue">Hoàn thành</span>';
                                    } elseif ($stats == "Tạm dừng") {
                                        echo '<span style="color: red">Tạm dừng</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <div class="button-group">
                                        <a href="leave-detailssv.php?madetai=<?php echo htmlentities($result->MaDeTai); ?>" 
                                        class="waves-effect waves-light btn blue m-b-xs">Chi tiết</a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='7'>Không có đề tài nào từ truy vấn</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Không có đề tài nào từ mảng</td></tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Không có đề tài nào được gửi qua URL</td></tr>";
            }

            ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        <div class="left-sidebar-hover"></div>
        
        <!-- Javascripts -->
        <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
        <script src="assets/js/alpha.min.js"></script>
        <script src="assets/js/pages/table-data.js"></script>
        
    </body>
</html>
<?php } ?>