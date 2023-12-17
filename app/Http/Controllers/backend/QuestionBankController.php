<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use App\Models\Subject;
use App\Models\Chapter;
use App\Models\QuestionBank;
use App\Models\PreviousYearQuestion;
use App\Models\QuestionBankInfo;
use DataTables;
use Auth;
use DB;
use PhpOffice\PhpWord\IOFactory;

class QuestionBankController extends Controller
{
    public function questionManage(Request $request){

        if($request->method() == 'GET'){

            $subject=Subject::where('status', '=', '1')->get();

            return view('backend.questionBank.newQuestion', ['subject'=>$subject]);
        } else {
            // $plainText = strip_tags($request->englishQuestion);

            // // Define replacements (you may need to adjust these)
            // $replacements = [
            //     '−' => '-', // Replace special minus sign with regular hyphen
            //     'cos' => 'cos', // Replace 'cos' with formatted 'cos' text
            //     'sin' => 'sin', // Replace 'sin' with formatted 'sin' text
            //     // Define more replacements as needed
            // ];
        
            // // Perform replacements
            // $formattedText = str_replace(array_keys($replacements), array_values($replacements), $plainText);


            // dd($formattedText);
            try{
                DB::beginTransaction();
                $questionBankIdd='';
                $previous_year = $request->previousAskedYear;
                if( ($request->questionType == 'Normal') OR ( $request->questionType == 'Group' AND $request->paragraphIdd ==''  )){

                    $questionBank = new QuestionBank();
                    $questionBank->subject_id =$request->subjectId;
                    $questionBank->chapter_id =$request->chapterId;
                    $questionBank->question_type =$request->questionType;
                    $questionBank->language_type =$request->languageType;
                    $questionBank->level =$request->questionLevel;
                    if($request->questionType == 'Group'){
                        $questionBank->pragraph =json_encode(
                            ['English'=>$request->languageType !='Hindi' ? $request->paragraphEnglish : '',
                            'Hindi'=>$request->languageType !='English' ? $request->paragraphHindi : ''
                            ]);
                    }
                    $questionBank->added_by=Auth::guard('admin')->user()->id;
                    $questionBank->save();

                    $questionBankIdd=$questionBank->id;

                    //SAVING INTO PREVIOUS YEAR QUESTIONS
                    if ($previous_year != null) {

                        $splitString = preg_split('/,\s*(?=[A-Z])/', trim($previous_year));
                        foreach ($splitString as $pair) {
                            $pairArray = explode('-', $pair, 2);
                            // dd($pair)
                            $exam = $pairArray[0];
                            $yearArray = json_decode($pairArray[1], true);
                            foreach ($yearArray as $year) {
                                $existingData = PreviousYearQuestion::where('exam', $exam)->where('year', $year)->first();
                                if ($existingData) {
                                    $prevQueIds = json_decode($existingData->questionBankInfoId);
                                        array_push($prevQueIds, $questionBank->id);
                                    $existingData->questionBankInfoId = json_encode($prevQueIds);
                                    $existingData->save();
                                } else {
                                    $questionArray = [];
                                    array_push($questionArray, $questionBank->id);
                                        $newQue = new PreviousYearQuestion();
                                        $newQue->exam = $exam;
                                        $newQue->year = $year;
                                        $newQue->questionBankInfoId = json_encode($questionArray);
                                        $newQue->save();
                                }
                            }
                        }
                    }
                } else {

                    $questionBankIdd=$request->paragraphIdd;
                }

                
                $questionInfo = new QuestionBankInfo();
                $questionInfo->question_bank_id =intVal($questionBankIdd);
                $questionInfo->question =json_encode(
                    ['English'=>$request->languageType !='Hindi' ? $request->englishQuestion : '',
                    'Hindi'=>$request->languageType !='English' ? $request->hindiQuestion : ''
                    ]);
                $questionInfo->option =json_encode(
                    ['English'=>$request->languageType !='Hindi' ?
                        [
                            $request->optionEnglishA,
                            $request->optionEnglishB,
                            $request->optionEnglishC,
                            $request->optionEnglishD,
                            $request->optionEnglishE
                        ] : '',
                    'Hindi'=>$request->languageType !='English' ?
                        [
                            $request->optionHindiA,
                            $request->optionHindiB,
                            $request->optionHindiC,
                            $request->optionHindiD,
                            $request->optionHindiE
                        ] : ''
                    ]);
                $questionInfo->answer =$request->answer;
                $questionInfo->ans_desc =json_encode(
                    ['English'=>$request->languageType !='Hindi' ? $request->answerDescriptionEnglish : '',
                    'Hindi'=>$request->languageType !='English' ? $request->answerDescriptionHindi : ''
                    ]);
                $questionInfo->added_by =Auth::guard('admin')->user()->id;;
                $questionInfo->save();

                

                // return response()->json(["statuscode" => '201', "message" => $questionBankIdd, "actionType"=>"003"]);

                $questionDetails = QuestionBankInfo::select('id', 'question_bank_id')->where('question_bank_id', $questionBankIdd)->where('status', '1')->get();
                // dd('hii');
                $data=[
                    'questionType' =>$request->questionType,
                    'languageType' =>$request->languageType,
                    'questionBankId' =>$questionBankIdd,
                    'questionDetails' => $questionDetails
                ];
                DB::commit();
                $callback=['groupQuestionInfo'];
                $closedPpopup='myModal';
                return response()->json(["statuscode" => '201', "message" => 'Successfully', "actionType"=>"003", "data"=>$data, "callback"=>$callback, "closedPpopup"=>$closedPpopup]);

            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['statuscode'=>'500', 'message'=>'Internal server error.. please contact to the Administrator!', 'errors'=>$e->getMessage()]);
            }
        }
    }

    public function questionBankList(Request $request){
        if($request->method()  == 'GET'){

            // $questionBank=QuestionBank::with('subject', 'chapter', 'questionBankInfo')->get();
            // $pra=json_decode($questionBank[1]->pragraph);
            // dd($questionBank[1]->questionBankInfo[0]['question']);
            $subject=Subject::with('class')->where('status', '=', '1')->get();
            // dd($subject);
            return view('backend.questionBank.questionList', ['subject'=>$subject]);
        }

        $questionBank=QuestionBank::with('subject', 'chapter', 'questionBankInfo');
        !empty($request->subject) ? $questionBank->where('subject_id','=',$request->subject) : '';
         !empty($request->chapter) ? $questionBank->where('chapter_id','=',$request->chapter) : '';
         !empty($request->questionType) ? $questionBank->where('question_type','=',$request->questionType) : '';
         !empty($request->languageType) ? $questionBank->where('language_type','=',$request->languageType) : '';
         !empty($request->questionLevel) ? $questionBank->where('level','=',$request->questionLevel) : '';
         !empty($request->questionId) ? $questionBank->where('id','=',$request->questionId) : '';
        return DataTables::eloquent($questionBank)
            ->addIndexColumn()
            ->addColumn('status', function ($data) use($request) {
                if ($data->status == '1') {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-success" style="text-decoration:none;" data="' . route($request->routePath.'.updateQuestionStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >Active</a>';
                } else {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-danger" style="text-decoration:none;" data="' . route($request->routePath.'.updateQuestionStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >In-Active</a>';
                }
                return $status;
            })
            ->addColumn('action', function($data) use($request) {
                $button='<center>';
                $button .= '<a href="'.route($request->routePath.'.editQuestion', ['id'=>$data->id]).'"   ><i class="fas fa-edit bg-light" style="font-size:20px; color:green;"></i></a>';
                $button.='&#160;&#160;&#160;&#160;';
                $button.='  <a href="javascript:void(0);" class="delete2023" url="'.route($request->routePath.'.deleteQuestionBank').'" id="'.$data->id.'"  ><i class="fas fa-trash-alt bg-light" style="font-size:20px;color:red;"></i></a>';
                $button.='</center>';
                return $button;
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

            // ->addColumn('option', function($data){
            //     // $pra =json_decode($data->pragraph);
            //     // $q=json_decode($data->questionBankInfo[0]['option']);


            //     $optionArr = json_decode($data->questionBankInfo[0]['option']);
            //     $l=$data->language_type;
            //     $englishData = $l =='Both' ? $optionArr->English : $optionArr->$l;
            //         $i = 'A';
            //         $cnt = 1;
            //         $options = '';
            //         foreach($englishData as $op){
            //             $options .= '<div style="display: flex;">'.$i.'. &nbsp;&nbsp; <span class="option_'.$cnt.'">'.$op.'</span></div>';
            //             $i++;
            //             $cnt++;
            //         }
            //     // $englishData = strip_tags($pra->English);
            //     // $englishData =$data->question_type =='Group' ? json_encode($pra->English) : '';

            //     return $options;
            // })

            ->addColumn('optionA', function($data){
                // $pra =json_decode($data->pragraph);
                // $q=json_decode($data->questionBankInfo[0]['option']);


                $optionArr = json_decode($data->questionBankInfo[0]['option']);
                $l=$data->language_type;
                $englishData = $l =='Both' ? $optionArr->English : $optionArr->$l;
                    $i = 'A';
                    $cnt = 1;
                    $options = '';
                    // foreach($englishData as $op){
                        $options .= '<span class="option_'.$cnt.'">'.$englishData[0].'</span>';
                    //     $i++;
                    //     $cnt++;
                    // }
                // $englishData = strip_tags($pra->English);
                // $englishData =$data->question_type =='Group' ? json_encode($pra->English) : '';

                return $options;
            })
            ->addColumn('optionB', function($data){
                // $pra =json_decode($data->pragraph);
                // $q=json_decode($data->questionBankInfo[0]['option']);


                $optionArr = json_decode($data->questionBankInfo[0]['option']);
                $l=$data->language_type;
                $englishData = $l =='Both' ? $optionArr->English : $optionArr->$l;
                    $i = 'B';
                    $cnt = 2;
                    $options = '';
                    // foreach($englishData as $op){
                        $options .= '<span class="option_'.$cnt.'">'.$englishData[1].'</span>';
                    //     $i++;
                    //     $cnt++;
                    // }
                // $englishData = strip_tags($pra->English);
                // $englishData =$data->question_type =='Group' ? json_encode($pra->English) : '';

                return $options;
            })
            ->addColumn('optionC', function($data){
                // $pra =json_decode($data->pragraph);
                // $q=json_decode($data->questionBankInfo[0]['option']);


                $optionArr = json_decode($data->questionBankInfo[0]['option']);
                $l=$data->language_type;
                $englishData = $l =='Both' ? $optionArr->English : $optionArr->$l;
                    $i = 'C';
                    $cnt = 3;
                    $options = '';
                    // foreach($englishData as $op){
                        $options .= '<span class="option_'.$cnt.'">'.$englishData[2].'</span>';
                    //     $i++;
                    //     $cnt++;
                    // }
                // $englishData = strip_tags($pra->English);
                // $englishData =$data->question_type =='Group' ? json_encode($pra->English) : '';

                return $options;
            })
            ->addColumn('optionD', function($data){
                // $pra =json_decode($data->pragraph);
                // $q=json_decode($data->questionBankInfo[0]['option']);


                $optionArr = json_decode($data->questionBankInfo[0]['option']);
                $l=$data->language_type;
                $englishData = $l =='Both' ? $optionArr->English : $optionArr->$l;
                    $i = 'D';
                    $cnt = 4;
                    $options = '';
                    // foreach($englishData as $op){
                        $options .= '<span class="option_'.$cnt.'">'.$englishData[3].'</span>';
                    //     $i++;
                    //     $cnt++;
                    // }
                // $englishData = strip_tags($pra->English);
                // $englishData =$data->question_type =='Group' ? json_encode($pra->English) : '';

                return $options;
            })

            ->addColumn('optionE', function($data){
                // $pra =json_decode($data->pragraph);
                // $q=json_decode($data->questionBankInfo[0]['option']);


                $optionArr = json_decode($data->questionBankInfo[0]['option']);
                $l=$data->language_type;
                $englishData = $l =='Both' ? $optionArr->English : $optionArr->$l;
                    $i = 'E';
                    $cnt = 5;
                    $options = '';
                    // foreach($englishData as $op){
                        $options .= '<span class="option_'.$cnt.'">'.$englishData[4].'</span>';
                    //     $i++;
                    //     $cnt++;
                    // }
                // $englishData = strip_tags($pra->English);
                // $englishData =$data->question_type =='Group' ? json_encode($pra->English) : '';

                return $options;
            })


            ->addColumn('lang', function($data){
                $button='<center>';

                $button.=$data->language_type == 'Both' ? 'English, Hindi' : $data->language_type;
                $button.='</center>';
                return $button;
            })

            ->rawColumns(array( "lang", "paragraphInfo", "option", "optionA", "optionB", "optionC", "optionD", "optionE", "status",  "action"))
            ->make(true);
    }

    public function paragraphQuestionBankList(Request $request){
        $questionBank=QuestionBank::with('subject', 'chapter', 'questionBankInfo');
         !empty($request->subject) ? $questionBank->where('subject_id','=',$request->subject) : '';
         !empty($request->chapter) ? $questionBank->where('chapter_id','=',$request->chapter) : '';
         !empty($request->questionType) ? $questionBank->where('question_type','=',$request->questionType) : '';
         !empty($request->languageType) ? $questionBank->where('language_type','=',$request->languageType) : '';
         !empty($request->questionLevel) ? $questionBank->where('level','=',$request->questionLevel) : '';
        return DataTables::eloquent($questionBank)
            ->addIndexColumn()
            ->addColumn('status', function ($data) use($request) {
                if ($data->status == '1') {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-success" style="text-decoration:none;" data="' . route($request->routePath.'.updateQuestionStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >Active</a>';
                } else {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-danger" style="text-decoration:none;" data="' . route($request->routePath.'.updateQuestionStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >In-Active</a>';
                }
                return $status;
            })
            ->addColumn('action', function($data) use($request) {
                $button='<center>';
                $button .= '<a href="'.route($request->routePath.'.editQuestion', ['id'=>$data->id]).'"   ><i class="fas fa-edit bg-light" style="font-size:20px; color:green;"></i></a>';
                $button.='&#160;&#160;&#160;&#160;';
                $button.='  <a href="javascript:void(0);" class="delete2023" url="'.route($request->routePath.'.deleteQuestionBank').'" id="'.$data->id.'"  ><i class="fas fa-trash-alt bg-light" style="font-size:20px;color:red;"></i></a>';
                $button.='</center>';
                return $button;
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

            // ->addColumn('optionA', function($data){
            //     $pra =json_decode($data->pragraph);
            //     $q=json_decode($data->questionBankInfo[0]['question']);
            //     $l=$data->language_type;
            //     // $englishData = strip_tags($pra->English);
            //     // $englishData =$data->question_type =='Group' ? json_encode($pra->English) : '';
            //     $englishData = $data->question_type =='Group' ? ($l =='Both' ? $pra->English : $pra->$l) : ($l =='Both' ? $q->English : $q->$l) ;
            //     return $englishData;
            // })


            ->addColumn('lang', function($data){
                $button='<center>';

                $button.=$data->language_type == 'Both' ? 'English, Hindi' : $data->language_type;
                $button.='</center>';
                return $button;
            })

            ->rawColumns(array( "lang", "paragraphInfo", "status",  "action"))
            ->make(true);
    }

    public function updateQuestionStatus(Request $request){

        $record = QuestionBank::find($request->id);
        $record->status = $request->status;

        $record->save();
        return response()->json(["statuscode" => '001', "message" => 'Status Updated Successfully!!' ]);
     }

    public function deleteQuestionBank(Request $request){
        try{
            DB::beginTransaction();

            $que= QuestionBank::with('questionBankInfo')->find($request->id);
            $que->questionBankInfo()->delete();
            $que->delete();

            DB::commit();
            return response()->json(["statuscode" => '001', "message" => 'Record Deleted Successfully!!' ]);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return response()->json(["statuscode" => '000', 'errors' => $e->getMessage()], 500);
        }
    }

    public function deleteQuestion(Request $request){
        try{
            DB::beginTransaction();

            $que= QuestionBankInfo::find($request->id);
            $que->delete();

            DB::commit();
            return response()->json(["statuscode" => '001', "message" => 'Record Deleted Successfully!!' ]);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return response()->json(["statuscode" => '000', 'errors' => $e->getMessage()], 500);
        }
    }

    public function editQuestion(REquest $request){
        if($request->method() == 'GET'){

            $questionBank=QuestionBank::with('subject', 'chapter', 'questionBankInfo')->where('id', $request->id)->get();
            // dd($questionBank[0]->questionBankInfo);
            return view('backend.questionBank.editQuestion', ['questionBank'=>$questionBank[0]]);
        }

        if($request->updationType == 'paragraphQuestion'){

            $question=QuestionBankInfo::where('id', '=', $request->questionId)->where('question_bank_id', '=', $request->paragraphIdd)->first();
            $question->question=json_encode(
                ['English'=>$request->languageType !='Hindi' ? $request->englishQuestion : '',
                'Hindi'=>$request->languageType !='English' ? $request->hindiQuestion : ''
                ]);
            $question->option=json_encode(
                ['English'=>$request->languageType !='Hindi' ?
                    [
                        $request->optionEnglishA,
                        $request->optionEnglishB,
                        $request->optionEnglishC,
                        $request->optionEnglishD,
                        $request->optionEnglishE
                    ] : '',
                'Hindi'=>$request->languageType !='English' ?
                    [
                        $request->optionHindiA,
                        $request->optionHindiB,
                        $request->optionHindiC,
                        $request->optionHindiD,
                        $request->optionHindiE
                    ] : ''
                ]);
            $question->answer=$request->answer;
            $question->ans_desc=json_encode(
                ['English'=>$request->languageType !='Hindi' ? $request->answerDescriptionEnglish : '',
                'Hindi'=>$request->languageType !='English' ? $request->answerDescriptionHindi : ''
                ]);
            $question->added_by=Auth::guard('admin')->user()->id;
            $question->save();

            return response()->json(["statuscode" => '201', "message" => 'Update Successfully', "actionType"=>"001"]);

        } else {
            $questionBank = QuestionBank::where('id', $request->paragraphIdd)->first();

                $questionBank->level =$request->questionLevel;
                if($request->questionType == 'Group'){
                    $questionBank->pragraph =json_encode(
                        ['English'=>$request->languageType !='Hindi' ? $request->paragraphEnglish : '',
                        'Hindi'=>$request->languageType !='English' ? $request->paragraphHindi : ''
                        ]);
                }
                $questionBank->added_by=Auth::guard('admin')->user()->id;
                $questionBank->save();

            return response()->json(["statuscode" => '201', "message" => 'Update Successfully', "actionType"=>"001"]);
        }
    }

    public function bulkWordOfficeUploadQuestion(Request $request){

        if($request->method() == 'GET'){

            $subject=Subject::where('status', '=', '1')->get();

            return view('backend.questionBank.bulkWordOfficeUploadQuestion', ['subject'=>$subject]);
        }

        $docFile = $request->file('doc_file');
        // $data = [];
        // $phpWord = IOFactory::load($docFile);
        // $sections = $phpWord->getSections();

        // dd($content = $phpWord->getSections()[0]->getText());
        // $text = '';
        // foreach ($sections as $section) {
        //     // dd($section);
        //     $elements = $section->getElements();
        //     // dd($elements);
        //     foreach ($elements as $element) {
        //         // dd($element->getElements());
        //         $elementClassName = get_class($element);
        //         // dd($elementClassName);
        //         // if ($elementClassName === 'PhpOffice\PhpWord\Element\TextRun') {
        //         //     dd($element);
        //         //     $data[] = [
        //         //         'question' => $element->getText(),
        //         //         'answer' => '', // You can leave this empty or populate it with appropriate data
        //         //     ];
        //         // } else {
        //         //     dd('hi');
        //         // }

        //         if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
        //             $text .= $element->getText();
        //             // dd($element);
        //         }
        //     }
        // }

        $phpWord = IOFactory::load($docFile);
        $content = '';
        // $i=0;
        // foreach ($phpWord->getSections() as $section) {
        //     foreach ($section->getElements() as $element) {
        //         if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
        //             $text .= $element->getText();
        //             $i++;
        //         }
        //     }
        // }

        foreach($phpWord->getSections() as $section) {
            foreach($section->getElements() as $element) {
                if (method_exists($element, 'getElements')) {
                    foreach($element->getElements() as $childElement) {
                        if (method_exists($childElement, 'getText')) {
                            $content .= $childElement->getText() . ' ';
                        }
                        else if (method_exists($childElement, 'getContent')) {
                            $content .= $childElement->getContent() . ' ';
                        }
                    }
                }
                else if (method_exists($element, 'getText')) {
                    $content .= $element->getText() . ' ';
                }
            }
        }
        
        // echo $content;
        
        // Split the $text content into rows (questions)
        $questions = explode("Question:", $content);
        // dd($questions);

        $rightInsertion=[];
        $badInsertion=[];
        foreach ($questions as $key => $value) {
            if($key !=0){
                // dd($value);
                $questionPart=preg_split('/Level:|Level :|Tag:|Tag :|Answer:|Answer :|Hindi:|Hindi :|English:|English :/', $value);
                $questionId=trim($questionPart[0]);
                try{
                    DB::beginTransaction();
                    
                    
                    $level=trim($questionPart[1]);
                    $previous_year=trim($questionPart[2]);
                    // $answerLen=trim($questionPart[3]);
                    $answer=trim( strtoupper($questionPart[3]));

                    if( (strlen($answer) !=1) || (!in_array($answer, ['A', 'B', 'C', 'D', 'E'])) ){
                        // throw new Exception("Error Processing Request", 1);
                        $badInsertion[]=$questionId;
                        break;
                        
                    }

                    if($request->languageType == 'Hindi'){
                        $hindi=trim($questionPart[4]);

                        // dd($hindi);
                        $hindiParts =preg_split('/Q:|Q :|\(\s*[a-e]\s*\)|Solution:|Solution :/', $hindi, -1, PREG_SPLIT_DELIM_CAPTURE);

                        $hindiQuestion=trim($hindiParts[1]) !='' ?'<p>'.trim($hindiParts[1]).'</p>' : '';

                        $hindiOptionA=trim($hindiParts[2]) !='' ? '<p>'.($hindiParts[2]).'</p>' : '';
                        $hindiOptionB=trim($hindiParts[3]) !='' ? '<p>'.($hindiParts[3]).'</p>' : '';
                        $hindiOptionC=trim($hindiParts[4]) !='' ? '<p>'.($hindiParts[4]).'</p>' : '';
                        $hindiOptionD=trim($hindiParts[5]) !='' ? '<p>'.($hindiParts[5]).'</p>' : '';
                        $hindiOptionE=trim($hindiParts[6]) !='' ? '<p>'.($hindiParts[6]).'</p>' : '';

                        $hindiDes=trim($hindiParts[7]) !='' ? '<p>'.trim($hindiParts[7]).'</p>' : '';

                    } else if($request->languageType == 'English'){
                        $english=trim($questionPart[4]);

                        $englishParts =preg_split('/Q:|Q :|\(\s*[a-e]\s*\)|Solution:|Solution :/', $english, -1, PREG_SPLIT_DELIM_CAPTURE);
                        // dd($englishParts);

                        $englishQuestion=trim($englishParts[1]) !='' ? '<p>'.trim($englishParts[1]).'</p>' : '';

                        $englishOptionA=trim($englishParts[2]) !='' ? '<p>'.trim($englishParts[2]).'</p>' : '';
                        $englishOptionB=trim($englishParts[3]) !='' ? '<p>'.trim($englishParts[3]).'</p>' : '';
                        $englishOptionC=trim($englishParts[4]) !='' ? '<p>'.trim($englishParts[4]).'</p>' : '';
                        $englishOptionD=trim($englishParts[5]) !='' ? '<p>'.trim($englishParts[5]).'</p>' : '';
                        $englishOptionE=trim($englishParts[6]) !='' ? '<p>'.trim($englishParts[6]).'</p>' : '';

                        $englishDes=trim($englishParts[7]) !='' ? '<p>'.trim($englishParts[7]).'</p>' : '';

                    } else {
                        $hindi=trim($questionPart[4]);
                        $english=trim($questionPart[5]);


                        // dd($hindi);
                        $hindiParts =preg_split('/Q:|Q :|\(\s*[a-e]\s*\)|Solution:|Solution :/', $hindi, -1, PREG_SPLIT_DELIM_CAPTURE);
                        // $englishParts =preg_split('/Q:|Q :|\(\s*[a-e]\s*\)|Solution:|Solution :/', $english, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
                        $englishParts =preg_split('/Q:|Q :|\(\s*[a-e]\s*\)|Solution:|Solution :/', $english, -1, PREG_SPLIT_DELIM_CAPTURE);
                        // dd($englishParts);

                        $hindiQuestion=trim($hindiParts[1]) !='' ?'<p>'.trim($hindiParts[1]).'</p>' : '';
                        $englishQuestion=trim($englishParts[1]) !='' ? '<p>'.trim($englishParts[1]).'</p>' : '';
                        // dd($hindiQuestion);
                        $englishOptionA=trim($englishParts[2]) !='' ? '<p>'.trim($englishParts[2]).'</p>' : '';
                        $englishOptionB=trim($englishParts[3]) !='' ? '<p>'.trim($englishParts[3]).'</p>' : '';
                        $englishOptionC=trim($englishParts[4]) !='' ? '<p>'.trim($englishParts[4]).'</p>' : '';
                        $englishOptionD=trim($englishParts[5]) !='' ? '<p>'.trim($englishParts[5]).'</p>' : '';
                        $englishOptionE=trim($englishParts[6]) !='' ? '<p>'.trim($englishParts[6]).'</p>' : '';

                        $hindiOptionA=trim($hindiParts[2]) !='' ? '<p>'.($hindiParts[2]).'</p>' : '';
                        $hindiOptionB=trim($hindiParts[3]) !='' ? '<p>'.($hindiParts[3]).'</p>' : '';
                        $hindiOptionC=trim($hindiParts[4]) !='' ? '<p>'.($hindiParts[4]).'</p>' : '';
                        $hindiOptionD=trim($hindiParts[5]) !='' ? '<p>'.($hindiParts[5]).'</p>' : '';
                        $hindiOptionE=trim($hindiParts[6]) !='' ? '<p>'.($hindiParts[6]).'</p>' : '';

                        $englishDes=trim($englishParts[7]) !='' ? '<p>'.trim($englishParts[7]).'</p>' : '';
                        $hindiDes=trim($hindiParts[7]) !='' ? '<p>'.trim($hindiParts[7]).'</p>' : '';

                    }
                    
                    
                    $questionBank = new QuestionBank();
                    $questionBank->subject_id =$request->subject;
                    $questionBank->chapter_id =$request->chapter;
                    $questionBank->question_type =$request->questionType;
                    $questionBank->language_type =$request->languageType;
                    if($level !=''){
                        $questionBank->level =$level;
                    }
                    
                    $questionBank->added_by=Auth::guard('admin')->user()->id;
                    $questionBank->save();


                    $questionInfo = new QuestionBankInfo();
                    $questionInfo->question_bank_id =intVal($questionBank->id);
                    $questionInfo->question =json_encode(
                        ['English'=>$request->languageType !='Hindi' ? $englishQuestion : '',
                        'Hindi'=>$request->languageType !='English' ? $hindiQuestion : ''
                        ]);
                    $questionInfo->option =json_encode(
                        ['English'=>$request->languageType !='Hindi' ?
                            [
                                $englishOptionA,
                                $englishOptionB,
                                $englishOptionC,
                                $englishOptionD,
                                $englishOptionE
                            ] : '',
                        'Hindi'=>$request->languageType !='English' ?
                            [
                                $hindiOptionA,
                                $hindiOptionB,
                                $hindiOptionC,
                                $hindiOptionD,
                                $hindiOptionE
                            ] : ''
                        ]);
                    $questionInfo->answer =strtoupper($answer);
                    $questionInfo->ans_desc =json_encode(
                        ['English'=>$request->languageType !='Hindi' ? $englishDes : '',
                        'Hindi'=>$request->languageType !='English' ? $hindiDes : ''
                        ]);
                    $questionInfo->added_by =Auth::guard('admin')->user()->id;;
                    $questionInfo->save();

                    //SAVING INTO PREVIOUS YEAR QUESTIONS
                        if ($previous_year != null) {
                            $splitString = preg_split('/,\s*(?=[A-Z])/', trim($previous_year));
                            foreach ($splitString as $pair) {
                                $pairArray = explode('-', $pair, 2);
                                $exam = $pairArray[0];
                                $yearArray = json_decode($pairArray[1], true);
                                foreach ($yearArray as $year) {
                                    $existingData = PreviousYearQuestion::where('exam', $exam)->where('year', $year)->first();
                                    if ($existingData) {
                                        $prevQueIds = json_decode($existingData->questionBankInfoId);
                                            array_push($prevQueIds, $questionBank->id);
                                        $existingData->questionBankInfoId = json_encode($prevQueIds);
                                        $existingData->save();
                                    } else {
                                        $questionArray = [];
                                        array_push($questionArray, $questionBank->id);
                                            $newQue = new PreviousYearQuestion();
                                            $newQue->exam = $exam;
                                            $newQue->year = $year;
                                            $newQue->questionBankInfoId = json_encode($questionArray);
                                            $newQue->save();
                                    }
                                }
                            }
                        }
                    //  END SAVING INTO PREVIOUS YEAR QUESTIONS

                    $rightInsertion[]=$questionId;
                    DB::commit();
                }catch (Exception $e) {
                    $badInsertion[]=$questionId;
                } // End Try Catch

            } // end if condition for $key !=0
        } // end foreach of question

        // if($badInsertion ==null){
        //     DB::commit();
        //     return response()->json(["statuscode" => '201', "message" => 'Successfully', "actionType"=>"001", "badInsertion"=>$badInsertion ]);
        // } else {
        //     return response()->json(["statuscode" => '401', "message" => 'Error On Question', "actionType"=>"004", "badInsertion"=>$badInsertion ]);
        // }
    
        return response()->json(["statuscode" => '201', "message" => 'Successfully', "actionType"=>count($badInsertion) ==0 ? '001' : '004', "badInsertion"=>$badInsertion ]);
        
        // dd($questionPart[5]);

        // $parts = preg_split('/(Q:|a.|b.|c.|d.|e. |Answer:|Tag:|Level:|Solution:)/', $questionPart[1]);
        
        // Assuming you have created a model named 'YourModelName'
        // YourModelName::insert($data);

    }

    public function bulkUploadQuestion(Request $request){
        if($request->method() == 'GET'){

            $subject=Subject::where('status', '=', '1')->get();

            return view('backend.questionBank.bulkUploadQuestion', ['subject'=>$subject]);
        }

        try{
            DB::beginTransaction();

            $file = $request->file('csvFile');
            if($file){
                $filename = $file->getClientOriginalName();
                $file = fopen($file, "r");
                $i = 0;
                $store_id = Auth::guard('admin')->user()->id;
                $errorArr=[];
                $questionArray = [];

                $badInsertion=[];
                while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {


                    $num = count($filedata);


                    if($i != 0){
                       
                        if(count($filedata) >16 AND $filedata[13] !=''){

                            try{

                                $level=$filedata[0];
                                $englishQuestion=$filedata[1] !='' ? '<p>'.$filedata[1].'</p>' : '';
                                $hindiQuestion=$filedata[2] !='' ? '<p>'.$filedata[2].'</p>' : '';
                                $englishOptionA=$filedata[3] !='' ? '<p>'.$filedata[3].'</p>' : '';
                                $hindiOptionA=$filedata[4] !='' ? '<p>'.$filedata[4].'</p>' : '';
                                $englishOptionB=$filedata[5] !='' ? '<p>'.$filedata[5].'</p>' : '';
                                $hindiOptionB=$filedata[6] !='' ? '<p>'.$filedata[6].'</p>' : '';
                                $englishOptionC=$filedata[7] !='' ? '<p>'.$filedata[7].'</p>' : '';
                                $hindiOptionC=$filedata[8] !='' ? '<p>'.$filedata[8].'</p>' : '';
                                $englishOptionD=$filedata[9] !='' ? '<p>'.$filedata[9].'</p>' : '';
                                $hindiOptionD=$filedata[10] !='' ? '<p>'.$filedata[10].'</p>' : '';
                                $englishOptionE=$filedata[11] !='' ? '<p>'.$filedata[11].'</p>' : '';
                                $hindiOptionE=$filedata[12] !='' ? '<p>'.$filedata[12].'</p>' : '';
                                $answer=strtoupper($filedata[13]);
                                $englishDes=$filedata[14] !='' ? '<p>'.$filedata[14].'</p>' : '';
                                $hindiDes=$filedata[15] !='' ? '<p>'.$filedata[15].'</p>' : '';
                                $previous_year = $filedata[16];

                                

                                $questionBank = new QuestionBank();
                                $questionBank->subject_id =$request->subject;
                                $questionBank->chapter_id =$request->chapter;
                                $questionBank->question_type =$request->questionType;
                                $questionBank->language_type =$request->languageType;
                                $questionBank->level =$level;

                                $questionBank->added_by=Auth::guard('admin')->user()->id;
                                $questionBank->save();

                                $questionInfo = new QuestionBankInfo();
                                $questionInfo->question_bank_id =intVal($questionBank->id);
                                $questionInfo->question =json_encode(
                                    ['English'=>$request->languageType !='Hindi' ? $englishQuestion : '',
                                    'Hindi'=>$request->languageType !='English' ? $hindiQuestion : ''
                                    ]);
                                $questionInfo->option =json_encode(
                                    ['English'=>$request->languageType !='Hindi' ?
                                        [
                                            $englishOptionA,
                                            $englishOptionB,
                                            $englishOptionC,
                                            $englishOptionD,
                                            $englishOptionE
                                        ] : '',
                                    'Hindi'=>$request->languageType !='English' ?
                                        [
                                            $hindiOptionA,
                                            $hindiOptionB,
                                            $hindiOptionC,
                                            $hindiOptionD,
                                            $hindiOptionE
                                        ] : '',
                                    ]);
                                $questionInfo->answer =$answer;
                                $questionInfo->ans_desc =json_encode(
                                    ['English'=>$request->languageType !='Hindi' ? $englishDes : '',
                                    'Hindi'=>$request->languageType !='English' ? $hindiDes : ''
                                    ]);
                                $questionInfo->added_by =Auth::guard('admin')->user()->id;
                                $questionInfo->save();

                                //SAVING INTO PREVIOUS YEAR QUESTIONS
                            if ($previous_year != null) {
                                $splitString = preg_split('/,\s*(?=[A-Z])/', trim($previous_year));

                                foreach ($splitString as $pair) {
                                    $pairArray = explode('-', $pair, 2);
                                    $exam = $pairArray[0];
                                    // dd($pairArray[1]);
                                    if (count($pairArray) >=2 ) {
                                        $arrYear = trim($pairArray[1], '[]');

                                        // Split the string into components
                                        $yearComponents = explode(',', $arrYear);
                                        // $yearArray = json_decode($pairArray[1], true);
                                        // dd($yearComponents);
                                        foreach ($yearComponents as $year) {
                                            $existingData = PreviousYearQuestion::where('exam', $exam)->where('year', $year)->first();
                                            if ($existingData) {
                                                $prevQueIds = json_decode($existingData->questionBankInfoId);
                                                    array_push($prevQueIds, $questionBank->id);
                                                $existingData->questionBankInfoId = json_encode($prevQueIds);
                                                $existingData->save();
                                            } else {
                                                $questionArray = [];
                                                array_push($questionArray, $questionBank->id);
                                                    $newQue = new PreviousYearQuestion();
                                                    $newQue->exam = $exam;
                                                    $newQue->year = $year;
                                                    $newQue->questionBankInfoId = json_encode($questionArray);
                                                    $newQue->save();
                                            }
                                        }
                                    } else {
                                         continue;
                                    }
                                    
                                }
                            }

                            }catch(Exception $e){
                                dd('hi');
                            }   

                        }else{
                            $badInsertion[]=$i+1;
                            // break;
                            continue;
                        }
                    }

     
                $i++;
                
            }
                if($badInsertion ==null){
                    DB::commit();
                    return response()->json(["statuscode" => '201', "message" => 'Successfully', "actionType"=>"001", "badInsertion"=>$badInsertion ]);
                } else {
                    return response()->json(["statuscode" => '401', "message" => 'Error On Question', "actionType"=>"004", "badInsertion"=>$badInsertion ]);
                }
            }
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['statuscode'=>'500', 'message'=>'Internal server error.. please contact to the Administrator!', 'errors'=>$e->getMessage()]);
        }
    }
    public function printQuestionSet(){
        return view('backend.questionBank.questionBankSet');
    }
}
