jQuery(document).ready(function($) {
	if (typeof acf == 'undefined') { return; }

	$(document).on('change', '#acf-re_province', function(e) {
		update_districts_on_province_change(e, $);
	});

	$(document).on('change', '#acf-re_district', function(e) {
		update_wards_on_district_change(e, $);
	});
});

function update_districts_on_province_change(e, $) {
	if (this.request) {
		this.request.abort();
	}

	var district_select = $('#acf-re_district');
	district_select.empty();

	var target = $(e.target);
	var province = target.val();

	if (!province) {
		return;
	}

	var data = {
		action: 'willgroup_load_district_field_choices',
		province: province
	}

	data = acf.prepareForAjax(data);

	this.request = $.ajax({
		url: acf.get('ajaxurl'),
		data: data,
		type: 'post',
		dataType: 'json',
		success: function(json) {
			if (!json) {
				return;
			}
			for(i = 0; i < json.length; i++) {
				var district_item = '<option value="'+json[i]['value']+'">'+json[i]['label']+'</option>';
				district_select.append(district_item);
			}
		}
	});

}

function update_wards_on_district_change(e, $) {
	if (this.request) {
		this.request.abort();
	}

	var ward_select = $('#acf-re_ward');
	ward_select.empty();

	var target = $(e.target);
	var district = target.val();

	if (!district) {
		return;
	}

	var data = {
		action: 'willgroup_load_ward_field_choices',
		district: district
	}

	data = acf.prepareForAjax(data);

	this.request = $.ajax({
		url: acf.get('ajaxurl'),
		data: data,
		type: 'post',
		dataType: 'json',
		success: function(json) {
			if (!json) {
				return;
			}
			for(i = 0; i < json.length; i++) {
				var ward_item = '<option value="'+json[i]['value']+'">'+json[i]['label']+'</option>';
				ward_select.append(ward_item);
			}
		}
	});

}
