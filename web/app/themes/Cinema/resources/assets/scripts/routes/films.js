import 'slick-carousel';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export default {
  init() {
    // JavaScript to be fired on the Films pagina's

      // openen en sluiten van de kaarten module
      document.addEventListener(
        'click',
        function(event) {
          console.log(event);
          // If user either clicks X button OR clicks outside the modal window, then close modal by calling closeModal()
          if (
            event.target.matches('#toon_tickets')
          ) {
            openModal()
          }
          else if (
            event.target.matches('#ticket-modal__close') ||
            !event.target.closest('.ticket-modal__wrapper')
          ) {
            closeModal()
          }
        },
        false
      )
      
      // GSAP voor animaties. Hiermee zetten we elementen vast onscrolltrigger:
      gsap.registerPlugin(ScrollTrigger);

      // scroll trigger pin CTA element
      ScrollTrigger.create({
        trigger: '#cta-barxxxx',
        start: 'top top', 
        pin: '#cta-barxxx',
        end: '+=15000',
        toggleClass: {targets: '#cta-bar', className: 'active'}, 
      });

      // pak alle sliders op de filmpagina op, in 1 array.
      const sliders = document.querySelectorAll('.block-slider')
      //Met alle sliders:
      sliders.forEach(slider => {
        const slidesToShow = 3;
        console.log(slidesToShow);

        const slick = slider.querySelectorAll('.sliderlijst')
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

function openModal() {
  $('#ticket-modal').addClass('show');
}

function closeModal() {
  $('#ticket-modal').removeClass('show');
}


