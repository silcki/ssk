$(document).ready(function(){
  
  $("input[name=update]").click(function () {
    $(this).attr({
      disabled: "disabled",
      value: "Идет перестройка урлов ..."
    });
    _button =  $(this);

    $.get("/admin/sef-url-update.php", function(data){
      alert('Все урлы обновлены');
      _button.attr({
        disabled: "",
        value: "Перестроить урлы"
      });
    });
  });



});