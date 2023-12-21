window.addEventListener('load', function () {
    document.querySelector(".form__inner").addEventListener("submit", function () {
        let notErr = true;
        document.querySelectorAll(".form__inner label").forEach(function (el) {
            if (el.classList.contains('field-text--error')) {
                notErr = false;
            }
        });
        if (notErr) {
            ym(94146641, 'reachGoal', 'Callback');
        }
    })
});