<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ChooseUsController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\SubcriptionController;
use App\Http\Controllers\UserRolesController;
use App\Http\Controllers\userMenusController;
use App\Http\Controllers\NewsLetterController;

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

Route::prefix('auth')->group(function () {
    // Apply middleware to the group of authentic
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/contact', [ContactUsController::class, 'insertContact']);
    Route::post('/subcription', [SubcriptionController::class, 'insertSubcription']);
    Route::post('/newsLetter', [NewsLetterController::class, 'insertNewsLetter']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/users', [UserController::class, 'users']);
        Route::get('/user/{userId}', [UserController::class, 'user']);
        Route::put('/user/profile/{userId}', [UserController::class, 'updateUserProfile']);

        Route::prefix('menu')->group(function () {
            Route::post('/insertMenu', [MenuController::class, 'insertMenu']);
            Route::get('/menus', [MenuController::class, 'menus']);
            Route::get('/active-menus', [MenuController::class, 'getActiveMenus']);
            Route::get('/menus/{menuId}', [MenuController::class, 'getActiveMenu']);
            Route::put('/updateMenu/{menuId}', [MenuController::class, 'updateMenu']);
        });

        Route::prefix('submenu')->group(function () {
            Route::post('/submenu', [MenuController::class, 'insertSubMenu']);
            Route::get('/submenus', [MenuController::class, 'submenus']);
            Route::get('/submenu/{subMenuId}', [MenuController::class, 'submenu']);
            Route::put('/submenu/{subMenuId}', [MenuController::class, 'updateSubMenu']);
        });

        Route::prefix('role')->group(function () {
            Route::post('/insertRole', [MenuController::class, 'insertRole']);
            Route::get('/roles', [MenuController::class, 'getRoles']);
            Route::get('/active-roles', [MenuController::class, 'getActiveRoles']);
            Route::get('/roles/{roleId}', [MenuController::class, 'getRole']);
            Route::put('/roles/{roleId}', [MenuController::class, 'updateRole']);
        });

        Route::prefix('userRoles')->group(function () {
            Route::post('/userRole', [UserRolesController::class, 'insertUserRole']);
            Route::get('/userRole/{userId}', [UserRolesController::class, 'getUserRoles']);
            Route::put('/userRole', [UserRolesController::class, 'updateUserRoles']);
        });

        Route::prefix('userMenus')->group(function () {
            Route::post('/userMenu', [userMenusController::class, 'insertUserMenu']);
        });

        Route::prefix('service')->group(function () {
            Route::post('/type', [ServiceController::class, 'insertServiceType']);
            Route::get('/types', [ServiceController::class, 'getServiceTypes']);
            Route::get('/type/{typeId}', [ServiceController::class, 'getServiceType']);
            Route::put('/type/{typeId}', [ServiceController::class, 'updateServiceType']);
            Route::post('/postService', [ServiceController::class, 'insertService']);
            Route::get('/services', [ServiceController::class, 'getActiveServices']);
            Route::get('/services/{serviceId}', [ServiceController::class, 'getActiveService']);
            Route::put('/updateService/{serviceId}', [ServiceController::class, 'updateService']);
        });

        Route::prefix('chooseUs')->group(function () {
            Route::post('/choose', [ChooseUsController::class, 'insertChooseUs']);
            Route::get('/chooses', [ChooseUsController::class, 'getActiveChooseUs']);
            Route::get('/choose/{chooseId}', [ChooseUsController::class, 'getActiveSigleChooseUs']);
            Route::put('/choose/{chooseId}', [ChooseUsController::class, 'updateChooseUs']);
        });

        Route::prefix('faq')->group(function () {
            Route::post('/faq', [FAQController::class, 'insertFAQ']);
            Route::get('/faqs', [FAQController::class, 'getActiveFAQ']);
            Route::get('/faq/{faqId}', [FAQController::class, 'getActiveSigleFAQ']);
            Route::put('/faq/{faqId}', [FAQController::class, 'updateFAQ']);
        });

        Route::prefix('blogs')->group(function () {
            Route::post('/blog', [BlogController::class, 'insertBlog']);
            Route::get('/blogs', [BlogController::class, 'getActiveBlogs']);
            Route::get('/blog/{blogId}', [BlogController::class, 'getActiveBlog']);
            Route::put('/blog/{blogId}', [BlogController::class, 'updateBlog']);
        });

        Route::prefix('contacts')->group(function () {
            Route::get('/contacts', [ContactUsController::class, 'getContacts']);
            Route::get('/contact/{contactId}', [ContactUsController::class, 'getContact']);
        });

        Route::prefix('newsletter')->group(function () {
            Route::get('/newsletters', [NewsLetterController::class, 'getNewsLetter']);
        });

        Route::prefix('subcriptions')->group(function () {
            Route::get('/subcriptions', [SubcriptionController::class, 'getSubcriptions']);
            Route::get('/subcription/{subcriptionId}', [SubcriptionController::class, 'getSubcription']);
        });
    });
});

