import 'slick-carousel';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export default {
  init() {
    // JavaScript to be fired on the Films pagina's
      
      // GSAP voor animaties. Hiermee zetten we elementen vast onscrolltrigger:
      gsap.registerPlugin(ScrollTrigger);

      // scroll trigger pin CTA element
      ScrollTrigger.create({
        start: 'top top', 
        end: '+=150000',
        toggleClass: {targets: '#cta-bar', className: 'active'}, 
      });

      ScrollTrigger.create({
        trigger: '#cta-bar',
        start: 'top top', 
        pin: '#cta-bar',
        end: '+=150000',
        toggleClass: {targets: '#cta-bar', className: 'smaller'},
      });

      // pak alle sliders op de filmpagina op, in 1 array.
      const sliders = document.querySelectorAll('.block-slider')
      //Met alle sliders:
      sliders.forEach(slider => {
        const slidesToShow = 3;
        // console.log(slidesToShow);

        const slick = slider.querySelectorAll('.slider')
        $(slick).slick( {

        
          // normal options...
          infinite: true,
          slidesToShow: slidesToShow,
          slidesToScroll: 1,
        
          // the magic
          responsive: [ {
        
            breakpoint: 1024,
            settings: {
              slidesToShow: slidesToShow -1,
            },
        
          // }, {
        
          // 	breakpoint: 600,
          // 	settings: {
          // 		slidesToShow: 2,
          // 		dots: true,
          // 	},
        
          // }, {
        
          // 	breakpoint: 300,
          // 	settings: 'unslick', // destroys slick
        
          } ],
        } );

      });
  },
};

