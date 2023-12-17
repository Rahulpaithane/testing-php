<?php 
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\teacher\TeacherAuthController;
use App\Http\Controllers\api\teacher\TeacherProfileController;
use App\Http\Controllers\api\teacher\TeacherTeachController;
use App\Http\Controllers\api\teacher\TeacherQuestionManagement;


Route::prefix('institute')->name('institute.')->group(function () {

    Route::controller(TeacherAuthController::class)->group(function () {
        Route::post('/signup', 'signup');
        Route::post('sendOtp', 'registeredEmailMobileSendOtp');
        Route::post('sendOtpWithoutReg', 'withoutRegisteredMobileSendOtp');
        Route::post('/loginWithPassword', 'loginWithPassword');
        Route::post('loginWithOtp', 'loginWithOtp');
        Route::get('refreshToken', 'refreshToken');
        Route::post('forgotPassword', 'forgotPassword');
        Route::get('/unAuth', 'unAuth'); 
        Route::post('logout', 'logout');
        
    });

    Route::controller(TeacherTeachController::class)->group(function () {
        Route::post('/guestSetContainerDataInfo', 'guestSetContainerDataInfo');
    });

    Route::group(['middleware' => 'jwt.instituteVerify'], function () {

        Route::controller(TeacherProfileController::class)->group(function () {
            Route::post('/preference', 'preference')->name('preference');
            Route::get('/viewProfile', 'viewProfile')->name('viewProfile');
            Route::post('/updateProfile', 'updateProfile')->name('updateProfile');
            Route::post('/resetPassword', 'resetPassword')->name('resetPassword');;
            Route::post('/activateBatch', 'activateBatch')->name('activateBatch');
            Route::get('/homeProfile', 'homeProfile')->name('homeProfile');
            Route::get('/transactionList', 'transactionList')->name('transactionList');
        });

        Route::controller(TeacherTeachController::class)->group(function () {
            Route::post('/homeExam', 'homeExam');
            Route::post('/setList', 'setList');
            Route::post('/webSetList', 'webSetList');
            
            Route::post('/deleteSet', 'deleteSet');
            Route::post('/createNewSet', 'createNewSet');
            Route::post('/editSet', 'editSet');

            Route::post('/createCategory', 'createCategory');
            Route::post('/createSubCategory', 'createSubCategory');

            Route::post('/webSetContainerInfo', 'webSetContainerInfo');
            Route::get('/subjectFilter', 'subjectFilter');
            Route::post('/chapterFilter', 'chapterFilter');
            Route::post('/submitFilter', 'submitFilter');
            Route::post('/webSubmitFilter', 'webSubmitFilter');

            Route::post('/updateMultipleQuestionAssign', 'updateMultipleQuestionAssign');
            Route::post('/questionAssign', 'questionAssign');

            Route::post('/setcontainerDataInfo', 'setcontainerDataInfo');

            Route::post('/updateOmrViaPdf', 'updateOmrViaPdf');


        });
        
         Route::controller(TeacherQuestionManagement::class)->group(function () {
            Route::post('/createNewQuestion', 'createNewQuestion');
        });

    });

});

?>