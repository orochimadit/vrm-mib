// example chart for dashboard

// morris area - Income and Spending Activity
(function($){
  "use strict";

  var ctx       = $("#ex-chart-db");
  var newChart  = new Chart(ctx, {
    type  : "line",
    data  : {
      labels  : ["2010", "2011", "2012", "2013", "2014", "2015", "2016", "2017"],
      borderWidth : 1,
      showLine  : true,
      datasets  : [{
        data  : [80000, 180000, 100000, 100000, 180000, 200000, 160000, 150000],
        backgroundColor : "rgba(23, 162, 184, 0.6)",
        borderColor : "#17a2b8",
        label : "Spending",
        borderWidth : 1,
        showLine  : true
      },{
        data  : [100000, 150000, 200000, 100000, 250000, 150000, 200000, 180000],
        backgroundColor : "rgba(40, 167, 69, 0.5)",
        borderColor : "#28a745",
        label : "Income",
        borderWidth : 1,
        showLine  : true
      }]
    },
    options : {
      responsive  : true,
      maintainAspectRatio : false,
      layout : {
        padding : {
          top: 20
        }
      },
      legend  : {
        display : false
      },
      scales  : {
        xAxes : [{
          display : false
        }],
        yAxes : [{
          display : false,
          ticks : {
            main  : 50000,
            stepSize : 50000
          }
        }]
      },
      tooltips  : {
        enabled : true,
        mode  : "single",
        displayColors : false,
        callbacks: {
          label : function(tooltipItems, data){
            return data.datasets[tooltipItems.datasetIndex].label+" : US$ "+tooltipItems.yLabel;
          },
        }
      }
    }
  });
  $("#lgd-ex-chart-db").html(newChart.generateLegend());
})(jQuery);

(function($){
  "use strict";

  var ctx     = $("#ex-donut-db");
  var myChart = new Chart(ctx,{
    type      : "doughnut",
    data      : {
      datasets : [{
        data            : [45, 25, 30],
        backgroundColor : ["#17a2b8", "#28a745", "#ffc107"]
      }],
      labels  : [
        "Task Complete",
        "Task Uncoplete",
        "Revision"
      ]
    },
    options: {
      tooltips  : {
        displayColors : false,
        callbacks: {
          title: function(tooltipItem, data) {
            return data['labels'][tooltipItem[0]['index']];
          },
          label: function(tooltipItem, data) {
            return data['datasets'][0]['data'][tooltipItem['index']]+"%";
          }
        }
      },
      responsive  : true,
      cutoutPercentage  : 70,
      legend      : {
        display : false
      }
    }
  });

  $("#lgd-ex-donut-db").html(myChart.generateLegend());
})(jQuery);
