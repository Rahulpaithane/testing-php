<?php

namespace App\Http\Controllers\api\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\InstituteTeacher;
use App\Models\InstituteExam;
use App\Models\InstituteExamCategories;
use App\Models\InstituteSetContainer;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\ClassModel;
use App\Models\QuestionBank;
use App\Models\PreviousYearQuestion;
use App\Models\OmrPaper;
use Auth;
use Validator;
use Carbon\Carbon;

class TeacherTeachController extends Controller
{
    public function homeExam(Request $request){

        $examCategory = InstituteExam::where('status', 1)
        ->where('institute_teacher_id', Auth::guard('institute')->user()->id)
        ->orWhere('institute_teacher_id', null)
        ->select('id', 'name')
        ->get();

        if(count($examCategory) > 0){
            $examId=$request->examCategoryId=='' ? $examCategory[0]->id : intVal($request->examCategoryId);

            $examSubCategory = InstituteExamCategories::where('status', 1)
                ->Where('institute_exam_id', $examId)
                ->where('institute_teacher_id', Auth::guard('institute')->user()->id)
                ->orWhere('institute_teacher_id', null)
                ->Where('institute_exam_id', $examId)
                ->select('id', 'name')
                ->get();

            // Create the additional object
            $additionalObject = (object) ['id' => 0, 'name' => 'Latest'];

            // Add the additional object at the beginning of the collection
            $examSubCategory->prepend($additionalObject);

            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'selectedExamCategory' =>$examId,
                'examCategory' => $examCategory,
                'examSubCategory' =>$examSubCategory
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Exam Categories Not Exist',
            ], 404);
        }
        

    }

    public function createCategory(Request $request){
        $validator = Validator::make($request->all(), 
            [ 
            'categoryName' => 'required',
        ]); 

        if ($validator->fails()) {  
            return response()->json(['message'=>$validator->errors()], 401); 
        }

        $category = new InstituteExam();
        $category->institute_teacher_id = Auth::guard('institute')->user()->id;
        $category->name = $request->categoryName;
        $category->status = 1;
        $category->save();

        return response()->json([
            'status' => true,
            'message' => 'Successfully Added',
        ], 200);


    }

    public function createSubCategory(Request $request){
        $validator = Validator::make($request->all(), 
            [ 
            'categoryId' => 'required',
            'subCategoryName' => 'required',
        ]); 

        if ($validator->fails()) {  
            return response()->json(['message'=>$validator->errors()], 401); 
        }

        $subCategory = new InstituteExamCategories();
        $subCategory->institute_teacher_id = Auth::guard('institute')->user()->id;
        $subCategory->institute_exam_id = $request->categoryId;
        $subCategory->name = $request->subCategoryName;
        $subCategory->status = 1;
        $subCategory->save();

        return response()->json([
            'status' => true,
            'message' => 'Successfully Added',
        ], 200);


    }

    public function setList(Request $request){
        $validator = Validator::make($request->all(), 
            [ 
            'examCategoryId' => 'required', 
            'examSubCategoryId' => 'required', 
        ]); 

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        if($request->examSubCategoryId == '0'){
            $examSubCategory = InstituteSetContainer::where('status', 1)
            ->where('institute_teacher_id', Auth::guard('institute')->user()->id)
            ->Where('institute_exam_id', $request->examCategoryId)
            ->select('id', 'setName')
            ->orderBy('id', 'desc')
            ->take(5) // Limit the result to the latest 10 records
            ->get();
        } else {
            $examSubCategory = InstituteSetContainer::where('status', 1)
            ->where('institute_teacher_id', Auth::guard('institute')->user()->id)
            ->Where('institute_exam_id', $request->examCategoryId)
            ->Where('institute_exam_categories_id', $request->examSubCategoryId)
            ->select('id', 'setName')
            ->orderBy('id', 'desc')
            ->get();
        }
        

        if(count($examSubCategory) > 0){
            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'setList' => $examSubCategory
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Set Not Exist',
            ], 404);
        }
    }

    public function webSetList(Request $request){
        $validator = Validator::make($request->all(), 
            [ 
            'examCategoryId' => 'required', 
            'examSubCategoryId' => 'required', 
        ]); 

        if ($validator->fails()) {  
            return response()->json(['status'=>false, 'message'=>$validator->errors()], 401); 
        }

        if($request->examSubCategoryId == '0'){
            $examSubCategory = InstituteSetContainer::where('status', 1)
            ->where('institute_teacher_id', Auth::guard('institute')->user()->id)
            ->Where('institute_exam_id', $request->examCategoryId);
            if ($request->search != '' ) {
                $examSubCategory->where('setName', 'like', '%' . $request->search . '%');
            }
            $result= $examSubCategory->select('id', 'setName', 'question_id AS total_question', 'setId', 'setPassword', 'created_at AS created_date')
            ->orderBy('id', 'desc')
            ->take(5) // Limit the result to the latest 10 records
            ->get();
        } else {
            $examSubCategory = InstituteSetContainer::where('status', 1)
            ->where('institute_teacher_id', Auth::guard('institute')->user()->id)
            ->Where('institute_exam_id', $request->examCategoryId)
            ->Where('institute_exam_categories_id', $request->examSubCategoryId);
            if ($request->search != '' ) {
                $examSubCategory->where('setName', 'like', '%' . $request->search . '%');
            }
            $result= $examSubCategory->select('id', 'setName', 'question_id AS total_question', 'setId', 'setPassword', 'created_at as created_date')
            ->orderBy('id', 'desc')
            ->get();
        }
        

        if(count($result) > 0){
            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'setList' => $result
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Set Not Exist',
            ], 404);
        }
    }

    public function createNewSet(Request $request){
        $validator = Validator::make($request->all(), 
            [ 
            'examCategoryId' => 'required', 
            'examSubCategoryId' => 'required', 
            'setName' => 'required', 
        ]); 

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        $setContainer = new InstituteSetContainer();
        $setContainer->institute_teacher_id = Auth::guard('institute')->user()->id;
        $setContainer->institute_exam_id = $request->examCategoryId;
        $setContainer->institute_exam_categories_id = $request->examSubCategoryId;
        $setContainer->setName = $request->setName;
        $setContainer->question_id = '';
        $setContainer->setId = uniqid();
        $setContainer->setPassword = uniqid();

        $setContainer->save();

        return response()->json([
            'status' => true,
            'message' => 'Successfully Added',
        ], 200);
    }

    public function editSet(Request $request){
        $validator = Validator::make($request->all(), 
            [ 
            'setId' => 'required', 
            'setName' => 'required', 
        ]); 

        if ($validator->fails()) {  
            return response()->json(['status'=>false, 'message'=>$validator->errors()], 401); 
        }

        $setInfo = InstituteSetContainer::where('id', $request->setId)->first();
        if($setInfo){
            $setInfo->setName = $request->setName;
            $setInfo->save();

            return response()->json([
                'status' => true,
                'message' => 'Successfully Updated',
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Data Not Found',
            ], 404);
        }
    }

    public function subjectFilter(Request $request){
        $subject=Subject::
        join('classes', 'subjects.class_id', 'classes.id')
        ->where('subjects.status', '=', '1')
        ->where('classes.status', '=', '1')
        ->select('subjects.id', DB::raw('CONCAT(subjects.name, "( ", classes.name, " )") as name'))
        ->get();
        if(count($subject) > 0){

            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'subject' =>$subject
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Subject Not Exist',
            ], 404);
        }
    }

    public function chapterFilter(Request $request){
        $validator = Validator::make($request->all(), 
            [ 
            'subject_id' => 'required',
        ]); 

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        $chapter = Chapter::where('status', '1')->where('subject_id', '=', $request->subject_id)
        ->select('id', 'subject_id', 'name')->get();

        if(count($chapter) > 0){

            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'chapter' =>$chapter
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Chapter Not Exist',
            ], 404);
        }
    }

    public function submitFilter(Request $request)
{
    $validator = Validator::make($request->all(), [
        'subject_id' => 'required',
        'chapter_id' => 'required',
        'questionType' => 'required',
        'languageType' => 'required',
        'questionLevel' => 'required',
        'setId' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400); // 400 Bad Request
    }

    $query = QuestionBank::with('questionBankInfo')
    ->select('id', 'subject_id', 'chapter_id', 'question_type', 'language_type');

    if ($request->subject_id !== 'All') {
        $query->where('subject_id', $request->subject_id);
    }
    
    if ($request->chapter_id !== 'All') {
        $query->where('chapter_id', $request->chapter_id);
    }
    
    if ($request->questionType !== 'All') {
        $query->where('question_type', $request->questionType);
    }
    
    if ($request->languageType !== 'All') {
        $query->where('language_type', $request->languageType);
    }
    
    if ($request->questionLevel !== 'All') {
        $query->where('level', $request->questionLevel);
    }

    $results = $query->paginate(1); // You can adjust the number of items per page as needed.
    $item=$results->items();


    $previousQuestionData = PreviousYearQuestion::whereJsonContains('questionBankInfoId', $item[0]->id)
    ->select('exam', 'year')
    ->get();

    $groupedData = $previousQuestionData->groupBy('exam')->map(function ($group) {
        $years = $group->pluck('year')->unique()->implode(', ');
        return $group->first()->exam . " - [" . $years . "]";
    });
    
    // $tag=$previousQuestionData[0]->exam;
    

    $set = InstituteSetContainer::where('id', $request->setId)->where('status', 1)->first();
    $containerName=$set->setName;
    $isAdded=false;
    if ($set->question_id != '') {
        $decodedArray = json_decode($set->question_id);
        
        if (is_array($decodedArray)) {
            $totalQuestion = count($decodedArray);
            if(in_array($item[0]->id, $decodedArray)){
                $isAdded=true;
            }
        } else {
            $totalQuestion = 0; // Not an array
        }
    } else {
        $totalQuestion = 0; // Value is null
    }

    $quest=[
        'id' => $item[0]->id,
        'isAdded' =>$isAdded,
        'question_type' => $item[0]->question_type,
        'language_type' => $item[0]->language_type,
        'pragraph' => $item[0]->pragraph,
        'tag' =>$groupedData->implode(', '), //$previousQuestionData == true ? $previousQuestionData->exam."-[".$previousQuestionData->year."]" : "",
        'question_bank_info' => $item[0]->questionBankInfo,
        
    ];

    return response()->json([
        'current_page' => $results->currentPage(),
        'items_per_page' => $results->perPage(),
        'total_items' => $results->total(),
        'info' => [
            'subject_id' => $request->subject_id,
            'chapter_id' => $request->chapter_id,
            'questionType' => $request->questionType,
            'languageType' => $request->languageType,
            'questionLevel' => $request->questionLevel,
            'setId' => $request->setId,
            'containerName' => $containerName,
            'totalQuestion' =>$totalQuestion
        ],
        
        'data' => $quest,
    
    ], 200); // 200 OK

}

public function webSetContainerInfo(Request $request){
    $validator = Validator::make($request->all(), [
        'setId' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => $validator->errors()], 400); // 400 Bad Request
    }

    $set = InstituteSetContainer::where('id', $request->setId)->where('status', 1)->first();

    if($set){
        return response()->json([
            'status' => true,
            'message' => 'Container found',
            'data' => $set,
        ], 200);
    } else {
        return response()->json([
            'status' => true,
            'message' => 'Container Not found',
            'data' => $set,
        ], 400);
    }
}

public function webSubmitFilter(Request $request)
{
    $validator = Validator::make($request->all(), [
        'subject_id' => 'required',
        'chapter_id' => 'required',
        'questionType' => 'required',
        'languageType' => 'required',
        'questionLevel' => 'required',
        // 'setId' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => $validator->errors()], 400); // 400 Bad Request
    }

    $query = QuestionBank::with('questionBankInfo')
    ->select('id', 'subject_id', 'chapter_id', 'question_type', 'language_type');

    if ($request->subject_id !== 'All') {
        $query->where('subject_id', $request->subject_id);
    }
    
    if ($request->chapter_id !== 'All') {
        $query->where('chapter_id', $request->chapter_id);
    }
    
    if ($request->questionType !== 'All') {
        $query->where('question_type', $request->questionType);
    }
    
    if ($request->languageType !== 'All') {
        $query->where('language_type', $request->languageType);
    }
    
    if ($request->questionLevel !== 'All') {
        $query->where('level', $request->questionLevel);
    }

    $results = $query->paginate(10); // You can adjust the number of items per page as needed.
    $item=$results->items();
    $quest=[];

    // $set = InstituteSetContainer::where('id', $request->setId)->where('status', 1)->first();
    //     $containerName=$set->setName;
    //     $containerQuestionId=$set->question_id;
    //     $totalQuestion = 0;
    foreach ($item as $q) {
        $previousQuestionData = PreviousYearQuestion::whereJsonContains('questionBankInfoId', $q->id)
        ->select('exam', 'year')
        ->get();

        $groupedData = $previousQuestionData->groupBy('exam')->map(function ($group) {
            $years = $group->pluck('year')->unique()->implode(', ');
            return $group->first()->exam . " - [" . $years . "]";
        });

        // $isAdded=false;
        // if ($set->question_id != '') {
        //     $decodedArray = json_decode($set->question_id);
            
        //     if (is_array($decodedArray)) {
        //         $totalQuestion = count($decodedArray);
        //         if(in_array($q->id, $decodedArray)){
        //             $isAdded=true;
        //         }
        //     } else {
        //         $totalQuestion = 0; // Not an array
        //     }
        // } else {
        //     $totalQuestion = 0; // Value is null
        // }

        $quest[]=[
            'id' => $q->id,
            // 'isAdded' =>$isAdded,
            'question_type' => $q->question_type,
            'language_type' => $q->language_type,
            'pragraph' => $q->pragraph,
            'tag' =>$groupedData->implode(', '), //$previousQuestionData == true ? $previousQuestionData->exam."-[".$previousQuestionData->year."]" : "",
            'question_bank_info' => $q->questionBankInfo,
            
        ];
    }

    
       
    
    return response()->json([
        'current_page' => $results->currentPage(),
        'items_per_page' => $results->perPage(),
        'total_items' => $results->total(),
        'info' => [
            'subject_id' => $request->subject_id,
            'chapter_id' => $request->chapter_id,
            'questionType' => $request->questionType,
            'languageType' => $request->languageType,
            'questionLevel' => $request->questionLevel,
            // 'setId' => $request->setId,
            // 'containerName' => $containerName,
            // 'containerQuestionId' =>$containerQuestionId,
            // 'totalQuestion' =>$totalQuestion
        ],
        
        'data' => $quest,
    
    ], 200); // 200 OK

}

public function updateMultipleQuestionAssign(Request $request){
    $validator = Validator::make($request->all(), [
        'setId' =>'required',
        'question_id' => 'required',
        
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => $validator->errors()], 400); // 400 Bad Request
    }

    $set = InstituteSetContainer::where('id', $request->setId)->where('status', 1)->first();

    $set->question_id=json_encode($request->question_id);

    $set->save();

        return response()->json([
            'status' => true,
            'message' => 'Successfullye Added',
        ], 200);

}

public function questionAssign(Request $request){
    $validator = Validator::make($request->all(), [
        'setId' =>'required',
        'question_id' => 'required',
        
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400); // 400 Bad Request
    }

    $set = InstituteSetContainer::where('id', $request->setId)->where('status', 1)->first();

    $container=$set->question_id;
    $questionValue=intval($request->question_id);

    if($container !=''){
        $myArray=json_decode($container,true);

        if (in_array($questionValue, $myArray)) {
            // If it exists, remove it
            $index = array_search($questionValue, $myArray);
            if ($index !== false) {
                unset($myArray[$index]);
            }

            $message="Successfullye Removed";
        } else {
            // If it doesn't exist, add it
            $myArray[] = $questionValue;
            $message="Successfullye Added";
        }

    } else {
        $myArray[] = $questionValue;
        $message="Successfullye Added";
    }

    $set->question_id=json_encode(array_values($myArray));

    $set->save();

        return response()->json([
            'status' => true,
            'message' => $message,
        ], 200);
}

public function setcontainerDataInfo(Request $request){
    $validator = Validator::make($request->all(), [
        'setId' =>'required',
        
    ]);

    if ($validator->fails()) {
        return response()->json(['status' =>false, 'message' => $validator->errors()], 400); // 400 Bad Request
    }

    $paper=InstituteSetContainer::with('instituteDetails', 'omrDetails')
    ->where('id', $request->setId) 
        ->where('status', 1)
        ->first();
    if($paper){

        $question=QuestionBank::whereIn('id',  json_decode($paper->question_id) )
        ->with('questionBankInfo')
        ->select('id', 'question_type', 'language_type', 'pragraph')
        ->get();

        $quest=[];

        foreach ($question as $q) {
            $previousQuestionData = PreviousYearQuestion::whereJsonContains('questionBankInfoId', $q->id)
                ->select('exam', 'year')
                ->get();

                $groupedData = $previousQuestionData->groupBy('exam')->map(function ($group) {
                    $years = $group->pluck('year')->unique()->implode(', ');
                    return $group->first()->exam . " - [" . $years . "]";
                });
                
                // $tag=$previousQuestionData[0]->exam;
                $quest[]=[
                    'id' => $q->id, 
                    'question_type' => $q->question_type,
                    'language_type' => $q->language_type,
                    'pragraph' => $q->pragraph,
                    'tag' =>$groupedData->implode(', '), //$previousQuestionData == true ? $previousQuestionData->exam."-[".$previousQuestionData->year."]" : "",
                    'question_bank_info' => $q->questionBankInfo,
                    'questionPosition'=>[
                        'x' =>10,
                        'y' =>10
                    ],
                    'optionPosition'=>[
                        'x' =>60,
                        'y' =>10
                    ]
                    
                ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
            'container' =>$paper,
            'question' =>$quest,
        ], 200);

    }else{
        return response()->json([
            'status' => false,
            'message' => 'data not found!',
        ], 400);
    }

}
public function deleteSet(Request $request){

    $validator = Validator::make($request->all(), [
        'setId' =>'required',
        
    ]);

    if ($validator->fails()) {
        return response()->json(['status' =>false, 'message' => $validator->errors()], 400); // 400 Bad Request
    }

    try{
        $set = InstituteSetContainer::find($request->setId);
        $set->delete();

        DB::commit();
        return response()->json(["statuscode" => '001', "message" => 'Record Deleted Successfully!!' ]);
    }catch (\Exception $e) {
        Log::error($e->getMessage());
        DB::rollback();
        return response()->json(["statuscode" => '000', 'errors' => $e->getMessage()], 500);
    }
}

public function guestSetContainerDataInfo(Request $request){
    $validator = Validator::make($request->all(), [
        'containerSetId' =>'required',
        'containerPassword' =>'required',
        
    ]);

    if ($validator->fails()) {
        return response()->json(['status' =>false, 'message' => $validator->errors()], 400); // 400 Bad Request
    }

    try {
        $query=InstituteSetContainer::where('setId', $request->containerSetId)
            ->where('setPassword', $request->containerPassword)
            ->where('status', 1)
            ->first();


        if(count( (array) $query) >0){

            if($query->question_id ==null || $query->question_id =='' || $query->question_id =='[]' ) {

                return response()->json([
                    'status' => false,
                    'message' => 'Container are Empty!',
                ], 400);

            } else {
                $institueDetail=InstituteTeacher::where('id', $query->institute_teacher_id)->first();
                $request->merge(['setId' => $query->id]);

                
                return $this->setcontainerDataInfo($request);
                
            }

           

            
        } else {
            return response()->json([
                'status' => false,
                'message' => 'data not found!',
            ], 400);
        }

    } catch(\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'data not found!',
        ], 400);
    }


}

public function updateOmrViaPdf(Request $request){

    try{
        DB::beginTransaction();
        $pdfInfo=InstituteSetContainer::where('id', $request->pdfId)
            ->where('status', 1)
        ->first();

        

        $questionArr=json_decode($pdfInfo->question_id);
        $totalQuestion=count( (array) $questionArr);


        if($request->isOmrExist ==true && $request->existOmrId !=''){
            $omrPaperUpdate=OmrPaper::where('id', $request->existOmrId)
                ->where('status', 1)
            ->first();
        } else {
            $omr_code = rand(1000,9999).rand(1000,9999);

            $omrPaperUpdate = new OmrPaper();
            $omrPaperUpdate->omr_type = 'Collection';
            $omrPaperUpdate->omr_code = $omr_code;
            $omrPaperUpdate->option_format='Alphabetical';
        }
    
    
        $omrPaperUpdate->paper_name=$request->paper_name;
        $omrPaperUpdate->question_id=json_encode($questionArr);
        $omrPaperUpdate->total_question=$totalQuestion;
        $omrPaperUpdate->numberPerQuestion=$request->numberPerQuestion;
        $omrPaperUpdate->total_marks= intval($totalQuestion) * intVal($request->numberPerQuestion);
        $omrPaperUpdate->examDuration=$request->examDuration;
        $omrPaperUpdate->isNegative=$request->isNegative;
        $omrPaperUpdate->numberPerNegative=$request->isNegative=='Yes' ? $request->numberPerNegative : 0;

        $omrPaperUpdate->exam_date='2023-07-29';
        $omrPaperUpdate->exam_time='01:00 PM';

        // $omrPaperUpdate->added_by=Auth::guard('admin')->user()->id;
        $omrPaperUpdate->status = 1;
        $omrPaperUpdate->save();

        $pdfInfo->omr_paper_id=$omrPaperUpdate->id;
        $pdfInfo->save();

        DB::commit();
        return response()->json(["statuscode" => '200', "message" => 'Digital OMR Collection Paper Added Successfully!', 'omrPaper'=>$omrPaperUpdate ]);

    }catch (\Exception $e) {
        Log::error($e->getMessage());
        DB::rollback();
        return response()->json(["statuscode" => '000', 'errors' => $e->getMessage()], 500);
    }
}

}
