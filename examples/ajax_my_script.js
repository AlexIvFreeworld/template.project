function callback(result) {
    // console.log("is callback");
    // console.log(result);
    document.querySelector(".bottom-size").innerHTML = result;
};
window.addEventListener("load", function () {
    // console.log("ready");
    document.querySelector(".ctl-menu.letters").addEventListener("click", function (event) {
        //console.log('Произошло событие', event.type);
        document.querySelectorAll(".ctl-menu").forEach(function (el) {
            if (el.classList.contains("is-active") && !el.classList.contains("letters")) {
                el.classList.remove("is-active");
            }
        })
        let data = {
            mode: "ajax",
            view: "letters"
        };
        // let url = "/ajax/catalog.php";
        let url = window.location.href;
        helpR52.getHtmlAjaxPost(url, data, callback);
    });
    document.querySelector(".ctl-menu.industries").addEventListener("click", function (event) {
        document.querySelectorAll(".ctl-menu").forEach(function (el) {
            if (el.classList.contains("is-active") && !el.classList.contains("industries")) {
                el.classList.remove("is-active");
            }
        })
        let data = {
            mode: "ajax",
            view: "industries"
        };
        let url = window.location.href;
        helpR52.getHtmlAjaxPost(url, data, callback);
    });
    document.querySelector(".ctl-menu.materials").addEventListener("click", function (event) {
        document.querySelectorAll(".ctl-menu").forEach(function (el) {
            if (el.classList.contains("is-active") && !el.classList.contains("materials")) {
                el.classList.remove("is-active");
            }
        })
        let data = {
            mode: "ajax",
            view: "materials"
        };
        let url = window.location.href;
        helpR52.getHtmlAjaxPost(url, data, callback);
    });
    document.querySelectorAll(".ctl-menu.industries a").forEach(function (el) {
        el.addEventListener("click", function (event) {
            event.stopPropagation();
            let industry = this.getAttribute("data-code");
            // console.log(industry);
            let data = {
                mode: "ajax",
                view: "industries",
                industry: industry
            };
            let url = window.location.href;
            helpR52.getHtmlAjaxPost(url, data, callback);
            document.querySelector(".anchor-block").scrollIntoView();
        })
    })
    document.querySelectorAll(".ctl-menu.materials a").forEach(function (el) {
        el.addEventListener("click", function (event) {
            event.stopPropagation();
            let material = this.getAttribute("data-code");
            // console.log(material);
            let data = {
                mode: "ajax",
                view: "materials",
                material: material
            };
            let url = window.location.href;
            helpR52.getHtmlAjaxPost(url, data, callback);
            document.querySelector(".anchor-block").scrollIntoView();
        })
    })
});
