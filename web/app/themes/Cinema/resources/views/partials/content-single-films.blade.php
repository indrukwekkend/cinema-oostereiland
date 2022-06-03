<div @php post_class() @endphp>

  <?php
  /**
   * Houder voor alle film content onderdelen
   */

    $film_id = get_the_id();
    // overige meta:
  ?>

  <!-- header van de Film pagina met Youtube en grote foto  -->
  @include('partials.page-header-film')

  <div class="entry-content">
    @php the_content() @endphp
  </div>


  <!-- OPTIE blok met Film Festival  -->
  @include('partials.content-single-films-festivals')

  <!-- OPTIE Special blok. Gekoppeld aan de Special CPT -->
  @include('partials.content-single-films-specials')

  <!-- OPTIE OVERIGE FILMS blok met interessante andere films -->
  @include('partials.content-single-films-overigeFilms')

</div>

<!-- Youtube script code  -->
<script>	
	var player;
	players = new Array();
	var headerplayer;
	var headerplayermobile;
	function onYouTubePlayerAPIReady() {
		var temp = jQuery("iframe.yt_players");
	    for (var i = 0; i < temp.length; i++) {
	        var t = new YT.Player(jQuery(temp[i]).attr('id'), {
	            events: {
	                'onReady': onPlayerReady,
	                'onStateChange': onPlayerStateChange
	            }
	        });
	        players.push(t);
	    }    
	    headerplayer = new YT.Player('video', {
		    events: {
		      'onReady': onPlayerReadyHeader
		    }
		});
		headerplayermobile = new YT.Player('video_mob', {
	    	events: {
			'onReady': onPlayerReadyHeaderMobile
			}
		});
	}
	function onPlayerReadyHeader(event) {
		var playButton = document.getElementById("open-youtube");
		playButton.addEventListener("click", function() {
			headerplayer.playVideo();
			jQuery('.film_media').slideToggle(800);
			jQuery('#masthead').slideToggle("slow");
		});
	  
		var pauseButton = document.getElementById("close-youtube");
		pauseButton.addEventListener("click", function() {
			headerplayer.pauseVideo();

		});
	}
	function onPlayerReadyHeaderMobile(event) {
		var playButton = document.getElementById("open-youtube-mobile");
		playButton.addEventListener("click", function() {
			headerplayermobile.playVideo();
		});
	}

	jQuery('.kill_btn').click(function () {
			jQuery('.film_media').slideToggle(800);
			jQuery('#masthead').slideToggle("slow");
	});

	jQuery('.youtube_btn_mob').click(function () {
			jQuery('.film_media_mob').slideToggle(600);
	});

	(function(window, document, undefined) {
	  "use strict";
	  var players = ['iframe[src*="youtube.com"]', 'iframe[src*="vimeo.com"]'];
	  var fitVids = document.querySelectorAll(players.join(","));
	  if (fitVids.length) {
	    for (var i = 0; i < fitVids.length; i++) {
	      var fitVid = fitVids[i];
	      var width = fitVid.getAttribute("width");
	      var height = fitVid.getAttribute("height");
	      var aspectRatio = height / width;
	      var parentDiv = fitVid.parentNode;
	      var div = document.createElement("div");
	      div.className = "fitVids-wrapper";
	      div.style.paddingBottom = aspectRatio * 100 + "%";
	      parentDiv.insertBefore(div, fitVid);
	      fitVid.remove();
	      div.appendChild(fitVid);
	      fitVid.removeAttribute("height");
	      fitVid.removeAttribute("width");
	    }
	  }
	})(window, document);
</script>
<script src="https://www.youtube.com/player_api" type="text/javascript"></script>