<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("test");
CUtil::InitJSCore(array('custom_main'));
?>
<p style="text-align: center;">
    <img alt="company" src="/images/company/company-page.jpg" class="img-responsive rounded-4">
</p>
<p>
    Allcorp3 — компания, которая занимается установкой систем безопасности, автоматизации и обеспечения комфортной жизнедеятельности. С 2009 года мы занимаемся сервисным обслуживанием и модернизацией любых инженерных систем.
</p>
<h3>Чем мы можем быть вам полезны</h3>
<ul>
    <li><b>Обеспечим вашу безопасность.</b> Проанализируем объект и установим лучшую систему охраны. Предложим квалифицированных операторов видеонаблюдения. Разработаем индивидуальную систему контроля доступа.</li>
    <li><b>Автоматизируем процессы.</b> Усовершенствуем ваши бизнес-процессы с помощью CRM-системы и IP-телефонии. Обучим персонал и предоставим доступ к обновлениям.</li>
    <li><b>Сделаем ваше жилье красивым.</b> Проконсультируем по материалам для отделки фасада. Положим надежную черепицу. Оформим террасу для летнего времяпрепровождения.</li>
</ul>
<p>
    В Allcorp3 работает 246 квалифицированных инженеров, готовых помочь вам в любой момент. Мы тщательно анализируем помещение и близлежащую территорию перед началом работ. Наша система коммуникации, выстроенная за 8 лет общения с клиентами, позволяет достичь отличного совместного результата.
</p>
<p>
    В нашем портфолио есть <a href="/projects/">проекты</a> для крупных промышленных предприятий и маленьких частных домов. Беремся за проекты любой сложности и одинаково ответственно относимся ко всем нашим клиентам.
</p>
<h3>Что уже сделано</h3>
<ul>
    <li>Установили 183 системы видеонаблюдения.</li>
    <li>За 1 месяц разработали систему контроля доступа на стадионе «Гладиатор».</li>
    <li>Поставили на заводы 1 000 тонн металлопроката.</li>
</ul>
<p>
    Мы знаем, как сделать лучше. Многолетний опыт работы и профессионализм сотрудников позволяют нам занимать лидирующие позиции в сфере услуг. Заказывайте в Allcorp3 — сделаем качественно и за короткий срок.
</p>
<div class="container-main">
    <button>Request</button>
</div>
<div class="container-result"></div>
<script type="text/javascript" language="javascript">
    function callback(result) {
        console.log("is callback");
        console.log(result);
        document.querySelector(".container-result").innerHTML = result;
    };

    function callbackJson(result) {
        console.log("is callbackJson");
        console.log(result);
        document.querySelector(".container-main").setAttribute("data-id", result.id);
    }
    window.addEventListener("load", function() {
        console.log("ready");
        document.querySelector(".main button").addEventListener("click", function(event) {
            console.log('Произошло событие', event.type);
            let data = {
                mode: "json",
                ar: [9, 3, 8]
            };
            let urlHtml = "/ajax/ajax_html.php?mode=html";
            let url = "/ajax/ajax_test.php";
            // makeAjax(url, data);
            // getHtmlAjax(urlHtml, callback);
            // getJsonAjax(url, data, callbackJson);
            helpR52.getHtmlAjax(urlHtml, callback);
            helpR52.getJsonAjax(url, data, callbackJson);
        });
        document.addEventListener("click", event => {
            if (event.target.matches("#ajax-form button")) {
                console.log("ajax-form button");
            }
        }, false);
    });
</script>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>