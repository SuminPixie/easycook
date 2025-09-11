<?php


if($row['category2'] == '자격증'){
  echo "  
  <ul>
    <li><a href='cook_academy.php?catagory2=국비' title='국비' class=' tab_mnu'>국비</a></li>
    <li><a href='cook_academy.php?catagory2=일반' title='일반' class='tab_mnu' >일반</a></li>
    <li><a href='cook_academy.php?catagory2=창업' title='창업' class='tab_mnu' >창업</a></li>
    <li><a href='cook_academy.php?catagory2=취미' title='취미' class='tab_mnu' >취미</a></li>
    <li><a href='cook_academy.php?catagory2=자격증' title='자격증' class='on tab_mnu' >자격증</a></li>
  </ul>
  ";
}elseif($row['category2'] == '국비'){
  echo "  
  <ul>
    <li><a href='cook_academy.php?catagory2=국비' title='국비' class='on tab_mnu'>국비</a></li>
    <li><a href='cook_academy.php?catagory2=일반' title='일반' class='tab_mnu' >일반</a></li>
    <li><a href='cook_academy.php?catagory2=창업' title='창업' class='tab_mnu' >창업</a></li>
    <li><a href='cook_academy.php?catagory2=취미' title='취미' class='tab_mnu' >취미</a></li>
    <li><a href='cook_academy.php?catagory2=자격증' title='자격증' class=' tab_mnu' >자격증</a></li>
  </ul>
  ";
}elseif($row['category2'] == '일반'){
  echo "  
  <ul>
    <li><a href='cook_academy.php?catagory2=국비' title='국비' class='tab_mnu'>국비</a></li>
    <li><a href='cook_academy.php?catagory2=일반' title='일반' class='on tab_mnu' >일반</a></li>
    <li><a href='cook_academy.php?catagory2=창업' title='창업' class='tab_mnu' >창업</a></li>
    <li><a href='cook_academy.php?catagory2=취미' title='취미' class='tab_mnu' >취미</a></li>
    <li><a href='cook_academy.php?catagory2=자격증' title='자격증' class=' tab_mnu' >자격증</a></li>
  </ul>
  ";
}elseif($row['category2'] == '창업'){
  echo "  
  <ul>
    <li><a href='cook_academy.php?catagory2=국비' title='국비' class='tab_mnu'>국비</a></li>
    <li><a href='cook_academy.php?catagory2=일반' title='일반' class='tab_mnu' >일반</a></li>
    <li><a href='cook_academy.php?catagory2=창업' title='창업' class='on tab_mnu' >창업</a></li>
    <li><a href='cook_academy.php?catagory2=취미' title='취미' class='tab_mnu' >취미</a></li>
    <li><a href='cook_academy.php?catagory2=자격증' title='자격증' class=' tab_mnu' >자격증</a></li>
  </ul>
  ";
}elseif($row['category2'] == '취미'){
  echo "  
  <ul>
    <li><a href='cook_academy.php?catagory2=국비' title='국비' class='tab_mnu'>국비</a></li>
    <li><a href='cook_academy.php?catagory2=일반' title='일반' class='tab_mnu' >일반</a></li>
    <li><a href='cook_academy.php?catagory2=창업' title='창업' class='tab_mnu' >창업</a></li>
    <li><a href='cook_academy.php?catagory2=취미' title='취미' class='on tab_mnu' >취미</a></li>
    <li><a href='cook_academy.php?catagory2=자격증' title='자격증' class=' tab_mnu' >자격증</a></li>
  </ul>
  ";
};


?>

