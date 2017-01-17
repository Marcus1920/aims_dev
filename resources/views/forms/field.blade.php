<?php
  $fieldTypes = array('choice'=>"Choice",'currency'=>"Currency",'date'=>"Date",'file'=>"File",'multichoice'=>"Multichoice",'number'=>"Number",'text'=>"Text",'time'=>"Time");
?>
<div class="fieldTemplate" style="border-top: 2px dashed green; display: none; margin: 5px 20px; padding: 15px 0">
	<div class="wSort">
		<a class="btn btn-sm sort_asc"><img src="{{asset('images/icon_order_up.png')}}"></a>
		<a class="btn btn-sm sort_desc"><img src="{{asset('images/icon_order_down.png')}}"></a>
		<a class="btn btn-sm delete"><img src="{{asset('images/icon_delete.png')}}"></a>
		</div>
	<!--<hr style="width: 75%"><br>-->
	<div>
		{!! Form::hidden('field[id][]',-1,['class' => 'form-control input-sm','id' => 'fieldId']) !!}
		<div>
			<div style="clear: both;">
				{!! Form::label('Name', 'Name', array('class' => 'col-md-2 control-label')) !!}
				<div class="col-md-5">
				{!! Form::text('field[name][]',NULL,['class' => 'form-control input-sm','id' => 'fieldName']) !!}
				</div>
			</div>
			<div style="clear: both;">
				{!! Form::label('Label', 'Label', array('class' => 'col-md-2 control-label')) !!}
				<div class="col-md-5">
				{!! Form::text('field[label][]',NULL,['class' => 'form-control input-sm','id' => 'fieldLabel']) !!}
				</div>
			</div>
		</div>
		<div>
		{!! Form::label('Type', 'Type', array('class' => 'col-md-2 control-label')) !!}
		{!! Form::select('field[type][]',$fieldTypes, null,['class' => 'form-control select-sm','id' => 'fieldType', 'style'=>"width: 10em"]) !!}
		</div>
	</div>
</div>