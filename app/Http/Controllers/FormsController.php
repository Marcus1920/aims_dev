<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DistrictRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Form;  
use App\FormField;  
use App\Http\Requests\FormsRequest;
use Illuminate\Support\Facades\DB as DB;

class FormsController extends Controller {
	public function index() {
		//$cntFields = FormField::select("count id")->where("form_id = 2");
		//$forms = Form::select(array('id','name','purpose','slug','created_at', Form::raw('1 AS cntFields')));
		///$forms = Form::select(array(Form::raw('1 as cntFields')));
		//$forms = Form::select(array('forms.id','forms.name','forms.purpose','forms.slug','forms.created_at', Form::raw('1 AS cntFields')));
		//->leftJoin("forms_fields","forms_fields.form_id", "=", "forms.id");//->leftJoinWhere("forms_fields", "forms.id", "=", "forms_fields.form_id");
		$forms = Form::select("forms.*", DB::raw('COUNT(forms_fields.id) as cntFields'))
			->leftJoin("forms_fields", "forms.id", "=", "forms_fields.form_id")
			->groupBy("forms.id");
		//\Session::flash('success', "SQL - ".$forms->toSql());
		return \Datatables::of($forms)
			->addColumn('actions','<a class="btn btn-xs btn-alt" data-toggle="modal" onClick="launchUpdateFormModal({{$id}});" data-target=".modalEditForm">Edit</a>')
      	->make(true);
	}
	
	public function edit($id) {
    $form = Form::where('id',$id)->first();
    $fields = FormField::select("*")->where('form_id', $id)->get();
    //echo "\$form<pre>".print_r($form, 1)."</pre>";
    //echo "\$fields<pre>".print_r($fields, 1)."</pre>";
   	return [$form, $fields];
  }
  
  public function store(FormsRequest $request) {
  	$form             = new Form();
		$form->name       = $request['name'];
		$form->slug       = $request['slug'];
		$form->purpose   = $request['purpose'];
		$form->created_by = \Auth::user()->id;
		$form->save();
		\Session::flash('success', $request['name'].' form has been successfully added!');
		return redirect()->back();
	}
  
  public function update(FormsRequest $request) {
  	$form               = Form::where('id',$request['formId'])->first();
    $form->name         = $request['name'];
    $form->purpose   = $request['purpose'];
    $form->updated_by   = \Auth::user()->id;
    $form->save();
    $form->saveFields($request);
    \Session::flash('success', 'well done! Form '.$request['name'].' has been successfully updated!');
    //\Session::flash('success', "REQUEST<pre>".print_r($request, 1)."</pre>");
    return redirect()->back();
  }
}
?>
