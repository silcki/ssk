$(document).ready(function() {

    Array.prototype.remove = function() {
        var what, a = arguments, L = a.length, ax;
        while (L && this.length) {
            what = a[--L];
            while ((ax = this.indexOf(what)) !== -1) {
                this.splice(ax, 1);
            }
        }
        return this;
    };

    function processing()
    {
        var url = $('.client_filter form').data('fileName');
        var enable_link = false;
        if (client_country.length > 0) {
            enable_link = true;
            url+= 'country/' + client_country.join() + '/';
        }

        if (client_scope.length > 0) {
            enable_link = true;
            url+= 'scope/' + client_scope.join() + '/';
        }

        if (client_product_type.length > 0) {
            enable_link = true;
            url+= 'product_type/' + client_product_type.join() + '/';
        }

        if (enable_link) {
            $('#client_filter_send').removeClass('disable');
        } else {
            $('#client_filter_send').addClass('disable');
        }

        $('.client_filter form').attr({'action': url});
    }

    var client_country = new Array();
    var client_scope = new Array();
    var client_product_type = new Array();

    $("ul.client_country input").click(function(event){
        if ($(this).is(':checked')) {
            client_country.push($(this).val());
        } else {
            client_country.remove($(this).val());
        }

        processing();
    });

    $("ul.client_scope input").click(function(event){
        if ($(this).is(':checked')) {
            client_scope.push($(this).val());
        } else {
            client_scope.remove($(this).val());
        }

        processing();
    });

    $("ul.client_product_type input").click(function(event){
        if ($(this).is(':checked')) {
            client_product_type.push($(this).val());
        } else {
            client_product_type.remove($(this).val());
        }

        processing();
    });

    $(".client_filter .btn_send").click(function(event){
        event.preventDefault();
    });
});