<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\FormField;

class Form extends Eloquent {
	protected $table    = 'forms';
	protected $fillable = ['name','slug','active', 'purpose'];
	
	public function saveFields($req, $fff) {
		$form_id = $req['formId'];
		$fields = $req['field'];
		//echo "saveFields(req) form_id - {$form_id}, fields<pre>".print_r($fields, 1)."</pre>";
		//echo "  fullUrlWithQuery - <pre>".print_r($req->input(),1)."</pre>";
		\Session::flash('success', "saveFields(req)");
		\Session::flash('success', "fields<pre>".print_r($fields, 1)."</pre>");
		$saved = true;
		if (count($fields) > 0) foreach ($fields AS $i=>$field) {
			$field['form_id'] = $form_id;
			$field['order'] = $i;
			/*$fff->validate($field, [
				'name'=>"required"
			]);*/
			if (array_key_exists($field['type'], $field['opts'])) $field['options'] = json_encode($field['opts'][$field['type']]);
			/////$field = array('id'=>$field_id);
			/////if ($field_id == -1) $field = array('form_id'=>$form_id, 'name'=>$fields['name'][$i]);
			//if ($field_id == -1) $field = array('form_id'=>$form_id, 'name'=>$fields['name'][$i], 'type'=>$fields['type'][$i]);
			//$ff = FormField::where(array('form_id'=>$form_id))->first();
			$ff = FormField::where(array('id'=>$field['id']))->first();
			if (!$ff) $ff = new FormField();
			$ff->fill($field);
			///echo "ff - <pre>".print_r($ff, 1)."</pre>";
			$saved = $ff->save();
		}
		//parent::save();
		//die("Im dead");
		return $saved;
	}
}
