function openNav() {
    document.getElementById("mySidebar").style.width = "250px";
}

function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
}

$(document).ready(function () {
    
    $('.sidebar a').on('click', function (e) {
        e.preventDefault();

        const url = $(this).data('url');

        $.ajax({

            url: url,
            method: 'GET',
            success: function (response) {

                $('#mapCanvas').html(response);
            },

            error: function () {
                alert('Failed to load content.');

            }
        });
    });
});
