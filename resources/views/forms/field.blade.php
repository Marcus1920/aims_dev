<?php
  //$fieldTypes = array('boolean'=>"Boolean",'choice'=>"Choice",'currency'=>"Currency",'date'=>"Date", 'datetime'=>"Date & Time",'file'=>"File",'multichoice'=>"Multichoice",'number'=>"Number",'text'=>"Text",'time'=>"Time");
  $types = array();
  $types['field'] = array(''=>"-- Select --",'boolean'=>"Boolean",'choice'=>"Choice",'currency'=>"Currency", 'datetime'=>"Date & Time",'file'=>"File",'number'=>"Number",'text'=>"Text");
  $types['boolean'] = array(''=>"-- Select --",'checkbox'=>"Checkbox", 'select'=>"Dropdown", 'radio'=>"Radio Buttons");
  //foreach ($types AS &$types2) array_unshift($types2, array(''=>"-- Select --"));
?>
<div class="fieldTemplate" style="border-top: 2px dashed green; display: none; height: 12em; margin: 5px 20px; padding: 15px 0">
	<div class="wSort">
		<a class="btn btn-sm sort_asc" title="Move field up"><img src="{{asset('images/icon_order_up.png')}}"></a>
		<a class="btn btn-sm sort_desc" title="Move field down"><img src="{{asset('images/icon_order_down.png')}}"></a>
		<a class="btn btn-sm delete" title="Delete field"><img src="{{asset('images/icon_delete.png')}}"></a>
		<a class="btn btn-sm duplicate" title="Duplicate field"><img src="{{asset('images/icon_duplicate.png')}}"></a>
		</div>
	<!--<hr style="width: 75%"><br>-->
	<div>
		{!! Form::text('field[][id]',NULL,['class' => 'form-control input-sm','id' => 'fieldId']) !!}
		<div style="" class="col-md-6">
			<div style="clear: both;" class="form-group">
				{!! Form::label('fieldName', 'Name', array('class' => 'col-md-3 control-label')) !!}
				<div class="col-md-9">
				{!! Form::text('field[][name]',NULL,['class' => 'form-control input-sm','id' => 'fieldName']) !!}
				</div>
			</div>
			<div style="clear: both;" class="form-group">
				{!! Form::label('fieldLabel', 'Label', array('class' => 'col-md-3 control-label', 'style'=>"float: left")) !!}
				<div class="col-md-9">
				{!! Form::text('field[][label]',NULL,['class' => 'form-control input-sm','id' => 'fieldLabel']) !!}
				</div>
			</div>
			<div style="clear: both;" class="form-group">
				{!! Form::label('fieldDesc', 'Description', array('class' => 'col-md-3 control-label', 'style'=>"float: left")) !!}
				<div class="col-md-9">
				{!! Form::textarea('field[][desc]',NULL,['class' => 'form-control input-sm','id' => 'fieldDesc', 'rows'=>2]) !!}
				</div>
			</div>
		</div>
		<div class="col-md-6" style="position: relative; white-space: nowrap;">
		{!! Form::label('Type', 'Type', array('class' => 'col-md-4 control-label')) !!}
		{!! Form::select('field[][type]',$types['field'], "",['class' => 'form-control select-sm','id' => 'fieldType', 'style'=>"width: 10em"]) !!}
			<div class="options" style="">
				{!! Form::label('Options', 'Options', array('class' => 'col-md-2 control-label')) !!}
				<div class="optsBoolean" style="clear: both; margin-left: 1em;">
					<div class="form-group">
						{!! Form::label('txtFalse', 'False Option', array('class' => 'col-md-4 control-label')) !!}
						{!! Form::text('field[][opts][boolean][false]',"No",['class' => 'form-control input-sm','id'=>'txtFalse', 'style'=>"width: 10em !important", 'maxlength'=>""]) !!}
					</div>
					<div class="form-group">
						{!! Form::label('txtTrue', 'True Option', array('class' => 'col-md-4 control-label')) !!}
						{!! Form::text('field[][opts][boolean][true]',"Yes",['class' => 'form-control input-sm','id'=>'txtTrue', 'style'=>"width: 10em !important", 'maxlength'=>""]) !!}
					</div>
					<div>
						{!! Form::label('selTypeBool', 'Type', array('class' => 'col-md-4 control-label')) !!}
						{!! Form::select('field[][opts][boolean][type]',$types['boolean'], "",['class' => 'form-control select-sm','id' => 'selTypeBool', 'style'=>"width: 10em"]) !!}
					</div>
				</div>
				<div class="optsChoice" style="clear: both; margin-left: 1em;">
					<div style="clear: both;">
						{!! Form::label('chkMultichoice', 'Multiple', array('class' => 'col-md-4 control-label')) !!}
						{!! Form::checkbox('field[][opts][choice][multi]',1, false,['id'=>'chkMultichoice', 'style'=>"padding-top: 10px;opacity: 1"]) !!}
					</div>
					<div style="clear: both;">
						<div id="optsChoices">
						
						</div>
						<a class="btn btn-sm" id="btnAddChoice">Add Choice</a>
					</div>
				</div>
				<div class="optsCurrency" style="clear: both; margin-left: 1em;">
					&nbsp;
				</div>
				<div class="optsDatetime" style="clear: both; margin-left: 1em;">
					<div>
						{!! Form::label('chkDateTimeSubA', 'Date & Time', array('class' => 'col-md-4 control-label', 'style'=>"clear: both; float: left;")) !!}
						<div style="padding-top: 7px;">
						{!! Form::radio('field[][opts][datetime][subtype]',"datetime", false,['class' => 'form-control input-sm','id'=>'chkDateTimeSubA', 'style'=>"opacity: 1; width: 4em !important", 'maxlength'=>"3"]) !!}
						</div>
						&nbsp;
					</div>
					<div>
						{!! Form::label('chkDateTimeSubB', 'Date Only', array('class' => 'col-md-4 control-label', 'style'=>"clear: both; float: left; ")) !!}
						<div style="padding-top: 7px;">
						{!! Form::radio('field[][opts][datetime][subtype]',"date", false,['class' => 'form-control input-sm','id'=>'chkDateTimeSubB', 'style'=>"width: 4em !important", 'maxlength'=>"3"]) !!}
						</div>
						&nbsp;
					</div>
					<div>
						{!! Form::label('chkDateTimeSubC', 'Time Only', array('class' => 'col-md-4 control-label', 'style'=>"clear: both; float: left; ")) !!}
						<div style="padding-top: 7px;">
						{!! Form::radio('field[][opts][datetime][subtype]',"time", false,['class' => 'form-control input-sm','id'=>'chkDateTimeSubC', 'style'=>"width: 4em !important;", 'maxlength'=>"3"]) !!}
						</div>
						&nbsp;
					</div>
				</div>
				<div class="optsFile" style="clear: both; margin-left: 1em;">
					<div>
						{!! Form::label('txtFileMax', 'Max Size', array('class' => 'col-md-4 control-label')) !!}
						{!! Form::text('field[][opts][file][maxsize]',"0",['class' => 'form-control input-sm','id'=>'txtFileMax', 'style'=>"float: left; width: 5em !important", 'maxlength'=>"", 'title'=>"Kilobytes"]) !!}
					</div>
				</div>
				<div class="optsNumber" style="clear: both; margin-left: 1em;">
					<div>
						{!! Form::label('txtDecimals', 'Decimals', array('class' => 'col-md-4 control-label')) !!}
						{!! Form::text('field[][opts][number][decimals]',"0",['class' => 'form-control input-sm','id'=>'txtDecimals', 'style'=>"opacity: 1; width: 4em !important", 'maxlength'=>"3"]) !!}
					</div>
					<div>
						{!! Form::label('txtMin', 'Min', array('class' => 'col-md-4 control-label', 'style'=>"clear: both")) !!}
						{!! Form::text('field[][opts][number][min]',"0",['class' => 'form-control input-sm','id'=>'txtMin', 'style'=>"width: 5em !important", 'maxlength'=>"-1"]) !!}
					</div>
					<div>
						{!! Form::label('txtMax', 'Max', array('class' => 'col-md-4 control-label', 'style'=>"clear: both")) !!}
						{!! Form::text('field[][opts][number][max]',"0",['class' => 'form-control input-sm','id'=>'txtMax', 'style'=>"width: 5em !important", 'maxlength'=>"-1"]) !!}
					</div>
				</div>
				<div class="optsText" style="clear: both; margin-left: 1em;">
					<div>
						{!! Form::label('txtLines', 'Lines', array('class' => 'col-md-4 control-label')) !!}
						{!! Form::text('field[][opts][text][lines]',"1",['class' => 'form-control input-sm','id'=>'txtLines', 'style'=>"width: 4em !important", 'maxlength'=>"3"]) !!}
					</div>
					<div>
						{!! Form::label('txtMinLen', 'Min', array('class' => 'col-md-4 control-label')) !!}
						{!! Form::text('field[][opts][text][min]',"1",['class' => 'form-control input-sm','id'=>'txtMinLen', 'style'=>"width: 4em !important", 'maxlength'=>"3"]) !!}
					</div>
					<div>
						{!! Form::label('txtMaxLen', 'Max', array('class' => 'col-md-4 control-label')) !!}
						{!! Form::text('field[][opts][text][max]',"255",['class' => 'form-control input-sm','id'=>'txtMaxLen', 'style'=>"width: 5em !important", 'maxlength'=>"5"]) !!}
					</div>
				</div>
				<br>
				<br>
			</div>
		</div>
	</div>
</div>