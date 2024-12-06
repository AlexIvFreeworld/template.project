BX.addCustomEvent("onLoadjqmCustomHandlers", (eventdata) => {
    const el = eventdata.element
    const value = eventdata.value

	let bSpecialization = ((el[0].getAttribute("data-sid") == 'SPECIALIZATION') || (el[0].getAttribute("name") == 'SPECIALIZATION'));

    if(typeof(el[0]) !== 'undefined' && typeof(el[0].tagName) !== 'undefined' && el[0].tagName == 'SELECT' && !bSpecialization){
        el.find('option[data-id="'+value+'"]').attr('selected', true);
		
        if(value){
          $(el).closest('form').find('select option').each(function(){
            var dataID = $(this).data('id').toString(),
              arDataID = (dataID.indexOf(',') != -1 ? dataID.split(',') : [dataID]);

            if(arDataID.indexOf(value.toString()) != -1){
              $(this).show();
            }
            else{
              $(this).hide();
            }
          });
        }
        el.closest('.row[data-sid]').show();
    } else if (bSpecialization) {
		el[0].removeAttribute('readonly');
		el.find('option[data-id="'+value+'"]').attr('selected', true);
	}
})
BX.addCustomEvent("formCustomHandlers", (eventdata) => {

	const selector = `.form form select[name=SPECIALIZATION]:not(.inited), .form form select[data-sid=SPECIALIZATION]`;
	const $date = $("input#POPUP_DATE");

  	if (typeof(dateTimePickerInit) !== 'function' || $date.data('datetimepicker')) return;

  	let datetimepickerParams = {  
    	minView: 2,   
	};

  	dateTimePickerInit(datetimepickerParams, '.form form input.date');  
	
	$(selector).on("change", function () {
		const $this = $(this),
			$selectSpecialist = $this.closest("form").find("select[name=SPECIALIST], select[data-sid=SPECIALIST]"),
			$selectSpecialistWrapper = $selectSpecialist.closest("div[data-sid='SPECIALIST']"),
			animationTime = 200;
		let arID = "all";

		if (typeof $this.find("option:selected").data("id") !== "undefined") {
			arID =
				$this.find("option:selected").data("id").toString().indexOf(",") != -1
					? $this.find("option:selected").data("id").split(",")
					: [$this.find("option:selected").data("id").toString()];
		}

		if ($selectSpecialist.length && !$selectSpecialist.attr("readonly")) {
			if (arID.indexOf($selectSpecialist.find("option:selected").data("id").toString()) == -1) {
				$selectSpecialist
					.find("option")
					.eq(0)
					.attr("selected", true)
					.closest("select")
					.val("");
			}

			if (arID[0] == "all") {
				$selectSpecialist.find("option").show();
				if ($selectSpecialistWrapper.is(":visible")) {
					$selectSpecialistWrapper.fadeOut(animationTime);
				}
			} else {
				$selectSpecialist
					.find("option")
					.each(function () {
						let ID = "";
						if (typeof $(this).data("id") !== "undefined") {
							ID =
								typeof $(this).data("id") == "number"
									? $(this).data("id").toString()
									: $(this).data("id");
						}

						if (arID.indexOf(ID) != -1 || $(this).data("id") == "all") {
							$(this).show();
						} else {
							$(this).hide();
						}
					})
					.promise()
					.done(function () {
						if (
							!$selectSpecialistWrapper.is(":visible") &&
							$selectSpecialist.find("option").css("display") === "block"
						) {
							$selectSpecialistWrapper.fadeIn();
						}
					});
			}
		}
	});
	$(selector).addClass('inited');
});