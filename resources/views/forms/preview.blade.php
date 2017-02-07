<?php
  
?>
<!-- Modal Default -->
<div class="modal fade modalPreviewForm" id="modalPreviewForm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Custom Form Preview</h4>
            </div>
            <div class="modal-body">
            {!! Form::open(['url' => 'testForm', 'method' => 'post', 'class' => 'form-horizontal', 'id'=>"testCustomForm", 'style'=>"height: 100%" ]) !!}
            <div style="height: 100%; overflow-x: hidden; overflow-y: auto;"></div>
            </div>
            <div class="modal-footer">
							<div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <button type="submit" id='submitTestCustomForm' type="button" class="btn btn-sm">Test</button>
                </div>
            	</div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<script type="text/javascript">
function launchPreviewFormModal(id) {
	$.ajax({
		type    :"GET",
		dataType:"json",
		url     :"{!! url('/forms/"+ id + "')!!}",
		success :function(data) {
			console.log("data - ", data);
			if (data[0] !== null) {
				$("#modalPreviewForm .modal-title").text(data[0].name);
				$("#modalPreviewForm .modal-header i").remove();
				$("#modalPreviewForm .modal-header").append("<i>"+data[0].purpose+"</i>");
			}
			$("#modalPreviewForm .modal-body form div").empty();
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
					$(lbl).attr("for", data[1][i].name);
					//$(lbl).css("white-space", "nowrap");
					$(group).append(lbl);
					
					var div = document.createElement("div");
					div.className = "col-md-6";
					
					var input = null;
					input = document.createElement("input");
					input.className = "form-control input-sm";
					input.name = data[1][i].name;
					input.id = data[1][i].name;
					input.required = true;
					
					if (data[1][i].type == "file") input.type = "file";
					
					if (data[1][i].type == "boolean") {
						if (opts.type == "checkbox") {
							$(div).append('<input id="'+data[1][i].name+'" name="'+data[1][i].name+'" style="opacity: 1" type="checkbox" value="1">');
						} else if (opts.type == "radio") {
							var wrapper = document.createElement("div");
							if (opts['false']) $(wrapper).append('<label style="">'+opts['false']+'<input name="'+data[1][i].name+'" style="opacity: 1" type="radio" value="0"></label>');
							///if (opts['false']) $(wrapper).append('<label style="">A <input id="fffA" name="'+data[1][i].name+'" style="opacity: 1" type="radio" value="0"></label>');
							$(wrapper).append("&nbsp;&nbsp;&nbsp;");
							if (opts['true']) $(wrapper).append('<label>'+opts['true']+'<input name="'+data[1][i].name+'" style="opacity: 1" type="radio" value="1"></label>');
							///if (opts['true']) $(wrapper).append('<label style="">B <input id="fffB" name="'+data[1][i].name+'" style="opacity: 1" type="radio" value="1"></label>');
							$(div).append(wrapper);
							
						} else if (opts.type == "select") {
							var sel = document.createElement("select");
							sel.className = "form-control select-sm";
							sel.id = data[1][i].name;
							sel.style.width = "5em";
							if (opts['false']) $(sel).append('<option value="0">'+opts['false']+'</option>');
							if (opts['true']) $(sel).append('<option value="1">'+opts['true']+'</option>');
							
							$(div).append(sel);
						}
					} else if (data[1][i].type == "choice") {
						var sel = document.createElement("select");
						sel.className = "form-control select-sm";
						sel.id = data[1][i].name;
						if (opts.multi == 1) {
							sel.multiple = true;
						}
						if (opts.options && opts.options.length > 0) {
							//if (opts.multi) sel.size = opts.options.length;
							for (var oi = 0; oi < opts.options.length; oi++) {
								$(sel).append('<option value="'+opts.options[oi]+'">'+opts.options[oi]+'</option>');
							}
						}
						$(div).append(sel);
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
					} else if (data[1][i].type == "text" && opts.lines && opts.lines > 1) {
						$(div).append('<textarea class="form-control" id="'+data[1][i].name+'" rows="'+opts.lines+'"></textarea>');
					} else if (data[1][i].type == "rel") {
						var sel = document.createElement("select");
						sel.className = "form-control select-sm";
						sel.id = data[1][i].name;
						getRelatedItems(opts, sel);
						$(div).append(sel);
					}	else $(div).append(input);
					$(div).find("input").iCheck("destroy");
					$(div).find("input").iCheck({
		    checkboxClass: 'icheckbox_minimal',
		    radioClass: 'iradio_minimal'
	});
					$(group).append(div);
					$("#modalPreviewForm .modal-body div").first().append(group);
					$('.datetime').datetimepicker({ collapse: false, sideBySide: true });
					$('.date-only').datetimepicker({ pickTime: false });
					$('.time-only').datetimepicker({ pickDate: false });
				}
			}
			
			$("#modalPreviewForm").validate({
				submitHandler: function(form) {
					console.log("submitHandler(form) form - ", form);
				}
			});
			
			var height = $(window).get(0).innerHeight - 200;
			console.log("  height - ", height, ", #modalPreviewForm .modal-body height - ", $("#modalPreviewForm .modal-body").height());
			if ($("#modalPreviewForm .modal-body").height() > height) $("#modalPreviewForm .modal-body").height( height );
		}
	});
}

function getRelatedItems(opts, sel) {
	var table = opts.table;
	console.log("getRelatedItems(opts, sel) opts - ",opts,", sel - ", sel);
	$.ajax({
		type    :"GET",
		dataType:"json",
		url     :"{!! url('/forms/database/data/"+ table + "')!!}",
		success :function(data) {
			console.log("  data - ", data);
			if (data) for (var i = 0; i < data.length; i++) {
				var val = "";
				if (opts.display) for (var oi = 0; oi < opts.display.length; oi++) val += data[i][opts.display[oi]] + " ";
				$(sel).append('<option value="'+val+'">'+val+'</option>');
				console.log("    "+i+", val - ",val);
			}
		}
	});
}

$(document).ready(function() {
	$("#submitTestCustomForm").on("click", function (ev) { 
		console.log("#submitTestCustomForm.click(ev) this - ", this);
		ev.preventDefault();
		//if (checkForm()) $("#updateCustomForm").submit();
		$("#testCustomForm").validate({
				submitHandler: function(form) {
					console.log("submitHandler(form) form - ", form);
				}
			});
			//$("#testCustomForm").submit();
	});
	
});
</script>