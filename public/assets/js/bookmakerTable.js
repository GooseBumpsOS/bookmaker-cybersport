$( ".nav-item > .nav-link" ).click(function() {

    var game = $(this).attr('id');

    $('tbody').html('');

$.ajax({
   type: "POST",
   dataType: "json",
   url: "api",
   data: {"game" : game},
   success: function(data){

     for(var i=0;i<data.length;i++){


       $('tbody').append('<tr>\n' +
           '                            <td> '+ data[i].name + '</td>\n' +
           '                            <td>\n' +
           '\n' +
           '                                <button type="button" class="btn btn-danger center-block"><a href=bookmaker/" ' + data[i].link + '">Отзывы</a></button>\n' +
           '\n' +
           '\n' +
           '                            </td>\n' +
           '                            <td> ' +  data[i].rating  + ' </td>\n' +
           '\n' +
           '                        </tr>');



     }
   }



 });



});
