import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export default {
  init() {
    // JavaScript to be fired on the home page

    // GSAP voor animaties. Hiermee zetten we elementen vast onscrolltrigger:
    gsap.registerPlugin(ScrollTrigger);

    // scroll trigger pin CTA element

    ScrollTrigger.create({
      trigger: '#masthead',
      start: 'top top',
      endTrigger: 'html',
      end: 'bottom top',
      toggleClass: { targets: '#masthead', className: 'active' },
      // markers: true,
    });

    // pak alle sliders op de filmpagina op, in 1 array.
    const sliders = document.querySelectorAll('.vandaag-slider');
    //Met alle sliders:
    sliders.forEach((slider) => {
      const slidesToShow = 3;

      const slick = slider.querySelectorAll('.slider');
      $(slick).slick({
        // normal options...
        infinite: true,
        slidesToShow: slidesToShow,
        slidesToScroll: 1,

        // the magic
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: slidesToShow - 1,
            },
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 2,
              dots: true,
            },
          },
          {
            breakpoint: 415,
            settings: 'unslick', // destroys slick
          },
        ],
      });
    });
  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  },
};
