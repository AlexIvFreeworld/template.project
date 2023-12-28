$(document).ready(function () {
	calcSum();
	$('#registration [name="SERVICES[]"]').change(calcSum);

	// добавление блока для заполнения данных участника
	/*$('.participant-add-button').click(function(event){
		let insertBlock = $(".participant-add-button").parents('.form-block');
		let newBlock = $(".participant_block:first").clone();
		//let deleteSelect = newBlock.find('.user_club');
		let userClub = $(".user_clubs_hidden:first").clone();
		newBlock.find('.user_club .choices').replaceWith(userClub);

		const selects = document.querySelectorAll('.field-select__select');
		  selects.forEach(function(item){
			new Choices(item, {
			  searchEnabled: false,
			  placeholderValue: 'Выберите',
			  shouldSort: false
			});
		  });

		newBlock.find('input, select').each(function(i,elem) { 
			$(this).removeAttr('disabled');
		});

		newBlock.insertBefore(insertBlock); 

	});*/
	let eventType = document.querySelector("input[name='testreg']").getAttribute("data-event-type");
	//console.log("eventType " + eventType);
	if (eventType == "49") {
		var countMembers = document.querySelectorAll(".cont-members").length - 1;
		//console.log("countMembers " + countMembers);
		document.querySelector(".f-new-copy__btn").addEventListener("click", function () {
			countMembers++;
			copyMember(countMembers);
		});
		document.querySelectorAll(".f-new-copy__delete").forEach(function (el) {
			el.addEventListener("click", function (e) {
				e.preventDefault();
				//console.log(this);
				deleteMember(this);
			});
		});
		// document.querySelectorAll(".form-block__item.captain input[type='radio']").forEach(function (el) {
		// 	el.addEventListener("click", function () {
		// 		let name = this.getAttribute("name");
		// 		this.value = "1";
		// 		document.querySelectorAll(".form-block__item.captain input[type='radio']").forEach(function (el) {
		// 			if (el.getAttribute("name") != name) {
		// 				el.removeAttribute("checked");
		// 				el.value = "";
		// 			}
		// 		})
		// 	});
		// });
		document.querySelectorAll("input[data-checkbox-group='STAGES']").forEach(function (el) {
			el.addEventListener("click", function (e) {
				let name = this.getAttribute("name");
				let value = this.value;
				//console.log(name + ' ' + value);
				document.querySelectorAll("input[name='" + name + "']").forEach(function (el) {
					if (el.value != value) {
						el.setAttribute("disabled", "disabled");
					}
				})
				document.querySelectorAll("input[data-checkbox-group='STAGES']").forEach(function (el) {
					if (el.name != name && el.value == value) {
						el.setAttribute("disabled", "disabled");
					}
				})
				cleanChecksTemplate();
			});
		});
	}
	document.querySelector("button[name='SEND_FORM']").addEventListener("click", function () {
		if (eventType == "49") {
			checkStagesNew(this);
		} else {
			document.querySelector("#registration").submit();
		}
	});
});

// пересчет цены при выборе услуг
function calcSum() {
	var servicesPrice = 0;
	$('#registration [name="SERVICES[]"]').each(function (index, element) {
		if ($(this).prop("checked")) {
			let servicePrice = Number($(this).parents('.form-block__item').find('.service-price').text());
			servicesPrice += servicePrice;
		}

		if (servicesPrice != null) {
			$('#add-service-price').text(servicesPrice);
			$('[name="SERVICES_PRICE"]').attr('value', servicesPrice);

			let price = Number($('[name="PRICE"]').val());
			let totalPrice = price + servicesPrice;

			$('#total-price').text(totalPrice);
			$('[name="TOTAL_PRICE"]').attr('value', totalPrice);

			if ($('input[name="PROMOCODE_YES"]').is(':checked')) {
				$(".check-promocode").trigger('click');
			}
		}
	});
}

function calcDistance(input) {
	let servicePrice = Number($('[name="SERVICES_PRICE"]').val());
	let price = Number(input.getAttribute('data-price'));
	$('#total-price').text(price + servicePrice);
	$('[name="PRICE"]').attr("value", price);
	$('#event-price').text(price);
	$('[name="TOTAL_PRICE"]').attr('value', price + servicePrice);
}
// f-copy__btn
function copyMember(countMembers) {
	// let newParticipant = document.querySelector(".f-copy__this").cloneNode(true);
	// document.querySelector(".f-copy__this").after(newParticipant);
	let curCount = 0;
	let count = document.querySelectorAll(".cont-members").length;
	//console.log("count " + count);
	document.querySelectorAll(".cont-members").forEach(function (el) {
		curCount++;
		//console.log("curCount " + curCount);
		if (parseInt(count) == parseInt(curCount)) {
			let newParticipant = document.querySelector(".cont-testreg").cloneNode(true);
			let memberKey = newParticipant.getAttribute("data-memkey");
			numberParticipant = newParticipant.querySelector(".number-participant").textContent = countMembers;
			// console.log(numberParticipant++);
			// numberParticipant = newParticipant.querySelector(".number-participant").textContent = numberParticipant;
			newParticipant.querySelectorAll("input[type='text'],input[type='radio'],input[type='checkbox']").forEach(function (el) {
				el.setAttribute("name", el.getAttribute("name").replace(memberKey, countMembers));
			});
			// newParticipant.querySelectorAll("input[type='radio']").forEach(function (el) {
			// 	el.value = "";
			// 	el.removeAttribute("checked");
			// });
			// newParticipant.querySelectorAll(".choices__inner select").forEach(function (el) {
			// 	el.options[el.selectedIndex].value = "";
			// });
			newParticipant.querySelectorAll(".field-select__select-wrap select").forEach(function (el) {
				el.setAttribute("name", el.getAttribute("name").replace(memberKey, countMembers));
				el.setAttribute("data-select-html", el.getAttribute("data-select-html").replace(memberKey, countMembers));
			});
			newParticipant.querySelector(".f-new-copy__delete").addEventListener("click", function () {
				//console.log(this);
				deleteMember(this);
			});
			// newParticipant.querySelectorAll(".form-block__item.captain input[type='radio']").forEach(function (el) {
			// 	el.addEventListener("click", function () {
			// 		let name = this.getAttribute("name");
			// 		document.querySelectorAll(".form-block__item.captain input[type='radio']").forEach(function (el) {
			// 			if (el.getAttribute("name") != name) {
			// 				el.removeAttribute("checked");
			// 			}
			// 		})
			// 	});
			// });
			newParticipant.querySelectorAll("input[data-checkbox-group='STAGES']").forEach(function (el) {
				el.addEventListener("click", function (e) {
					let name = this.getAttribute("name");
					let value = this.value;
					//console.log(name + ' ' + value);
					document.querySelectorAll("input[name='" + name + "']").forEach(function (el) {
						if (el.value != value) {
							el.setAttribute("disabled", "disabled");
						}
					})
					document.querySelectorAll("input[data-checkbox-group='STAGES']").forEach(function (el) {
						if (el.name != name && el.value == value) {
							el.setAttribute("disabled", "disabled");
						}
					})
				});
			});
			newParticipant.classList.remove("cont-testreg");
			el.after(newParticipant);
		}
	});
}
function checkMembers() {
	// deleteMember(this);
	let count = 0;
	document.querySelectorAll(".cont-members").forEach(function (el) {
		count++;
	});
	// console.log(count);
	let stages = document.querySelector("[data-stages]").getAttribute("data-stages");
	// console.log(stages);
	if (parseInt(stages) > parseInt(count)) {
		document.querySelector(".f-new-copy__btn").style.display = "block";
	}
}
function deleteMember(el) {
	//console.log(el.parentNode.parentNode.parentNode.parentNode);
	el.parentNode.parentNode.parentNode.parentNode.remove();
}
function checkStagesNew(el) {
	//console.log(el);
	let countCheck = 0;
	let countRadio = 0;
	let noEmptyField = true;
	document.querySelectorAll(".form-block__item.notestreg input[type='text']").forEach(function (el) {
		if (el.value == "") {
			noEmptyField = false;
		}
	});
	document.querySelectorAll(".form-block__item.gender input[type='radio']").forEach(function (el) {
		if (el.checked) {
			countRadio++;
		}
	});
	document.querySelectorAll("input[data-checkbox-group='STAGES']").forEach(function (el) {
		if (el.checked) {
			countCheck++;
		}
		let name = el.getAttribute("name");
		let value = el.value;
		//console.log(name + ' ' + value);
	});
	//console.log("countCheck " + countCheck);
	countMembersFinal = document.querySelectorAll(".cont-members").length - 1;
	//console.log("countMembersFinal " + countMembersFinal);
	//console.log("countRadio " + countRadio);
	//console.log("noEmptyField " + noEmptyField);
	if (countCheck == countMembersFinal && countRadio == countMembersFinal && noEmptyField) {
		if (document.querySelector(".cont-testreg")) {
			document.querySelector(".cont-testreg").remove();
		}
		document.querySelector("#registration").submit();
	}
}
function cleanChecksTemplate() {
	document.querySelectorAll(".cont-testreg input[data-checkbox-group='STAGES']").forEach(function (el) {
		if (el.checked) {
			el.removeAttribute("checked");
		}
	});
}