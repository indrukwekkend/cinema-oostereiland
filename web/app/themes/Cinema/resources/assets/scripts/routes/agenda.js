export default {
  init() {
    // JavaScript to be fired on the agenda page

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
            },
            success: function (result) {
          
                $('.ajax-content').html(result);
                $('#loading').hide();
            },
        });
    }
}