$(() => {
    function addListenerOnPrivateInput(unprivateButtonId, privateInput) {
        unprivateButtonId.click(e => {
            e.preventDefault()
            privateInput.attr('type', (privateInput.attr('type') === 'password' ? 'text' : 'password'))
        })
    }

    addListenerOnPrivateInput($('.password-visible'), $('#form_password'))
    addListenerOnPrivateInput($('.password-visible2'), $('#create_password'))
    addListenerOnPrivateInput($('.password-visible3'), $('#confirm_password'))
    addListenerOnPrivateInput($('.password-visible4'), $('#password-account'))
    addListenerOnPrivateInput($('.password-visible5'), $('#password-profile'))

});

