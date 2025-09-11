<?php
  session_start();
  include('../inc/dbconn.php');

  if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
  }else{
    $id = null;
  }

$category1 = isset($_GET['category1']) ? $_GET['category1'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '최신순'; // 기본값 '최신순'

$whereClauses = [];

if ($category1 !== '') {
    $whereClauses[] = "category1 LIKE '%$category1%'";
}
if ($type !== '') {
    $whereClauses[] = "category2 = '$type'";
}
if ($date !== '') {
    $whereClauses[] = "category3 = '$date'";
}
if ($difficulty !== '') {
    $whereClauses[] = "grade = '$difficulty'";
}

// 현재날짜 조회 후 현재날짜 이후의 강의들만 불러옴
$currentDate = date('Y-m-d');
$whereClauses[] = "start_date > '$currentDate'";


$whereSql = implode(' AND ', $whereClauses);

if ($whereSql) {
    $whereSql = 'WHERE ' . $whereSql;
}

// 최신등록순을 기본으로 출력함
//$sql = "SELECT * FROM easycook_academy_list $whereSql ORDER BY datetime DESC";


// 정렬 기준에 따른 쿼리 작성
switch ($sort) {
  case '인기순':
    $sql = "
      SELECT a.*, COUNT(o.class_no) AS order_count
      FROM easycook_academy_list a
      LEFT JOIN `easycook_order` o ON a.class_no = o.class_no
      $whereSql
      GROUP BY a.class_no
      ORDER BY order_count DESC
    ";
    break;
  case '마감임박순':
    $sql = "
      SELECT *, DATEDIFF(start_date, NOW()) AS days_left 
      FROM easycook_academy_list a
      $whereSql
      ORDER BY days_left ASC
    ";
    break;
  case '최신순':
  default:
    $sql = "SELECT * FROM easycook_academy_list $whereSql ORDER BY datetime DESC";
    break;
}

$result = mysqli_query($conn, $sql);

$cart_class_no = [];
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $cart_sql = "SELECT class_no FROM easycook_cart WHERE id = '$id'";
    $cart_result = mysqli_query($conn, $cart_sql);
    while ($cart_row = mysqli_fetch_assoc($cart_result)) {
        $cart_class_no[] = $cart_row['class_no'];
    }
}

while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <div>
      <div class="cart" data-no="<?php echo $row['class_no']; ?>">
        <img src="./images/common/<?php echo in_array($row['class_no'], $cart_class_no) ? 'heart_r' : 'heart_w'; ?>.png" alt="찜버튼">
      </div>
      <a href="./detail.php?class_no=<?php echo $row['class_no']; ?>" title="상품">
        <img src="./uploads/class_main/<?php echo $row['thumnail_img']; ?>" alt="이미지">
        <div class="con-text">
          <p class="con-title"><?php echo $row['name']; ?></p>
          <p class="con-sub"><?php echo $row['detail']; ?></p>
        </div>
      </a>
    </div>
    <?php
}

?>
