<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Chapter;
use DataTables;

class SubjectController extends Controller
{
    //TO VIEW AND ADD THE SUBJECTS AND CHAPTERS
    public function manageSubject(Request $request){
        if($request->method() == 'GET'){
            $classCategory = ClassModel::where('status', 1)->get();
            return view('backend.subject.subjectsList', ['classCategory'=>$classCategory]);
        }

        try{
            // if($request->subjectId !='' && !$subject = Subject::where('id', '=', $request->subjectId)->first() ) {
            //     $subject = new Subject();
            // }
            $validatedData = $request->validate([
                'class' => 'required',
                'subjectName.*' => 'required|string|max:255',
                // 'chapters.*' => 'required|string',
            ]);
            DB::beginTransaction();
            if($request->subjectName !=null){
                $subKey = 0;
                foreach ($request->subjectName as $key => $sub) {

                    if($request->subjectId =='' || !$subject=Subject::where('id', '=', $request->subjectId)->first() ) {
                        $subject = new Subject();
                    }

                    $subject->class_id = $request->class;
                    $subject->name = $sub;
                    $subject->added_by = Auth::guard('admin')->user()->id;
                    $subject->save();

                    $subArray = explode(",", $request->chapters[$subKey +1]);

                    if($request->subjectId !=''){
                        $existingTags = Chapter::where('subject_id', $subject->id)
                        ->pluck('name')
                        ->all();
    
                        // Remove any deleted tags from the database
                        $deletedTags = array_diff($existingTags, $subArray);
                        // echo json_encode($existingTags);
                        // echo json_encode($subArray);
                        // echo json_encode($deletedTags);
                       $as= Chapter::whereIn('name', $deletedTags)
                        ->where('subject_id', $subject->id)
                        ->delete();
                    }

                    foreach($subArray as $sKey => $chapter){

                        $existingTag = Chapter::
                        where('name', $chapter)
                        ->where('subject_id', $subject->id)
                        ->first();

                        if(!$existingTag){
                            $chapters = new Chapter();
                            $chapters->subject_id = $subject->id;
                            $chapters->name = $chapter;
                            $chapters->added_by = Auth::guard('admin')->user()->id;
                            $chapters->save();
                        }

                    }
                    $subKey+=2;
                }
            }
                DB::commit();
                $callback=['load_data'];
                $closedPopup='myModal';
                return response()->json(["statuscode" => '201', "message" => 'Subject and Chapters has been added Successfully!', "actionType"=>"003", "callback"=>$callback, "closedPopup"=>$closedPopup]);
            }catch(\ValidationException $e){
            Log::error($e->getMessage());
            return response()->json(['errors' =>$e->getMessage()], 422);
        }
    }

    //TO SHOW TO LIST OF SUBJECTS AND CHAPTERS
    public function subjectChpatersList(Request $request){
        $subjects = Subject::whereNull('deleted_at')->with('class', 'chapters');
            return DataTables::eloquent($subjects)
                ->addIndexColumn()
                ->addColumn('status', function ($data) use($request) {
                    if ($data->status == '1') {
                        $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-success" style="text-decoration:none;" data="' . route($request->routePath.'.updateSubjcetStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >Active</a>';
                    } else {
                        $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-danger" style="text-decoration:none;" data="' . route($request->routePath.'.updateSubjcetStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >In-Active</a>';
                    }
                    return $status;
                })
                ->addColumn('action', function($data) use($request) {
                    $button='<center>';
                    $button .= '<a href="javascript:void(0);" onClick="editSubject(' . htmlentities(json_encode($data)) . ')" class="edit_batch" data="' . route($request->routePath.'.manageSubject') . '" id="' . $data->id . '"><i class="fas fa-edit bg-light" style="font-size:20px; color:green;"></i></a>';
                    $button.='&#160;&#160;&#160;&#160;';
                    $button.='  <a href="javascript:void(0);" class="delete2023" url="'.route($request->routePath.'.deleteSubject').'" id="'.$data->id.'"  ><i class="fas fa-trash-alt bg-light" style="font-size:20px;color:red;"></i></a>';
                    $button.='</center>';
                    return $button;
                })

                ->addColumn('chaptersName', function($data) {
                    $button = '<section>';
                    // $button .= '<div class="row">
                        // <div class="col-md-8">';
                        $ii=1;
                        foreach($data->chapters as $chapter){
                            $button .= '<span class="p-1 badge badge-success">'.$chapter->chapter_name.'</span>&#160;&#160;&#160;';
                            if($ii==4){
                                $button .='<br/>';
                                $ii=0;
                            }
                            $ii++;
                        }
                    // $button .= '</div>
                    // </div>';
                    $button .= '</section>';
                    return $button;
                })
                ->rawColumns(['chaptersName', 'status', 'action'])
                ->make(true);
    }

    public function fetchChapter(Request $request){
        $data = Chapter::where('status', '1')->where('subject_id', '=', $request->category)->get();

        return response()->json(["statuscode" => '201', "message" => 'Successfully!', "chapter"=>$data ]);
    }

    public function updateSubjcetStatus(Request $request){
        $record = Subject::find($request->id);
        $record->status = $request->status;
        $record->save();
        return response()->json(["statuscode" => '001', "message" => 'Status Updated Successfully!!' ]);
     }

     public function deleteSubject(Request $request){
        try{
            DB::beginTransaction();
            $sub = Subject::with('chapters')->find($request->id);
            $sub->chapters()->delete();
            $sub->delete();

            DB::commit();
            return response()->json(["statuscode" => '001', "message" => 'Record Deleted Successfully!!' ]);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return response()->json(["statuscode" => '000', 'errors' => $e->getMessage()], 500);
        }
     }
}
