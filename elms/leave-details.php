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
        <title>Employee | Leave Details </title>
        
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
        </style>
    </head>
    <body>
       <?php include('includes/header.php');?>
            
       <?php include('includes/sidebar.php');?>
            <main class="mn-inner">
                <div class="row">
                    <div class="col s12">
                        <div class="page-title" style="font-size:24px;">Chi tiết đề tài quản lý</div>
                    </div>
                   
                    <div class="col s12 m12 l12">
                        <div class="card">
                            <div class="card-content">
                                <span class="card-title">Chi tiết</span>
                                <?php if($msg){?><div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div><?php }?>
                                <table id="example" class="display responsive-table ">
                               
                                 
                                    <tbody>
                                        

            <!-- Lấy dữ liệu từ đề tài trong sql và truyền vào thẻ td -->
            <?php 
           if (isset($_GET['madetai'])) {
            $madetai = $_GET['madetai'];
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
                <td style="font-size:16px; width: 300px"> <b>Mã đề tài :</b></td>
                <td><?php echo htmlentities($kqua->MaDeTai);?></td>
                <td style="font-size:16px;"><b>Tên đề tài :</b></td>
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
                <td colspan="5"><?php echo htmlentities($kqua->MoTa);?></td>
            </tr>

            <tr>
                <td style="font-size:16px;"><b>Giảng viên hướng dẫn :</b></td>
                <td><?php echo htmlentities($kqua->GiangVien);?> </td>
            </tr>
<?php 
// $lid=($_GET['leaveid']);
// $sql = "SELECT tbldetai.MaDeTai as lid,tbldetai.*,sinh_vien.*
//              from tbldetai 
//              join sinh_vien on tbldetai.sinhvien=tblgiangvien.magiangvien
//              where tblgiangvien.GiangVien=:lid";
// $query = $dbh -> prepare($sql);
// $query->bindParam(':lid',$lid,PDO::PARAM_STR);
// $query->execute();
// $results=$query->fetchAll(PDO::FETCH_OBJ);
// $cnt=1;
// if($query->rowCount() > 0)
// {
// foreach($results as $result)
// {         
// Truy vấn sinh viên
// // Giả sử $result->SinhVien chứa danh sách mã sinh viên dạng "7,4"
//             $student_ids = explode(",", $result->SinhVien); // Tách các mã sinh viên từ chuỗi
//             $student_ids_placeholder = implode(",", array_fill(0, count($student_ids), "?")); // Tạo placeholder cho câu lệnh SQL

//             $sql_students = "SELECT sinh_vien.MaSV, sinh_vien.Hoten 
//                             FROM sinh_vien
//                             WHERE sinh_vien.MaSV IN ($student_ids_placeholder)
//                             ORDER BY sinh_vien.Hoten ASC";

//             // Chuẩn bị câu truy vấn với PDO
//             $stmt = $pdo->prepare($sql_students);
//             $stmt->execute($student_ids); // Truyền danh sách mã sinh viên vào truy vấn
//             $students = $stmt->fetchAll(PDO::FETCH_OBJ); // Lấy danh sách sinh viên
      ?>  
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



<!-- if($result->AdminRemark==""){
  echo "waiting for Approval";  
}
else{
echo htmlentities($result->AdminRemark);
} -->
<!-- 
 </tr> -->

 <tr>

 </tr>

   </form>                                     </tr>
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
<?php } ?>