console.log("custom");
document.addEventListener('submit', event => {
    if (event.target.matches('form[data-id="callback"]')) {
        //console.log('submit callback');
        // idProd = $('.to_cart').attr('data-id');
        setTimeout(function () {
            // sendAjax(idSwitch, idClock, idAudio, idHeating, idProd);
            let res = document.querySelector(".callback-popup__container.popup-container h1").textContent;
            //console.log(res);
            if (res.indexOf("отправлена") != -1) {
                //console.log("отправлена");
                ym(86928008, 'reachGoal', 'OrderCall');
            }
        }, 1400);
    }
    if (event.target.matches('form[data-id="card-order-get-contract"]')) {
        //console.log('submit card-order-get-contract');
        // idProd = $('.to_cart').attr('data-id');
        setTimeout(function () {
            // sendAjax(idSwitch, idClock, idAudio, idHeating, idProd);
            let res = document.querySelector(".order-popup__container.popup-container h1").textContent;
            //console.log(res);
            if (res.indexOf("отправлена") != -1) {
                //console.log("отправлена");
                ym(86928008, 'reachGoal', 'GetContract');
            }
        }, 2000);
    }
    if (event.target.matches('form[data-id="card-order-contract"]')) {
        //console.log('submit card-order-contract');
        // idProd = $('.to_cart').attr('data-id');
        setTimeout(function () {
            // sendAjax(idSwitch, idClock, idAudio, idHeating, idProd);
            let res = document.querySelector(".order-popup__container.popup-container h1").textContent;
            //console.log(res);
            if (res.indexOf("отправлена") != -1) {
                //console.log("отправлена");
                ym(86928008, 'reachGoal', 'OrderCard1');
            }
        }, 2000);
    }
    if (event.target.matches('form[data-id="card-order-wout-contract"]')) {
        //console.log('submit card-order-wout-contract');
        // idProd = $('.to_cart').attr('data-id');
        setTimeout(function () {
            // sendAjax(idSwitch, idClock, idAudio, idHeating, idProd);
            let res = document.querySelector(".order-popup__container.popup-container h1").textContent;
            //console.log(res);
            if (res.indexOf("отправлена") != -1) {
                //console.log("отправлена");
                ym(86928008, 'reachGoal', 'OrderCard2');
            }
        }, 2000);
    }
    if (event.target.matches('form[data-id="calculator_form"]')) {
        //console.log('submit calculator_form');
        // idProd = $('.to_cart').attr('data-id');
        setTimeout(function () {
            // sendAjax(idSwitch, idClock, idAudio, idHeating, idProd);
            let res = document.querySelector(".calc__result.bg-cream h2").textContent;
            //console.log(res);
            if (res.indexOf("выгода") != -1) {
                //console.log("Ваша выгода");
                ym(86928008, 'reachGoal', 'CalculateSavings');
            }
        }, 2000);
    }
    if (event.target.matches('form[data-id="ask-a-question"]')) {
        console.log('submit ask-a-question');
        // idProd = $('.to_cart').attr('data-id');
        ym(86928008, 'reachGoal', 'GetOffer');
    }
}, false);