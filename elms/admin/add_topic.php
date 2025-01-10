<?php
session_start();
error_reporting(0);

include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {   
    header('location:index.php');
} else {
    if(isset($_POST['add'])) {
        // Lấy dữ liệu từ form
        $name = $_POST['topicname'];          // Tên đề tài
        $field = $_POST['field'];             // Lĩnh vực
        $status = $_POST['status'];           // Trạng thái
        $description = $_POST['description']; // Mô tả
        $teacher_id = $_POST['teacher_id'];   // Giảng viên
        $student_ids = isset($_POST['student_ids']) ? implode(",", $_POST['student_ids']) : ''; // Thành viên nhóm

        // Định dạng ngày
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));

        // Giả sử các ký tự đại diện cho lĩnh vực
        $fields = [
            'Công nghệ' => 'CN',
            'Kinh tế' => 'KT',
            'Xã hội học' => 'XHH',
            'Nông nghiệp' => 'NN',
            'Giáo dục' => 'GD',
            'Khoa học' => 'KH',
            'Lịch sử' => 'LS'
        ];

        // Lấy mã lĩnh vực từ dữ liệu nhập vào
        $field_code = $fields[$field];

        // Kiểm tra xem tên đề tài đã tồn tại chưa
        $sql_check = "SELECT COUNT(*) FROM tbldetai WHERE TenDeTai = :name";
        $query_check = $dbh->prepare($sql_check);
        $query_check->bindParam(':name', $name, PDO::PARAM_STR);
        $query_check->execute();
        $result_check = $query_check->fetchColumn();

        if ($result_check > 0) {
            // Nếu có đề tài trùng tên, hiển thị thông báo JavaScript
            echo "<script type='text/javascript'>
            alert('Tên đề tài này đã tồn tại!');
            window.history.back();
            </script>";
            exit();
        } else {
            // Nếu không có đề tài trùng tên, tiếp tục thực hiện lưu
            // Lấy số tự động tăng (sử dụng giá trị MAX từ MaDeTai hiện tại)
            $sql_max = "SELECT MAX(CAST(SUBSTRING(MaDeTai, 3) AS UNSIGNED)) AS max_id FROM tbldetai WHERE MaDeTai LIKE :field_code";
            $query_max = $dbh->prepare($sql_max);
            $field_code_with_percent = $field_code . '%';
            $query_max->bindParam(':field_code', $field_code_with_percent, PDO::PARAM_STR);

            $query_max->execute();
            $result = $query_max->fetch(PDO::FETCH_ASSOC);
            $max_id = $result['max_id'] ? $result['max_id'] + 1 : 1;  // Tăng số tự động, nếu không có thì bắt đầu từ 1

            // Tạo mã đề tài
            $madetai = $field_code . str_pad($max_id, 3, '0', STR_PAD_LEFT);  // Mã đề tài 
                
            // Lệnh SQL để chèn dữ liệu
            $sql = "INSERT INTO tbldetai (MaDeTai, TenDeTai, LinhVuc, NgayBatDau, NgayKetThuc, TrangThai, MoTa, GiangVien, SinhVien) 
                    VALUES (:madetai, :name, :field, :start_date, :end_date, :status, :description, :teacher_id, :student_ids)";

            $query = $dbh->prepare($sql);

            // Liên kết các tham số
            $query->bindParam(':madetai', $madetai, PDO::PARAM_STR);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->bindParam(':field', $field, PDO::PARAM_STR);
            $query->bindParam(':start_date', $start_date, PDO::PARAM_STR);
            $query->bindParam(':end_date', $end_date, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_STR);
            $query->bindParam(':description', $description, PDO::PARAM_STR);
            $query->bindParam(':teacher_id', $teacher_id, PDO::PARAM_STR);
            $query->bindParam(':student_ids', $student_ids, PDO::PARAM_STR);

            // Thực thi truy vấn
            $query->execute();

            // Kiểm tra lỗi khi insert
            if ($query->errorCode() != '00000') {
                $msg = "Đề tài thêm thất bại!";
                echo "Error: " . implode(", ", $query->errorInfo());
            } else {
                // Lưu thông báo thành công vào biến $msg
                $msg = "Đề tài đã được thêm thành công!";
                header('location:add_topic.php');
                exit();
            }
        }
        
    }

     // Truy vấn sinh viên
     $sql_students = "SELECT * FROM sinh_vien ORDER BY Hoten ASC";
     $query_students = $dbh->prepare($sql_students);
     $query_students->execute();
     $students = $query_students->fetchAll(PDO::FETCH_OBJ);

    // Truy vấn giảng viên
    $sql_teachers = "SELECT * FROM tblgiangvien ORDER BY tengiangvien ASC";
    $query_teachers = $dbh->prepare($sql_teachers);
    $query_teachers->execute();
    $teachers = $query_teachers->fetchAll(PDO::FETCH_OBJ);
?>


    <!DOCTYPE html>
    <html lang="en">
        <head>
            
            <!-- Title -->
            <title>Admin | Thêm đề tài</title>
            
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
    <?php include('includes/header.php');?>
                
        <?php include('includes/sidebar.php');?>
            <main class="mn-inner">
                <div class="row">
                    <div class="col s12">
                        <div class="page-title">Thêm đề tài</div>
                    </div>
                    <div class="col s12 m12 l6" style="width: 90%">
                        <div class="card">
                            <div class="card-content">
                            
                                <div class="row">
                                    <form class="col s12" name="chngpwd" method="post">
                                        <div class="col m6">
                                    
                                        <?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
                                            else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
                                        <div class="row">
                                            <div class="input-field col s12">
                                                    <input type="text"  class="validate" autocomplete="off" name="topicname" required>
                                                    <label for="deptname">Tên đề tài</label>
                                            </div>


                                            <div class="input-field col s12">
                                                <select name="field" autocomplete="off" required>
                                                    <option value="" disabled selected>Lĩnh vực</option>
                                                    <option value="Công nghệ">Công nghệ</option>
                                                    <option value="Kinh tế">Kinh tế</option>
                                                    <option value="Xã hội học">Xã hội học</option>
                                                    <option value="Nông nghiệp">Nông nghiệp</option>
                                                    <option value="Giáo dục">Giáo dục</option>
                                                    <option value="Khoa học">Khoa học</option>
                                                    <option value="Lịch sử">Lịch sử</option>
                                                </select>
                                            </div>
                                            <div class="input-field col s12">
                                                <select name="status" autocomplete="off" required>
                                                    <option value="" disabled selected>Trạng thái</option>
                                                    <option value="Chưa bắt đầu">Chưa bắt đầu</option>
                                                    <option value="Đang làm việc">Đang làm việc</option>
                                                    <option value="Đã hoàn thành">Đã hoàn thành</option>
                                                    <option value="Tạm dừng">Tạm dừng</option>
                                                </select>
                                            </div>

                                            <div class="input-field col s12">
                                                <select name="teacher_id" <?php echo $readonly; ?>>
                                                    <option value="" disabled selected>Chọn giảng viên</option>
                                                    <?php foreach ($teachers as $teacher): ?>
                                                        <option value="<?= $teacher->magiangvien ?>">
                                                            <?= htmlentities($teacher->tengiangvien) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <label for="teacher_id">Giảng viên</label>
                                            </div>

                                           

                                            <div class="input-field col s12">
                                                <!-- Thẻ Select Thành viên nhóm -->
                                                <label for="students">Thành viên nhóm</label>
                                                <select name="student_ids[]" id="students" class="form-control select2" multiple="multiple" required>
                                                    <option value="" disabled selected>Thành viên nhóm</option>
                                                    <?php foreach ($students as $student): ?>
                                                        <option value="<?php echo $student->MaSV; ?>">
                                                            <?php echo htmlentities($student->Hoten); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div></div>

                        <!-- tách đôi màn hình -->
                                        <div class="col m6">
                                            <div class="row">
                                                <div style="margin-left: 12px; margin-bottom: -20px">
                                                    <label for="start_date">Ngày bắt đầu</label>
                                                </div>
                                                <div class="input-field col s12">
                                                    <input id="start_date" name="start_date" type="date" autocomplete="off" required onchange="validateDates()">
                                                </div>

                                                <div style="margin-left: 12px; margin-bottom: -20px; margin-top: -20px">
                                                    <label for="end_date">Ngày kết thúc</label>
                                                </div>
                                                <div class="input-field col s12">
                                                    <input id="end_date" name="end_date" type="date" autocomplete="off" required onchange="validateDates()">
                                                </div>


                                                <script>
                                                    function validateDates() {
                                                        const startDateInput = document.getElementById('start_date');
                                                        const endDateInput = document.getElementById('end_date');

                                                        // Kiểm tra nếu trường không có giá trị
                                                        if (!startDateInput || !endDateInput) return;

                                                        const startDateValue = startDateInput.value; // Lấy giá trị ngày bắt đầu
                                                        const endDateValue = endDateInput.value;    // Lấy giá trị ngày kết thúc

                                                        // Nếu cả hai giá trị không rỗng
                                                        if (startDateValue && endDateValue) {
                                                            const startDate = Date.parse(startDateValue); // Chuyển ngày bắt đầu thành dạng timestamp
                                                            const endDate = Date.parse(endDateValue);     // Chuyển ngày kết thúc thành dạng timestamp

                                                            // Kiểm tra ngày bắt đầu lớn hơn ngày kết thúc
                                                            if (startDate >= endDate) {
                                                                // Thông báo lỗi
                                                                alert('Ngày kết thúc phải lớn hơn ngày bắt đầu. Vui lòng nhập lại');

                                                                // Xóa giá trị không hợp lệ
                                                                endDateInput.value = '';
                                                                endDateInput.focus(); // Đưa con trỏ về trường Ngày kết thúc
                                                            }
                                                        }
                                                    }

                                                </script>
            

                                                <div style="margin-left: 12px">
                                                    <label for="des" >Mô tả</label>
                                                </div>
                                                <div class="input-field col s12" style="margin-top: 5px">
                                                    <textarea name="description" id="" style="height: 180px"></textarea>
                                                </div>

                                                <!-- <div class="input-field col s12">
                                                    <select name="department" autocomplete="off">
                                                        <option value="">Department...</option>
                                                        <?php $sql = "SELECT DepartmentName from tbldepartments";
                                                        $query = $dbh->prepare($sql);
                                                        $query->execute();
                                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                        $cnt = 1;
                                                        if ($query->rowCount() > 0) {
                                                            foreach ($results as $result) { ?>
                                                                <option
                                                                    value="<?php echo htmlentities($result->DepartmentName); ?>">
                                                                    <?php echo htmlentities($result->DepartmentName); ?>
                                                                </option>
                                                            <?php }
                                                        } ?>
                                                    </select>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

    <!-- Lưu trữ dữ liệu -->
    <div class="input-field col s12">
    <button type="submit" name="add" class="waves-effect waves-light btn indigo m-b-xs">Thêm mới</button>

                            </div>
                    </div>
                
                </form>
            </div>
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

            
        </body>
    </html>
    <?php } ?> 