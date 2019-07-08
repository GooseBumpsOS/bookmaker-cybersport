
    var star = [];

    $('tbody').html('');

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "api",
        data: {"game" : "all"},
        success: function(data){

            for(var i=0;i<data.length;i++){

                for (var c = 0;c < Math.round(data[i].rating); c++)
                    star.push('<i class="fa fa-star" style="color: #FFD700; margin-right: 5px;" aria-hidden="true"></i> ');
                star.push(Math.round( + data[i].rating) + '/5');

                $('tbody').append('<tr>\n' +
                    '                            <td> <a href="'+  data[i].link_to_site +'">'+ '<img class="imgBookmaker" src="'+ data[i].logo +'">' + '</a><td>\n' +
                    '\n' +
                    '                                <button type="button" class="btn btn-danger center-block"><a href="bookmaker/' + data[i].link + '">Обзор</a></button>\n' +
                    '\n' +
                    '\n' +
                    '                            </td>\n' +
                    '                            <td> ' +  star.join('')  + ' </td>\n' +
                    '\n' +
                    '                        </tr>');
                star.length = 0; //обнуление массива
            }
        }

    });


$( ".nav-item > .nav-link" ).click(function() { //при нажатии на другую игру

    var star = [];
    var game = $(this).attr('id');

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "api",
        data: {"game" : game},
        success: function(data){
            $('tbody').html('');

            for(var i=0;i<data.length;i++){

                for (var c = 0;c < Math.round(data[i].rating); c++)
                    star.push('<i class="fa fa-star" style="color: #FFD700; margin-right: 5px;" aria-hidden="true"></i> ');
                star.push(Math.round( + data[i].rating) + '/5');

                $('tbody').append('<tr>\n' +
                    '                            <td> <a href="'+  data[i].link_to_site +'">'+ '<img class="imgBookmaker" src="'+ data[i].logo +'">' + '</a><td>\n' +
                    '\n' +
                    '                                <button type="button" class="btn btn-danger center-block"><a href="bookmaker/' + data[i].link + '">Обзор</a></button>\n' +
                    '\n' +
                    '\n' +
                    '                            </td>\n' +
                    '                            <td> ' +  star.join('')  + ' </td>\n' +
                    '\n' +
                    '                        </tr>');
                star.length = 0; //обнуление массива
            }
        }
,
        beforeSend: function (data) { //прелоадер

            $('tbody').html('');

            $('.table-self').html('<tr>\n' +
                '                            <td></td>\n' +
                '                            <td>\n' +
                '\n' +
                '                                <img src="assets/images/tablePreloader.gif">\n' +
                '\n' +
                '\n' +
                '                            </td>\n' +
                '                            <td>\n' +
                '\n' +
                '\n' +
                '\n' +
                '\n' +
                '                            </td>\n' +
                '\n' +
                '                        </tr>\n' +
                '                        ');



        },




    });



});
