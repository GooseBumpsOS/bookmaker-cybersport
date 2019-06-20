$( document ).ready(function() {

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "result/api",
        success: function(data){
            var games_img = ['https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRnxJ1uby9M-2eY00l1pXTinOe-HSH7OnG-hiQSOjGK6VXBOXPj', 'https://image.flaticon.com/icons/svg/588/588308.svg', 'https://c1.staticflickr.com/9/8398/29628694882_8eed12375e.jpg', 'https://wiki.gamedetectives.net/images/b/bf/Overwatch_logo.jpg'];
            var games = ['League of Legends\n', 'dota 2', 'CS:GO', 'overwatch'];

           for (var i = 0; i < data.length; i++) {
               $('.mt-mb-15').append('<div style="margin: 20px 25%;" class="section-header text-center">            <h2 class="section-title">Результаты '+ games[i] +'</h2>            <p>ЗДесь вы можете найти результаты по '+ games[i] +'.</p>        </div>');
               for (var c = 0; c < data[i]['name'].length; c++) {

                    $('.mt-mb-15').append('  <div class="col-lg-6">\n' +
                       '                <div class="result-item d-flex text-center align-items-center justify-content-center">\n' +
                       '                    <div class="team team--left">\n' +
                       '                        <span class="name">' + data[i]['name'][c][0] + ' </span>\n' +
                       '                        <span class="flag"><i><img style="height: 30px;" src="' + games_img[i] + '"></i></span>\n' +
                       '                    </div>\n' +
                       '                    <div class="result-details">\n' +
                       '                        <p>\n' +
                       '                            <span class="left-team-result">' + data[i]['coeff'][c][0] + '</span>\n' +
                       '                            <span class="right-team-result"></span>\n' +
                       '                        </p>\n' +
                       '                        <span class="date">' + data[i]['time'][c] + '</span>\n' +
                       '                    </div>\n' +
                       '                    <div class="team team--right">\n' +
                       '                        <span class="name">' + data[i]['name'][c][1] + '</span>\n' +
                       '                        <span class="flag"><i><img style="height: 30px;" src="' + games_img[i] + '"></i></span>\n' +
                       '                    </div>\n' +
                       '                </div>\n' +
                       '            </div><!-- result-item end -->');


               }

           }



        },
        complete: function() {

        $('#resultPreloader').hide();

    }


    });


});

//['lol', 'dota 2', 'cs:go', 'overwatch'];