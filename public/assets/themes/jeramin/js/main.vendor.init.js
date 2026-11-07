/* vendor.init.js Reed Admin
 * this js file for initialize plugin
 */

// jquery Slim Scroll
(function($){
  "use strict";

  if($.fn.slimScroll){

    //sidebar scroll
    $(".sidebar-inner").slimScroll({
      height : "100%",
      position : "left"
    });

    //notif header scroll
    $(".scroll-item").slimScroll({
      height : "300px"
    });
  }
})(jQuery);
