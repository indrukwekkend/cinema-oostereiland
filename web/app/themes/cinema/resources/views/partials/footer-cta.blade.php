@if( get_field('cta'))

@php if (TICKETURL === 'tickets.cinemaoostereiland.nl') :
  $cinema = 'Oostereiland';
  $action = 'https://cinemaoostereiland.us6.list-manage.com/subscribe/post?u=8d1d8791782b169a586b36ec0&amp;id=026b2fad4d';
  else : 
    $cinema = 'Enkhuizen';
    $action = 'https://cinemaenkhuizen.us10.list-manage.com/subscribe/post?u=9eaefe3f350b7bf18603721c2&id=451aabf3db';
  endif;
@endphp  

  <!-- Footer formulier -->
<div class="wp-container-2 wp-block-group alignfull has-special-grijs-background-color has-background">
  <div class="wp-block-group__inner-container">
    <h2 class="has-text-align-center">Profiteer van korting op elke film</h2>

    <p class="has-text-align-center">Ben je filmliefhebber en wil je onze activiteiten ondersteunen? Je bent van harte welkom als Vriend van Cinema Oostereiland!</p>

    <div class="wp-container-1 wp-block-buttons">
      <div class="wp-block-button aligncenter is-style-secundair"><a class="wp-block-button__link friend" data-site="<?= TICKETURL ?>">Ik word vriend</a></div>
    </div>
  </div>
</div>

<div class="wp-container-2 wp-block-group alignfull has-special-wit-background-color has-background">
  <div class="wp-block-group__inner-container">
    <h2 class="has-text-align-center">Op de hoogte blijven?</h2>
    <p class="has-text-align-center">Schrijf je dan in voor onze e-mail nieuwsbrief met het actuele programma en de laatste nieuwtjes.</p>

    <div class="wp-container-1 wp-block-buttons nieuwsbriefformulier">
      <div class="wp-block-button aligncenter is-style-secundair">
      <form action="<?= $action ?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate="">
        <div class="input-group">
        

          
          <input type="email" value="" name="EMAIL" class="email search-field form-control" id="mce-EMAIL" placeholder="e-mail adres" required="">
          
          <span class="input-group-btn wp-block-button">
          <button type="submit" value="Inschrijven" name="subscribe" id="mc-embedded-subscribe" class="button is-style-secundair btn-default">Inschrijven</button>
          </span>
        
        </div>
      </form>

      </div>
    </div>
  </div>
</div>

@endif
