{*
* @author : Sathi
* @copyright : Sathi 2020
* @license : 2020
*}

<script>
var text_reference = "{l s='Reference:' mod='stadditionalproductlisting'}";
$(function(){

    /* Show/hide hover pause hidden field*/
    $('[for="auto_on"]').on('click', function(){
        $('.st-auto-crousel').removeClass('hide');
    });
    $('[for="auto_off"]').on('click', function(){
        $('.st-auto-crousel').addClass('hide');
    });

    function showHidePauseField() {
        if ($('[name="auto"]:checked').val() == 1) {
            $('.st-auto-crousel').removeClass('hide');
        } else {
            $('.st-auto-crousel').addClass('hide');
        }
    }

    /* Change tab*/
    $('[data-toggle="tab"]').on('click', function(){
        $('#current_tab').val($(this).attr('href'));
    });
    $('#st_additionalproductlisting_form a[href="'+$('#current_tab').val()+'"]').trigger('click');

    /* Show/hide hidden fields*/
    $('[for="is_carousel_on"]').on('click', function(){
        $('.st-crousel:not(.st-auto-crousel)').removeClass('hide');
        showHidePauseField();
    });
    $('[for="is_carousel_off"]').on('click', function(){
        $('.st-crousel').addClass('hide');
    });
    if ($('[name="is_carousel"]:checked').val() == 1) {
        $('.st-crousel:not(.st-auto-crousel)').removeClass('hide');
        showHidePauseField();
    } else {
        $('.st-crousel').addClass('hide');
    }

    var type = $('#filter_type option:selected').val();
    $('[class*="st-filter-'+type+'"]').removeClass('hide');
    if(type > 6){
        $('#hook').val('displayFooterProduct');
        $('.st-hook').hide();
    } else {
        $('.st-hook').show();
    }
    /* Change filter data*/
    $('#filter_type').on('change', function(){
        var type = $(this).val();
        $('[class*="st-filter-"]').addClass('hide') ;
        $('[class*="st-filter-'+type+'"]').removeClass('hide');
        if(type > 6){
            $('#hook').val('displayFooterProduct');
            $('.st-hook').hide();
        } else {
            $('.st-hook').show();
        }
    });

    /* Search products */
    $('#product-search').on('keyup', function(){
        var search_text = $(this).val();
        if(search_text){
            $.ajax({
               url: window.location.href+'&rand='+ Math.random(),
               type: "POST",
               cache: false,
               dataType: "json",
               data: {
                   ajax: true,
                   action: 'searchProduct',
                   search_text: search_text
               },
               success: function(data)
               {
                   var li = '';
                   if (!data.has_errors && data.found) {
                       $.each(data.products, function(i, product){
                        li += '<div id="product_'+product.id_product+'" class="list-group-item col-lg-12"><div class="col-lg-2"><img src="'+product.image+'" alt="'+product.name+'" /></div><div class="col-lg-10"><h4>'+product.name+'</h4><em>'+text_reference+' '+product.reference+'</em><i class="icon-trash pull-right"></i><input type="hidden" name="filter_product[]" value="'+product.id_product+'" /></div></div>';
                       });
                       $('#ajax_list').html(li).show();
                   }
               }
           });
       }
   });

   $('#product-search').bind('click focus', function(e){
       $('#ajax_list').show();
        e.stopPropagation();
   });

   $(document).on('click', function(e){
       $('#ajax_list').hide();
   });

   $('.panel-footer').on('click', 'button', function(e){
       $('#ajax_list').html('');
   });

   $('#ajax_list').on('click', '.list-group-item', function(){
       var element = $(this).detach();
       if($('#product-list').find('#'+$(this).attr('id')).length == 0){
           $('#product-list').append(element);
       }
       $('#ajax_list').hide();
   });
   $('#product-list').on('click', 'i.icon-trash', function(){
       $(this).closest('.list-group-item').remove();
   });
    $('.check-uncheck').on('click', function(){
        var checkBoxes = $('[id^='+$(this).attr('id')+']');
        checkBoxes.prop("checked", !checkBoxes.prop("checked"));
    });
});
</script>
