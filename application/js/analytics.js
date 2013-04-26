
$(function(){
  getAverages();
  makeGraphs();
});

function getAverages(){
  $.ajax({
    url: 'getAverages',
    dataType: 'json',
    type: 'POST',
    success: function(response){
      console.log(response);

      $('#avg-receipt-amount').text(response.avgReceiptAmount);
      $('#avg-bill-amount').text(response.avgBillAmount);
      console.log(response.avgUsersPerGroup);
      $('#avg-users-per-group').text(response.avgUsersPerGroup);

    },
    error: function(response){
      $.sticky("Error while fetching averages.", {'position': 'top-center', 'type': 'st-error'});
    }

  });
}
function makeGraphs(){


  $.ajax({
    url: 'getNewUsersByMonth',
    dataType: 'json',
    type: 'POST',
    success: function(response){

      var years = response.usersByMonth;
      var yArr = [];
      var xArr = [];
      for(var year in years){
        var months = years[year];
        var xArrOneYear = [];
        for(var month in months){
          xArrOneYear.push(month + "/" + year);
        }
        xArrOneYear.sort();
        var yArrOneYear = [];
        for(var i = 0; i < xArrOneYear.length; ++i){
          var moYr = xArrOneYear[i].split("/");
          var mo = moYr[0];
          var yr = moYr[1];
          yArrOneYear.push(response.usersByMonth[yr][mo]);
        }
        xArr.push.apply(xArr, xArrOneYear);
        yArr.push.apply(yArr, yArrOneYear);
      }

      $('#users-over-time-chart').highcharts({
        chart: {
          type: 'line',
          zoomType: 'x',
          borderWidth: 1,
          spacingTop: 0,
          spacingRight: 0,
          spacingBottom: 0,
          spacingLeft: 0
        },

        title: {
          text: 'New Users Over Time',
          x: -20 //center
        },
        xAxis: {
          categories: xArr,
          title: {
            text: 'Month/Year'
          },
        },
        yAxis: {
          title: {
            text: 'Number of Users'
          },
          plotLines: [{
            value: 0,
            width: 1,
            color: '#808080'
          }]
        },
        legend: {
          layout: 'vertical',
          align: 'right',
          verticalAlign: 'top',
          x: -10,
          y: 100,
          borderWidth: 0
        },
        series: [{
          name: 'Users',
          data: yArr
        }]
      });



    },
    error: function(){
      $.sticky("Error while fetching graph data.", {'position': 'top-center', 'type': 'st-error'});
    }
  });


}

