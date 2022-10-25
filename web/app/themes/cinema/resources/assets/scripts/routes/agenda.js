import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export default {
  init() {
    // JavaScript to be fired on the agenda page
    // ajax agenda tickets is de code die staat op: 
    // films plugin: film-agenda
    // 

    // GSAP voor animaties. Hiermee zetten we elementen vast onscrolltrigger:
    gsap.registerPlugin(ScrollTrigger);

    ScrollTrigger.create({
      trigger: '.filters',
      start: 'top top',
      endTrigger: 'html',
      //top verplaatsen naar beneden, krijg je marge, maar naar boven plaatsen van de stop gaat wel goed!
      end: 'bottom -9000px top',
      toggleClass: { targets: '.filters', className: 'active' },
      // markers: true,
    });

    // bij openen gelijk deze functie uitvoeren ("Vandaag, alle films")
    $(function() {
        
      var data = $('#film_number').serializeArray();
      var film_value = $('#film_number').text();

      data.push({
          name:   'action',
          value:  'get_ajax_agenda_tickets',
      });

      // Voorbereidingen voor de input van de verschillende filteringen. 
      // Get calls uit de URL halen en de filtesr voorzien van de juiste ?info=
      data.push({
          name:   'date',
          value:  film_value, // Hier moet het id komen van de film om in te laden in de AJAX call
      });

      data.push({
          name:   'order',
          value:  film_value, // Hier moet het id komen van de film om in te laden in de AJAX call
      });

      data.push({
        name:   'genre',
        value:  film_value, // Hier moet het id komen van de film om in te laden in de AJAX call
      });

      actionAjax(data);

    });

    // Onclick events
    document.getElementById('vandaag').onclick = function(e){
      e.preventDefault();
      removeActiveClass();
      this.classList.add('active');

      var data = Array();

      data.push({
        name:   'action',
        value:  'get_ajax_agenda_tickets',
      });

      actionAjax(data);
    }

    document.getElementById('az').onclick = function(e){
      e.preventDefault();
      removeActiveClass();
      this.classList.add('active');

      var data = Array();

      data.push({
        name:   'action',
        value:  'get_ajax_agenda_tickets',
      });

      data.push({
        name:   'order',
        value:  'az',
      });

      actionAjax(data);
    }

    // onchange event
    document.getElementById('zoek').onchange = function(e){

      e.preventDefault();
      removeActiveClass();
      
      var data = Array();
      data.push({
        name:   'action',
        value:  'get_ajax_agenda_tickets',
      });

      data.push({
        name:   'order',
        value:  this.value,
      });

      actionAjax(data);
    };

    document.getElementById('programma').onclick = function(e){
      e.preventDefault();
      // controleren en toevoegen en verwijderen van action class!!!
      removeActiveClass();
      this.classList.add('active');

      var data = Array();

      data.push({
        name:   'action',
        value:  'get_ajax_agenda_tickets',
      });

      data.push({
        name:   'programma',
        value:  'programma',
      });

      actionAjax(data);
    }

    // onchange event
    // document.getElementById('start').onchange = function(e){
    //   e.preventDefault();
    //   removeActiveClass();

    //   console.log(this.value);
      
    //   var data = Array();
    //   data.push({
    //     name:   'action',
    //     value:  'get_ajax_agenda_tickets',
    //   });

    //   data.push({
    //     name:   'date',
    //     value:  this.value,
    //   });

    //   actionAjax(data);
    // };


    // openen en sluiten van de kaarten module
    document.addEventListener(
      'click',
      function(event) {
        console.log(event);
        // If user either clicks X button OR clicks outside the modal window, then close modal by calling closeModal()

        if (
          event.target.matches('.bestellen') 
        ) {
          console.log(event);
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
  },
};

function actionAjax(data) {
    const ajax_url = window.my_ajax_object.ajax_url || false;
    if (ajax_url) {
        $.ajax({
            method: 'POST',
            url: ajax_url,
            data: data,
            //action: 'get_ajax_cursus', // in de data-call
            
            beforeSend: function() {
                $('#loading').show();
                $('.ajax-content').html('');
            },
            success: function (result) {
                $('.ajax-content').html(result);
                $('#loading').hide();
            },
        });
    }
}

function removeActiveClass() {
  const elems = document.querySelectorAll('a.option');

  elems.forEach((elem) => {
    elem.classList.remove('active');
  });
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
  console.log(index);

  var makeIframe = document.createElement('iframe');
  makeIframe.setAttribute('src', 'https://'+site+'/shop/tickets.php?showid='+index);
  // makeIframe.setAttribute('src', 'https://kassa.echtekaartjes.nl/shop/tickets-new.php?showid=636');
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
