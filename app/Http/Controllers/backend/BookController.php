<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManageBook;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Carbon\Carbon;

class BookController extends Controller
{
    public function manageBooks(Request $request){
        try{
            if($request->method() == 'GET'){
                return view('backend.books.booksList');
            }

            $validatedData = $request->validate([
                'book_type' => 'required',
                'book_name' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'publication' => 'required|string|max:255',
                'class' => 'required',
                'is_payable' => 'required',
                // 'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if (isset($request->thumbnail) != null) {
                $filename = uniqid() . $request->thumbnail->getClientOriginalName();
                $imgPath = 'images/'.$filename;
                $request->thumbnail->move(public_path('images'), $filename);
                }else{
                    $imgPath = $request->existing_thumnail_image;
                }

            if (isset($request->attachment) != null) {
                $maxFileSize = 10 * 1024 * 1024;
                if ($request->attachment->getSize() <= $maxFileSize) {

                    $filename = uniqid() . $request->attachment->getClientOriginalName();
                    $filePath = 'attachments/'.$filename;
                    $request->attachment->move(public_path('attachments'), $filename);
                } else {
                    // File size exceeds the limit
                    return response()->json(["statuscode" => '400', 'message' => 'upload maxime file 10 MB.']);
                }
                }else{
                    $filePath = $request->existing_attachment;
                }

            $user_id = Auth::guard('admin')->user()->id;
            
            if($request->bookId =='') {
                $book = new ManageBook();
            } else {
                $book=ManageBook::where('id', '=', $request->bookId)->first();
            }

            $book->book_type = $request->book_type;
            $book->book_name = $request->book_name;
            $book->author = $request->author;
            $book->publication = $request->publication;
            $book->class =$request->class;
            $book->is_payable = $request->is_payable;
            $book->stock = $request->stock;
            $book->price = $request->price;
            $book->thumbnail = $imgPath;
            $book->description = $request->description;
            $book->attachment = $filePath;
            $book->added_by = $user_id;
            $book->save();

            $callback=['load_data'];
            $closedPopup='myModal';
            return response()->json(["statuscode" => '201', "message" => 'Book has been added Successfully!!', "actionType"=>"003", "callback"=>$callback, 'closedPopup'=>$closedPopup]);
        }catch(\ValidationException $e){
            Log::error($e->getMessage());
            return response()->json(['errors' =>$e->getMessage()], 422);
        }
     }

     //
    public function booksList(Request $request){
        try{

            $books = ManageBook::query();
            return DataTables::eloquent($books)
            ->addIndexColumn()
            ->addColumn('status', function ($data) use($request) {
                if ($data->status == '1') {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-success" style="text-decoration:none;" data="' . route($request->routePath.'.updateBookStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >Active</a>';
                } else {
                    $status = '<a href="javascript:void(0);" class="toggleStatus alert alert-danger" style="text-decoration:none;" data="' . route($request->routePath.'.updateBookStatus') . '" id="'. $data->id .'"  status_data="'. $data->status .'" >In-Active</a>';
                }
                return $status;
            })
            ->addColumn('action', function($data) use($request) {
                $button='<center>';
                $button .= '<a href="javascript:void(0);" onClick="editBook(' . htmlentities(json_encode($data)) . ')" class="edit_batch" data="' . route($request->routePath.'.manageBooks') . '" id="' . $data->id . '"><i class="fas fa-edit bg-light" style="font-size:20px; color:green;"></i></a>';
                $button.='&#160;&#160;&#160;&#160;';
                $button.='  <a href="javascript:void(0);" class="delete2023" url="'.route($request->routePath.'.deleteBook').'" id="'.$data->id.'"  ><i class="fas fa-trash-alt bg-light" style="font-size:20px;color:red;"></i></a>';
                $button.='</center>';
                return $button;
            })
            ->addColumn('imgFile', function($data){
                $url = $data->thumbnail;
                $img='<a href="'.$url.'" target="_blank" class=""><img src="'.$url.'" width="40px" height="40px" class="img-rounded" align="center" alt="file" /></a>';
                return $img;
            })
            ->addColumn('pdfFile', function($data){
                $url = asset($data->attachment);
                $attachment='<a href="'.$url.'" target="_blank" class="">View File</a>';
                return $attachment;
            })
            ->rawColumns(array("status", "imgFile",'pdfFile', "action"))
            ->make(true);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
        }
     }

     public function updateBookStatus(Request $request){
        $record = ManageBook::find($request->id);
        $record->status = $request->status;
        $record->save();
        return response()->json(["statuscode" => '001', "message" => 'Status Updated Successfully!!' ]);
     }

     public function deleteBook(Request $request){
        try{
            $book = ManageBook::find($request->id);
            $book->delete();

            DB::commit();
            return response()->json(["statuscode" => '001', "message" => 'Record Deleted Successfully!!' ]);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return response()->json(["statuscode" => '000', 'errors' => $e->getMessage()], 500);
        }
    }
}
