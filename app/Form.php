<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\FormField;

class Form extends Eloquent {
	protected $table    = 'forms';
	protected $fillable = ['name','slug','active', 'purpose'];
	
	public function saveFields($req) {
		$form_id = $req['formId'];
		$fields = $req['field'];
		///echo "saveFields(req) form_id - {$form_id}, fields<pre>".print_r($fields, 1)."</pre>";
		\Session::flash('success', "saveFields(req)");
		\Session::flash('success', "fields<pre>".print_r($fields, 1)."</pre>");
		$saved = true;
		foreach ($fields['id'] AS $i=>$field_id) {
			$field = array('id'=>$field_id);
			if ($field_id == -1) $field = array('form_id'=>$form_id, 'name'=>$fields['name'][$i]);
			//if ($field_id == -1) $field = array('form_id'=>$form_id, 'name'=>$fields['name'][$i], 'type'=>$fields['type'][$i]);
			//$ff = FormField::where(array('form_id'=>$form_id))->first();
			$ff = FormField::where($field)->first();
			if (!$ff) $ff = new FormField();
			$ff->fill(array('form_id'=>$form_id, 'label'=>$fields['label'][$i], 'name'=>$fields['name'][$i], 'order'=>$i, 'type'=>$fields['type'][$i]));
			///echo "ff - <pre>".print_r($ff, 1)."</pre>";
			$ff->save();
		}
		//parent::save();
		///die("Im dead");
		return $saved;
	}
}
