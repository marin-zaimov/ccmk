$(function(){
  testButton();
});

function testButton(){
  $('#wuddup').click('on', function(){

    var payload = {senderId: 1};
    $.ajax({
      url: 'bySender',
      data: JSON.stringify(payload),
      dataType: 'json',
      type: 'POST',
      success: function(response){
        console.log(response);
      },
      error: function(){
        console.log(' uh oh ');
      }

    });

  });
}
