
<style>
  /* 푸터서식 */
  footer{
    background: var(--darkbrown);
    color: rgba(255,255,255,0.6);
    font-size: var(--fs-small);
    font-weight: var(--fw-thin);
  }
  footer .container{
    padding: 0 var(--p_20);
    max-width: 1025px; /* 기본 푸터너비 1025, index에서는 1200따로 설정 */
  }
  footer .link{
    display: flex;
    justify-content: space-around;
  }
  footer .link a{
    color: var(--white);
    font-weight: var(--fw-light);
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
        <a href="#" title="회사소개">회사소개</a>
        <a href="#" title="회원약관">회원약관</a>
        <a href="#" title="개인정보처리방침">개인정보처리방침</a>
        <a href="#" title="수강료 안내">수강료 안내</a>
      </div>
      <div class="col-12 col-md-6 mt-4 mb-4 d-flex" style="line-height: 160%;">
        <div>
          <p>Company.Easy Cook(이지쿡)</p>
          <p>
            Address. 0000 0000 00-0 0층<br>
            Business License. 2024 0000 0000 0000000<br>
            Owner. 000
          </p>
        </div>
        <div>
          <p>
            Personal Information Manager. 000<br>
            Email.00000@0000.000<br>
            Phone Number. 000-0000-0000
          </p>
          <p>Copyright ⓒ Easy Cook, All Rights Reserved.</p>
        </div>
      </div>
      <div class="col-12 col-md-6 logo-box">
        <div class="logo-img">
          <img src="./images/common/logo_w.png" alt="하단로고" class="footer-logo">
        </div>
        <div class="social-icons mb-4">
          <a href="#"><i class="bi bi-chat-dots"></i></a>
          <a href="#"><i class="bi bi-twitter"></i></a>
          <a href="#"><i class="bi bi-youtube"></i></a>
          <a href="#"><i class="bi bi-facebook"></i></a>
        </div>
      </div>
    </div>
  </div>
</footer>

