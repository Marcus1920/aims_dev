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
                {!! Form::label('Name', 'Name', array('class' => 'col-md-2 control-label')) !!}
                <div class="col-md-10">
                  {!! Form::text('name',NULL,['class' => 'form-control input-sm','id' => 'name']) !!}
                  @if ($errors->has('name')) <p class="help-block red">*{{ $errors->first('name') }}</p> @endif
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('Purpose', 'Purpose', array('class' => 'col-md-2 control-label')) !!}
                <div class="col-md-10">
                  {!! Form::text('purpose',NULL,['class' => 'form-control input-sm','id' => 'purpose']) !!}
                  @if ($errors->has('purpose')) <p class="help-block red">*{{ $errors->first('purpose') }}</p> @endif
                </div>
            </div>
            
            <hr>
            <h3 class="block-title">Fields</h3>
            <a class="btn btn-sm" data-toggle="modal" data-target=".modalAddField" id="btnAddField">Add Field</a>
            <span id="cntFields">0</span>
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
function launchUpdateFormModal(id) {
	console.log("launchUpdateFormModal(id) id - "+id+", fields - ",$(".fieldTemplateClone").length);
	$(".modal-body #formId").val(id);
	//$("#formFields").remove(".fieldTemplate");
	$(".fieldTemplateClone").remove();
	$("#cntFields").text($(".fieldTemplateClone").length);
	cntFields = 0;
	$.ajax({
		type    :"GET",
		dataType:"json",
		url     :"{!! url('/forms/"+ id + "')!!}",
		success :function(data) {
			if(data[0] !== null) {
				$("#modalEditForm #name").val(data[0].name);
				$("#modalEditForm #purpose").val(data[0].purpose);
			}
			else {
				$("#modalEditForm #name").val('');
			}
		}
	});	
}

function addField() {
	cntFields++;
	$("#cntFields").text(cntFields);
	var template = $(".fieldTemplate").clone().get(0);
	template.className = "fieldTemplateClone";
	//template.css("display: block !important");
	template.style.display = "block";
	$("#formFields").append(template);
	updateFields();
	///$(template).on("mousedown", startDrag);
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
		$(this).find(".sort_asc").on("click", function(e) {
			reorder(1, index);
		});
		$(this).find(".sort_desc").on("click", function(e) {
			reorder(-1, index);
		});
		$(this).find(".delete").on("click", function(e) {
			deleteField(index);
			
		});
	});
}

$(document).ready(function() {
	$("#btnAddField").on("click", addField);
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