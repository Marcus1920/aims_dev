<?php
  $fieldTypes = array('choice','currency','date','file','multichoice','number','text','time');
?>
<div class="fieldTemplate" style="border-top: 2px dashed green; display: none; margin: 5px 20px; padding: 15px 0">
	<!--<hr style="width: 75%"><br>-->
	<div>
		{!! Form::label('Name', 'Name', array('class' => 'col-md-2 control-label')) !!}
		<div class="col-md-5">
		{!! Form::text('fieldName',NULL,['class' => 'form-control input-sm','id' => 'fieldName']) !!}
		</div>
		{!! Form::label('Type', 'Type', array('class' => 'col-md-2 control-label')) !!}
		{!! Form::select('fieldType',$fieldTypes, null,['class' => 'form-control select-sm','id' => 'fieldType', 'style'=>"width: 10em"]) !!}
	</div>
	<div><a class="btn btn-sm sort_asc">^</a><a class="btn btn-sm sort_desc">v</a></div>
</div>