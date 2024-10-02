<?php 
CJSCore::Init(array( 'Bx'));
?>
<?
// template
$bxajaxid = CAjax::GetComponentID($component->__name, $component->__template->__name, '');
?>

<?// container for replace?>
<div class="selection-table" data-ajaxId="<?= $bxajaxid ?>">
</div>

<script>
    // script for template
    var getAjax = function() {
        let url = window.location.pathname + "?";
        document.querySelectorAll(".selection-container select").forEach(function(el) {
            // console.log(el.options[el.selectedIndex].value);
            url += el.getAttribute("id") + "=" + el.options[el.selectedIndex].value + "&";
        });
        url = url.slice(0, -1);
        // console.log(url);
        // window.location.href = url;

        let bxajaxid = document.querySelector(".selection-table").getAttribute('data-ajaxId');
        // console.log(bxajaxid);
        // url = "/selection-test/podbor-normiruyushchikh-izmeritelnykh-preobrazovateley-po-parametram/?MOUNTING_TYPE=all&INPUT_SIGNAL_TYPE=all&OUTPUT_SIGNAL_TYPE=all&CONFIGURATION_METHOD=all&GALVANIC_ISOLATION=all&NUMBER_OUTPUTS=all&AVAILABILITY_RS_485=all&ELECTRIC_SUPPLY=all&TEMPERATURE_RANGE=1456";
        url += '&bxajaxid=' + bxajaxid;
        BX.ajax.insertToNode(url, 'comp_' + bxajaxid);

    }
    var resetFilter = function() {
        let url = window.location.pathname + "?";
        document.querySelectorAll(".selection-container select").forEach(function(el) {
            url += el.getAttribute("id") + "=" + "all" + "&";
        });
        url = url.slice(0, -1);

        let bxajaxid = document.querySelector(".selection-table").getAttribute('data-ajaxId');
        url += '&bxajaxid=' + bxajaxid;
        BX.ajax.insertToNode(url, 'comp_' + bxajaxid);

    }
    if (true) {
        document.addEventListener('click', event => {
            if (event.target.matches('button.prodfilter-reset')) {
                // window.location.href = window.location.pathname;
                resetFilter();
            }
        }, false);

        document.addEventListener('click', event => {
            if (event.target.matches('button.prodfilter-print')) {
                window.print();
            }
        }, false);

        document.addEventListener('change', event => {
            if (event.target.matches('.selection-container select')) {
                // console.log('.selection-container select');
                getAjax();
            }
        }, false);

    }
</script>