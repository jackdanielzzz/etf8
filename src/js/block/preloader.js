$(() => {
    let typing = new Typed("#typed", {
        strings: ["ETFRIX"],
        typeSpeed: 160,
        backSpeed: 60,
        backDelay: 1000,
        loop: false,
        smartBackspace: false,
        cursorChar: '_',
        attr: null
    });

});

function loadData() {
    return new Promise((resolve, reject) => {
        // setTimeout не является частью решения
        // Код ниже должен быть заменен на логику подходящую для решения вашей задачи
        setTimeout(resolve, 3000);
    })
}

loadData()
    .then(() => {
        let preloaderEl = document.getElementById('preloader');
        preloaderEl.classList.add('hidden_preloader');
        preloaderEl.classList.remove('visible_preloader');
});