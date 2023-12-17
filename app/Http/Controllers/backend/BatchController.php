<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\Batch;
use App\Models\BatchCategory;
use App\Models\BatchSubCategory; 
use DataTables;

class BatchController extends Controller
{
    //TO MANAGE THE BATCHES
    public function batchManage(Request $request){
        if($request->method() == 'GET'){
            
            // $batchData = Batch::get();
            // dd(json_encode($batchData[0]->batchCategory[0]->batchSubCategory));
            // $batchData = Batch::with(['batchCategory.batchSubCategory' => function ($query) {
            //     $query->select('sub_category_name');
            // }])->get();
            // dd($batchData);

        //     $batchModel = new batch();
        // $batchModel->getPurchaseAttribute(1);
        // $batchModel->studentLedger(Auth::guard('api')->user()->id);
        

        // $data=batch::get();

        // dd($data);
            $classCategory = ClassModel::where('status', 1)->get();
            return view('backend.batch.batchList', ['classCategory'=>$classCategory]);
        }

        if($request->batchId =='') {
            $batch = new Batch();
        } else {
            $batch=Batch::where('id', '=', $request->batchId)->first();
            $existCat=BatchCategory::where('batch_id', $request->batchId)
            ->pluck('id')
            ->all();

            $deletedCatSub = array_diff($existCat, $request->batchCategory);

            $as= BatchCategory::whereIn('id', $deletedCatSub)
                    ->where('batch_id', $request->batchId)
                    ->delete();

                    BatchSubCategory::whereIn('batch_category_id', $deletedCatSub)
                    ->where('batch_id', $request->batchId)
                    ->delete();
        }

        if (isset($request->batchImage) != null) {
            $filename = uniqid() . $request->batchImage->getClientOriginalName();
            $path = 'images/'.$filename;
            $request->batchImage->move(public_path('images'), $filename);
            }else{
                $path = $request->existing_batch_image;
            }
        // dd($request->class);
        $batch->class_id = intVal($request->class);
        $batch->name = $request->batchName;
        $batch->badge =$request->badge;
        $batch->start_date = $request->startDate;
        $batch->end_date = $request->endDate;
        $batch->batch_type =$request->batchType;
        $batch->batch_price = $request->batchPrice;
        $batch->batch_offer_price = $request->batchOfferPrice;
        $batch->description =$request->description;
        $batch->batch_image = $path;
        $batch->save();
            // echo count($request->batchCategory);
        if($request->batchCategory !=null && count($request->batchCategory) >=1){
            $subKey=0;
            foreach ($request->batchCategory as $key => $cat) {
                if($request->batchCategoryId[$key] =='' || !$batchCategory=BatchCategory::where('id', '=', $request->batchCategoryId[$key])->first() ) {
                    $batchCategory = new BatchCategory();
                }
                
                $batchCategory->batch_id = $batch->id;
                $batchCategory->category_name = $cat;
                $batchCategory->save();

                $subArray = explode(",", $request->batchSubCategory[$subKey +1]);
                

                if($request->batchCategoryId[$key] !=''){
                    $existingTags = BatchSubCategory::where('batch_id', $batch->id)
                    ->where('batch_category_id', $batchCategory->id)
                    ->pluck('sub_category_name')
                    ->all();

                    // Remove any deleted tags from the database
                    $deletedTags = array_diff($existingTags, $subArray);
                    // echo json_encode($existingTags);
                    // echo json_encode($subArray);
                    // echo json_encode($deletedTags);
                   $as= BatchSubCategory::whereIn('sub_category_name', $deletedTags)
                    ->where('batch_id', $batch->id)
                    ->where('batch_category_id', $batchCategory->id)
                    ->delete();
                }
                

                foreach($subArray as $sKey => $sub){
                    $existingTag = BatchSubCategory::
                    where('sub_category_name', $sub)
                    ->where('batch_id', $batch->id)
                    ->where('batch_category_id', $batchCategory->id)
                    ->first();
                    if(!$existingTag){
                        $batchSubCategory = new BatchSubCategory();
                        $batchSubCategory->batch_id = $batch->id;
                        $batchSubCategory->batch_category_id = $batchCategory->id;
                        $batchSubCategory->sub_category_name = $sub;
                        $batchSubCategory->save();
                    }
                }
                $subKey+=2;
            }
        }
        $callback=['load_data'];
        $closedPopup='myModal';
        return response()->json(["statuscode" => '201', "message" => 'Batch Created Successfully!', "actionType"=>"003", "callback"=>$callback, "closedPopup"=>$closedPopup]);
    }

    //TO SHWO THE LIST OF ADDED BATCHES
    public function batchList(Request $request){
        $batchData = Batch::with('batchCategory.batchSubCategory');

        return DataTables::eloquent($batchData)
        ->addIndexColumn()

        ->addColumn('batchType', function ($data) use($request) {
            $popularType='<center>';
            if ($data->is_popular == '0') {
                $popularType.= '<a href="javascript:void(0);" class="toggleBatchPopular alert alert-success" style="text-decoration:none;" data="' . route($request->routePath.'.updateBatchPopular') . '" id="'. $data->id .'"  status_data="'. $data->is_popular .'" >Normal</a>';
            } else {
                $popularType.= '<a href="javascript:void(0);" class="toggleBatchPopular alert alert-danger" style="text-decoration:none;" data="' . route($request->routePath.'.updateBatchPopular') . '" id="'. $data->id .'"  status_data="'. $data->is_popular .'" >Popular</a>';
            }
            $popularType.='</center>';
            return $popularType; 
        })

        ->addColumn('status', function ($data) use($request) {
            $status='<center>';
            if ($data->status == '1') {
                $status= '<a href="javascript:void(0);" class="toggleStatus alert alert-success" style="text-decoration:none;" data="' . route($request->routePath.'.updateBatchStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >Active</a>';
            } else {
                $status= '<a href="javascript:void(0);" class="toggleStatus alert alert-danger" style="text-decoration:none;" data="' . route($request->routePath.'.updateBatchStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >In-Active</a>';
            }
            $status.='</center>';
            return $status; 
        })
        ->addColumn('action', function($data) use($request) {
            $button='<center>';
            $button .= '<a href="javascript:void(0);" onClick="editBatch(' . htmlentities(json_encode($data)) . ')" class="edit_batch" data="' . route($request->routePath.'.batchManage') . '" id="' . $data->id . '"><i class="fas fa-edit bg-light" style="font-size:20px; color:green;"></i></a>';
            $button.='&#160;&#160;&#160;&#160;';
            $button.='  <a href="javascript:void(0);" class="delete2023" url="'.route($request->routePath.'.deleteBatch').'" id="'.$data->id.'"  ><i class="fas fa-trash-alt bg-light" style="font-size:20px;color:red;"></i></a>';
            $button.='</center>';
            return $button;
        })
        ->addColumn('profile', function($data) {
            $button = '<section>';
            foreach ( $data->batchCategory as $key => $value) {
                $button .= '<div class="row">
                                <div class="col-md-3 text-wrap"><span class="badge badge-danger text-wrap">' . $value->category_name . '</span></div>
                                <div class="col-md-7">';
                foreach ( $value->batchSubCategory as $skey => $sub) {
                    $button .= '<span class="badge badge-success">' . $sub->sub_category_name . '</span>&#160;&#160;&#160; &#160;';
                }
                $button .= '</div>
                            </div>';
            }
            $button .= '</section>';
            return $button;
        })
        ->addColumn('batch_image', function($data){
            $url = asset($data->batch_image);
            $img='<a href="'.$url.'" target="_blank" class=""><img src="'.$url.'" width="40px" height="40px" class="img-rounded" align="center" alt="file" /></a>';
            return $img;
        })
        ->rawColumns(array("profile", 'batch_image', 'batchType', 'status', 'action'))
        ->make(true);
    }

    public function updateBatchPopular(Request $request){
        $record = Batch::find($request->id);
        $record->is_popular = $request->status;
        $record->save();
        return response()->json(["statuscode" => '001', "message" => 'Type Updated Successfully!!' ]);
    }

    public function updateBatchStatus(Request $request){
        $record = Batch::find($request->id);
        $record->status = $request->status;
        $record->save();
        return response()->json(["statuscode" => '001', "message" => 'Status Updated Successfully!!' ]);
    }

     public function deleteBatch(Request $request){
        try{
            DB::beginTransaction();
            $batch = Batch::with('batchCategory.batchSubCategory')->find($request->id);

            $batch->batchCategory()->each(function ($batch) {
                $batch->batchSubCategory()->delete();
            });

            $batch->batchCategory()->delete();
            $batch->delete();
            $batch->save();
            DB::commit();
            return response()->json(["statuscode" => '001', "message" => 'Record Deleted Successfully!!' ]);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return response()->json(["statuscode" => '000', 'errors' => $e->getMessage()], 500);
        }
    }
}
