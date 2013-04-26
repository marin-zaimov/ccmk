$(function(){
  getPayments();
});

function getPayments(){

  var payload = {senderId: 1};

  $.ajax({
    url: 'getUnpaid',
    data: JSON.stringify(payload),
    dataType: 'json',
    type: 'POST',
    success: function(response){
      var payments = response.data.payments;
      var i = 0;
      for(i = 0; i < payments.length; ++i){
        
         


        var row = "";
        var p = payments[i]; 
        var groupTd = '<td>'+p.group.name+'</td>';
        var receiptTd = '<td>'+p.receipt.name+'</td>';
        var receiverTd = '<td>'+p.receiver.firstName+' '+p.receiver.lastName+'</td>';
        var totalTd = '<td>'+p.receipt.amountDue+'</td>';
        var amountDueTd = '<td>'+p.amountDue+'</td>';
        var payBtnTd = '<td id="btn-col"><button id="'+ p.senderId +'-'+ p.receipt.id +'" style="background-color:#00FF00;">Pay Now</button></td>';
        
        row += '<tr>';// id="pay-' + p.id + '">';
        row += groupTd + receiptTd + receiverTd + totalTd + amountDueTd + payBtnTd;
        row += '</tr>';
        $('#unpaid').append(row);
        
        // on pay button click
        $('#'+ p.senderId +'-'+ p.receipt.id).on('click', function(e){

          /*$('#'+e.target.id).css('background-color', 'red');
          $('#'+e.target.id).attr('disabled', true);
          $('#'+e.target.id).text('Pending Confirmation');*/



          var ids = e.target.id.split("-");
          var data = {
            userId: ids[0],
            receiptId: ids[1]
          };

          $.ajax({
            url: 'pay',
            data: JSON.stringify(data),
            dataType: 'json',
            type: 'POST',
            success: function(response){
              $.sticky('Successfully charged your PayPal account.', {'position': 'top-center', 'type': 'st-success'});
              $('#'+e.target.id).closest('tr').remove();
            },
            error: function(){
              $.sticky("Error on PayPal submit.", {'position': 'top-center', 'type': 'st-error'});
            }
          });
        });
      }

    },
    error: function(){
      $.sticky("Error while fetching payments.", {'position': 'top-center', 'type': 'st-error'});
    }

  });







}

