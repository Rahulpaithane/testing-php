<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;


use App\Http\Controllers\CacheController;
use App\Http\Controllers\backend\AuthController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\BatchController;
use App\Http\Controllers\backend\QuestionBankController;
use App\Http\Controllers\backend\ExamPaperController;
use App\Http\Controllers\backend\ClassController;
use App\Http\Controllers\backend\SubjectController;
use App\Http\Controllers\backend\TeacherController;
use App\Http\Controllers\backend\MyAccountController;
use App\Http\Livewire\ViewPaper;
use App\Http\Livewire\ViewOmrPaper;
use App\Http\Controllers\backend\OmrDigitalController;
use App\Http\Controllers\backend\BookController;
use App\Http\Controllers\backend\StudentController;
use App\Http\Controllers\backend\transactionController;
use App\Http\Controllers\backend\InstituteController;
use App\Http\Controllers\backend\ManageLiveQuizController;
use App\Http\Controllers\backend\BannerController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Route::prefix('/')->name('tenantAdmin.')->group(function () {

        //ROUTE GROUP FOR AUTH CONTROLLER
        Route::controller(AuthController::class)->group(function () {
            Route::get('/', 'login')->name('login');
            Route::get('/login', 'login')->name('login2');
            Route::post('login-auth', 'loginAuth')->name('login-auth');
            Route::get('forgot-password', 'forgotPassword')->name('forgotPassword');
            Route::post('password-reset', 'passwordReset')->name('password-reset');
            Route::match(['get', 'post'], 'auth-password-reset/{key}', 'authPasswordReset', ['authPasswordReset'])->name('auth-password-reset');
            Route::match(['get', 'post'], 'institute-password-reset/{key}/{id}', 'institutePasswordReset', ['institutePasswordReset'])->name('institutePasswordReset');
        });
    
     // MIDDLEWARE WILL APPLAY ON BELOW GROUPED ROUTES
        Route::group(['middleware' => ['auth:admin']], function () {
    
            Route::controller(DashboardController::class)->group(function () {
                Route::get('/dashboard', 'dashboard')->name('dashboard');
                Route::post('totalStudentInBatch', 'totalStudentInBatch')->name('totalStudentInBatch');
                Route::post('latestTransactionHistory', 'latestTransactionHistory')->name('latestTransactionHistory');
    
            });
    
             //ROUTE GROUP FOR MY ACCOUNT  CONTROLLER
             Route::controller(MyAccountController::class)->group(function () {
                Route::get('profile', 'profile')->name('profile');
                Route::get('sign-out', 'signOut')->name('signOut');
                Route::post('updatePassword', 'updatePassword')->name('updatePassword');
                Route::post('updateProfile', 'updateProfile')->name('updateProfile');
            });
    
            //ROUTE GROUP TO MANAGE ALL BATCHES
            Route::controller(BatchController::class)->group(function () {
                Route::match(['get', 'post'], 'batchManage', 'batchManage')->name('batchManage');
                Route::post('batchList', 'batchList')->name('batchList');
                Route::post('batch-status', 'updateBatchStatus')->name('updateBatchStatus');
                Route::post('delete-batch', 'deleteBatch')->name('deleteBatch');
            });
    
            //ROUTE GROUP TO MANAGE CLASSES
            Route::controller(ClassController::class)->group(function () {
                Route::match(['get', 'post'], 'manage-classes', 'manageClasses')->name('manageClasses');
                Route::post('classes-list', 'classesList')->name('classesList');
                Route::post('class-status', 'updateClassStatus')->name('updateClassStatus');
                Route::post('classesDelete', 'classesDelete')->name('classesDelete');
            });
    
            //ROUTE GROUP TO MANAGE SUBJECTS AND CHAPTERS
            Route::controller(SubjectController::class)->group(function () {
                Route::match(['get', 'post'], 'manage-subjects', 'manageSubject')->name('manageSubject');
                Route::post('subject-chapter-list', 'subjectChpatersList')->name('subjectChpatersList');
                Route::post('fetchChapter', 'fetchChapter')->name('fetchChapter');
                Route::post('subject-status', 'updateSubjcetStatus')->name('updateSubjcetStatus');
                Route::post('delete-subject', 'deleteSubject')->name('deleteSubject');
            });
    
            //ROUTE GROUP TO MANAGE Teachers Module
            Route::controller(TeacherController::class)->group(function () {
                Route::match(['get', 'post'], 'manage-teachers', 'manageTeachers')->name('manageTeachers');
                Route::post('teachers-list', 'teachersList')->name('teachersList');
                Route::post('teacher-status', 'updateTeacherStatus')->name('updateTeacherStatus');
                Route::post('delete-teacher', 'deleteTeacher')->name('deleteTeacher');
            });
    
            //ROUTE GROUP TO MANAGE Question Bank Module
            Route::controller(QuestionBankController::class)->group(function () {
                Route::match(['get', 'post'], "questionManage", 'questionManage')->name('questionManage');
                Route::match(['get', 'post'], "questionBankList", 'questionBankList')->name('questionBankList');
                Route::post('paragraphQuestionBankList', 'paragraphQuestionBankList')->name('paragraphQuestionBankList');
                Route::match(['get', 'post'], "bulkUploadQuestion", 'bulkUploadQuestion')->name('bulkUploadQuestion');
                Route::post('question-status', 'updateQuestionStatus')->name('updateQuestionStatus');
                Route::post('deleteQuestionBank', 'deleteQuestionBank')->name('deleteQuestionBank');
                Route::post('deleteQuestion', 'deleteQuestion')->name('deleteQuestion');
                Route::match(['get', 'post'], "editQuestion/{id}", 'editQuestion')->name('editQuestion');
                Route::get('/print-question-set', 'printQuestionSet')->name('printQuestionSet');
    
                Route::match(['get', 'post'], 'bulkWordOfficeUploadQuestion', 'bulkWordOfficeUploadQuestion')->name('bulkWordOfficeUploadQuestion');
    
            });
    
            //ROUTE GROUP TO MANAGE Books Module
            Route::controller(BookController::class)->group(function () {
                Route::match(['get', 'post'], "manage-books", 'manageBooks')->name('manageBooks');
                Route::post('books-list', 'booksList')->name('booksList');
                Route::post('book-status', 'updateBookStatus')->name('updateBookStatus');
                Route::post('delete-book', 'deleteBook')->name('deleteBook');
            });
    
            //ROUTE TO MANAGE EXAM PAPERS CONTROLLERS
            Route::controller(ExamPaperController::class)->group(function () {
                Route::match(['get', 'post'], "createPaper", 'createPaper')->name('createPaper');
                Route::post('insertPaper', 'insertPaper')->name('insertPaper');
                Route::match(['get', 'post'], "managePaper", 'managePaper')->name('managePaper');
    
                Route::post('examPaperStatus', 'examPaperStatus')->name('examPaperStatus');
                Route::post('examPaperDelete', 'examPaperDelete')->name('examPaperDelete');
    
                // Route::get('viewPaper/{id}', 'viewPaper')->name('viewPaper');
                Route::post('fetchClassToBatch', 'fetchClassToBatch')->name('fetchClassToBatch');
                Route::post('fetchClassToSubject', 'fetchClassToSubject')->name('fetchClassToSubject');
                Route::post('fetchBatchToPaperCategory', 'fetchBatchToPaperCategory')->name('fetchBatchToPaperCategory');
               
                Route::post('fetchPaperCategoryToPaperSubCategory', 'fetchPaperCategoryToPaperSubCategory')->name('fetchPaperCategoryToPaperSubCategory');
                // Route::match(['get', 'post'], "questionBankList", 'questionBankList')->name('questionBankList');
            });
            Route::get("viewPaper/{id}", ViewPaper::class )->name('viewPaper');
    
            Route::controller(OmrDigitalController::class)->group(function () {
                Route::match(['get', 'post'], "new-digital-paper", 'newDigitalPaper')->name('newDigitalPaper');
                Route::match(['get', 'post'], "new-collection-paper", 'newOmrCollectionPaper')->name('newOmrCollectionPaper');
                Route::match(['get', 'post'], "digitalPaperList", 'digitalPaperList')->name('digitalPaperList');
                Route::post('collection-omr-list', 'collectionOmrList')->name('collectionOmrList');
                Route::post('digitalPaperStatus', 'digitalPaperStatus')->name('digitalPaperStatus');
                Route::post('digitalPaperDelete', 'digitalPaperDelete')->name('digitalPaperDelete');
    
            });
            Route::get("view-omr-paper/{id}", ViewOmrPaper::class )->name('viewOmrPaper');
    
    
            Route::controller(StudentController::class)->group(function () {
                Route::match(['get', 'post'], "manageStudent", 'manageStudent')->name('manageStudent');
                Route::post('updateStudentStatus', 'updateStudentStatus')->name('updateStudentStatus');
                Route::post('deleteStudent', 'deleteStudent')->name('deleteStudent');
    
            });
    
            Route::controller(transactionController::class)->group(function () {
                Route::match(['get', 'post'], "studentTransactionController", 'studentTransactionController')->name('studentTransactionController');
            });
    
            Route::controller(ManageLiveQuizController::class)->group(function () {
                Route::match(['get', 'post'], 'quizRoomManage', 'quizRoomManage')->name('quizRoomManage');
                Route::post('quizRoomList', 'quizRoomList')->name('quizRoomList');
                Route::post('quizRoomStatus', 'quizRoomStatus')->name('quizRoomStatus');
                Route::post('quizRoomDelete', 'quizRoomDelete')->name('quizRoomDelete');
    
                Route::match(['get', 'post'], 'quizQuestionManage/{id}', 'quizQuestionManage')->name('quizQuestionManage');  
                Route::post('quizQuestionList', 'quizQuestionList')->name('quizQuestionList');          
            });
    
            Route::controller(InstituteController::class)->group(function () {
                Route::post("searchDomain", 'searchDomain')->name('searchDomain');
                Route::match(['get', 'post'], "newInstitute", 'newInstitute')->name('newInstitute');
        
                Route::match(['get', 'post'], "instituteList", 'instituteList')->name('instituteList');
                Route::get("instituteProfile/{id}", "instituteProfile")->name("instituteProfile");
    
                Route::match(['get', 'post'], "importInstituteQuestion", 'importInstituteQuestionIndex')->name('importInstituteQuestionIndex');
                Route::post("importQuestionTenantToAdmin", "importQuestionTenantToAdmin")->name("importQuestionTenantToAdmin");
    
            });
    
            Route::controller(BannerController::class)->group(function () {
                Route::match(['get', 'post'], 'bannerManage', 'bannerManage')->name('bannerManage');
                Route::post('bannerList', 'bannerList')->name('bannerList');
                Route::post('bannerStatus', 'bannerStatus')->name('bannerStatus');
                Route::post('bannerDelete', 'bannerDelete')->name('bannerDelete');
    
            });
    
            
    
        });
    
    });

 

    
});
