// import { gsap } from 'gsap';
// import { ScrollTrigger } from 'gsap/ScrollTrigger';

// gsap.registerPlugin(ScrollTrigger);

// GSAP nog experimenteel. Nog inladen in aparte branch

export default {
  init() {
    // JavaScript to be fired on all pages

    // Back to top pijltje, scroll naar boven
    if ($('#back-to-top').length) {
      var scrollTrigger = 100, // px
      backToTop = function () {
          var scrollTop = $(window).scrollTop();
          if (scrollTop > scrollTrigger) {
              $('#back-to-top').addClass('show');
          } else {
              $('#back-to-top').removeClass('show');
          }
      };
      backToTop();
      $(window).on('scroll', function () {
          backToTop();
      });
      $('#back-to-top').on('click', function (e) {
          e.preventDefault();
          $('html,body').animate({
              scrollTop: 0,
          }, 700);
      });
    }

    $('#search-open').click( function(){
      $('#search').addClass('active');
    }),
    $('#search-close').click( function(){
      $('#search').removeClass('active');
    }),

    $('#cart-open').click( function(event){
      // console.log(event);
      openOverlay(event);
    }),


    $( 'button.hamburger' ).click(function() {
      $( this ).toggleClass('is-active');
      $('.mobile-navigation-container').removeClass('start');
      $('.mobile-navigation-container').toggleClass('open');
      $('.mobile-navigation-container').toggleClass('closed');

      $('.mobile-navigation-overlay').removeClass('start');
      $('.mobile-navigation-overlay').toggleClass('open');
      $('.mobile-navigation-overlay').toggleClass('closed');

      $('#page').toggleClass('menu-open');
    });

    // accordion navigatie op mobiel
    // http://cssmenumaker.com/blog/wordpress-accordion-menu-tutorial/
    
    $('#cssmenu li.has-sub>a').on('click', function(){
      // var href = $(this).attr('href'); 
      // $(this). attr('data-href', href);
      $(this).removeAttr('href');
      $(this).children('.link_text').prepend('<a>');

      var element = $(this).parent('li');
      if (element.hasClass('open')) {
        // var dataHref = $(this).attr('data-href'); 
        // $(this). attr('href', dataHref);
        element.removeClass('open');
        element.find('li').removeClass('open');
        element.find('ul').slideUp();
      }
      else {
        element.addClass('open');
        element.children('ul').slideDown();
        element.siblings('li').children('ul').slideUp();
        element.siblings('li').removeClass('open');
        element.siblings('li').find('li').removeClass('open');
        element.siblings('li').find('ul').slideUp();
      }
      //$(this). attr('href', href);
    });
  
    $('#cssmenu>ul>li.has-sub>a').append('<span class="holder"></span>');  

    // nog een keer voor de Footer:
    $('#mobilefootermenu li.has-sub>a').on('click', function(){
      // var href = $(this).attr('href'); 
      // $(this). attr('data-href', href);
      $(this).removeAttr('href');
      $(this).children('.link_text').prepend('<a>');

      var element = $(this).parent('li');
      if (element.hasClass('open')) {
        // var dataHref = $(this).attr('data-href'); 
        // $(this). attr('href', dataHref);
        element.removeClass('open');
        element.find('li').removeClass('open');
        element.find('ul').slideUp();
      }
      else {
        element.addClass('open');
        element.children('ul').slideDown();
        element.siblings('li').children('ul').slideUp();
        element.siblings('li').removeClass('open');
        element.siblings('li').find('li').removeClass('open');
        element.siblings('li').find('ul').slideUp();
      }
      //$(this). attr('href', href);
    });
  
    $('#mobilefootermenu>ul>li.has-sub>a').append('<span class="holder"></span>');  

    // pak alle sliders op de filmpagina op, in 1 array.
    const sliders = document.querySelectorAll('.festival-films')
    //Met alle sliders:
    sliders.forEach(slider => {
      const slidesToShow = 5;
      console.log(slidesToShow);

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

    // openen en sluiten van de kaarten bestel modules
    document.addEventListener(
      'click',
      function(event) {
         console.log(event);
        // If user either clicks X button OR clicks outside the modal window, then close modal by calling closeModal()
        if (
          event.target.matches('.bestellen') 
        ) {
          // console.log(event);
          openOverlay(event);
        }
        else if (
          event.target.matches('.friend') 
        ) {
          // console.log(event);
          openOverlay(event);
        }
        else if (
          event.target.matches('.cadeau') 
        ) {
          // console.log(event);
          openOverlay(event);
        }
        else if (
          event.target.matches('.cart') 
        ) {
          // console.log(event);
          openOverlay(event);
        }
      },
      false
    )
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};



const ticketOverlay = document.querySelector('#tickets-overlay');
const closeButton = document.querySelector('.close-btn');
const gaVerder = document.querySelector('.ga-verder');
const body = document.querySelector('body');
const getRef = document.getElementById('tickets-iframe-holder');


closeButton.addEventListener('click', () => {
  ticketOverlay.classList.remove('active');
  body.classList.remove('stop-scroll');
});

gaVerder.addEventListener('click', () => {
  var currentLocation = window.location.pathname;

  if (currentLocation == '/agenda/') {
    ticketOverlay.classList.remove('active');
    body.classList.remove('stop-scroll');
  } else {
      window.location = '/agenda';
  }
});


function openOverlay(e) {
  //stop klikken en toevoegen classes
  e.preventDefault;
  // console.log(e);
  ticketOverlay.classList.add('active');
  body.classList.add('stop-scroll');

  // Haal de informatie op om de juiste Target toe te voegen aan de iframe:

  let el = e.target;

  var index = el.dataset.number;
  var site = el.dataset.site;
  var classes = el.className;

  // console.log (el);
  // console.log (site);

  var makeIframe = document.createElement('iframe');
  
  if (classes.includes('friend')) {
    makeIframe.setAttribute('src', 'https://'+site+'/membership/enroll.php');
  }
  else if (classes.includes('cart')) {
    // console.log('hier' + classes);
    makeIframe.setAttribute('src', 'https://'+site+'/shop/cart.php');
  }
  else if (classes.includes('cadeau')) {
    // console.log('hier' + classes);
    makeIframe.setAttribute('src', 'https://'+site+'/coupons/purchase.php');
  }
  else {
    makeIframe.setAttribute('src', 'https://'+site+'/shop/tickets.php?showid='+index);
  }
  makeIframe.setAttribute('scrolling', 'yes');
  makeIframe.style.border = 'none';
  makeIframe.style.maxWidth = '865px';
  makeIframe.style.height = '150px';

  window.addEventListener('message', function(e) {
		// message that was passed from iframe page
		let message = e.data;

		makeIframe.style.height = message.height + 'px';
		makeIframe.style.width = message.width + 'px';
	} , false);

  getRef.innerHTML = '';
  getRef.appendChild(makeIframe);

  

}


