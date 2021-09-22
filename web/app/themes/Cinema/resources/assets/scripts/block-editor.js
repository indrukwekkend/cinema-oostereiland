/**
 * Dit is JS code voor het gebruik van blokken algemeen.
 * @Since startertheme 0.0.1
 */

// button styles
wp.domReady( function() {
  
  wp.blocks.registerBlockStyle( 'core/button', {
     name: 'secundair',
     label: 'Secundair Button',
  } );

  wp.blocks.registerBlockStyle( 'core/button', {
     name: 'arrow',
     label: 'Pijl Link',
  } );

  wp.blocks.unregisterBlockStyle( 'core/button', 'fill' );
} );


// Todo hier overige block instellingen: verwijderen van niet gebruikte blokken bijvoorbeeld