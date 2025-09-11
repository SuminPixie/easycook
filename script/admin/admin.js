$('document').ready(function(){
  let tab_mnu = $('#tab_con a');
  tab_mnu.click(function(e){
    e.preventDefault; //새로고침 방지 -- 무조건 쓰기! 
    $('.con').hide(); //콘텐츠 숨기고
    $(this).next().show(); // 내가 선택한 것의 형제 div를 보여준다
    $(this).addClass('act').parent().siblings().find('a').removeClass('act'); //클래스 변경하기
  });

  let class_list = document.querySelectorAll('.list ul li a'); 
  // console.log(class_list);
  let class_con04 = document.getElementById('#class_con04'); 
  
  for(let i=0;i<class_list.length;i++){
    class_list[i].addEventListener('click',function(e){
      e.preventDefault();
      if(i==0){
        // alert(0);
        this.classList.add('list_act');
        i++;
      }else if(i==1){
        // alert(1);
        this.classList.add('list_act');
        i++;
      }
      else if(i==2){
        // alert(2);
        this.classList.add('list_act');
        i++;
      }
      else{
        // alert(3);
        this.classList.add('list_act');
      }
    })};

  // 모바일 토글 열기 닫기
    const list_btn = document.getElementById('m_list');
    list_btn.addEventListener('click',function(){
      // alert('클릭');
      document.getElementById('left_nav').style.display = "block";
      document.getElementById('left_nav').style.width = "70%";
    });

    const close_m_list = document.getElementById('close_m_list');
    close_m_list.addEventListener('click',function(){
      // alert('클릭');
      document.getElementById('left_nav').style.display = "none";
      document.getElementById('left_nav').style.width = "0px";
    });  

    //--------------탑버튼 서식-----------
    var $topButton = $('#topButton');
    var $main = $('section'); // main 요소를 선택

    $main.scroll(function() {
      if ($(this).scrollTop() > 20) {
        $topButton.fadeIn();
      } else {
        $topButton.fadeOut();
      }
    });

    $topButton.click(function() {
      $main.animate({ scrollTop: 0 }, 'fast');
    });



});