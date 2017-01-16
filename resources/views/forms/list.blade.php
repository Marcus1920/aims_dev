<?php
  
?>
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
					<th>Id</th>
					<th>name</th>
					<th>Purpose</th>
					<th>Actions</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
@include('forms.field')
@include('forms.edit')
@include('forms.add')
@endsection
@section('footer')
<script>
    $(document).ready(function() {

      $('#formsTable').DataTable({
                "processing": true,
                "serverSide": true,
                "dom": 'Bfrtip',
                "order" :[[0,"desc"]],
                "ajax": "{!! url('/forms-list')!!}",
                "buttons": [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',{

                      extend : 'pdfHtml5',
                      title  : 'Siyaleader',
                      header : 'I am text in',
                    },
                ],
                 "columns": [

                    {data: 'id', name: 'forms.id'},
                    {data: 'name', name: 'forms.name'},
                    {data: 'purpose', name: 'forms.purpose'},
                    {data: 'actions',  name: 'actions'}

               ],

            "aoColumnDefs": [
                { "bSearchable": false, "aTargets": [0,3] },
                { "bSortable": false, "aTargets": [3] }
            ]

         });
		});
		
		@if (count($errors) > 0)
			$('#modalAddForm').modal('show');
    @endif
</script>
@endsection