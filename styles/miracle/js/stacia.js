$(document).ready(function(){
	$(window).on("load",function() {
	  $(window).scroll(function() {
		var windowBottom = $(this).scrollTop() + $(this).innerHeight();
		$(".fadeinstacia").each(function() {
		  var objectBottom = $(this).offset().top + $(this).outerHeight();
		  if (objectBottom < windowBottom) { 
			if ($(this).css("opacity")==0) {$(this).fadeTo(1000,1);}
		  } else { 
			if ($(this).css("opacity")==1) {$(this).fadeTo(1000,0);}
		  }
		});
	  }).scroll();
	});

	var animated = false;
    var animating = false;
    $(window).scroll(scroll);

    function scroll(){
        if(!animating) {
            if ($(document).scrollTop() > 100) {
                    if(!animated){
                    animating = true;
                        $('#scrolltop').fadeIn();
                        $('#leftnote').animate({
                                left: 0
                        }, {"duration":500,"complete":complete});
                        animated = true;
                    }
            } else if(animated){
                animating = true;
                    $('#scrolltop').fadeOut();
                    $('#leftnote').animate({
                        left: -115
                    }, {"duration":500,"complete":complete} );
                    animated = false;
            }
        }
    }

    function complete(){
        animating = false;
        scroll();
    }
});

/*Interactivity to determine when an animated element in in view. In view elements trigger our animation*/
$(document).ready(function() {

  //window and animation items
  var animation_elements = $.find('.animation-element');
  var web_window = $(window);

  //check to see if any animation containers are currently in view
  function check_if_in_view() {
    //get current window information
    var window_height = web_window.height();
    var window_top_position = web_window.scrollTop();
    var window_bottom_position = (window_top_position + window_height);

    //iterate through elements to see if its in view
    $.each(animation_elements, function() {

      //get the element sinformation
      var element = $(this);
      var element_height = $(element).outerHeight();
      var element_top_position = $(element).offset().top;
      var element_bottom_position = (element_top_position + element_height);

      //check to see if this current container is visible (its viewable if it exists between the viewable space of the viewport)
      if ((element_bottom_position >= window_top_position) && (element_top_position <= window_bottom_position)) {
        element.addClass('in-view');
      } else {
        element.removeClass('in-view');
      }
    });

  }

  //on or scroll, detect elements in view
  $(window).on('scroll resize', function() {
      check_if_in_view()
    })
    //trigger our scroll event on initial load
  $(window).trigger('scroll');

});