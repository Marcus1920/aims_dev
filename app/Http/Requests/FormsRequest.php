<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class FormsRequest extends Request {
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize() {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules() {
  	//echo "<pre>".print_r($_REQUEST, 1)."</pre>";
  	$rules = [
			'name'     =>'required|unique:forms,name,'.$_REQUEST['formId']
			//'field.name' =>'required'
			
    ];
    if ($this->request->get("field")) {
    	//echo "<pre>".print_r($this->request->get("field"), 1)."</pre>";
    	//$rules['field.*.name'] = "required|unique:forms_fields";
			foreach ($this->request->get("field") as $key=>$val) {
				//echo "field[$key]<pre>".print_r($val, 1)."</pre>";
				$rules['field.'.$key.'.name'] = "required|unique:forms_fields,name,".($val['id']?:"NULL").",id,form_id,".$_REQUEST['formId'];//.",id";
				//$rules['field['.$key.'][name]'] = "required|unique:forms_fields";
				//$rules['field.name'] = "required";
				//$rules['field.label'] = "required";
			}
    }
    //die("rules<pre>".print_r($rules, 1)."</pre>");
    return $rules;
  }
}
