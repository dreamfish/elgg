<?php
// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

global $CONFIG;
?>
// ELGG TOOLBAR MENU
jQuery.fn.elgg_topbardropdownmenu = function(options) {
    
  options = jQuery.extend({speed: 350}, options || {});
  
  this.each(function() {
    
    var root = this, zIndex = 5000;
    
    function getSubnav(ele) {
      if (ele.nodeName.toLowerCase() == 'li') {
        var subnav = jQuery('> ul', ele);
        return subnav.length ? subnav[0] : null;
      } else {
	      
        return ele;
      }
    }
    
    function getActuator(ele) {
      if (ele.nodeName.toLowerCase() == 'ul') {
        return jQuery(ele).parents('li')[0];
      } else {
        return ele;
      }
    }
    
    function hide() {
      var subnav = getSubnav(this);
      if (!subnav) return;
      jQuery.data(subnav, 'cancelHide', false);
      setTimeout(function() {
        if (!jQuery.data(subnav, 'cancelHide')) {
          jQuery(subnav).slideUp(100);
        }
      }, 250);
    }
  
    function show() {
      var subnav = getSubnav(this);
      if (!subnav) return;
      jQuery.data(subnav, 'cancelHide', true);
      jQuery(subnav).css({zIndex: zIndex++}).slideDown(options.speed);
      if (this.nodeName.toLowerCase() == 'ul') {
        var li = getActuator(this);
        jQuery(li).addClass('hover');
        jQuery('> a', li).addClass('hover');
      }
    }
    
    jQuery('ul, li', this).hover(show, hide);
    jQuery('li', this).hover(
      function() { jQuery(this).addClass('hover'); jQuery('> a', this).addClass('hover'); },
      function() { jQuery(this).removeClass('hover'); jQuery('> a', this).removeClass('hover'); }
    );
    
  });
  
};

function save_topbar(topbar) {
        jQuery.post("<?php echo $CONFIG->wwwroot; ?>mod/vanillaforum/vanilla/extensions/Elgg/set_topbar.php",{'elgg_topbar':topbar});
        jQuery('#topbar').html(topbar);
        jQuery('ul.topbardropdownmenu').elgg_topbardropdownmenu();
    }    