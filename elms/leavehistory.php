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
       <?php include('includes/header.php');?>
            
       <?php include('includes/sidebar.php');?>
            <main class="mn-inner">
                <div class="row">
                    <div class="col s12">
                        <div class="page-title">Số dự án</div>
                    </div>
                   
                    <div class="col s12 m12 l12">
                        <div class="card">
                            <div class="card-content">
                                <span class="card-title">Số dự án</span>
                                <?php if($msg){?><div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div><?php }?>
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
            $eid=$_SESSION['eid'];
            $sql = "SELECT tbldetai.MaDeTai as madetai,TenDeTai,LinhVuc,NgayBatDau,NgayKetThuc,TrangThai
                    from tbldetai 
                    where GiangVien=:eid";
            $query = $dbh -> prepare($sql);
            $query->bindParam(':eid',$eid,PDO::PARAM_STR);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            $cnt=1;
            if($query->rowCount() > 0)
            {
            foreach($results as $result)
            {               ?>  
            <tr>
                <td> <?php echo htmlentities($result->madetai);?></td>
                <td><?php echo htmlentities($result->TenDeTai);?></td>
                <td><?php echo htmlentities($result->LinhVuc);?></td>
                <td><?php echo htmlentities($result->NgayBatDau);?></td>
                <td><?php echo htmlentities($result->NgayKetThuc);?></td>
                <td><?php $stats=$result->TrangThai;
                if($stats=="Chưa bắt đầu"){
                    ?>
                    <span style="color: green">Chưa bắt đầu</span>
                        <?php } if($stats=="Đang làm việc")  { ?>
                    <span style="color: yellow">Đang hoạt động</span>
                        <?php } if($stats=="Đã hoàn thành")  { ?>
                    <span style="color: blue">Hoàn thành</span>
                    <?php } if($stats=="Tạm dừng")  { ?>
                        <span style="color: red">Tạm dừng</span>
                <?php } ?>
                </td>
                <td>
                <div class="button-group">
        <a href="leave-details.php?madetai=<?php echo htmlentities($result->madetai);?>" class="waves-effect waves-light btn blue m-b-xs">Chi tiết</a>
        <a href="edit_detai.php?madetai=<?php echo htmlentities($result->madetai);?>" class="waves-effect waves-light btn blue m-b-xs">Cập nhật</a>
    </div>
                </td>                        
            </tr>
                                         <?php } }?>
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