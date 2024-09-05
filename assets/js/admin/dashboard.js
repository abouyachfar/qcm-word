jQuery(document).ready(function(){
    jQuery.ajax({
        url: "/wp-json/qcm-word/api/v1/qcm-word-report-stats-by-country",
        type: "GET",
        cache: false,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (result) {
            var data = JSON.parse(result);
            const ctx = jQuery('#report-country-chart');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    datasets: [{
                    label: translation.nbr_de_rapport,
                    data: data,
                    borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                    y: {
                        beginAtZero: true
                    }
                    }
                }
            });
  
            return false;
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
          console.log("Error!");
        },
    });

    jQuery.ajax({
        url: "/wp-json/qcm-word/api/v1/qcm-word-report-stats-by-term",
        type: "GET",
        cache: false,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (result) {
            var data = JSON.parse(result);
            const ctx = jQuery('#report-term-chart');
            
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.labels,
                    datasets: [{
                    label: translation.nbr_de_rapport,
                    data: data.values,
                    borderWidth: 1
                    }]
                },
                options: {
                    //cutoutPercentage: 40,
                    responsive: false,
                }
            });
  
            return false;
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
          console.log("Error!");
        },
    });
});