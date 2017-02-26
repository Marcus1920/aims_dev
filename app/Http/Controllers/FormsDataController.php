<?php
	namespace App\Http\Controllers;
	
	use App\Http\Controllers\Controller;
	use App\Form;
	use App\FormField;
	use App\FormsData;
	use App\Http\Requests\FormsRequest;
	
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\DB as DB;
	
	//use yajra\Datatables\Datatables;
	
  class FormsDataController extends Controller {
  	public $forms = array();
  	
  	/*public function __construct() {
			
  	}*/
  	
  	public function getData($req = array()) {
  		$txtDebug = "FormsDataController->getData(\$req = array()) \$req - ".print_r($req, 1).", \n\$_REQUEST - ".print_r($_REQUEST,1);
  		$search = $_REQUEST['search']['value'];
  		$query = FormsData::select(
  			"forms_data.id", 
  			DB::raw("DATE_FORMAT(forms_data.created_at, '%a, %d %b %Y<br>at %H:%i') AS created_att"), 
  			"form_id", 
  			"forms.name",
  			"forms_data.data",
  			DB::raw("CONCAT(forms.name,' (', form_id, ')') AS title")
  			)->leftJoin("forms", "forms.id", "=", "forms_data.form_id");
  			///if ($search) $query->havingRaw("created_att like '%{$search}%'");
  		//$query = FormsData::select(["forms_data.id", "form_id", "forms.name",DB::raw("forms.name AS title"),"forms_data.created_at", "forms_data.data"])->leftJoin("forms", "forms.id", "=", "forms_data.form_id");
  		//$query = $query->where(implode(", ", $req));
  		$txtDebug .= "\n  \$query - {$query->toSql()}";
  		$data = $query->get();
  		//$txtDebug .= "\n  \$data - ".print_r($data->toArray(), 1);
			//die("<pre>{$txtDebug}</pre>");
			/*return \Datatables::of($query)
				->addColumn('tits','WWWW')
				->addColumn('action','
		      <div class="col-md-2">
		          <select onchange="doAction(this,{{$id}});" class="form-control input-sm selFormOptions">
		              <option value="0">Select</option>
		              <option value="edit">Edit</option>
		              <option value="preview">Preview</option>
		              <option value="dataedit">Edit Data</option>
		              <option value="manage">Manage Data</option>
		              <option value="dataview">View Data</option>
		          </select>
		      </div>')*/
				/*->filterColumn('tits', function($query, $keyword) {
        	$query->whereRaw("CONCAT(forms.name,' (', form_id, ')') like ?", ["%{$keyword}%"]);
        })*/
        /*->filter(function ($query) use ($request) {
            if ($request->has('title')) {
                $query->where('name', 'like', "%{$request->get('title')}%");
            }
        })*/
        $datatables = \Datatables::of($query);
        //->filterColumn('title', 'whereRaw', "CONCAT(forms.name,' (', form_id, ')') like ? ", ["$search"]);
        ///$datatables->filterColumn("created_att", "whereRaw", "DATE_FORMAT(forms_data.created_at, '%a, %d %b %Y<br>at %H:%i') = '%34%'");
        $datatables->addColumn('actions','
	      <div class="col-md-2">
	          <select onchange="doAction(this,{{$id}},{{$form_id}});" class="form-control input-sm selFormOptions">
	              <option value="0">Select</option>
	              <option value="edit">Edit</option>
	              <option value="view">View</option>
	              <option value="editform">Edit Form</option>
	          </select>
	      </div>');
				return $datatables->make(true);
  	}
  	
  	public function edit($id, $form_id = -1) {
  		$txtDebug = "FormsDataController->edit(\$id, \$form_id) \$id - {$id}, \$form_id - {$form_id}";
  		
			if ($id != -1) $formdata = FormsData::where('id',$id)->first()->toArray();
			else $formdata = new FormsData(array('form_id'=>$form_id));
			$txtDebug .= "\n  \$formdata - ".print_r($formdata, 1)."";
			//die("<pre>{$txtDebug}</pre>");
			$form = Form::where('id',$formdata['form_id'])->first()->toArray();
			$fields = FormField::where('form_id',$formdata['form_id'])->orderBy("order")->get()->toArray();
			$formdata['form'] = $form;
			$data = json_decode($formdata['data']);
			$txtDebug .= "\n  \$data - ".print_r($data, 1)."";
			$txtDebug .= "\n  \$formdata - ".print_r($formdata, 1)."";
			$txtDebug .= "\n  \$fields - ".print_r($fields, 1)."";
			
			//die("<pre>{$txtDebug}</pre>");
			return [$form, $fields, $data];
  	}
  	
  	/**
  	* put your comment there...
  	* 
  	* @param FormsRequest $request
  	* 
  	* @return Response
  	*/
  	public function update(Request $request) {
  		//$input = Input::all();
  		$input = $request;
  		$txtDebug = "FormsDataController->update(FormsRequest \$request) \$request - ".print_r($request->all(), 1);
  		//$txtDebug = "FormsDataController->update(FormsRequest \$request) \$request - ".print_r($request, 1).", \$input - ".print_r($input,1);
  		//die("<pre>{$txtDebug}</pre>");
  		$id = $input['id'];
  		$form_id = $input['formId'];
  		if ($id != -1) $formdata = FormsData::where('id',$id)->first();//->toArray();
  		else $formdata = new FormsData(array('form_id'=>$form_id));
  		$data = json_encode($input['data']);
  		$formdata->data = $data;
  		$txtDebug .= "\n  \$formdata - ".print_r($formdata,1);
  		$txtDebug .= "\n  \$data - ".print_r($data,1);
  		//die("<pre>{$txtDebug}</pre>");
  		if ($formdata->save()) \Session::flash('success', 'well done! Form Data has been successfully updated!');
  		else \Session::flash('failure', 'Whoops! Error updating Form Data');
  		
  		return redirect()->back()->withInput();
		}
		
  	public function anyFormId($form_id = -1) {
  		//echo "FormsDataController->anyId(\$form_id = -1) \$form_id - {$form_id}";
			return self::anyIndex($form_id);
  	}
  	
  	public function anyIndex($form_id = -1) {
			$results = DB::table("forms")->select("id","name", "slug")->get();
			$forms = array('-1'=>"Select a form");
			foreach ($results AS $res) {
				$cnt = DB::table("forms_data")->where("form_id",$res->id)->count();
				//echo "\$cnt - <pre>".print_r($cnt,1)."</pre>";
				$forms[$res->id] = $res->name." ({$cnt})";
			}
			if ($form_id == -1 && Input::get("form_id") != -1) $form_id = Input::get("form_id");
			/*echo "FormsDataController->anyIndex(\$form_id = -1) \$form_id - {$form_id}";
			die("<pre>".print_r(Input::all(),1)."</pre>");*/
			return view("forms.data.list", ['forms'=>$forms, 'form_id'=>$form_id]);
  	}
  	
	}
?>
