

$( document ).ready(function() {
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    $(".phone").inputmask({
		"mask": "(999) 999-9999"
	});
});

$(function () {
    $('.add').on('click',function(){
        var $qty=$(this).closest('div').find("input[name='qty']");

        var buying_price=$(this).closest('div').find("input[name='buying_price']").val();
      
        var stock=$(this).closest('div').find("input[name='stock']").val();
        var item_id=$(this).closest('div').find("input[name='item_id']").val();
        var currentVal = parseInt($qty.val());
        
       
        if(stock >currentVal){
            if (!isNaN(currentVal)) {
                $qty.val(currentVal + 1);
            }
            price=parseInt($('#price').val())+parseInt(buying_price);
            $('#total_price').html(price)
            $('#price').val(price)



            $.ajax({
                url: "/pos/manage_qty",
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    "item_id":item_id,
                    "current_qty": currentVal + 1
                },
                success: function(data) {
                //   alert(data)
                }
              })


        }else{

            alert('out of stock');
        }
        

    });
    $('.minus').on('click',function(){
        var $qty=$(this).closest('div').find("input[name='qty']");
        var buying_price=$(this).closest('div').find("input[name='buying_price']").val();
      
        var stock=$(this).closest('div').find("input[name='stock']").val();
        var currentVal = parseInt($qty.val());
        if (!isNaN(currentVal) && currentVal > 1) {
            $qty.val(currentVal - 1);

            price=parseInt($('#price').val())-parseInt(buying_price);
            $('#total_price').html(price)
            $('#price').val(price)
        }

        
    });
});