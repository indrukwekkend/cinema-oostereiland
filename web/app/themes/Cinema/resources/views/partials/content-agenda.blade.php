<div class="entry-content entry-content-agenda">
  <div class="filters">
    <h2>Wat wilt u zien?</h2>
    <div class="list">
      <a href="https://lumiere.nl/programma?sort=date&amp;date=2021-08-05" class="option">Vandaag</a> 
      <div class="option active">11 aug. 2021</div>
      <a href="https://lumiere.nl/programma?sort=now&amp;date=2021-08-11" class="option">films a-z</a> 
      <a href="https://lumiere.nl/verwacht" class="option">Verwacht</a>
    </div>
  </div>
  @php the_content() @endphp
  {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
</div>
