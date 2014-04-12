function redesign(select, addclass) {
  addclass = typeof(addclass) != 'undefined' ? addclass : '';

  $(select).wrap('<div class=\"sel_wrap ' + addclass + '\"/>');

  var sel_options = '';

  var selected_option = false;

  $(select).children('option').each(function() {
    if($(this).is(':selected')){
      selected_option = $(this).index();
    }
    
    img = $(this).attr('alt');
    sel_options = sel_options + '<div id= \"sel_option_'+$(this).val()+'\" class=\"sel_option\" value=\"' + $(this).val() + '\"><img src="'+img+'" height="50"/><span>' + $(this).html() + '</span></div>';
  });



  var sel_imul = '<div class=\"sel_imul\">\
  <div class=\"sel_selected\">\
  <div id= \"sel_option_selected_'+$(select).children('option').eq(selected_option).val()+'\" class=\"selected-text\"><span>' + $(select).children('option').eq(selected_option).html() + '</span></div>\
  <div class=\"sel_arraw\"></div>\
  </div>\
  <div class=\"sel_options\">' + sel_options + '</div>\
  </div>';

  $(select).before(sel_imul);
}
$(document).ready(function(){
  $('.sel_imul').live('click', function() {

    $('.sel_imul').removeClass('act');
    $(this).addClass('act');

    if ($(this).children('.sel_options').is(':visible')) {
      $('.sel_options').hide();
    }
    else {
      $('.sel_options').hide();
      $(this).children('.sel_options').show();
    }

  });


  $('.sel_option').live('click', function() {

    var tektext = $(this).html();
    $(this).parent('.sel_options').parent('.sel_imul').children('.sel_selected').children('.selected-text').html(tektext);
    $(this).parent('.sel_options').parent('.sel_imul').children('.sel_selected').children('.selected-text').attr('id', 'sel_option_selected_'+$(this).attr('value'));

    $(this).parent('.sel_options').children('.sel_option').removeClass('sel_ed');
    $(this).addClass('sel_ed');

    var tekval = $(this).attr('value');
    tekval = typeof(tekval) != 'undefined' ? tekval : tektext;
    $(this).parent('.sel_options').parent('.sel_imul').parent('.sel_wrap').children('select').children('option').removeAttr('selected').each(function() {
    if ($(this).val() == tekval) {

    $(this).attr('selected', 'select');

    }
    });
    document.currencies.submit();
  });


  var selenter = false;

  $('.sel_imul').live('mouseenter', function() {
    selenter = true;
  });

  $('.sel_imul').live('mouseleave', function() {
    selenter = false;
  });

  $(document).click(function() {
    if (!selenter) {
      $('.sel_options').hide();
      $('.sel_imul').removeClass('act');
    }
  });
});