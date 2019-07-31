$( document ).ready(function() {

    ajaxCall('All');

    $( ".nav-item > .nav-link" ).click(function() {

        var selectGame = $(this).attr('id');

        $('.jQuerySelectorCoef').html('');

        ajaxCall(selectGame);

    })


    });



function ajaxCall(selectGame) {

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "coef/api",
        data: {'game' : selectGame},
        success: function(data){

            for(var i = 0 ; i < data.length; i++)
                $('.jQuerySelectorCoef').append(

                    '            <div class="col-lg-6">\n' +
                    '                <div class="result-item d-flex text-center align-items-center justify-content-center">\n' +
                    '                <div class="team team--left">\n' +
                    '                <span class="name">'+data[i]['team_1']+'</span>\n' +
                    '                </div>\n' +
                    '                <div class="result-details">\n' +
                    '                <span class="flag"><img style="width: 30px" src="'+ data[i]['game'][0]+'" alt="image"></span>\n' +
                    '                <p>\n' +
                    '                <span class="left-team-result">'+data[i]['coef_1']+'</span>\n' +
                    '                <span class="right-team-result">'+data[i]['coef_2']+'</span>\n' +
                    '                </p>\n' +
                    '                <span class="date">'+data[i]['date']+'</span>\n' +
                    '            </div>\n' +
                    '            <div class="team team--right">\n' +
                    '                <span class="name">'+data[i]['team_2']+'</span>\n' +
                    '                </div>\n' +
                    '                </div>\n' +
                    '                </div><!-- result-item end -->'

                );


        },
        complete: function() {

            $('#resultPreloader').hide();

        },

        beforeSend: function () {

            $('.jQuerySelectorCoef').html('<img id="resultPreloader" src="assets/images/tablePreloader.gif">');

        },

        error: function () {

            alert('Произошла ошибка');
        }




    });

}