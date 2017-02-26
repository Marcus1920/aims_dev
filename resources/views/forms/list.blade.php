@extends('master')

@section('content')
<ol class="breadcrumb hidden-xs">
    <li><a href="#">Administration</a></li>
    <li><a href="{{ url('list-forms') }}">Forms</a></li>
    <li class="active">Forms Listing</li>
</ol>

<h4 class="page-title">Forms</h4>
<div class="block-area" id="alternative-buttons">
	<h3 class="block-title">Forms Listing</h3>
	<a class="btn btn-sm" data-toggle="modal" data-target=".modalAddForm">
     Add Form
    </a>
</div>

<div class="block-area" id="responsiveTable">
	<div class="table-responsive overflow">
		@if(Session::has('success'))
      <div class="alert alert-success alert-icon">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ Session::get('success') }}
        <i class="icon">&#61845;</i>
      </div>
    @endif
    {!! Form::open(['url' => 'list-formsdata', 'method' => 'post', 'class' => 'form-horizontal', 'id'=>"listForm" ]) !!}
		<table class="table tile table-striped" id="formsTable">
			<thead>
				<tr>
					<th style="width: 3em;">Id</th>
					<th>name</th>
					<th>Purpose</th>
					<th style="width: 3em;">Fields</th>
					<th style="width: 3em;">Items</th>
					<th>Actions</th>
				</tr>
			</thead>
		</table>
		{!! Form::hidden('form_id',NULL,['id' => 'formId']) !!}
		{!! Form::close() !!}
	</div>
</div>

@include('forms.preview')
@include('forms.field')
@include('forms.edit')
@include('forms.add')
@endsection
@section('footer')
<?php
	/*if (count($errors) > 0) {
		echo "request<pre>".print_r(Request::all(), 1)."</pre>";
		echo "errors<pre>".print_r($errors, 1)."</pre>";
		die("Dyind from errors");
	}*/
?>
<script>
	function doAction(el, id) {
		var action = el.options[el.selectedIndex].value;
		console.log("doAction(el, id) id - ",id,", action - ",action,", el - ",el);
		if (action == "edit") {
			$(".modalEditForm").modal();
			launchUpdateFormModal(id, true);
		} else if (action == "preview") {
			$(".modalPreviewForm").modal();
			launchPreviewFormModal(id);
		} else if (action == "manage") {
			console.log(this);
			//return redirect("list-formsdata");
			$("#formId").val(id);
			$("#listForm").submit();
		}
		el.selectedIndex = 0;
	}

    $(document).ready(function() {

      $('#formsTable').DataTable({
                "processing": true,
                "serverSide": true,
                "dom": 'frtip',
                "order" :[[0,"desc"]],
                ajax: {
									url: "{!! url('/forms-list/')!!}"
									, complete: function() {
										
									}
									, data: function(d) {

									}
				        },
                "columns": [

                    {data: "id", name: "forms.id"},
                    {data: "name", name: "forms.name"},
                    {data: "purpose", name: "forms.purpose"},
                    {data: "cntFields", name: "cntFields", className: "cntFields"},
                    {data: "cntData", name: "cntData", className: "cntData"},
                    {data: "actions",  name: "actions"}

               ],

            "aoColumnDefs": [
                { "bSearchable": false, "aTargets": [3, 4] },
                { "bSortable": false, "aTargets": [5] }
            ]

         });
		});
		
		@if (count($errors) > 0)
			@if (Request::old("formId"))
				//alert("Launching update form for id <?php echo Request::old("formId"); ?>");
				$('#modalEditForm').modal('show');
				//launchUpdateFormModal(<?php echo Request::old("formId"); ?>, false);
				updateFields();
			@else
				$('#modalAddForm').modal('show');
			@endif
		@elseif (isset($id))
    	$('#modalEditForm').modal('show');
    	launchUpdateFormModal({{$id}});
    @endif
</script>
@endsection
