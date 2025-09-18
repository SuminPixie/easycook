<!-- 공지관리 -->

<?php 

include('../inc/dbconn.php');

$no = $_POST['no'];
$delete = $_POST['delete'];

if(isset($no)){
  $notice_title = $_POST['notice_title'];
  $notice_memo = nl2br($_POST['notice_memo']);
  $class_no = $_POST['class_no'];
  $teacher = $_POST['teacher'];
  date_default_timezone_set('Asia/Seoul'); //서울시간대
  $datetime = date('y-m-d H:i:s');

  if(isset($delete)){
    // echo $delete;
    // echo $no;
    $sql = "delete from easycook_board where no = '$no'";
    $result = mysqli_query($conn, $sql);    
    if($result){
      echo "<script>confirm('공지를 삭제하였습니다.')</script>";
      echo "<script>history.go(-2);</script>";
    }else{
      echo "<script>alert(등록 실패 : ". mysqli_error($conn).")</script>";
      mysqli_close($conn);
    }
  }else{
    //echo "수정";
    $sql = "update easycook_board 
    set 
    class_no = '$class_no',
    title = '$notice_title',
    memo = '$notice_memo',
    id = '$teacher',
    datetime = '$datetime'
    where no = '$no'";  
    $result = mysqli_query($conn, $sql);    
    if($result){
      echo "<script>alert('공지가 수정 되었습니다.')</script>";
      echo "<script>history.go(-2);</script>";
    }else{
      echo "<script>alert(등록 실패 : ". mysqli_error($conn).")</script>";
      mysqli_close($conn);
    }
  }

}else{
  echo "작성";
  //board 공지사항에서 데이터 받아오기
  $sql = "select * from easycook_board where no='$no';";
  $result = mysqli_query($conn, $sql);
  $db = mysqli_fetch_array($result);  
  
  // 공지 작성
  $class_no = $_POST['class_no'];
  echo "번호".$class_no;
  
  $id = $_SESSION['id'];    
  $memo = nl2br($_POST['memo']);
  $title = $_POST['title'];
  date_default_timezone_set('Asia/Seoul'); //서울시간대
  $datetime = date('y-m-d H:i:s');
  
  echo "<br>아이디".$id;
  echo "<br>내용".$memo;
  echo "<br>제목".$title;
  echo "<br>작성일".$datetime;
  
  $sql = "select * from easycook_board where no='$no'";
  $result = mysqli_query($conn, $sql);
  $q=mysqli_fetch_row($result);
  
  
  $sql = "
  insert into easycook_board 
  set 
  class_no= '$class_no', 
  title = '$title', 
  memo ='$memo', 
  id ='$id', 
  datetime='$datetime';
  ";
  
  $result = mysqli_query($conn, $sql);
  
  if($result){
    echo "<script>alert('공지 작성 되었습니다.')</script>";
    echo "<script>history.go(-2);</script>";
  }else{
    echo "<script>alert(등록 실패 : ". mysqli_error($conn).")</script>";
    mysqli_close($conn);
  }
}


?>