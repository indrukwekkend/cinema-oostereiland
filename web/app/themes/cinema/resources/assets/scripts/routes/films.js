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
            event.target.matches('.bestellen') 
          ) {
            // console.log(event);
            openOverlay(event);
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

// open de kaarten modal:
function openModal() {
  $('#ticket-modal').addClass('show');
}

function closeModal() {
  $('#ticket-modal').removeClass('show');
}



const ticketOverlay = document.querySelector('#tickets-overlay');
const closeButton = document.querySelector('.close-btn');
const body = document.querySelector('body');
const getRef = document.getElementById('tickets-iframe-holder');


closeButton.addEventListener('click', () => {
  ticketOverlay.classList.remove('active');
  body.classList.remove('stop-scroll');
});

function openOverlay(e) {
  //stop klikken en toevoegen classes
  e.preventDefault;
  ticketOverlay.classList.add('active');
  body.classList.add('stop-scroll');

  // Haal de informatie op om de juiste Target toe te voegen aan de iframe:
  var el = e.target;
  var index = el.dataset.number;
  var site = el.dataset.site;

  var makeIframe = document.createElement('iframe');
  makeIframe.setAttribute('src', 'https://'+site+'/shop/tickets.php?showid='+index);
  makeIframe.setAttribute('scrolling', 'yes');
  makeIframe.style.border = 'none';
  makeIframe.style.maxWidth = '865px';
  makeIframe.style.height = '1529px';

  getRef.innerHTML = '';
  getRef.appendChild(makeIframe);

}

