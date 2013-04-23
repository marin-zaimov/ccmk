$(function(){
  getPayments();
});

function getPayments(){
  //$('#wuddup').click('on', function(){

  var payload = {senderId: 1};
  $.ajax({
    url: 'bySender',
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
        var payBtnTd = '<td><button id="pay-' + p.id + '" style="background-color:#00FF00;">Pay now</button></td>';
        var row = '<tr>'
        
        row += '<tr>';// id="pay-' + p.id + '">';
        row += groupTd + receiptTd + receiverTd + totalTd + amountDueTd + payBtnTd;
        row += '</tr>';
        $('#unpaid').append(row);
        $('#pay-'+p.id).on('click', function(e){
          console.log(e);
          alert(e.target.id);
        });
      }
      /*$.each(function(index, element){
      });
      console.log(p);*/
    },
    error: function(){
      console.log(' uh oh ');
    }

  });

  //});
}
