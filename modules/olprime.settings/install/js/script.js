addEventListener('DOMContentLoaded', function() {
	
	'use strict';
	
	let sortOrder = document.querySelectorAll('.sortOrder'),
		form = document.getElementById('olprimeForm');
	
	if(!!sortOrder)
	{
		sortOrder.forEach(function(elem){
			Sortable.create(elem,
			{
				handle: '.drag',
				animation: 150,
				forceFallback: true,
				filter: '.no_drag',
				onStart: function(e){
					e.oldIndex;
					window.getSelection().removeAllRanges();
				},
				onMove: function(e){
					return e.related.className.indexOf('no_drag') === -1;
				},
				onUpdate: function(e){
					let order = [],
						siteId = elem.getAttribute('data-site');
					
					elem.querySelectorAll('.block').forEach(function(block) {
						order.push(block.querySelector('input[type="checkbox"]').getAttribute('name').replace('_' + siteId, ''));
					});

					elem.closest('.adm-detail-content-table.edit-table').querySelector('input[name="SORT_ORDER_'+ siteId +'"]').value = order.join(',');
				}
			});
		});
	}
	
	if(!!form)
	{
		let input = document.getElementById('tabControl_active_tab'),
			myInput = document.getElementById('olprimeTabControl');
		
		form.addEventListener('submit', function() {
			myInput.value = input.value;
		});
	}
});