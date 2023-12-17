<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\quizRoom;
use App\Models\QuestionBank;
use App\Models\ClassModel;
use App\Models\Subject;
use DataTables; 

class ManageLiveQuizController extends Controller
{
    public function quizRoomManage(Request $request){
        if($request->method() == 'GET'){
            return view('backend.liveQuiz.room.roomManage', []);
        }

        $validatedData = $request->validate([
            'room_name' => 'required',
            'prepration' => 'required',
            // 'class_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $message="Class Updated Successfully!!";

        if (isset($request->room_image) != null) {
            $filename = uniqid() . $request->room_image->getClientOriginalName();
            $path = 'images/'.$filename;
            $request->room_image->move(public_path('images'), $filename);
        }else{
                $path = $request->existing_room_image;
        }

        if (isset($request->room_banner) != null) {
            $filename2 = uniqid() . $request->room_banner->getClientOriginalName();
            $path2 = 'images/'.$filename2;
            $request->room_banner->move(public_path('images'), $filename2);
        }else{
                $path2 = $request->existing_room_banner;
        }
        
        if($request->roomId =='' || !$roomAdd=quizRoom::where('id', '=', $request->roomId)->first() ) {
                $roomAdd = new quizRoom();
                $message="Class Added Successfully!!";
        }
        $roomAdd->name=$request->room_name;
        $roomAdd->prepration=$request->prepration;
        $roomAdd->image=$path;
        $roomAdd->banner=$path2;
        $roomAdd->room_type=$request->room_type;
        $roomAdd->price=$request->price;
        $roomAdd->timeDurationPerQuestion='30';
        $roomAdd->added_by=Auth::guard('admin')->user()->id;
        $roomAdd->save();

        $callback=['load_data'];
        $closedPopup='myModal';
        return response()->json(["statuscode" => '201', "message" => $message, "actionType"=>"003", "callback"=>$callback, 'closedPopup'=>$closedPopup]);
    }

    public function quizRoomList(Request $request){
        try{

            $classes = quizRoom::query();
            return DataTables::eloquent($classes)
            ->addIndexColumn()
            ->addColumn('status', function ($data) use($request) {
                if ($data->status == '1') {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-success" style="text-decoration:none;" data="' . route($request->routePath.'.quizRoomStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >Active</a>';
                } else {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-danger" style="text-decoration:none;" data="' . route($request->routePath.'.quizRoomStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >In-Active</a>';
                }
                return $status;
            })
            ->addColumn('action', function($data) use($request) {
                $button='<center>';
                $button .= '<a href="javascript:void(0);" onClick="editQuizRoom(' . htmlentities(json_encode($data)) . ')" class="edit_employee" data="' . route($request->routePath.'.quizRoomManage') . '" id="' . $data->id . '"><i class="fas fa-edit bg-light" style="font-size:20px; color:green;"></i></a>';
                $button.='&#160;&#160;&#160;&#160;';
                $button.='  <a href="javascript:void(0);" class="delete2023" url="'.route($request->routePath.'.quizRoomDelete').'" id="'.$data->id.'"  ><i class="fas fa-trash-alt bg-light" style="font-size:20px;color:red;"></i></a>';
                $button.='</center>';
                return $button;
            })
            ->addColumn('assign', function($data) use($request){
                return '<a href="'.route($request->routePath.'.quizQuestionManage', ['id'=>base64_encode($data->id)]).'" class="btn btn-info" >Assign Question</a>';
            })

            ->addColumn('totalQuestion', function($data){
                $question=$data->question_id == null || $data->question_id == '' ? [] : json_decode($data->question_id);
                $totalQuestion=count($question);  
                return $totalQuestion;
            })
            ->addColumn('image', function($data){
                $url = $data->image;
                $img='<a href="'.$url.'" target="_blank" class=""><img src="'.$url.'" width="40px" height="40px" class="img-rounded" align="center" alt="file" /></a>';
                return $img;
            })
            ->rawColumns(array("status", "image", "action", "assign", "totalQuestion"))
            ->make(true);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    public function quizRoomStatus(Request $request){
        $record = quizRoom::find($request->id);
        $record->status = $request->status;
        $record->save();
        return response()->json(["statuscode" => '001', "message" => 'Status Updated Successfully!!' ]);
    }

    public function quizRoomDelete(Request $request){
        $record = quizRoom::find($request->id);
        $record->delete();
        $record->save();
        return response()->json(["statuscode" => '001', "message" => 'Record Deleted Successfully!!' ]);
    }


    // -------------------- start Quiz Question Manage- -----------------------

    public function quizQuestionManage(Request $request){
        if($request->method() == 'GET'){
            $classModel=ClassModel::where('status','1')->get();
            $subject=Subject::with('class')->where('status', '=', '1')->get();

            $roomInfo = quizRoom::find(base64_decode($request->id));
            // dd($roomInfo);
            $question=$roomInfo->question_id == null || $roomInfo->question_id == '' ? [] : json_decode($roomInfo->question_id);
            $totalQuestion=count($question);  

            return view('backend.liveQuiz.room.quizQuestionManage', ['classModel'=>$classModel, 'subject'=>$subject, 'roomInfo'=>$roomInfo, 'totalQuestion'=>$totalQuestion]);
        }

        $roomInfo = quizRoom::find($request->id);
        $roomInfo->question_id=$request->quetionIdsArr;
        $roomInfo->save();

        return response()->json(["statuscode" => '201', "message" => 'Updated Successfully!', "actionType"=>"001"]);

    }

    public function quizQuestionList(Request $request){
        $roomInfo = quizRoom::find($request->roomId);
        $question=$roomInfo->question_id == null || $roomInfo->question_id == '' ? [] : json_decode($roomInfo->question_id);

        $questionBank=QuestionBank::with('subject', 'chapter', 'questionBankInfo');
        !empty($request->subject) ? $questionBank->where('subject_id','=',$request->subject) : '';
        !empty($request->chapter) ? $questionBank->where('chapter_id','=',$request->chapter) : '';
        !empty($request->questionType) ? $questionBank->where('question_type','=',$request->questionType) : '';
        !empty($request->languageType) ? $questionBank->where('language_type','=',$request->languageType) : '';
        !empty($request->questionLevel) ? $questionBank->where('level','=',$request->questionLevel) : '';

        !empty($request->selectionType) ? $request->selectionType =='Selected' ? $questionBank->whereIn('id',  json_decode($roomInfo->question_id) ) : $questionBank->whereNotIn('id',  json_decode($roomInfo->question_id) ) : '';

       //  $questionBank->que;


       return DataTables::eloquent($questionBank)
           ->addIndexColumn()
           ->addColumn('inputCheck', function ($data) use($question) {

                if (in_array($data->id, $question)) {
                    $input='<input type="checkbox" class="checkQuizRow" checked value="'.$data->id.'">';
                } else {
                    $input='<input type="checkbox" class="checkQuizRow" value="'.$data->id.'">';
                }
               
               return $input;
           })
           ->addColumn('paragraphInfo', function($data){
               $pra =json_decode($data->pragraph);
               $q=json_decode($data->questionBankInfo[0]['question']);
               $l=$data->language_type;
               // $englishData = strip_tags($pra->English);
               // $englishData =$data->question_type =='Group' ? json_encode($pra->English) : '';
               $englishData = $data->question_type =='Group' ? ($l =='Both' ? $pra->English : $pra->$l) : ($l =='Both' ? $q->English : $q->$l) ;
               return $englishData;
           })

           ->addColumn('lang', function($data){
               $button='<center>';
               
               $button.=$data->language_type == 'Both' ? 'English, Hindi' : $data->language_type;
               $button.='</center>';
               return $button;
           })
      
           ->rawColumns(array( "lang", "inputCheck", "paragraphInfo", "status",  "action"))
           ->make(true);
    }
}
