export default {
  init() {
    // JavaScript to be fired on the agenda page

    // bij openen gelijk deze funvtie uitvoeren ("Vandaag")
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
    document.getElementById('start').onchange = function(e){
      e.preventDefault();
      removeActiveClass();

      console.log(this.value);
      
      var data = Array();
      data.push({
        name:   'action',
        value:  'get_ajax_agenda_tickets',
      });

      data.push({
        name:   'date',
        value:  this.value,
      });

      actionAjax(data);
    };

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