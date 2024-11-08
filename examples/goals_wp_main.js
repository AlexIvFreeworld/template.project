// custom scripts
window.addEventListener('load', function () {
    document.addEventListener('submit', event => {
        if (event.target.matches('#wpcf7-f59-o1 form.wpcf7-form')) {
            setTimeout(function () {
                let curForm = document.querySelector("#wpcf7-f59-o1 form.wpcf7-form");
                let statusForm = curForm.getAttribute("data-status");
                if (statusForm == "sent") {
                    ym(98438229, 'reachGoal', 'BookCall');
                }
            }, 2400);
        }
        if (event.target.matches('#wpcf7-f60-o2 form.wpcf7-form')) {
            setTimeout(function () {
                let curForm = document.querySelector("#wpcf7-f60-o2 form.wpcf7-form");
                let statusForm = curForm.getAttribute("data-status");
                if (statusForm == "sent") {
                    ym(98438229, 'reachGoal', 'CostCalculation');
                }
            }, 2400);
        }
        if (event.target.matches('#wpcf7-f61-o3 form.wpcf7-form')) {
            setTimeout(function () {
                let curForm = document.querySelector("#wpcf7-f61-o3 form.wpcf7-form");
                let statusForm = curForm.getAttribute("data-status");
                if (statusForm == "sent") {
                    ym(98438229, 'reachGoal', 'RentalRequest');
                }
            }, 2400);
        }
    }, false);

});
