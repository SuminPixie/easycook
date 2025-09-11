  <style>
    .hide{display:none;}
    .re_com{
      width:90%;
      margin:50px auto;
    }
    .re_com article{
      width:100%;
      text-align:center;
      padding-top: 50px;
    }
    .re_com a{
      color:#fff;
      font-weight:var(--fw-normal);
    }
    .re_com i{
      font-size:84px;
      color:#a8a8a8;
    }
    .re_com h2{
      font-size:var(--fs-xlarge);
      font-weight:var(--fw-bold);
      margin-top:28px;
      margin-bottom:28px;
    }
    .re_com span{
      color:var(--green);
    }
    .re_com p{
      font-size:var(--fs-medium-large);
      margin-bottom:62px;
    }
    /* @media (min-width: 1400px) {
      .re_com {
        width: 1400px;
      }
      .re_com article{
        width:50%;
        margin:0 auto;
        text-align:center;
        padding-top:180px;
      }
    } */
  </style>

  <section class="re_com">
    <h2 class="hide">현재 페이지는 <span>준비중</span>입니다</h2>
    <article>
      <i class="bi bi-alarm"></i>
      <h2>현재 페이지는 <span>준비중</span>입니다</h2>
      <p>이용에 불편을 드려 죄송합니다.<br>빠른 시일 내에 더욱 나은 모습으로 <br> 찾아뵙겠습니다.</p>
      <div class="btn-box-l">
        <a href="./index.php" class="btn-l">메인으로</a>
      </div>
    </article>
  </section>

  <script>
    // ----------모든 <style> 태그를 찾아서 <head>로 이동---------
    $('style').each(function() {
      // <head>가 존재하는지 확인
      if ($('head').length) {
        // <style> 태그를 <head>로 이동
        $('head').append($(this));
      }
    });
  </script>
