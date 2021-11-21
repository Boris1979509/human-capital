<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('users', UserController::class);
    $router->resource('contents', ContentController::class);
    $router->resource('cities', CityController::class);
    $router->resource('universities', UniversityController::class);
    $router->resource('dictionaries', DictionaryController::class);
    $router->resource('tags', TagController::class);
    $router->resource('selections', SelectionController::class);
    $router->resource('panels', PanelController::class);
    $router->resource('professions', ProfessionController::class);
    $router->resource('autocomplete-words', AutocompleteWordController::class);


    $router->group(['prefix' => 'api'], function (Router $router) {
        $router->get('selection_items', 'ApiController@getContentForSelectionItem')
            ->where(['type' => '[a-z\_]+', 'id' => 'integer', 'content_type' => 'nullable|integer']);
        $router->get('selection_items/{selection_item}/media', 'ApiController@getSelectionItemMedia');
        $router->get('update_autocomplete_words', 'ApiController@updateAutocompleteWords');
        ;
    });
});
