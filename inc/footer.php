<style>
  /* 푸터서식 */
  footer{
    background: var(--darkbrown);
    color: rgba(255,255,255,0.6);
    font-size: var(--fs-small);
    font-weight: var(--fw-light);
  }
  footer .container{
    padding: 0 var(--p_20);
    max-width: 1025px; /* 기본 푸터너비 1025, index에서는 1200따로 설정 */
    padding-bottom: 60px;
  }
  footer .link{
    display: flex;
    justify-content: space-around;
  }
  footer .link a{
    color: var(--white);
    font-weight: var(--fw-normal);
    text-align: center;
  }
  /* 하단 로고랑 sns */
  .logo-box{
    display: flex;
    justify-content: space-between;
    margin-top: 0;
  }
  .footer-logo {
    width: 150px;
    height: 50px;
  }
  .social-icons{
    height: 50px;
    font-size: var(--fs-xlarge);
    margin-top: 0;
    text-align: right;
  }
  .social-icons a {
    color: rgba(255,255,255,0.6);
    margin: 0 5px;
  }

  @media (min-width: 768px) {
  .logo-box{
    margin-top: 1.5rem;
    flex-wrap:wrap;
    flex-direction: column;
    justify-content:right;
  }
  .logo-img{
    text-align: right;
  }
  .social-icons{
    margin-top: 20px;
  }
}
</style>


<!-- 푸터영역 -->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-12 link mt-4">
        <a href="./intro.php?cata=소개&tab=소개" title="회사소개">회사소개</a>
        <a href="./promise.php" title="회원약관">회원약관</a>
        <a href="./promise.php" title="개인정보처리방침">개인정보처리방침</a>
        <a href="javascript:void(0);" title="수강료 안내">수강료 안내</a>
      </div>
      <div class="col-12 col-md-6 mt-4 mb-4 d-flex" style="line-height: 160%; gap:10px;">
      <div>
        <p>Company. Easy Cook (이지쿡)</p>
        <p>
          Address. 서울특별시 강남구 테헤란로 123<br>
          Business License. 123-45-67890<br>
          Owner. 홍길동
        </p>
      </div>
      <div>
        <p>
          Personal Information Manager. 박수민<br>
          Email. easycook@example.com<br>
          Phone Number. 010-1234-5678
        </p>
        <p>Copyright ⓒ Easy Cook. All Rights Reserved.</p>
      </div>

      </div>
      <div class="col-12 col-md-6 logo-box">
        <div class="logo-img">
          <img src="./images/common/logo_w.png" alt="하단로고" class="footer-logo">
        </div>
        <div class="social-icons mb-4">
          <a href="javascript:void(0);"><i class="bi bi-chat-dots"></i></a>
          <a href="javascript:void(0);"><i class="bi bi-twitter"></i></a>
          <a href="javascript:void(0);"><i class="bi bi-youtube"></i></a>
          <a href="javascript:void(0);"><i class="bi bi-facebook"></i></a>
        </div>
      </div>
    </div>
  </div>
</footer>

