$(window).on("load", function() {

    callApi('active');

});

$(".time_machine > button").click(function () {

    callApi($(this).data('time'));

});

var callApi =  function (time){
    $.ajax({
    type: "POST",
    dataType: "json",
    url: "match_result/api",
    data: {"status": time},
    success: function (data) {

        $('#jqueryAdd').html('');

        $('.result-section').css('padding-top', '0px');

        $('#jqueryAdd').append('<div style="margin: 20px 25%;" class="section-header text-center">            <h2 class="section-title">Результаты матчей </h2>            <p>Здесь вы можете найти результаты игр</p>        </div>');

        for (var i = 0; i < data.length; i++ )
            $('#jqueryAdd').append('   <div class="col-lg-6">\n' +
                '                <div class="result-item d-flex text-center align-items-center justify-content-center">\n' +
                '                    <div class="team team--left">\n' +
                '                        <span class="name">'+data[i]['command_1']+'</span>\n' +
                '                        <span class="flag"><img style="height: 30px;" src="'+data[i]['logo']+'" alt="image"></span>\n' +
                '                    </div>\n' +
                '                    <div class="result-details">\n' +
                '                        <p>\n' +
                '                            <span class="left-team-result">'+data[i]['score']+'</span>\n' +
                '                        </p>\n' +
                '                        <span class="date">'+ data[i]['time']+'</span>\n' +
                '                    </div>\n' +
                '                    <div class="team team--right">\n' +
                '                        <span class="name">'+data[i]['command_2']+'</span>\n' +
                '                        <span class="flag"><img style="height: 30px;" src="'+data[i]['logo']+'" alt="image"></span>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '            </div>');

    },

    beforeSend: function (data) {

        $('#jqueryAdd').html('');

        $('#jqueryAdd').html('<img id="resultPreloader" src="/bookmaker-cybersport/public/assets/images/tablePreloader.gif">');


    }


});
                    }
