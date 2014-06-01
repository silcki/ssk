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

        var client_country = $('select.client_country').val();
        var client_scope = $('select.client_scope').val();
        var client_product_type = $('select.client_product_type').val();
        var client_sort = $('select.client_sort').val();

        switch (client_sort) {
            case '1':
                url+= 'order/name/asc/asc/';
                break
            case '2':
                url+= 'order/name/asc/desc/';
                break
        }

        if (client_country > 0) {
            enable_link = true;
            url+= 'country/' + client_country + '/';
        }

        if (client_scope > 0) {
            enable_link = true;
            url+= 'scope/' + client_scope + '/';
        }

        if (client_product_type > 0) {
            enable_link = true;
            url+= 'product_type/' + client_product_type + '/';
        }

        $('.client_filter form').attr({'action': url});
    }

    $('select.client_country').change(function(){
        processing();
    });

    $('select.client_scope').change(function(){
        processing();
    });

    $('select.client_product_type').change(function(){
        processing();
    });

    $(".client_filter .btn_send").click(function(event){
        event.preventDefault();
        processing();
        $(this).parents('form').submit();
    });
});