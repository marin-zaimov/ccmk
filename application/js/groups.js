$(function(){
  getGroups();
});

function getGroups(){
  $.ajax({
    url: 'getAllUsersGroups',
    //data: JSON.stringify(payload),
    //dataType: 'json',
    type: 'GET',
    success: function(response){
      var groups = $.parseJSON(response).data.groups;
      for(var i = 0; i < groups.length; ++i){

        var name = groups[i].name;

        var row = "";
        var nameTd = '<td>'+name+'</td>';
        
        row += '<tr>';
        row += nameTd;
        row += '</tr>';
        $('#groups').append(row);


      }
    },
    error: function(response){
      $.sticky("Error while fetching groups.", {'position': 'top-center', 'type': 'st-error'});
    }

  });
}
