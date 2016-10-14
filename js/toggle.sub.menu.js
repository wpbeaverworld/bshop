jQuery(document).ready(function ($) {
  $( '.fl-page-nav-collapse ul.navbar-nav > li > .sub-menu' ).before( 
    $( '<button />', {
      'class' : 'sub-menu-toggle',
      'aria-expanded' : false,
      'aria-pressed' : false,
      'role' : 'button'
    } )
    .append( $( '<span />', {
      'class' : 'screen-reader-text',
      text : menuL10n.sunMenu
    } ) )
  );
  
  $( 'button.sub-menu-toggle' ).on( 'click', function(){
    $(this).toggleClass('activated');
    $(this).prev().parent().toggleClass('fl-mobile-sub-menu-open');
  });  
});