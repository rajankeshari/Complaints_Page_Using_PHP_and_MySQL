$(document).ready(function() {
    $.ajax({
        url: site_url('edc_booking/management/load_building_status/old/' + $('#auth').text()),
        error : function(e) {
            alert(e.responseText);
        },
        success: function(result) {
            $('#old_tab').html(result);
        }
    });

    //get the value of child of li with active class
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var activeTab = e.target;
        var activeTabString= $(activeTab).attr('href');
        var active_building = activeTabString.substring(1, activeTabString.length - 4);

        $.ajax({
            url: site_url('edc_booking/management/load_building_status/' + active_building + '/' + $('#auth').text()),
            error : function(e) {
                alert(e.responseText);
            },
            success: function(result) {
                $(activeTabString).html(result);
            }
        });
    });
});
