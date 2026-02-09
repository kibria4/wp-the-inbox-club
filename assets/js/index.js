// Tailwind styles
import '../css/style.css';

// --- OPTIONAL LIBRARIES ---
// Comment/uncomment as needed. Keeping them here for future use.

// Swiper (sliders/carousels)
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

// import Swiper from 'swiper';
// import 'swiper/css';

// GSAP (animations)
// import { gsap } from 'gsap';

// Barba (page transitions)
// import barba from '@barba/core';

// Flowbite (Tailwind components)
import 'flowbite';

// HTMX (progressive enhancement / partial updates)
// import 'htmx.org';

// Check if we're in the WordPress block editor
// const isBlockEditor = document.body.classList.contains('block-editor-page');

/**
 //HEADER - links-social, header-nav, header-nav-services
  document.addEventListener('DOMContentLoaded', () => {
    if( isBlockEditor) return;
    if(!document.querySelector('.block-herofullscreen')) return;

    const linksSocial = document.querySelectorAll('.links-social a');
    const headerNav = document.querySelectorAll('#nav a');
    const headerNavServices = document.querySelectorAll('#search-toggle');
    document.querySelector('#header #search-toggle span').classList.add('text-white');
    document.querySelectorAll('#header #search-toggle svg path').forEach(path => path.classList.add('stroke-white'));

    // document.querySelector('header hr').classList.add('lg:border-white', 'border-dark');

    [...headerNav, ...headerNavServices].forEach(link => link.classList.add('text-white'));

    [...linksSocial].forEach(link => {
        var first = true;
        if(first){
            first = false;
            link.classList.add('brightness-[5]');
        }
        link.querySelector('img').classList.add('invert');
    });
  });
 */

// Example: basic Swiper initialisation if .js-swiper exists
/**
document.addEventListener('DOMContentLoaded', () => {
  const swiperEl = document.querySelector('.js-swiper');
  if (swiperEl) {
    // eslint-disable-next-line no-new
    new Swiper(swiperEl, {
      slidesPerView: 1,
      loop: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
  }
});
*/
