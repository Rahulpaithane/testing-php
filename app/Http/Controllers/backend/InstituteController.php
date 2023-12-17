<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant; 
use App\Models\Institute;
use App\Models\User;
use App\Models\QuestionBank;
use App\Models\QuestionBankInfo;
use App\Models\PreviousYearQuestion;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\ClassModel;
use App\Models\Batch;
use App\Models\Student;
use App\Models\ManageBook;
use Illuminate\Support\Facades\Mail; 
use App\Mail\InstitutePasswordReset;
use DataTables;
use Tenancy\Affects\Connections\Events\Resolving;
use Auth;


class InstituteController extends Controller
{
    public function searchDomain(Request $request){
        if(Tenant::where('id', $request->domain)->first() ) {
            return response()->json(["statusCode" => '404', "message" => 'Domain Not Exist. Try Another !']);
        } else {
            return response()->json(["statusCode" => '200', "message" => 'Domain Exist']);
        }
    }

    public function newInstitute(Request $request){
        if($request->method() == 'GET'){
         
            return view('backend.institute.newInstitute', ['domain'=>request()->getHost()]);
        }else {

            if(Tenant::where('id', $request->domainName)->first() ) {
                return response()->json(["statuscode" => '401', "message" => 'Domain Not Exist. Try Another !']);
            } else {

                // DB::beginTransaction();
                // try {

                    $tenant = Tenant::create(['id' => $request->domainName]);
                    $tenant->domains()->create(['domain' => $request->domainName.'.'.request()->getHost()]);

                    if(!$client=Institute::where('ownerEmailId', '=', $request->instituteOwnerEmail)->first() ) {
                        $client = new Institute();
                    }

                    $client->tenant_id = $request->domainName;
                    $client->institute_name = $request->instituteName;
                    $client->gstNo = '';
                    $client->address = $request->instituteAddress;
                    $client->contactNo = '';
                    $client->ownerName = $request->instituteOwnerName;
                    $client->ownerMobileNo = $request->instituteOwnerMobileNo;
                    $client->ownerEmailId = $request->instituteOwnerEmail;
                    $client->save();

                    $token=rand(100000, 1000000);

                    $tenant->run(function () use($request, $token) {
                        $ownerDetails=new User();
                        $ownerDetails->name=$request->instituteOwnerName;
                        $ownerDetails->email=$request->instituteOwnerEmail;
                        $ownerDetails->mobile=$request->instituteOwnerMobileNo;
                        $ownerDetails->role_type='Admin';
                        $ownerDetails->reset_token=$token;
                        // $ownerDetails->password=Hash::make($request->gymOwnerMobileNo);
                        $ownerDetails->save();

                    });

                    $resetToken = [
                        'domainId' =>$request->domainName,
                        'instituteName' => $request->instituteOwnerName,
                        'domain' => $request->domainName.'.'.request()->getHost(),
                        'reset_token' => encrypt($token),
                    ];

                    Mail::to($request->instituteOwnerEmail)->send(new InstitutePasswordReset($resetToken));

                    return response()->json(["statuscode" => '201', 'actionType'=>'001',  "message" => 'added successfully!']);

                // }catch (\Exception $e) {
                //     DB::rollback();

                //     // return redirect()->back()->with('error', 'something Wrong!');
                //     return response()->json(["statuscode" => '401', "message" => 'something Wrong!']);
                
                // }
            }

        }
    }

    public function instituteList(Request $request){
        if($request->method() == 'GET'){
            // dd($client=Institute::all());
            return view('backend.institute.instituteList', []);
        } else {

            $inst=Institute::query();

            return Datatables::eloquent($inst)
            ->addIndexColumn()
            ->addColumn('profile', function($data) use($request) {
                $button='<center>';
                $button.='<a href="'.route($request->routePath.'.instituteProfile',['id'=>$data->id]).'" class="btn btn-info"  >Profile</a>';
                $button.='</center>';
                return $button;
            })

            ->addColumn('userInterFace', function($data){
                $button='<center>';$button.='<a target="_blank" href="' . 'http://'.$data->tenant_id.'.'.request()->getHost().'" >'.$data->tenant_id.'</a>';
                
                $button.='</center>';
                return $button;
            })

            ->rawColumns(array("profile", "userInterFace"))
            ->make(true);
            
        }
    }

    public function instituteProfile(Request $request){
        $data=Institute::where('id', $request->id)->first();

        $tenant = Tenant::find($data->tenant_id);

        if ($tenant) {
            $userData = [];
            // Execute the callback within the context of the tenant
            $tenant->run(function () use($request, &$userData) {
                
                $userData['classes'] = ClassModel::all();
                $userData['batches'] = Batch::all();
                $userData['questions'] = QuestionBank::all();
                $userData['teachers'] = User::where('role_type', 'Teacher')->get();
                $userData['books'] = ManageBook::all();
                $userData['students'] = Student::all();
            });

            // dd($userData->count());
        }
            return view('backend.institute.profile.instituteProfile', ['data'=>$data, 'userData'=>$userData]);
    }

    public function importInstituteQuestionIndex(Request $request){
        if($request->method() == 'GET'){
            $instituteList=Institute::get();
            $classModel=ClassModel::where('status','1')->get();

            // $tenant = Tenant::find('vishal2');

            // if ($tenant) {
            //     $userData = null;
            //     // Execute the callback within the context of the tenant
            //     $tenant->run(function () use($request, &$userData) {
                    
            //         $userData=QuestionBank::with('subject', 'chapter', 'questionBankInfo');
                    
            //     });

            //     dd($userData);
            // }
            return view('backend.institute.importInstituteQuestion', ['instituteList'=>$instituteList, 'classModel'=>$classModel]);
        } else {

            $tenant = Tenant::find($request->tenant_id);
            // dd($tenant);
            if ($tenant) {
                $userData = null;
                // Execute the callback within the context of the tenant
                $tenant->run(function () use($request, &$userData) {
                    
                    $data2=QuestionBank::with('subject', 'chapter', 'questionBankInfo')->where('importStatus', 'Not Imported');

                    $userData= DataTables::eloquent($data2)
                    ->addIndexColumn()
                    ->addColumn('inputCheck', function ($data) {
                        $input='<input type="checkbox" class="checkOneRow" value="'.$data->id.'">';
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

                });

                return $userData;

                // dd($userData);

                
                
            } else {
                // Handle the case when the tenant with the given ID is not found
                dd('ss');
            }
        
        }
        
    }

    public function importQuestionTenantToAdmin(Request $request){
        // Log::info('Received data:', $request->quetionIdsArr);

        // ###########33 Tenant Data $$$$$$$$$$$$$$$$$$$$
            $tenantId=$request->tenantId;
            $questionId=json_decode($request->quetionIdsArr);
        // ###########33 Tenant Data $$$$$$$$$$$$$$$$$$$$

        // ###########33 Central Data $$$$$$$$$$$$$$$$$$$$
            // $classId=$request->class;
            $subject=$request->subject;
            $chapter=$request->chapter;
        // ###########33 Central Data $$$$$$$$$$$$$$$$$$$$
        $tenant = Tenant::find('vishal2');

        if ($tenant) {
            // Execute the callback within the context of the tenant
            $userData = $tenant->run(function () use ($request, $questionId) {
                $data = [];
                $data2 = QuestionBank::with('questionBankInfo')->whereIn('id',  $questionId)->get();
                foreach ($data2 as $questionBank) {
                    $questionBank['previousYear']=$questionBank->previousYearQuestions;
                    $data[] = [
                        'question' => $questionBank,
                        // 'previousYear' => $questionBank->previousYearQuestions,
                    ];
                }

                QuestionBank::whereIn('id',$questionId)
                  ->update(['importStatus' => 'Imported']);

                return $data; // Return the data from the callback
            });

            foreach ($userData as $data){
                // dd($data['question']);

                $questionBankIdd='';
                // $previous_year = $request->previousAskedYear;
                // if( ($data['question']->question_type == 'Normal') OR ( $data['question']->question_type == 'Group' AND $request->paragraphIdd ==''  )){

                    $questionBank = new QuestionBank();
                    $questionBank->subject_id =$subject;
                    $questionBank->chapter_id =$chapter;
                    $questionBank->question_type =$data['question']->question_type;
                    $questionBank->language_type =$data['question']->language_type;
                    $questionBank->level =$data['question']->level;
                    if($data['question']->question_type == 'Group'){
                        $questionBank->pragraph =$data['question']->pragraph;
                    }
                    $questionBank->added_by=Auth::guard('admin')->user()->id;
                    $questionBank->save();

                    $questionBankIdd=$questionBank->id;

                    foreach($data['question']->questionBankInfo as $info){
                        // dd($info);

                        $questionInfo = new QuestionBankInfo();
                        $questionInfo->question_bank_id =intVal($questionBankIdd);
                        $questionInfo->question =$info->question;
                        $questionInfo->option =$info->option;
                        $questionInfo->answer =$info->answer;
                        $questionInfo->ans_desc =$info->ans_desc;
                        $questionInfo->added_by =Auth::guard('admin')->user()->id;;
                        $questionInfo->save();
                    }

                    foreach($data['question']->previousYear as $preQues){
                        // dd($preQues);

                        $existingData = PreviousYearQuestion::where('exam', $preQues->exam)->where('year', $preQues->year)->first();
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

            return response()->json(['statuscode'=>'201', 'actionType'=>'001', 'message' => 'successfully']);

           

            // return response()->json(['message' => $a]);
        }
                
            
        // $questionId=[1587];
        // $filteredData = PreviousYearQuestion::whereJsonContains('questionBankInfoId', $questionId)->get();
        
        // $data2=QuestionBank::with('questionBankInfo')->get();
        
    }
}
