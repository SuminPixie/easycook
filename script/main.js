//==================== 메인 배너 슬라이드 ====================
const swiper1 = new Swiper(".mySwiper1", {
  spaceBetween: 0,
  centeredSlides: true,
  autoplay: {
    delay: 5000,
    disableOnInteraction: false,
  },
  pagination: {
    el: ".swiper-pagination",
    type: "fraction",
    clickable: true,
  },
});

// 재생/정지 버튼
// const pauseBtn = document.querySelector("#sec01 .swiper-button-play-pause");

// pauseBtn.addEventListener("click", () => {
//   if (swiper1.autoplay.running) {
//     swiper1.autoplay.stop();
//     pauseBtn.classList.remove("bi-pause");
//     pauseBtn.classList.add("bi-play-fill");
//   } else {
//     swiper1.autoplay.start();
//     pauseBtn.classList.remove("bi-play-fill");
//     pauseBtn.classList.add("bi-pause");
//   }
// });

document.addEventListener("DOMContentLoaded", () => {
  const pauseBtn = document.querySelector("#sec01 .swiper-button-play-pause");
  if (!pauseBtn) return;

  pauseBtn.addEventListener("click", () => {
    if (swiper1.autoplay.running) {
      swiper1.autoplay.stop();
      pauseBtn.classList.remove("bi-pause");
      pauseBtn.classList.add("bi-play-fill");
    } else {
      swiper1.autoplay.start();
      pauseBtn.classList.remove("bi-play-fill");
      pauseBtn.classList.add("bi-pause");
    }
  });
});



//==================== 실시간 인기수강 슬라이드 ====================
const swiper2 = new Swiper(".mySwiper2", {
  slidesPerView: 1.5,
  spaceBetween: 12,
  touchRatio: 1,
  simulateTouch: true,
  breakpoints: {
    376: { slidesPerView: 1.5, spaceBetween: 12 },
    768: { slidesPerView: 2.2, spaceBetween: 12 },
    1025: { slidesPerView: 4, spaceBetween: 12 },
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});


//==================== 마감임박 외 슬라이드 ====================
const swiper3 = new Swiper(".mySwiper3", {
  slidesPerView: 1.5,
  spaceBetween: 12,
  touchRatio: 1,
  simulateTouch: true,
  breakpoints: {
    376: { slidesPerView: 2.2, spaceBetween: 12 },
    768: { slidesPerView: 3.2, spaceBetween: 12 },
    1025: { slidesPerView: 5, spaceBetween: 12 },
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});


//==================== 광고 배너 슬라이드 ====================
const swiper4 = new Swiper(".mySwiper4", {
  spaceBetween: 0,
  centeredSlides: true,
  autoplay: {
    delay: 5000,
    disableOnInteraction: false,
  },
  pagination: {
    el: ".swiper-pagination",
    type: "fraction",
    clickable: true,
  },
});


//==================== 수강생 후기 슬라이드 ====================
const swiper5 = new Swiper(".mySwiper5", {
  slidesPerView: 1.5,
  spaceBetween: 12,
  touchRatio: 1,
  simulateTouch: true,
  breakpoints: {
    376: { slidesPerView: 1.5, spaceBetween: 12 },
    768: { slidesPerView: 2.2, spaceBetween: 12 },
    1025: { slidesPerView: 4, spaceBetween: 12 },
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});


//==================== 탭 전환 이벤트 ====================
$(document).ready(() => {
  $('.tab-link').on('click', function () {
    $('.tab-link').removeClass('active');
    $(this).addClass('active');

    const target = $(this).data('tab-target');
    $('.tab_con > div').removeClass('active');
    $(target).addClass('active');
  });
});


//==================== PC 이미지 변경 함수 ====================
function updateImageSources() {
  const updateImages = (selector) => {
    const images = document.querySelectorAll(selector);
    images.forEach((img) => {
      const src = img.getAttribute('src');
      const isPc = window.innerWidth >= 1025;

      if (isPc && !src.includes('_pc')) {
        img.src = src.replace('.jpg', '_pc.jpg');
      } else if (!isPc && src.includes('_pc')) {
        img.src = src.replace('_pc.jpg', '.jpg');
      }
    });
  };

  updateImages('#sec01 .swiper-slide img');
  updateImages('#sec05 .swiper-slide img');
  updateImages('#sec10 img');
}

window.addEventListener('load', updateImageSources);
window.addEventListener('resize', updateImageSources);
