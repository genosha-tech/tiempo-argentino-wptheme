(function($){
    //console.log('tiempo balancer');
    $(document).ready(function() {
        $('#personalize-city').on('keyup',function(){
           if($(this).val().length > 3){
               $.ajax({
                    type:'post',
                    url:balancer_place_ajax.url,
                    data: {
                        action: balancer_place_ajax.action,
                        b_search: $(this).val()
                    },
                    success: function(res){
                        $('#autocompletar').html(res);

                    },
                    error: function(res){
                        console.log(res)
                    }
               });
           }
        });
    });

    $(document).on('click','.suggest',function(){
        $('#personalize-city').val($(this).text());
        $('#suggest-ul').remove();
    });

    $(document).ready(function(){
        $(".topic").on('change', function() {
            var temaID = $(this).attr('id')
            var temaIDSelector = $('#'+temaID);
            if ($(this).is(":checked")) {
                $(temaIDSelector).parent().parent().addClass('theme-selected')
            } else {
                $(temaIDSelector).parent().parent().removeClass('theme-selected')
            }
        });

        
        $(".post-item").on('change', function() {
            var temaID = $(this).attr('name')
            //var temaIDSelector = $('#' + temaID);
            if ($(this).is(":checked")) {
                $(this).parent().parent().addClass('art-selected')
            } else {
                $(this).parent().parent().removeClass('art-selected')
            }
        });

        $(".photo").on('change', function() {
            // var temaID = $(this).attr('name')
            // var temaIDSelector = $('#' + temaID);
    
            if ($(this).is(":checked")) {
                $(this).parent().parent().parent().addClass('photo-selected')
            } else {
                $(this).parent().parent().parent().removeClass('photo-selected')
            }
        })
    })
})(jQuery);