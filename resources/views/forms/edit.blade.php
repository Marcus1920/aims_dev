<?php
	use App\Http\Controllers\DatabaseController AS DbController;
	$dbTables = DbController::getTables(true);
	array_unshift($dbTables, "-- Select --");
?>
<!-- Modal Default -->
<div class="modal fade modalEditForm" id="modalEditForm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Custom Form</h4>
            </div>
            <div class="modal-body">
            {!! Form::open(['url' => 'updateForm', 'method' => 'post', 'class' => 'form-horizontal', 'id'=>"updateCustomForm" ]) !!}
            {!! Form::hidden('formId',NULL,['id' => 'formId']) !!}
            {!! Form::hidden('id',Auth::user()->id) !!}
            <div class="form-group">
                {!! Form::label('name', 'Name', array('class' => 'col-md-2 control-label')) !!}
                <div class="col-md-10">
                  {!! Form::text('name',NULL,['class' => 'form-control input-sm','id' => 'name']) !!}
                  @if ($errors->has('name')) <p class="help-block red">*{{ $errors->first('name') }}</p> @endif
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('purpose', 'Purpose', array('class' => 'col-md-2 control-label')) !!}
                <div class="col-md-10">
                  {!! Form::text('purpose',NULL,['class' => 'form-control input-sm','id' => 'purpose']) !!}
                  @if ($errors->has('purpose')) <p class="help-block red">*{{ $errors->first('purpose') }}</p> @endif
                </div>
            </div>
            
            <hr>
            <div class="form-group">
            	
		            {!! Form::label('selTable', 'Table', array('class' => 'col-md-2 control-label')) !!}
		            <div class="col-md-3">
		            {!! Form::select('table',$dbTables, 0,['class' => 'form-control select-sm','id' => 'selTable', 'style'=>"width: 10em", 'onchange'=>"selectTable(this.options[this.selectedIndex].value)"]) !!}
		          	</div>
		          	{!! Form::label('chkSystem', 'System', array('class' => 'col-md-2 control-label', 'title'=>"Include system fields")) !!}
	            	{!! Form::checkbox('chkSystem',1, false,['id'=>'chkSystem']) !!}
	          </div>
            <h3 class="block-title">Fields</h3>
            <a class="btn btn-sm" data-toggle="modal" data-target=".modalAddField" id="btnAddField">Add Field</a>
            <!--<span id="cntFields">0</span>-->
            <div class="form-group" id="formFields">
            
            </div>
            
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <button type="submit" id='submitUpdateCustomForm' type="button" class="btn btn-sm">Save Changes</button>
                </div>
            </div>
            </div>
            <div class="modal-footer">

            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script type="text/javascript">
var cntFields = 0;
var tablename = "";


function launchUpdateFormModal(id) {
	console.log("launchUpdateFormModal(id) id - "+id+", fields - ",$(".fieldTemplateClone").length);
	$(".modal-body #formId").val(id);
	//$("#formFields").remove(".fieldTemplate");
	$(".fieldTemplateClone").remove();
	/*$("#cntFields").text($(".fieldTemplateClone").length);*/
	cntFields = 0;
	$.ajax({
		type    :"GET",
		dataType:"json",
		url     :"{!! url('/forms/"+ id + "')!!}",
		success :function(data) {
			console.log("data - ", data);
			if(data[0] !== null) {
				$("#modalEditForm #name").val(data[0].name);
				$("#modalEditForm #purpose").val(data[0].purpose);
			}
			else {
				$("#modalEditForm #name").val('');
			}
			if(data[1] !== null) {
				for (var i = 0; i < data[1].length; i++) {
					addField(data[1][i]);
				}
			}
			/*$(".fieldTemplateClone").each(function(i, el) {
				//var el = this;
				console.log(".fieldTemplate(i, el) i - "+i+", el - ", el);
				$(el).find("[name*='field']").each(function(i2, el2) {
					console.log("  field(i2, el2) i - "+i2+", el2 - ", el2);
					el2.name = el2.name.replace("[]", "["+i+"]");
					el2.id= el2.id + i;
					var lbl = $(el2).parent().find("label").first();
					lbl = $(el2).siblings("label").first();
					if (lbl.length == 0) lbl = $(el2).parent().parent().find("label").first();
					if (lbl.length == 0) lbl = $(el2).parent().parent().parent().find("label").first();
					console.log("  lbl - ", lbl);
					console.log("  lbl.text() - ", lbl.text());
					console.log("  lbl.val() - ", lbl.val());
					console.log("  for - ", lbl.attr("for"));
					lbl.attr("for", el2.id);
					console.log("  for - ", lbl.attr("for"));
				});
			});*/
			$("#chkSystem").off("ifChanged");
			$("#chkSystem").on("ifChanged", function(ev) {
				var selTable = $("#selTable").get(0);
				//var tbl = selTable.options[selTable.selectedIndex].text;
				var tbl = $("#selTable").val();
				//alert("chkSystem.changed() tbl - "+tbl);
				if (tbl != "0") selectTable(tbl);
			});
			
			$("a[title!=''],input[title!=''],label[title!='']").tooltip( { track: true } );
		}
	});	
}

function addChoice(template, val) {
	var choices = $(template).find("#optsChoices");
	var wrapper = document.createElement("div");
	var choice = document.createElement("input");
	choice.className = "form-control input-sm";
	choice.name = "field[][opts][choice][options][]";
	if (val) choice.value = val;
	wrapper.appendChild(choice);
	choices.append(wrapper);
	updateFields();
}

function addField(vals) {
	console.log("addField(vals) vals - ", vals);
	cntFields++;
	/*$("#cntFields").text(cntFields);*/
	var template = $(".fieldTemplate").clone(true).get(0);
	template.className = "fieldTemplateClone";
	//template.css("display: block !important");
	//template.disabled = false;
	////$(template).find("#fieldType").removeAttr("disabled");
	template.style.display = "block";
	$("#formFields").append(template);
	if (!vals) template.scrollIntoView();
	updateFields();
	///$(template).on("mousedown", startDrag);
	$(template).find(".options").find("[class^='opts']").hide();
	if (vals) {
		if (vals.id) $(template).find("[id^=fieldId]").val(vals.id);
		if (vals.name) $(template).find("[id^=fieldName]").val(vals.name);
		if (vals.label) $(template).find("[id^=fieldLabel]").val(vals.label);
		if (vals.desc) $(template).find("[id^=fieldDesc]").val(vals.desc);
		if (vals.type) {
			$(template).find("[id^=fieldType]").val(vals.type);
			selectType(template, vals.type);
		}
		if (vals.options) {
			var opts = JSON.parse(vals.options);
			console.log("  Updating options - ",vals.options," ("+vals.options.length+"), opts - ",opts," ("+opts.length+")");
			for (var prop in opts) {
				var f = $(template).find("[name$='[opts]["+vals.type+"]["+prop+"]']");
				console.log("    f (before) - ", f);
				if (f.length == 1) {
					console.log("    f.val (before) - ", f.val());
					if ((f[0].type == "checkbox" || f[0].type == "radio") && f[0].value == opts[prop]) f.iCheck("check");
					else f.val(opts[prop]);
					console.log("    f.val (after) - ", f.val());
				}
				else {
					for (var fi = 0; fi < f.length; fi++) {
						//f.iCheck("check");
						if ((f[fi].type == "checkbox" || f[fi].type == "radio") && f[fi].value == opts[prop]) $(f[fi]).iCheck("check");
					}
					//f.val(opts[prop]);
				}
				if (prop == "options" && vals.type && vals.type == "choice") {
					var choices = opts[prop];
					for (var ci = 0; ci < choices.length; ci++) if (choices[ci] != "") addChoice(template, choices[ci]);
				}
			}
		}
	}
	/*$(template).find("input").on("ifClicked", function(ev) {
		console.log("Checkbox clicked");
	});
	$(template).find("input").on("ifChecked", function(ev) {
		console.log("Checked");
	});
	$(template).find("input").on("ifUnchecked", function(ev) {
		console.log("Unchecked");
	});
	$(template).find("input").on("ifClicked", function(ev) {
		console.log("Clicked");
	});*/
	
	$(template).find("input").iCheck("destroy");
	$(template).find("input").iCheck({
		    checkboxClass: 'icheckbox_minimal',
		    radioClass: 'iradio_minimal',
		    increaseArea: '50%' // optional
	});
	//$(template).find("input").iCheck("check");
	//$(template).find("input").iCheck("destroy");
	//$(template).find("input").iCheck("enable");
	//$(template).find("input").iCheck("update");
	$(template).find("[id^='fieldType']").on("change", function(ev) {
		console.log("fieldType.change: ev - ", ev, ", this - ",this);
		selectType(template, this.options[this.selectedIndex].value);
	});
	$(template).find("[id^='btnAddChoice']").on("click", function(ev) {
		addChoice(template);
	});
}

function checkForm() {
	console.log("checkForm()");
	var valid = true;
	$(".fieldTemplateClone").each(function(i, template) {
		console.log("  .fieldTemplateClone: i - "+i+", this - ", this);
		$(this).find(".invalid").hide();
		$(this).find(".invalid").each(function() {
			if ($(this).prev().val() == "") {
				$(this).show();
				template.scrollIntoView();
				valid = false;
			}
		});
		//var inputs = $(this).find("[class^='opts']").filter(":visible").find("input");
		var opts = $(this).find("[class^='opts']").filter(":visible");
		//console.log("    inputs - ", inputs);
		/*inputs.each(function(i2, input) {
			console.log("    inputs["+i2+"] - ", input);
		});*/
		opts.find("input").removeClass("error");
		if (opts.find("[id^=txtMin]")[0] && opts.find("[id^=txtMax]")[0]) {
			var min = opts.find("[id^=txtMin]")[0];
			var max = opts.find("[id^=txtMax]")[0];
			console.log("    min - ",$(min).val(),", max - ", $(max).val());
			if (Number($(min).val()) > Number($(max).val())) {
				$(min).addClass("error");
				$(max).addClass("error");
				valid = false;
				min.scrollIntoView();
			}
		}
	});
	return valid;
}

function duplicateField(index) {
	var field = $("#formFields").find(".fieldTemplateClone").get(index);
	var vals = { type: "" }
	var type = $(field).find("[id^=fieldType]").val();
	var newfield = $(field).clone(true).get(0);
	console.log("duplicateField(index) vals - ", vals);
	$("#formFields").append(newfield);
	newfield.scrollIntoView();
	updateFields();
	$(newfield).find("input").iCheck("destroy");
	$(newfield).find("input").iCheck({
		    checkboxClass: 'icheckbox_minimal',
		    radioClass: 'iradio_minimal'
	});
	$(newfield).find("[id^=fieldType]").val(type);
	$(newfield).find("[id^=fieldId]").val("");
}

function deleteField(index) {
	console.log("deleteField(index) index - "+index);
	//$("#formFields").find(".fieldTemplateClone")[1].remove();
	var field = $("#formFields").find(".fieldTemplateClone").get(index);
	$(field).remove();
	updateFields();
}

function orderDown(e) {
	
}

function orderUp(e) {
	
}

function reorder(dir, index) {
	console.log("reorder(dir, index) dir - "+dir+", index "+index);
	var fields = $("#formFields").find(".fieldTemplateClone");
	var index2 = 0;
	if (dir > 0) index2 = index-1;
	else if (dir < 0) index2 = index+1;
	var el1 = fields.get(index);
	var el2 = fields.get(index2);
	swapElements(el1, el2);
	updateFields();
}

function selectTable(name) {
	var chkSystem = $("#chkSystem").get(0).checked;
	console.log("selectTable(name) name - "+name+", chkSystem - "+chkSystem);
	$(".fieldTemplateClone").remove();
	if (name == "0") return;
	if (name != null) tablename = name;
	$.ajax({
		type    :"GET",
		dataType:"json",
		url     :"{!! url('/forms/database/tables/"+ tablename + "')!!}",
		success :function(data) {
			
			console.log("data - ", data);
			if(data !== null) {
				var systemTables = ["active","created_at", "created_by", "id","remember_token", "updated_at", "updated_by"];
				for (var i = 0; i < data.columns.length; i++) {
					var col_tmp = data.columns[i];
					var col = { name: col_tmp.name, type: col_tmp.type };
					if (col.type == "integer") col.type = "number";
					else if (col.type == "string") col.type = "text";
					else if (col.type == "date" || col.type == "datetime" || col.type == "time") {
						col.type = "datetime";
						col.options = JSON.stringify({subtype: col_tmp.type});
					}
					var isSystem = false;
					for (var j = 0; j < systemTables.length; j++) if (systemTables[j] == col.name) isSystem = true;
					if (isSystem == false || (chkSystem)) addField(col);
				}
			}
		}
	});
}

function selectType(template, selection) {
	console.log("selectType(template, selection) template - ", template,", selection - ", selection);
	$(template).find(".options").find("[class^='opts']").hide();
	if (selection != "") selection = selection[0].toUpperCase()+selection.substr(1);
	$(template).find(".options").find("[class^='opts"+selection+"']").show();
}

function swapElements(obj1, obj2) {
	// save the location of obj2
	var parent2 = obj2.parentNode;
	var next2 = obj2.nextSibling;
	// special case for obj1 is the next sibling of obj2
	if (next2 === obj1) {
		// just put obj1 before obj2
		parent2.insertBefore(obj1, obj2);
	} else {
		// insert obj2 right before obj1
		obj1.parentNode.insertBefore(obj2, obj1);

		// now insert obj1 where obj2 was
		if (next2) {
			// if there was an element after obj2, then insert obj1 right before that
			parent2.insertBefore(obj1, next2);
		} else {
			// otherwise, just append as last child
			parent2.appendChild(obj1);
		}
	}
}

function updateFields() {
	console.log("updateFields() this - ",this);
	var fields = $("#formFields").find(".fieldTemplateClone");
	fields.each(function(index) {
		console.log("Updating field "+index+" of "+fields.length+", ordering buttons - ",$(this).find(".sort_asc").length);
		if (index == 0) $(this).find(".sort_asc").css("visibility","hidden");
		else $(this).find(".sort_asc").css("visibility","visible");
		if (index > 0 && index == fields.length-1) $(this).find(".sort_desc").css("visibility","hidden");
		else $(this).find(".sort_desc").css("visibility","visible");
		$(this).find(".sort_asc").off("click");
		$(this).find(".sort_desc").off("click");
		$(this).find(".delete").off("click");
		$(this).find(".duplicate").off("click");
		$(this).find(".sort_asc").on("click", function(e) {
			reorder(1, index);
		});
		$(this).find(".sort_desc").on("click", function(e) {
			reorder(-1, index);
		});
		$(this).find(".delete").on("click", function(e) {
			deleteField(index);
		});
		$(this).find(".duplicate").on("click", function(e) {
			duplicateField(index);
		});
	});
	
	fields.each(function(i, el) {
		//var el = this;
		///console.log(".fieldTemplate(i, el) i - "+i+", el - ", el);
		$(el).find("[name*='field']").each(function(i2, el2) {
			///console.log("  field(i2, el2) i - "+i2+", el2 - ", el2);
			el2.name = el2.name.replace(/\[\d*\]/, "["+i+"]");
			if (el2.id != "") {
				el2.id = el2.id.replace(/\d*$/, i);
				var lbl = $(el2).parent().find("label").first();
				lbl = $(el2).siblings("label").first();
				if (lbl.length == 0) lbl = $(el2).parent().parent().find("label").first();
				if (lbl.length == 0) lbl = $(el2).parent().parent().parent().find("label").first();
				///console.log("  lbl - ", lbl);
				///console.log("  lbl.text() - ", lbl.text());
				///console.log("  lbl.val() - ", lbl.val());
				///console.log("  for - ", lbl.attr("for"));
				lbl.attr("for", el2.id);
				///console.log("  for - ", lbl.attr("for"));
			}
		});
	});
}

$(document).ready(function() {
	$("#btnAddField").on("click", function (ev) { addField(); });
	
	$("input[type=checkbox]").iCheck("destroy");
	
	$("#submitUpdateCustomForm").on("click", function (ev) { 
		ev.preventDefault();
		if (checkForm()) $("#updateCustomForm").submit();
	});
	
});
</script>

<style type="">
#formFields {
    position: relative;
    display: block;
    /*padding: 20px 5px 20px 5px;
    margin: 20px;*/
    /*width: 200px;
    height: 200px;*/
    border: 1px solid black;
}
.fieldTemplateClone {
    position: relative;
    top: 0;
    display: table;
    /*margin-bottom: 2px;
    height: 25px;*/
    clear: both;
}
.fieldTemplate.drag { z-index: 99; color: red; }
</style>