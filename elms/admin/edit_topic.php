<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else {
    if(isset($_POST['back'])){
        header('location:manage_topic.php');
    }
    elseif (isset($_POST['update_page'])) {
        $madetai = $_GET['update'];
        $topicname = $_POST['topicname'];
        $field = $_POST['field'];
        $status = $_POST['status'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $teacher_id = $_POST['teacher_id'];
        $student_ids = implode(",", $_POST['student_ids']);
        $description = $_POST['description'];
    
        $sql = "UPDATE tbldetai 
                SET TenDeTai=:topicname, LinhVuc=:field, TrangThai=:status, NgayBatDau=:start_date, NgayKetThuc=:end_date,MoTa=:description, GiangVien=:teacher_id, SinhVien=:student_ids
                WHERE MaDeTai=:did";
        $query = $dbh->prepare($sql);
        $query->bindParam(':topicname', $topicname, PDO::PARAM_STR);
        $query->bindParam(':field', $field, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':start_date', $start_date, PDO::PARAM_STR);
        $query->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $query->bindParam(':teacher_id', $teacher_id, PDO::PARAM_STR);
        $query->bindParam(':student_ids', $student_ids, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':did', $madetai, PDO::PARAM_STR);
        $query->execute();
    
        $msg = "Cập nhật thông tin thành công";
        header('location:manage_topic.php');
    }
}

    // Truy vấn giảng viên
    $sql_teachers = "SELECT * FROM tblgiangvien ORDER BY tengiangvien ASC";
    $query_teachers = $dbh->prepare($sql_teachers);
    $query_teachers->execute();
    $teachers = $query_teachers->fetchAll(PDO::FETCH_OBJ);

    // Truy vấn sinh viên
    $sql_students = "SELECT * FROM sinh_vien ORDER BY Hoten ASC";
    $query_students = $dbh->prepare($sql_students);
    $query_students->execute();
    $students = $query_students->fetchAll(PDO::FETCH_OBJ);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        
        <!-- Title -->
        <title>Admin | Update Department</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />
        
        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css"/>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
<link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/custom.css" rel="stylesheet" type="text/css"/>
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
    <?php include('includes/header.php'); ?>  
<?php include('includes/sidebar.php'); ?>
<main class="mn-inner">
    <div class="row">
        <div class="col s12 m12 l6" style="width: 90%">
            <div class="page-title">Cập nhật thông tin đề tài</div>
        </div>
        <div class="card">
            <div class="card-content">                            
                <div class="row">
                    <form class="col s12" name="chngpwd" method="post">
 
                        <?php 
                            $readonly = " "; // Mặc định không có thuộc tính readonly/disabled
                            $button_show = " ";
                            if (isset($_GET['show'])) {
                                $button_show = "display: none;";
                                $readonly = "disabled"; // Chỉ cho phép xem thông tin
                                $madetai = $_GET['show']; // Lấy tham số udp từ URL
                                // Truy vấn thông tin đề tài theo MaDeTai
                                $sql = "SELECT * FROM tbldetai WHERE MaDeTai = :MaDeTai";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':MaDeTai', $madetai, PDO::PARAM_STR);
                                $query->execute();
                                $result = $query->fetch(PDO::FETCH_OBJ); // Lấy dữ liệu của đề tài
                            }
                            elseif (isset($_GET['update'])) {
                                $button_show = " ";
                                $readonly = " ";
                                $madetai = $_GET['update']; // Lấy tham số udp từ URL
                                // Truy vấn thông tin đề tài theo MaDeTai
                                $sql = "SELECT * FROM tbldetai WHERE MaDeTai = :MaDeTai";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':MaDeTai', $madetai, PDO::PARAM_STR);
                                $query->execute();
                                $result = $query->fetch(PDO::FETCH_OBJ); // Lấy dữ liệu của đề tài
                            }
                        ?>
                        <div class="col m6">
                            <div class="row">
                                <div class="input-field col s12">
                                    <input disabled id="ma" type="text" name="ma" value="<?php echo htmlentities($result->MaDeTai); ?>" <?php echo $readonly; ?> required>
                                    <label for="topicname">Mã đề tài</label>
                                </div>
                                <div class="input-field col s12">
                                    <input id="topicname" type="text" name="topicname" value="<?php echo htmlentities($result->TenDeTai); ?>" <?php echo $readonly; ?> required>
                                    <label for="topicname">Tên đề tài</label>
                                </div>
                                <div class="input-field col s12">
                                    <input type="hidden" name="field" value="<?= $result->LinhVuc ?>">
                                    <select name=" " disabled>
                                        <option value="" disabled selected>Chọn lĩnh vực</option>
                                        <option value="Công nghệ" <?= $result->LinhVuc == 'Công nghệ' ? 'selected' : '' ?>>Công nghệ</option>
                                        <option value="Kinh tế" <?= $result->LinhVuc == 'Kinh tế' ? 'selected' : '' ?>>Kinh tế</option>
                                        <option value="Xã hội học" <?= $result->LinhVuc == 'Xã hội học' ? 'selected' : '' ?>>Xã hội học</option>
                                        <option value="Nông nghiệp" <?= $result->LinhVuc == 'Nông nghiệp' ? 'selected' : '' ?>>Nông nghiệp</option>
                                        <option value="Giáo dục" <?= $result->LinhVuc == 'Giáo dục' ? 'selected' : '' ?>>Giáo dục</option>
                                        <option value="Khoa học" <?= $result->LinhVuc == 'Khoa học' ? 'selected' : '' ?>>Khoa học</option>
                                        <option value="Lịch sử" <?= $result->LinhVuc == 'Lịch sử' ? 'selected' : '' ?>>Lịch sử</option>
                                    </select>
                                    <label for="field">Lĩnh vực</label>
                                </div>
                                <div class="input-field col s12">
                                    <select name="status" <?php echo $readonly; ?>>
                                        <option value="" disabled selected>Chọn trạng thái</option>
                                        <option value="Chưa bắt đầu" <?php if($result->TrangThai == 'Chưa bắt đầu') echo 'selected'; ?>>Chưa bắt đầu</option>
                                        <option value="Đang làm việc" <?php if($result->TrangThai == 'Đang làm việc') echo 'selected'; ?>>Đang làm việc</option>
                                        <option value="Đã hoàn thành" <?php if($result->TrangThai == 'Đã hoàn thành') echo 'selected'; ?>>Đã hoàn thành</option>
                                    </select>
                                    <label for="status">Trạng thái</label>
                                </div>
                                <div class="input-field col s12">
                                    <select name="teacher_id" <?php echo $readonly; ?>>
                                        <option value="" disabled selected>Chọn giảng viên</option>
<?php foreach ($teachers as $teacher): ?>
                                            <option value="<?= $teacher->magiangvien ?>" <?= $result->GiangVien == $teacher->magiangvien ? 'selected' : '' ?>>
                                                <?= htmlentities($teacher->tengiangvien) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="teacher_id">Giảng viên</label>
                                </div>
                                <div class="input-field col s12">
                                    <select name="student_ids[]" multiple <?php echo $readonly; ?>>
                                        <option value="" disabled selected>Chọn thành viên nhóm</option>
                                        <?= $selected_students = explode(",", $result->SinhVien);
                                        foreach ($students as $student):
                                            $selected = in_array($student->MaSV, $selected_students) ? "selected" : "";
                                            echo "<option value='{$student->MaSV}' $selected>{$student->Hoten}</option>";
                                         endforeach; ?>
                                    </select>
                                    <label for="student_ids">Thành viên nhóm</label>
                                </div>
                            </div>
                        </div>

                        <div class="col m6">
                            <div class="row">
                                <div style="margin-left: 12px; margin-bottom: -20px">
                                    <label for="birthdate">Ngày bắt đầu</label>
                                </div>
                                <div class="input-field col s12" >
                                    <input type="date" name="start_date" oninput="validateDates()" value="<?php echo htmlentities($result->NgayBatDau); ?>"  <?php echo $readonly; ?> required>
                                </div>
                                <div style="margin-left: 12px; margin-bottom: -20px">
                                    <label for="birthdate">Ngày kết thúc</label>
                                </div>
                                <div class="input-field col s12">
                                    <input type="date" name="end_date" oninput="validateDates()" value="<?php echo htmlentities($result->NgayKetThuc); ?>" <?php echo $readonly; ?> required>
                                </div>
                                
                                <div style="margin-left: 12px">
                                    <label for="description">Mô tả</label>
                                </div>
                                <div class="input-field col s12" style="margin-top: 5px">
<textarea name="description" id="description" style="height: 260px"  <?php echo $readonly; ?>> <?php echo htmlentities($result->MoTa); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="input-field col s12">
                            <button type="submit" name="back" class="waves-effect waves-light btn indigo m-b-xs" style="margin-right: 10px;">Quay lại</button>
                            <button type="submit" name="update_page" class="waves-effect waves-light btn indigo m-b-xs" style="<?php echo $button_show; ?>">Cập nhật</button>
                        </div>
                    </form>
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
        <script src="../assets/js/alpha.min.js"></script>
        <script src="../assets/js/pages/form_elements.js"></script>
        
        <script>
                function validateDates() {
            // Lấy giá trị từ các ô nhập
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            // Kiểm tra nếu cả hai giá trị đã được nhập
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);

                // So sánh giá trị ngày
                if (start > end) {
                    alert('Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu. Vui lòng kiểm tra lại!');
                }
            }
        }

        </script>

    </body>
</html>
<?php  ?>