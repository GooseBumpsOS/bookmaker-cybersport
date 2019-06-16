$( ".nav-item > .nav-link" ).click(function() {

    var star = [];
    var game = $(this).attr('id');

    $('tbody').html('');

$.ajax({
   type: "POST",
   dataType: "json",
   url: "api",
   data: {"game" : game},
   success: function(data){

     for(var i=0;i<data.length;i++){

         for (var c = 0;c < Math.round(data[i].rating); c++)
             star.push('<i class="fa fa-star" style="color: #FFD700; margin-right: 5px;" aria-hidden="true"></i> ');
         star.push(Math.round( + data[i].rating) + '/5');


       $('tbody').append('<tr>\n' +
           '                            <td> '+ data[i].name + '</td>\n' +
           '                            <td>\n' +
           '\n' +
           '                                <button type="button" class="btn btn-danger center-block"><a href="bookmaker/' + data[i].link + '">Отзывы</a></button>\n' +
           '\n' +
           '\n' +
           '                            </td>\n' +
           '                            <td> ' +  star.join('')  +  '</td>\n' +
           '\n' +
           '                        </tr>');

         star.length = 0; //обнуление массива

     }

   }



 });



});



