<div class="entry-content entry-content-agenda">
  <div class="filters">

@php  $alleen_vandaag = get_field('films_datum_selectie', 'options'); @endphp
@php  $titel_actueel = get_field('agenda_actuele_films', 'options'); @endphp


    <div class="list">

      @if ($alleen_vandaag)
        <a href="/agenda" class="option active" id="vandaag">Vandaag</a> 
      @else 
        <a href="/agenda" class="option active" id="vandaag">@php echo $titel_actueel @endphp</a> 
      @endif

<!-- 
      <div class="option">	<input type="date" id="start" name="from"
        value={lastWeekDisplay}
        min="{today}" max={today}></input>
      </div> -->


      <a href="?order=az" class="option" id="az">
        Films A-Z
      </a> 

      <a href="?specials=true" class="option" id="programma">Speciale voorstellingen</a>

    </div>
  </div>
    <div id="loading">
      <div class="sk-chase">
        <div class="sk-chase-dot"></div>
        <div class="sk-chase-dot"></div>
        <div class="sk-chase-dot"></div>
        <div class="sk-chase-dot"></div>
        <div class="sk-chase-dot"></div>
        <div class="sk-chase-dot"></div>
      </div>
  </div>
  @php the_content() @endphp
  {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
</div>
