$(document).on('submit', '#form-login', function(e) {
    e.preventDefault();

    helper.post({ url: baseUrl + 'sesion/login', data: $(this)[0], formdata: true })
        .then(response => {
            console.log(response);
        })
        .catch(err => {
            console.log(err);
        })

})