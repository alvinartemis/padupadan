<?php

use App\Http\Controllers\LogoutController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StylistAuthController;
use App\Http\Controllers\SetPreferenceController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\DigitalWardrobeController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LookbookController;
use App\Http\Controllers\StylistProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatStylistController;
use App\Http\Controllers\UploadVideoController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [VideoController::class, 'home'])->name('home');
Route::get('/api/videos', [VideoController::class, 'index'])->name('api.videos');
Route::post('/comments', [CommentController::class, 'store'])->name('api.comments.store');


Route::middleware('auth')->group(function () {
    Route::get('/upload', function () {return view('upload');})->name('upload');
    Route::post('/upload-video', [UploadVideoController::class, 'uploadAndRedirect'])->name('upload.video');
    Route::get('/upload/detail/{id}', [UploadVideoController::class, 'showVideoDetail'])->name('upload.detail');
    Route::post('/upload/post-final/{id}', [UploadVideoController::class, 'finalPost'])->name('upload.final.post');
    Route::post('/upload/discard', [UploadVideoController::class, 'discardUpload'])->name('upload.discard');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
});

Route::get('/stylist/login', [App\Http\Controllers\StylistAuthController::class, 'showLoginForm'])->name('stylist.login');
Route::post('/stylist/login', [App\Http\Controllers\StylistAuthController::class, 'login'])->name('stylist.login.submit');
Route::post('/stylist/logout', [App\Http\Controllers\StylistAuthController::class, 'logout'])->name('stylist.logout');

Route::middleware('auth:stylist')->group(function () {
    Route::get('/homestylist', [App\Http\Controllers\StylistAuthController::class, 'dashboard'])->name('stylist.homestylist');
});

Route::middleware('auth:')->group(function () {
    Route::get('/set-preference/{step?}', [SetPreferenceController::class, 'index'])->name('set_preference.index');
    Route::post('/set-preference/save/{step}', [SetPreferenceController::class, 'saveStep'])->name('set_preference.save_step');
    Route::get('/quiz/countdown', [SetPreferenceController::class, 'showCountdown'])->name('set_preference.countdown');
    Route::get('/set-preference/result', [SetPreferenceController::class, 'showResult'])->name('set_preference.result');
    Route::post('/set-preference/complete', [SetPreferenceController::class, 'complete'])->name('set_preference.complete');
});

Route::get('/settings/editprofile', [EditProfileController::class, 'editprofile'])->name('profile.edit');
Route::put('/profile/update', [EditProfileController::class, 'update'])->name('profile.update');
Route::get('/settings/bookmark', [BookmarkController::class, 'bookmark'])->name('bookmark');
Route::get('/settings/bookmark/item/{id}', [BookmarkController::class, 'showItem'])->name('bookmark.show_item');

Route::get('/digital-wardrobe', [App\Http\Controllers\DigitalWardrobeController::class, 'index'])
    ->name('digital.wardrobe.index')
    ->middleware('auth');
Route::get('/digital-wardrobe/create', [App\Http\Controllers\DigitalWardrobeController::class, 'create'])
    ->name('digital.wardrobe.create')
    ->middleware('auth');
Route::post('/digital-wardrobe/process-photo', [App\Http\Controllers\DigitalWardrobeController::class, 'processPhoto'])
    ->name('digital.wardrobe.processPhoto')
    ->middleware('auth');
Route::post('/digital-wardrobe/store', [App\Http\Controllers\DigitalWardrobeController::class, 'storeDetails'])
    ->name('digital.wardrobe.storeDetails')
    ->middleware('auth');
Route::post('/digital-wardrobe/discard-temporary-photo', [App\Http\Controllers\DigitalWardrobeController::class, 'discardTemporaryPhoto'])
    ->name('digital.wardrobe.discardTemporaryPhoto')
    ->middleware('auth');
Route::get('/digital-wardrobe/{koleksiPakaian}', [App\Http\Controllers\DigitalWardrobeController::class, 'show'])
    ->name('digital.wardrobe.show')
    ->middleware('auth');
Route::get('/digital-wardrobe/{koleksiPakaian}/edit', [App\Http\Controllers\DigitalWardrobeController::class, 'edit'])
    ->name('digital.wardrobe.edit')
    ->middleware('auth');
Route::put('/digital-wardrobe/{koleksiPakaian}', [App\Http\Controllers\DigitalWardrobeController::class, 'update'])
    ->name('digital.wardrobe.update')
    ->middleware('auth');
Route::delete('/digital-wardrobe/{koleksiPakaian}', [App\Http\Controllers\DigitalWardrobeController::class, 'destroy'])
    ->name('digital.wardrobe.destroy')
    ->middleware('auth');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'processEmailAndShowOtpForm'])->name('password.email.otp');
Route::get('/otp-verification', [ForgotPasswordController::class, 'showOtpForm'])->name('password.otp.form');
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.otp.verify');
Route::get('/reset-password-form', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/reset-password-update', [ForgotPasswordController::class, 'resetPassword'])->name('password.update.new');

Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/stylists', [App\Http\Controllers\ChatController::class, 'getStylists'])->name('chat.stylists');
    Route::get('/chat/stylist/{stylist}', [App\Http\Controllers\ChatController::class, 'showChatWithStylist'])->name('chat.show');
    Route::get('/chat/stylist/{stylist}/profile', [App\Http\Controllers\ChatController::class, 'showProfileStylist'])->name('chat.profilestylist');
    Route::post('/chat/stylist/{stylist}/send', [App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/stylist/{stylist}/messages', [App\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/message/{pesan}/read', [App\Http\Controllers\ChatController::class, 'markAsRead'])->name('chat.read');

    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/api/search/recent', [SearchController::class, 'getRecentSearches'])->name('api.search.recent');
});

Route::middleware(['auth:stylist'])->group(function () {
    Route::get('/stylist/chat', [ChatStylistController::class, 'index'])->name('chat.indexstylist');
    Route::get('/stylist/chat/{user}', [ChatStylistController::class, 'showChatWithUser'])->name('chat.showstylist');
    Route::post('/stylist/chat/{user}/send', [ChatStylistController::class, 'sendMessage'])->name('chat.sendMessageStylist');
    Route::get('/stylist/chat/{user}/messages', [ChatStylistController::class, 'getMessages'])->name('chat.getMessagesStylist');
    Route::post('/stylist/chat/messages/{pesan}/read', [ChatStylistController::class, 'markAsRead'])->name('chat.markAsReadStylist');
    Route::get('/stylist/chat/{user}/profile', [ChatStylistController::class, 'showProfileUser'])->name('chat.profileuser');

    Route::get('/stylist/lookbook', [LookbookController::class, 'index'])->name('lookbook.index');
    Route::get('/stylist/lookbook/create', [LookbookController::class, 'create'])->name('lookbook.create');
    Route::post('/stylist/lookbook', [LookbookController::class, 'store'])->name('lookbook.store');
});


Route::get('/lookbook', [LookbookController::class, 'userIndex'])->name('user.lookbook.index');
Route::get('/lookbook/{lookbook}', [LookbookController::class, 'show'])->name('lookbook.show');
Route::get('/lookbook/tags/suggestions', [LookbookController::class, 'getTagSuggestions'])->name('lookbook.tags.suggestions');


Route::middleware('auth')->group(function () {
    Route::post('/bookmark/lookbook/{lookbook}', [BookmarkController::class, 'toggleLookbookBookmark'])->name('bookmark.lookbook.toggle');
    Route::post('/bookmark/video/{video}', [BookmarkController::class, 'toggleVideoBookmark'])->name('bookmark.video.toggle'); // <-- TAMBAHKAN INI
    Route::get('/settings/bookmark', [BookmarkController::class, 'bookmark'])->name('bookmark');
});
