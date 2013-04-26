$(function(){
  getReceipts();
});

function getReceipts(){
  $.ajax({
    url: 'getAllUsersReceipts',
    type: 'GET',
    success: function(response){
      var receipts = $.parseJSON(response).data.receipts;
      for(var i = 0; i < receipts.length; ++i){


        var receipt = receipts[i];
        var name = receipt.name;
        var total = receipt.amountDue;
        var group = receipt.groupName;
        var paid = receipt.paid;
        
        var row = "";
        var nameTd = '<td>'+name+'</td>';
        var totalTd = '<td>'+total+'</td>';
        var groupTd = '<td>'+group+'</td>';
        var paidTd = '<td>'+paid+'</td>';
        
        row += '<tr>';
        row += nameTd + totalTd + groupTd + paidTd;
        row += '</tr>';
        $('#receipts').append(row);

      }
    },
    error: function(response){
      $.sticky("Error while fetching groups.", {'position': 'top-center', 'type': 'st-error'});
    }

  });
}
