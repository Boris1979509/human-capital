<?php

use App\Http\Controllers\Api\CalendarEntriesController;
use App\Http\Controllers\Api\CheckController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CommentsController;
use App\Http\Controllers\Api\CurriculaController;
use App\Http\Controllers\Api\DictionaryController;
use App\Http\Controllers\Api\DislikesController;
use App\Http\Controllers\Api\Employer\EmployerPublicController;
use App\Http\Controllers\Api\Employer\EmployerVacanciesResponsesController;
use App\Http\Controllers\Api\Employer\ResponsesController;
use App\Http\Controllers\Api\Employer\UserVacanciesResponsesController;
use App\Http\Controllers\Api\Employer\VacanciesPublicController;
use App\Http\Controllers\Api\FavoritesController;
use App\Http\Controllers\Api\Institution\InstitutionCurriculaController;
use App\Http\Controllers\Api\Institution\InstitutionEmployeeController;
use App\Http\Controllers\Api\Institution\InstitutionPublicController;
use App\Http\Controllers\Api\Institution\InstitutionSettingController;
use App\Http\Controllers\Api\Employer\UserEmployerSettingController;
use App\Http\Controllers\Api\Journal\ContentsController;
use App\Http\Controllers\Api\Journal\ContentsManagementController;
use App\Http\Controllers\Api\Journal\EventRegistrationsController;
use App\Http\Controllers\Api\LikesController;
use App\Http\Controllers\Api\ProfessionController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SelectionController;
use App\Http\Controllers\Api\SubscriptionsController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\TemporaryUploadsController;
use App\Http\Controllers\Api\UIController;
use App\Http\Controllers\Api\UniversityController;
use App\Http\Controllers\Api\Institution\InstitutionController;
use App\Http\Controllers\Api\Employer\EmployerController;
use App\Http\Controllers\Api\Employer\VacanciesController;
use App\Http\Controllers\Api\UserPersonal\UserPersonalController;
use App\Http\Controllers\Api\UserPersonal\UserEducationController;
use App\Http\Controllers\Api\UserPersonal\UserJobController;
use App\Http\Controllers\Api\UserResumeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('signup', [AuthController::class, 'signup']);

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('user', [AuthController::class, 'user']);
    });
});

Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['auth:api']], function () {
    // Пользователь
//    Route::get('whoami', [AuthController::class, 'whoami'])->name('whoami');
//    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
//    Route::get('refresh', [AuthController::class, 'refresh'])->name('refresh');
//    Route::get('user', [AuthController::class, 'user'])->name('user');

    Route::resource('/', UserPersonalController::class, ['only' => ['index', 'store']]);
    Route::post('avatar', [UserPersonalController::class, 'avatar']);
    Route::delete('avatar', [UserPersonalController::class, 'avatarDelete']);
    Route::resource('education', UserEducationController::class, ['only' => ['store', 'destroy']]);
    Route::resource('job', UserJobController::class, ['only' => ['store', 'destroy']]);
    Route::get('resume', [UserResumeController::class, 'generate'])->name('resume');
    Route::get('calendar', [UserPersonalController::class, 'calendar'])->name('calendar');
});

Route::get('institutions/autocomplete', [InstitutionController::class, 'autocomplete'])
    ->name('institutions.autocomplete');

Route::group(['prefix' => 'lk', 'as' => 'institutions.', 'middleware' => 'auth:api'], function () {
    Route::resource('institutions', InstitutionController::class);
    Route::resource('institutions/{institution}/employees', InstitutionEmployeeController::class);
    Route::resource('institutions/{institution}/settings', InstitutionSettingController::class);
    Route::post('institutions/{institution}/avatar', [InstitutionController::class, 'avatar']);
    Route::delete('institutions/{institution}/avatar', [InstitutionController::class, 'avatarDelete']);

    Route::prefix('institutions/{institution}/curricula')->name('curricula.admin.')->group(static function () {
        Route::get('/', [InstitutionCurriculaController::class, 'index'])->name('index');
        Route::get('/{curriculum:id}', [InstitutionCurriculaController::class, 'show'])->name('show');
        Route::post('/', [InstitutionCurriculaController::class, 'create'])->name('create');
        Route::put('/{curriculum:id}', [InstitutionCurriculaController::class, 'update'])->name('update');
        Route::delete('/{curriculum:id}', [InstitutionCurriculaController::class, 'delete'])->name('delete');
    });

    Route::post('/institutions/{institution}/journal', [ContentsController::class, 'create'])
        ->name('contents.create');
    Route::get('/institutions/{institution}/journal', [ContentsManagementController::class, 'index'])
        ->name('contents.management.index');
    Route::get('/institutions/{institution}/calendar', [InstitutionController::class, 'calendar'])
        ->name('contents.calendar.index');
});

Route::prefix('/lk/employer/vacancies')->name('employer.management.vacancies.')
    ->middleware('auth:api')
    ->group(function () {
        Route::put('{vacancy}', [VacanciesController::class, 'update'])->name('update');
        Route::get('{vacancy}', [VacanciesController::class, 'show'])->name('show');
        Route::delete('{vacancy}', [VacanciesController::class, 'delete'])->name('delete');
    });


Route::prefix('/lk/employer/{employer?}')->middleware('auth:api')->name('employer.management.')->group(function () {
    Route::get('journal', [ContentsManagementController::class, 'index'])->name('journal.index');

    Route::post('/vacancies', [VacanciesController::class, 'create'])->name('vacancies.create');
    Route::get('/vacancies', [VacanciesController::class, 'index'])->name('vacancies.index');

    Route::get('/', [EmployerController::class, 'show'])->name('show');
    Route::post('/', [EmployerController::class, 'store']);
    Route::post('/journal', [ContentsController::class, 'create'])->name('journal.create');

    Route::resource('/settings', UserEmployerSettingController::class);

    Route::post('avatar', [EmployerController::class, 'avatar']);
    Route::delete('avatar', [EmployerController::class, 'avatarDelete']);

    Route::post('cover', [EmployerController::class, 'cover']);
    Route::delete('cover', [EmployerController::class, 'coverDelete']);

    Route::get('/calendar', [EmployerController::class, 'calendar']);
});

// отклики на вакансии

Route::middleware('auth:api')
    ->get('user/cvs', [UserResumeController::class, 'uploaded'])
    ->name('user.uploaded-cvs');

Route::middleware('auth:api')
    ->get('user/responses', [UserVacanciesResponsesController::class, 'index'])
    ->name('user.responses.index');

Route::middleware('auth:api')
    ->delete('user/responses/{response}', [ResponsesController::class, 'delete'])
    ->name('user.responses.delete');

Route::prefix('user/vacancies/')
    ->middleware('auth:api')
    ->name('user.vacancies.responses.')
    ->group(function () {
        Route::post('{vacancy}/responses', [UserVacanciesResponsesController::class, 'create'])->name('create');
    });

Route::prefix('/lk/employer/vacancies/')
    ->middleware('auth:api')
    ->name('employer.vacancies.')
    ->group(function () {
        Route::get('{vacancy}/responses', [EmployerVacanciesResponsesController::class, 'index'])->name('index');
    });

Route::prefix('/lk/employer/responses/{vacancyResponse}')
    ->middleware('auth:api')
    ->name('employer.responses.')
    ->group(function () {
        Route::post('/seen', [ResponsesController::class, 'seen'])->name('seen');
        Route::post('/accept', [ResponsesController::class, 'invite'])->name('invite');
        Route::post('/reject', [ResponsesController::class, 'reject'])->name('reject');
    });


Route::get('vacancies', [VacanciesPublicController::class, 'index'])->name('vacancies.index');
Route::get('vacancies/{vacancy}', [VacanciesPublicController::class, 'show'])->name('vacancies.public.show');
Route::get(
    'vacancies/{vacancy}/similar',
    [VacanciesPublicController::class, 'similar']
)->name('vacancies.public.similar');

Route::prefix('employer')->name('employer.public.')->group(function () {
    Route::get('/', [EmployerPublicController::class, 'index'])->name('index');
    Route::get('/summary', [EmployerPublicController::class, 'summary'])->name('summary');
    Route::get('/{employer}', [EmployerPublicController::class, 'show'])->name('show');
});

Route::get('check', [CheckController::class, 'index']);

Route::get('tags', [TagController::class, 'index']);
Route::get('cities', [CityController::class, 'index']);
Route::get('universities', [UniversityController::class, 'index']);
Route::get('professions', [ProfessionController::class, 'index']);
Route::get('dictionaries', [DictionaryController::class, 'index']);

Route::prefix('ui')->name('ui.')->group(static function () {
    Route::get('/panels', [UIController::class, 'panels'])->name('panels');
    Route::get('/autocomplete', [UIController::class, 'autocomplete'])->name('autocomplete');
});

Route::get('/curricula', [CurriculaController::class, 'index'])->name('curricula.index');
Route::get('/curricula/summary', [CurriculaController::class, 'summary'])->name('curricula.summary');
Route::get('/curricula/{curriculum}', [CurriculaController::class, 'show'])->name('curricula.show');
Route::get('/curricula/{curriculum}/similar', [CurriculaController::class, 'similar'])->name('curricula.similar');
Route::get('/programs/types/summary', [CurriculaController::class, 'typesSummary'])->name('curricula.types.summary');

Route::prefix('journal')->name('contents.')->group(static function () {
    Route::get('/', [ContentsController::class, 'index'])->name('index');
    Route::get('/{content}', [ContentsController::class, 'show'])->name('show');
    Route::put('/{content}', [ContentsController::class, 'update'])->name('update');
    Route::delete('/{content}', [ContentsController::class, 'delete'])->name('delete');
    Route::get('/{content}/similar', [ContentsController::class, 'similar'])->name('similar');
});

Route::post('uploads', [TemporaryUploadsController::class, 'store'])->name('uploads.store');

Route::get('region', [RegionController::class, 'index'])->name('region.index');

Route::get('region/summary', [RegionController::class, 'summary'])->name('region.summary');

Route::prefix('selections')->name('selections.')->group(static function () {
    Route::get('/', [SelectionController::class, 'index'])->name('index');
    Route::get('/{selection}', [SelectionController::class, 'show'])->name('show');
    Route::get('/{selection}/items', [SelectionController::class, 'items'])->name('items');
});

Route::prefix('/institutions')->name('institutions.public.')->group(static function () {
    Route::get('/', [InstitutionPublicController::class, 'index'])->name('index');
    Route::get('/summary', [InstitutionPublicController::class, 'summary'])->name('summary');
    Route::get('/{institution}', [InstitutionPublicController::class, 'show'])->name('show');
    Route::get('/{institution}/employees', [InstitutionPublicController::class, 'employees'])->name('employees');
});

Route::middleware('auth:api')->group(function () {
    Route::post('/{commentableType}/{commentableId}/comments', [CommentsController::class, 'create'])
        ->name('comments.create');

    Route::post('/{likeableType}/{likeableId}/like', [LikesController::class, 'create'])
        ->name('likes.create');

    Route::delete('/{likeableType}/{likeableId}/like', [LikesController::class, 'delete'])
        ->name('likes.delete');

    Route::post('/{dislikeableType}/{dislikeableId}/dislike', [DislikesController::class, 'create'])
        ->name('dislikes.create');

    Route::delete('/{dislikeableType}/{dislikeableId}/dislike', [DislikesController::class, 'delete'])
        ->name('dislikes.delete');

    Route::post('/{rateableType}/{rateableId}/rating', [RatingController::class, 'create'])
        ->name('ratings.create');

    Route::get('favorites/count', [FavoritesController::class, 'count'])
        ->name('favorites.count');

    Route::get('/{calendareableType}/{calendareableId}/calendarEntry', [CalendarEntriesController::class, 'show'])
        ->name('calendar_entry.show');

    Route::post('/{calendareableType}/{calendareableId}/calendarEntry', [CalendarEntriesController::class, 'create'])
        ->name('calendar_entry.create');

    Route::delete('/{calendareableType}/{calendareableId}/calendarEntry', [CalendarEntriesController::class, 'delete'])
        ->name('calendar_entry.delete');

    Route::post('/{subscribableType}/{subscribableId}/subscription', [SubscriptionsController::class, 'create'])
        ->name('subscriptions.create');

    Route::delete('/{subscribableType}/{subscribableId}/subscription', [SubscriptionsController::class, 'delete'])
        ->name('subscriptions.delete');

    Route::post('/{favoriteableType}/{favoriteableId}/favorite', [FavoritesController::class, 'create'])
        ->name('favorites.create');

    Route::delete('/{favoriteableType}/{favoriteableId}/favorite', [FavoritesController::class, 'delete'])
        ->name('favorites.delete');
});

Route::get('/{commentableType}/{commentableId}/comments', [CommentsController::class, 'index'])
    ->name('comments.index');

// Поиск
Route::get('search', [SearchController::class, 'index'])->name('search');

// Регистрация на мероприятие
Route::middleware('auth:api')
    ->prefix('/event/{event}/registrations')
    ->name('event-registrations.')
    ->group(function () {
        Route::get('/', [EventRegistrationsController::class, 'index'])->name('index');
        Route::get('/user', [EventRegistrationsController::class, 'show'])->name('show');
        Route::post('/', [EventRegistrationsController::class, 'create'])->name('create');
    });

Route::middleware('auth:api')
    ->prefix('event-registrations/')
    ->name('event-registrations.')
    ->group(function () {
        Route::post('accept', [EventRegistrationsController::class, 'accept'])->name('accept');
        Route::post('reject', [EventRegistrationsController::class, 'reject'])->name('reject');
        Route::delete('', [EventRegistrationsController::class, 'delete'])->name('delete');
    });
