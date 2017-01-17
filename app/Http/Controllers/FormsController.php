<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DistrictRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Form;  
use App\Http\Requests\FormsRequest;

class FormsController extends Controller {
	public function index() {
		$forms = Form::select(array('id','name','purpose','slug','created_at'));
		return \Datatables::of($forms)
			->addColumn('actions','<a class="btn btn-xs btn-alt" data-toggle="modal" onClick="launchUpdateFormModal({{$id}});" data-target=".modalEditForm">Edit</a>')
      	->make(true);
	}
	
	public function edit($id) {
    $form = Form::where('id',$id)->first();
   	return [$form];
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
