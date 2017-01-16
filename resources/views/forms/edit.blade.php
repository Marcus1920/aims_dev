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
	$(".modal-body #formId").val(id);
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
	//template.css("display: block !important");
	template.style.display = "block";
	$("#formFields").append(template);
	updateFields();
}

function updateFields() {
	var fields = $("#formFields").find(".fieldTemplate");
	fields.each(function(index) {
		console.log("Updating field "+index+" of "+fields.length);
		//if (index == 0) 
		$(this).find("a.sort_asc").css("display: none !important");
	});
}

$(document).ready(function() {
	$("#btnAddField").on("click", addField);
});
</script>