<aside id="slide-out" class="side-nav white fixed">
                <div class="side-nav-wrapper">
                    <div class="sidebar-profile">
                        <div class="sidebar-profile-image">
                            <img src="assets/images/profile-image.png" class="circle" alt="">
                        </div>
                        <div class="sidebar-profile-info">
                    <?php 
$eid=$_SESSION['eid'];
$sql = "SELECT FirstName,LastName,EmpId from  tblemployees where id=:eid";
$query = $dbh -> prepare($sql);
$query->bindParam(':eid',$eid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>  
                                <p><?php echo htmlentities($result->FirstName." ".$result->LastName);?></p>
                                <span><?php echo htmlentities($result->EmpId)?></span>
                         <?php }} ?>
                        </div>
                    </div>
              

                    <?php
$eid = $_SESSION['eid']; // Mã sinh viên đăng nhập

// Truy vấn toàn bộ đề tài và danh sách sinh viên tham gia
$sql = "SELECT MaDeTai, SinhVien FROM tbldetai";
$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

$participatingProjects = []; // Mảng lưu mã đề tài mà sinh viên tham gia

// Duyệt qua từng đề tài
foreach ($results as $result) {
    // Tách danh sách mã sinh viên từ chuỗi SinhVien
    $studentIds = explode(",", $result->SinhVien);

    // So sánh mã sinh viên hiện tại với $eid
    if (in_array($eid, $studentIds)) {
        $participatingProjects[] = $result->MaDeTai; // Thêm mã đề tài vào mảng
    }
}

$topicArray = implode(",", $participatingProjects);

$count = count($participatingProjects);

?>
                <ul class="sidebar-menu collapsible collapsible-accordion" data-collapsible="accordion">
               <li class="no-padding"><a class="waves-effect waves-grey" href="dashboardsv.php"><i class="material-icons">settings_input_svideo</i>Trang chủ</a></li>   
  <li class="no-padding"><a href="leavehistorysv.php?detai=<?php echo urlencode($topicArray); ?>"><i class="material-icons">account_box</i>Đề tài NCKH</a></li>
  <li class="no-padding"><a class="waves-effect waves-grey" href="sv-changepassword.php"><i class="material-icons">settings_input_svideo</i>Đổi mật khẩu</a></li>
                   
         
               
                  <li class="no-padding">
                                <a class="waves-effect waves-grey" href="logout.php"><i class="material-icons">exit_to_app</i>Đăng xuất</a>
                            </li>  
                 
                   
                </ul>
                <div class="footer">
                    <p class="copyright"> RTMS ©</p>
                
                </div>
                </div>
            </aside>