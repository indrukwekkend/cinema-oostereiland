<div class="entry-content entry-content-agenda">
  <div class="filters">

@php  $alleen_vandaag = get_field('films_vandaag', 'options'); @endphp


    <div class="list">

      @if ($alleen_vandaag)
        <a href="/agenda" class="option active" id="vandaag">Vandaag</a> 

        <div class="option">	<input type="date" id="start" name="from"
          value={lastWeekDisplay}
          min="{today}" max={today}></input>
        </div>

      @endif

      @if (!$alleen_vandaag)
        <a href="/agenda" class="option active" id="vandaag">Actueel</a> 
      @endif

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
