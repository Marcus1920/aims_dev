@extends('master')

@section('content')
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
		<table class="table tile table-striped" id="formsTable">
			<thead>
				<tr>
					<th style="width: 3em;">Id</th>
					<th>name</th>
					<th>Purpose</th>
					<th style="width: 3em;">Fields</th>
					<th>Actions</th>
				</tr>
			</thead>
		</table>
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
    $(document).ready(function() {

      $('#formsTable').DataTable({
                "processing": true,
                "serverSide": true,
                "dom": 'frtip',
                "order" :[[0,"desc"]],
                "ajax": "{!! url('/forms-list/')!!}",
                "columns": [

                    {data: 'id', name: 'forms.id'},
                    {data: 'name', name: 'forms.name'},
                    {data: 'purpose', name: 'forms.purpose'},
                    {data: 'cntFields', name: 'cntFields'},
                    {data: 'actions',  name: 'actions'}

               ],

            "aoColumnDefs": [
                { "bSearchable": false, "aTargets": [1] },
                { "bSortable": false, "aTargets": [4] }
            ]

         });
		});
		
		@if (count($errors) > 0)
			@if (Request::old("formId"))
				//alert("Launching update form for id <?php echo Request::old("formId"); ?>");
				$('#modalEditForm').modal('show');
				//launchUpdateFormModal(<?php echo Request::old("formId"); ?>);
				updateFields();
			@else
				$('#modalAddForm').modal('show');
			@endif
    @endif
</script>
<?php
	//echo "_REQUEST<pre>".print_r($_REQUEST, 1)."</pre>";
?>
@endsection
