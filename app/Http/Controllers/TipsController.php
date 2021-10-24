<?php

namespace App\Http\Controllers;

use App\Functions\Algoritms;
use App\Functions\Settings;
use App\Models\Tips;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipsController extends Controller
{
    private $algoritms;
    private $settings;
    public function __construct(){
        $this->algoritms=new Algoritms();
        $this->settings=new Settings();
    }

    public function InsertTip(Request $request){

        //todo if we have user to call api , then we must have api_token
        $this->algoritms->discreate_key($request->post('key'));

        $guid=$request->post('guid');
        $title=$request->post('title');
        $description=$request->post('description');

        if (strlen($guid) <= 256 && strlen($description) <= 256 && strlen($title) <= 100
            && !empty($guid) && !empty($description) && !empty($title)
            ){

            $tip = new Tips();
            $tip->guid = $guid;
            $tip->description = $description;
            $tip->title = $title;
            if ($tip->save()){
                $response['status'] = 'ok';
                return json_encode($response);
            }else{
                $response['status'] = 'error';
                return json_encode($response);
            }

        }else{
            $response['status'] = 'the parameter not valid';
            return json_encode($response);
        }

    }

    public function UpdateTip(Request $request){

        //todo if we have user to call api , then we must have api_token
        $this->algoritms->discreate_key($request->post('key'));



        $tip_id = $this->algoritms->discreate_id($request->post('tip_id'));
        $guid=$request->post('guid');
        $title=$request->post('title');
        $description=$request->post('description');

        $tip=Tips::where('id','=',$tip_id);
        $tip_row = $this->algoritms->check_exist_row($tip);

        if (strlen($guid) <= 256 && strlen($description) <= 256 && strlen($title) <= 100
            && !empty($guid) && !empty($description) && !empty($title)
            ){

            $tip = Tips::find($tip_id);

            $tip->guid = $guid;
            $tip->description = $description;
            $tip->title = $title;

            if ($tip->save()){
                $response['status'] = 'ok';
                return json_encode($response);
            }else{
                $response['status'] = 'error';
                return json_encode($response);
            }

        }else{
            $response['status'] = 'the parameter not valid';
            return json_encode($response);
        }

    }

    public function DeleteTip(Request $request){

        //todo if we have user to call api , then we must have api_token
        $this->algoritms->discreate_key($request->post('key'));

        $tip_id = $this->algoritms->discreate_id($request->post('tip_id'));

        $tip=Tips::where('id','=',$tip_id);
        $tip_row = $this->algoritms->check_exist_row($tip);


        $tip = Tips::find($tip_id);

        $tip->delete_flag = 1;
        $tip->deleted_at = date('Y-m-d h:i:s');

        if ($tip->save()){
            $response['status'] = 'ok';
            return json_encode($response);
        }else{
            $response['status'] = 'error';
            return json_encode($response);
        }

    }

    public function GetOneTip(Request $request){

        $this->algoritms->discreate_key($request->post('key'));

        $tip_id = $this->algoritms->discreate_id($request->post('tip_id'));

        $tip=Tips::select('guid','title','description')
        ->where('id','=',$tip_id)
        ->where('delete_flag','=','0');
        $tip_row = $this->algoritms->check_exist_row($tip);

        return json_encode($tip_row);
    }

    public function GetAllTip(Request $request){

        $this->algoritms->discreate_key($request->post('key'));

//**************** paging the tips for decrease time of query and little response json ***************//
        if (isset($_POST['page']) && !empty($_POST['page'])) {
            $page = $_POST['page'];
        } else {
            $page = 1;
        }

        $tips_row=Tips::select('id','guid','title','description')
            ->where('delete_flag','=','0')
            ->paginate($this->settings->get_limit(), ['*'], 'page', $page  );

        $res['page'] = $tips_row->lastPage() ;
        $list = $tips_row->items();

        foreach ($list as $row) {
            $row->tip_id=$this->algoritms->create_id($row->id);
            unset($row->id);
        }
        $res['list']=$list;

        return json_encode($res);

    }

}
