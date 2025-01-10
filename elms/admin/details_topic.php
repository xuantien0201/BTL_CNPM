<?php
session_start();
error_reporting(E_ALL);
include('includes/config.php');

// Kiểm tra nếu người dùng chưa đăng nhập
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if(isset($_POST['back'])){
        header('location:manage_topic.php');
    }
}
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>

        <!-- Title -->
        <title>Admin | Chi tiết đề tài</title>

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
                        <div class="page-title" style="font-size:24px;">Chi tiết đề tài quản lý</div>
                    </div>
                   
                    <div class="col s12 m12 l11">
                        <div class="card">
                            <div class="card-content">
                                <span class="card-title">Chi tiết</span>
                                <!-- <?php if($msg){?><div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div><?php }?> -->
                                <table id="example" class="display responsive-table">
                               
                                 
                                    <tbody>
                                        

            <!-- Lấy dữ liệu từ đề tài trong sql và truyền vào thẻ td -->
            <?php 
           if (isset($_GET['show'])) {
            $madetai = $_GET['show'];
            $sql = "SELECT * FROM tbldetai WHERE MaDeTai = :madetai";
            $query = $dbh->prepare($sql);
            $query->bindParam(':madetai', $madetai, PDO::PARAM_STR);
            $query->execute();
        
            // Sử dụng fetch() thay vì fetchAll() nếu chỉ lấy một dòng dữ liệu
            $kqua = $query->fetch(PDO::FETCH_OBJ);
        
            // Kiểm tra xem có dữ liệu trả về hay không
            if (!$kqua) {
                echo "<tr><td colspan='4'>Không tìm thấy thông tin đề tài</td></tr>";
                exit;
            }
        }
            ?>  
            <tr>
                <td style="font-size:16px; width: 250px"> <b>Mã đề tài :</b></td>
                <td style="width: 370px"><?php echo htmlentities($kqua->MaDeTai);?></td>
                <td style="font-size:16px; width: 200px"><b>Tên đề tài :</b></td>
                <td><?php echo htmlentities($kqua->TenDeTai);?></td>
                </tr>

                <tr>
                    <td style="font-size:16px;"><b>Lĩnh vực :</b></td>
                <td><?php echo htmlentities($kqua->LinhVuc);?></td>
                    <td style="font-size:16px;"><b>Trạng thái :</b></td>
                    <td><?php echo htmlentities($kqua->TrangThai);?></td>

                </tr>

                <!-- <td><?php echo htmlentities($kqua->TrangThai);?></td>
                <td>&nbsp;</td>
                    <td>&nbsp;</td>
            </tr> -->

            <tr>
                <td style="font-size:16px;"><b>Ngày bắt đầu :</b></td>
                <td><?php echo htmlentities($kqua->NgayBatDau);?></td>
                    <td style="font-size:16px;"><b>Ngày kết thúc :</b></td>
                <td><?php echo htmlentities($kqua->NgayKetThuc);?> </td>
            </tr>

            <tr>
                <td style="font-size:16px; height: 50px"><b>Mô tả : </b></td>
                <td colspan="10"><?php echo htmlentities($kqua->MoTa);?></td>
            </tr>

            <tr>
                <td style="font-size:16px;"><b>Giảng viên hướng dẫn :</b></td>
                <td><?php echo htmlentities($kqua->GiangVien);?> </td>
            </tr>

      <?php 
// Kiểm tra nếu có mã đề tài
if (isset($kqua->SinhVien) && !empty($kqua->SinhVien)) {
    $student_ids = explode(",", $kqua->SinhVien); // Tách danh sách mã sinh viên
    $placeholders = implode(",", array_fill(0, count($student_ids), "?")); // Tạo placeholders
    
    $sql_students = "SELECT MaSV, Hoten 
                     FROM sinh_vien 
                     WHERE MaSV IN ($placeholders) 
                     ORDER BY Hoten ASC";

    $stmt = $dbh->prepare($sql_students);
    $stmt->execute($student_ids); // Truyền danh sách mã sinh viên
    $students = $stmt->fetchAll(PDO::FETCH_OBJ);
} else {
    $students = [];
}
?>
     <tr>
        <td style="font-size:16px;"><b>Sinh viên tham gia:</b></td>
        <td colspan="3">
            <?php if (!empty($students)): ?>
                <ul>
                    <?php foreach ($students as $student): ?>
                        <li><?= htmlentities($student->MaSV) ?> - <?= htmlentities($student->Hoten) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                Không có sinh viên tham gia.
            <?php endif; ?>
        </td>
    </tr>

<!-- <td colspan="5"><?php echo $student->id ?>" <?= in_array($student->id, explode(",", $result->SinhVien))?></td> -->


   </form>                                    
   
                                    </tbody>
                                    
                                </table>
                            </div>
                            
                        </div>
                        <form type="post" method="post">
                            <button name="back" class="waves-effect waves-light btn indigo m-b-xs" style="margin-right: 10px;">Quay lại</button>
                        </form>
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
<?php ?>