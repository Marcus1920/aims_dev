<script type="text/javascript">
function getRelatedItems(opts, sel) {
	var table = opts.table;
	console.log("getRelatedItems(opts, sel) opts - ",opts,", sel - ", sel);
	$.ajax({
		type    :"GET",
		dataType:"json",
		url     :"forms/database/data/"+ table + "",
		success :function(data) {
			console.log("  data - ", data);
			if (data) for (var i = 0; i < data.length; i++) {
				var text = "";
				var val = -1;
				if (data[i]['id']) val = data[i]['id'];
				if (opts.display) for (var oi = 0; oi < opts.display.length; oi++) text += data[i][opts.display[oi]] + " ";
				$(sel).append('<option value="'+val+'">'+text+'</option>');
				console.log("    "+i+", val - ",val,", text - ",text);
			}
		}
	});
}

function launchDataModal(id, form_id) {
	//console.log("launchFormModal(id, edit) id - ",id,", edit - ",edit);
	console.log("launchDataModal(id, form_id) id - ",id,", form_id - ",form_id);
	//editForm = edit;
	var symbols = { AUD: "$", BRL: "R$", CAD: "$", CNY: "¥", EUR: "€", HKD: "$", INR: "?", JPY: "¥", MXN: "$", NZD: "$", NOK: "kr", GBP: "&pound;", RUB: "?",SGD: "$", KRW: "?", SEK: "kr", CHF: "Fr", TRY: "?", USD: "$", ZAR: "R" }
	$(".modal-body #formId").val(form_id);
	$(".modal-body #formDataId").val(id);
	$.ajax({
		type    :"GET",
		dataType:"json",
		url     :"{!! url('/forms/data/"+ id + "/"+form_id+"')!!}",
		success :function(data) {
			console.log("data - ", data);
			if (data[0] !== null) {
				$("#modalDataForm .modal-title").text(data[0].name);
				$("#modalDataForm .modal-header i").remove();
				$("#modalDataForm .modal-header").append("<i>"+data[0].purpose+"</i>");
			}
			$("#modalDataForm .modal-body .fields").empty();
			var theRules = {};
			if (data[1] !== null) {
				for (var i = 0; i < data[1].length; i++) {
					/*$(".modal-body").append('<div class="form-group"></div>');
					var group = $(".modal-body").find(".form-group").first();
					group.append("<label>RRR</label>");
					//lbl.text(data[1][i].label);
					//lbl.attr("class", "col-md-2 control-label");*/
					var opts = JSON.parse(data[1][i].options);
					console.log("  data[1]["+i+"] - ", data[1][i],", opts - ", opts);
					var group = document.createElement("div");
					group.className = "form-group clearfix";
					var lbl = document.createElement("label");
					lbl.className = "col-md-2 control-label";
					lbl.innerText = data[1][i].label;
					///$(lbl).attr("for", data[1][i].name);
					//$(lbl).css("white-space", "nowrap");
					$(group).append(lbl);
					
					var div = document.createElement("div");
					div.className = "col-md-6";
					
					var input = null;
					if (data[1][i].type != "choice" && data[1][i].type != "rel" && opts.type != "select") input = document.createElement("input");
					else input = document.createElement("select");
					input.className = "form-control input-sm";
					var name = "data["+data[1][i].name+"]";
					input.name = name;
					input.id = data[1][i].name;
					///input.required = true;
					input.style.display = "inline-block";
					input.style.width = "initial";
					input.type = "text";
					var val = "";
					if (data[2] !== null) val = data[2][data[1][i].name];
					input.value = val;
					$(lbl).attr("for", input.id);
					$(input).attr("data-opts", data[1][i].options);
					
					if (data[1][i].type == "file") input.type = "file";
					
					if (data[1][i].type == "boolean") {
						if (opts.type == "") opts.type = "checkbox";
						if (opts.type == "checkbox") {
							var checked = "";
							if (val == 1) checked = "checked";
							$(div).append('<input id="'+data[1][i].name+'" name="'+name+'" style="opacity: 1" type="checkbox" value="1" '+checked+'>');
						} else if (opts.type == "radio") {
							var wrapper = document.createElement("div");
							var labels = ["False", "True"];
							var checked = ["", ""];
							checked[val] = "checked";
							if (opts['false']) labels[0] = opts['false'];
							$(wrapper).append('<label style="">'+labels[0]+'<input name="'+name+'" style="opacity: 1" type="radio" value="0" '+checked[0]+'></label>');
							///if (opts['false']) $(wrapper).append('<label style="">A <input id="fffA" name="'+data[1][i].name+'" style="opacity: 1" type="radio" value="0"></label>');
							$(wrapper).append("&nbsp;&nbsp;&nbsp;");
							if (opts['true']) labels[1] = opts['true'];
							$(wrapper).append('<label>'+labels[1]+'<input name="'+name+'" style="opacity: 1" type="radio" value="1" '+checked[1]+'></label>');
							///if (opts['true']) $(wrapper).append('<label style="">B <input id="fffB" name="'+data[1][i].name+'" style="opacity: 1" type="radio" value="1"></label>');
							$(div).append(wrapper);
							
						} else if (opts.type == "select") {
							input.className = "form-control select-sm";
							input.id = data[1][i].name;
							input.style.width = "5em";
							if (opts['false']) {
								if (val == 0) $(input).append('<option selected value="0">'+opts['false']+'</option>');
								else $(input).append('<option value="0">'+opts['false']+'</option>');
							}
							if (opts['true']) {
								if (val == 1) $(input).append('<option selected value="1">'+opts['true']+'</option>');
								else $(input).append('<option value="1">'+opts['true']+'</option>');
							}
							
							$(div).append(input);
						}
					} else if (data[1][i].type == "choice") {
						input.className = "form-control select-sm";
						input.id = data[1][i].name;
						if (opts.multi == 1) {
							input.multiple = true;
							input.name += "[]";
						} 
						if (opts.options && opts.options.length > 0) {
							//if (opts.multi) sel.size = opts.options.length;
							for (var oi = 0; oi < opts.options.length; oi++) {
								if (opts.multi == 1) {
									var sel = "";
									for (var vi = 0; vi < val.length; vi++) {
										if (val[vi] == opts.options[oi]) sel = "selected";
									}
									$(input).append('<option '+sel+' value="'+opts.options[oi]+'">'+opts.options[oi]+'</option>');
								} else {
								if (val == opts.options[oi]) $(input).append('<option selected value="'+opts.options[oi]+'">'+opts.options[oi]+'</option>');
								else $(input).append('<option value="'+opts.options[oi]+'">'+opts.options[oi]+'</option>');
								}
							}
						}
						
						$(div).append(input);
					} else if (data[1][i].type == "currency") {
						var div2 = document.createElement("div");
						input.style.textAlign = "right";
						$(div2).append(symbols[opts.type]+" ");
						$(div2).append(input);
						$(input).attr("placeholder", "sdjsldsh");
						//$(input).attr("required", "required");
						//$(input).attr("digits", "digits");
						//$(input).attr("data-rule-requiredd", "true");
						//$(input).attr("data-rule-currency", "true");
						//$(input).attr("data-type", "currency");
						$(input).attr("data-rule-number", "true");
						$(div).append(div2);
					} else if (data[1][i].type == "datetime") {
						if (opts.subtype == "datetime") $(input).attr("data-format", "yyyy-MM-dd hh:mm:ss");
						else if (opts.subtype == "date") $(input).attr("data-format", "yyyy-MM-dd");
						else if (opts.subtype == "time") $(input).attr("data-format", "hh:mm:ss");
						var div2 = document.createElement("div");
						div2.className = "input-icon datetime-pick ";
						if (opts.subtype == "date") div2.className += "date-only";
						else if (opts.subtype == "time") div2.className += "time-only";
						else div2.className += "datetime";
						$(div2).append(input);
						$(div2).append('<span class="add-on"><i class="sa-plus"></i></span>');
						$(div).append(div2);
					} else if (data[1][i].type == "number") {
						var len = 0;
						//if (opts.min) 
						var inputNum = $(div).append(input).find("input").last();
						console.log("inputNum - ", inputNum);
						//$(input).attr("data-rule-number", "true");
						//$(input).rules("add", { required: true, digits: true });
						/*$(input).rules("add", {
							required: true, currency: true
						});*/
						if ((opts.decimals) == 0) $(input).attr("data-rule-digits", "true");
						else $(input).attr("data-rule-number", "true");
					} else if (data[1][i].type == "text" && opts.lines && opts.lines > 1) {
						$(div).append('<textarea class="form-control" id="'+data[1][i].name+'" name="data['+data[1][i].name+']" rows="'+opts.lines+'">'+val+'</textarea>');
					} else if (data[1][i].type == "rel") {
						input.className = "form-control select-sm";
						input.id = data[1][i].name;
						getRelatedItems(opts, input);
						$(div).append(input);
					}	else $(div).append(input);
					if (opts.min && opts.max) $(input).attr("data-rule-range", [opts.min, opts.max]);
					else if (opts.min) $(input).attr("data-rule-min", opts.min);
					else if (opts.max) $(input).attr("data-rule-max", opts.max);
					$(div).find("input").iCheck("destroy");
					$(div).find("input").iCheck({
		    checkboxClass: 'icheckbox_minimal',
		    radioClass: 'iradio_minimal'
	});
					$(group).append(div);
					$("#modalDataForm .modal-body div").first().append(group);
					$('.datetime').datetimepicker({ collapse: false, sideBySide: true });
					$('.date-only').datetimepicker({ pickTime: false });
					$('.time-only').datetimepicker({ pickDate: false });
					
					var title = data[1][i].desc;
					if (title) title += "\n";
					if (opts.lines) title += opts.lines+" line(s), ";
					if (opts.min || opts.max) {
						if (opts.min && opts.max) title += " > "+opts.min+" & < "+opts.max;
						else if (opts.min) title += " > "+opts.min;
						else if (opts.max) title += " < "+opts.max;
						if (data[1][i].type == "text") title += " characters";
					}
					$(input).attr("title", title);
					$(input).attr("data-original-title", title);
					if (title != "") $(input).tooltip({placement: "right", html: true, animation: true, template: '<div class="tooltip" role="tooltip" style="white-space: pre-wrap"><div class="tooltip-arrow"></div><div class="tooltip-inner" style="background-color: rgba(128,128,128,0.75); "></div></div>',});
				}
			}
			updateFields(data[1]);
			
			/*$("#testCustomForm").validate({
				submitHandler: function(form) {
					console.log("submitHandler(form) form - ", form);
				}
			});*/
			
			var height = $(window).get(0).innerHeight - 200;
			console.log("  height - ", height, ", #modalDataForm .modal-body height - ", $("#modalDataForm .modal-body").height());
			if ($("#modalDataForm .modal-body").height() > height) $("#modalDataForm .modal-body").height( height );
			
			jQuery.validator.setDefaults({
				debug: true
				, success: "valid"
			});
			
			$("#testCustomForm").validate({
			onfocusout: function(el, ev) {
				console.log("onfocusout(el, ev) el - , ",el);
				//$(el.form).valid();
				//if (el.value != "") 
				$(el).valid();
			}
			, onkeyup: function(el, ev) {
				console.log("onkeyup(el, ev) el - , ", el,", ev - ", ev);
				$(el).valid();
			}
			/*, rules: {
				"nolimitss": {
					//required: true
				}
				, "checkcode": {
					//required: true
				}
			}*/
			, rules: theRules
		});
			
			/*$('input').rules("add", {
				required: true, currency: true
			});*/
			
			
		}
	});
}

function submitData(ev) {
	console.log("submitData(ev) ev - ", ev);
	//var action = $('#dataFom')
	var token    = $('#dataForm input[name="_token"]').val();
	var formId = $("#formId").val();
	var formDataId = $("#formDataId").val();
	var formData = {};
	//formData['_token'] = token;
	formData['formId'] = formId;
	formData['id'] = formDataId;
	$("#dataForm").find("[name^='data']").each(function(i, el) {
		console.log("  data.each("+i+") el - ",el);
		formData[el.name] = $(el).val();
	});
	console.log("  formData - ", formData);
	
	$.ajax({
    type    :"POST",
    data    : formData,
    headers : { 'X-CSRF-Token': token },
    url     :"{!! url('updateFormData')!!}",
    success : function(data, status) {
			console.log("  success! status - ",status,", data - ", data);
			if (data == "true") {
				
			} else {
				//redirect()->back()->withInput();
			}
    }
	});

}

function updateFields(fields) {
	var form = $("#modalDataForm").first();
	console.log("updateFields(fields, form) fields - ",fields,", form - ", form);
}

$(document).ready(function() {
	$("#submitDataForm").on("click",function(ev) {
		ev.preventDefault();
		submitData(ev);
	});
});
</script>