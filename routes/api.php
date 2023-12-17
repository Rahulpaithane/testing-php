<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\query\ExecuteQuery;

use App\Http\Controllers\api\StudentAuthController;
use App\Http\Controllers\api\StudentProfile;
use App\Http\Controllers\api\ExamController;
use App\Http\Controllers\api\PracticePaperController;
use App\Http\Controllers\api\OfflinePaperController;
use App\Http\Controllers\api\ManageBookController;
use App\Http\Controllers\api\ManageLiveQuizController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(ExecuteQuery::class)->group(function() {
    Route::get('/runQuery', 'runQuery');
});
 
Route::controller(StudentAuthController::class)->group(function () {
    Route::post('/signup', 'signup');
    Route::post('sendOtp', 'registeredEmailMobileSendOtp');
    Route::post('sendOtpWithoutReg', 'withoutRegisteredMobileSendOtp');
    Route::post('/loginWithPassword', 'loginWithPassword');
    Route::post('loginWithOtp', 'loginWithOtp');
    Route::post('forgotPassword', 'forgotPassword');
    Route::get('/unAuth', 'unAuth');
    Route::post('logout', 'logout');
    Route::get('refreshToken', 'refreshToken');
});
 
Route::group(['middleware' => 'jwt.verify'], function () {

    Route::controller(StudentProfile::class)->group(function () {
        Route::post('/preference', 'preference')->name('preference');
        Route::get('/viewProfile', 'viewProfile')->name('viewProfile');
        Route::post('/updateProfile', 'updateProfile')->name('updateProfile');
        Route::post('/activateClass', 'activateClass')->name('activateClass');
        Route::post('/activateBatch', 'activateBatch')->name('activateBatch');
        Route::get('/homeProfile', 'homeProfile')->name('homeProfile');
        Route::get('/transactionList', 'transactionList')->name('transactionList');
    });

    Route::controller(ExamController::class)->group(function () {
        Route::post('/allExam', 'allExam')->name('allExam');
        Route::post('/allBatch', 'allBatch')->name('allBatch');
        Route::post('/initiatePayment', 'initiatePayment')->name('initiatePayment');
        Route::post('/purchaseBatch', 'purchaseBatch')->name('purchaseBatch');
        // Route::post('payment/verify', [RazorpayController::class, 'verifyPayment']);
        Route::get('/myExam', 'myExam')->name('myExam');

    });

    Route::controller(PracticePaperController::class)->group(function () {
        Route::post('/practiceListCategory', 'practiceListCategory')->name('practiceListCategory');
        Route::post('/latestPracticeList', 'latestPracticeList')->name('latestPracticeList');
        Route::post('/practiceSetList', 'practiceSetList')->name('practiceSetList');
        Route::post('/attemptPracticeSet', 'attemptPracticeSet')->name('attemptPracticeSet');
        Route::post('/attemptPracticeSett', 'attemptPracticeSett')->name('attemptPracticeSett');
        Route::post('/submitPracticeSet', 'submitPracticeSet')->name('submitPracticeSet');
        Route::post('/resultPracticePaper', 'resultPracticePaper')->name('resultPracticePaper');
        Route::post('/resultQuestionAnalysis', 'resultQuestionAnalysis')->name('resultQuestionAnalysis');
    });

    Route::controller(OfflinePaperController::class)->group(function () {
        Route::post('/digitalPaperList', 'digitalPaperList')->name('digitalPaperList');
        Route::post('/digitalPaperQuestion', 'digitalPaperQuestion')->name('digitalPaperQuestion');
        Route::post('/submitDigitalPaper', 'submitDigitalPaper')->name('submitDigitalPaper');
        Route::post('/resultDigitalPaper', 'resultDigitalPaper')->name('resultDigitalPaper');
        Route::post('/searchOmrPaper', 'searchOmrPaper')->name('searchOmrPaper');
        Route::get('/attemptPaperList', 'attemptPaperList')->name('attemptPaperList');
    });

    Route::controller(ManageBookController::class)->group(function () {
        Route::post('/bookList', 'bookList')->name('bookList');
        Route::post('/purchaseBook', 'purchaseBook')->name('purchaseBook');
        Route::get('/myEBook', 'myEBook')->name('myEBook');
        Route::post('/viewEBook', 'viewEBook')->name('viewEBook');
    });

    Route::controller(ManageLiveQuizController::class)->group(function () {
        Route::post('/quizRoomList', 'quizRoomList')->name('quizRoomList');
        Route::get('/myQuizRoom', 'myQuizRoom')->name('myQuizRoom');
        Route::post('/purchaseQuizRoom', 'purchaseQuizRoom')->name('purchaseQuizRoom');
        Route::post('/initialRoomQuestion', 'initialRoomQuestion')->name('initialRoomQuestion');
        Route::post('/initialRoomQuestionforSequntial', 'initialRoomQuestionforSequntial')->name('initialRoomQuestionforSequntial');
        Route::post('/submitRoomQuestion', 'submitRoomQuestion')->name('submitRoomQuestion');

    });

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

require __DIR__ . '/instituteTecher.php';
