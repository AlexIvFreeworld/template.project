function submitForm(form) {
    let dataForm = new FormData(form);
    fetch(form.getAttribute('action'), {
        method: "POST",
        body: dataForm,
    })
        .then((response) => response.json())
        .then((result) => {
            console.log(result);
            // form.querySelector(".popup__footer").remove();
            // form.querySelector(".popup__header").innerHTML = ``;
            // form.querySelector(".container_result").innerHTML = `<p>${result.name}</p>`;
            document.querySelector(".container_result").innerHTML = `<a target="_blank" href='/products.xml'>Скачать файл</a>`;
            let footer = document.querySelector(".ialex-adm .popup__footer");
            let isFooterHide = footer.classList.contains("ialex-hide");
            if (!isFooterHide) {
                footer.classList.add("ialex-hide");
            }
        })
}
function callback(result) {
    // console.log("is callback");
    // console.log(result);
    let ajaxSections = document.querySelector(".ajax-sections-wrap");
    ajaxSections.innerHTML = result;
    let isError = ajaxSections.innerHTML.indexOf("error") != -1 ? true : false;
    let footer = document.querySelector(".ialex-adm .popup__footer");
    let isFooterHide = footer.classList.contains("ialex-hide");
    if (isFooterHide && !isError) {
        footer.classList.remove("ialex-hide");
    }
    document.querySelector(".container_result").innerHTML = ``;
};
function removeRow(el) {
    el.parentNode.parentNode.remove();
}
window.addEventListener("load", function () {
    // console.log("ready");
    document.querySelector("#request_block_id").addEventListener("change", function (event) {
        // console.log('Произошло событие', event.type);
        let footer = document.querySelector(".ialex-adm .popup__footer");
        let isFooterHide = footer.classList.contains("ialex-hide");
        if (!isFooterHide) {
            footer.classList.add("ialex-hide");
        }
        let iblockId = this.value;
        let data = {
            type: "sections",
            iblockId: iblockId
        };
        let url = "/local/modules/ialex.importxml/admin/ajax.php";
        helpR52.getHtmlAjaxPost(url, data, callback);
    });
});