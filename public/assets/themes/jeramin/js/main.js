/* main.js Reed Admin
 *
 * main javascript for Reed Admin
 * This file should be included in all pages
 * author : f733d0m
 */

//make sure jquery has been loaded before main.js
if(typeof jQuery === "undefined"){
  throw new Error ("Require jQuery");
}

//slim sidebar
(function ($){
  "use strict";

  var $body = $("body");
  var $eventClass = "slim-sidebar";
  var $triggerClass = $(".sidebar-toggle-lg");

  $(".sidebar-toggle-lg").click(function(){
    if($body.hasClass($eventClass)){
      $body.removeClass($eventClass);
    }else{
      $body.addClass($eventClass);
    }
  });

})(jQuery);

//toggle mobile sidebar
(function ($){
  "use strict";

  var $toggleClass = $(".sidebar-toggle-sm");
  var $eventClass = $(".sidebar");
  var $triggerClass = "active";

  $toggleClass.click(function(){
    if($eventClass.hasClass($triggerClass)){
      $eventClass.removeClass($triggerClass);
      $(this).removeClass($triggerClass);
    }else{
      $eventClass.addClass($triggerClass);
      $(this).addClass($triggerClass);
    }
  });
})(jQuery);

//collapse sidebar Menu
(function ($){
  "use strict";

  var $dropdownOth = $(".sb-dropdown");
  var $dropdownEvent = $(".sb-dropdown > a");
  var $dropdownChild = $dropdownEvent.next();
  var $triggerClass  = "active";

  $dropdownEvent.click(function(e){
    if($(this).parent().hasClass($triggerClass)){
      $(this).parent().removeClass($triggerClass);
    }else{
      $dropdownOth.removeClass($triggerClass);
      $(this).parent().addClass($triggerClass);
    }

    e.preventDefault();
  });
})(jQuery);

//toggle search header
(function ($){
  "use strict";

  var $toggleClass = $(".toggle-search-sm");
  var $eventClass = $(".search-header");
  var $formEvent = $(".search-header > form");
  var $triggerClass = "active";

  $toggleClass.click(function(e){
    if($eventClass.hasClass($triggerClass)){
      $eventClass.removeClass($triggerClass);
    }else{
      $eventClass.addClass($triggerClass);
    }

    $formEvent.click(function(e){
      e.stopPropagation();
    })

    e.stopPropagation();
  });

  $(document).click(function(){
    $eventClass.removeClass($triggerClass);
  });
})(jQuery);
