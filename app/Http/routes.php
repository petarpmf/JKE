<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



$app->get('/', function() use ($app) {
        return $app->welcome();
});


$app->group(['middleware'=>['AddOrigin']], function($app)  {
        $app->get('/media/display/{id}',['uses'=>'App\Http\Controllers\MediaController@displayMedia']);
        $app->get('/media/download/{id}/{originalName}',['uses'=>'App\Http\Controllers\MediaController@downloadMedia']);

        $app->get('/media/upload',['uses'=>'App\Http\Controllers\MediaController@check']);
        $app->post('/media/upload',['uses'=>'App\Http\Controllers\MediaController@store']);

        $app->get('/users/upload',['uses'=>'App\Http\Controllers\UsersController@check']);
        $app->post('/users/upload',['uses'=>'App\Http\Controllers\UsersController@upload']);

});

$app->group(['middleware' => 'AddOrigin'], function ($app) {
        $app->post('/register', ['uses' => 'App\Http\Controllers\UsersController@store']);

        $app->post('/login', ['uses' => 'App\Http\Controllers\LoginController@login']);
        $app->get('/logout', ['uses' => 'App\Http\Controllers\LoginController@logout']);
        $app->post('/forgot-password', ['uses' => 'App\Http\Controllers\LoginController@forgotPassword']);
        $app->post('/reset-password', ['uses' => 'App\Http\Controllers\LoginController@resetPassword']);
        $app->post('/valid-forgot-token', ['uses' => 'App\Http\Controllers\LoginController@validForgotToken']);

        //SecureRoute Dashboard
        $app->group(['prefix' => 'seeking-job-positions', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
             $app->get('/', ['uses' => 'App\Http\Controllers\DashboardController@seekingJobPositions']);
        });

        //SecureRoute Dashboard
        $app->group(['prefix' => 'total-active-candidates', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->get('/', ['uses' => 'App\Http\Controllers\DashboardController@totalActiveCandidates']);
        });

        //SecureRoute Dashboard
        $app->group(['prefix' => 'recently-added-candidates', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->get('/', ['uses' => 'App\Http\Controllers\DashboardController@recentlyAddedCandidates']);
        });

        //SecureRoute Dashboard
        $app->group(['prefix' => 'number-of-projects', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->get('/', ['uses' => 'App\Http\Controllers\DashboardController@numberOfProjects']);
        });

        //SecureRoute Dashboard
        //$app->group(['prefix' => 'recent-activity', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                //$app->get('/', ['uses' => 'App\Http\Controllers\DashboardController@recentActivity']);
        //});

        //SecureRoute Dashboard
        $app->group(['prefix' => 'recent-activity-view-all', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->get('/', ['uses' => 'App\Http\Controllers\DashboardController@recentActivityViewAll']);
        });

        //SecureRoute Dashboard
        $app->group(['prefix' => 'recent-project-team-activity', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->get('/{userId}', ['uses' => 'App\Http\Controllers\DashboardController@recentProjectTeamActivity']);
        });
        //SecureRoute Dashboard
        //$app->group(['prefix' => 'recent-created', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
               // $app->get('/', ['uses' => 'App\Http\Controllers\DashboardController@recentCreated']);
        //});

        //SecureRoute Dashboard
        $app->group(['prefix' => 'recent-updated', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->get('/', ['uses' => 'App\Http\Controllers\DashboardController@recentUpdated']);
        });

        //SecureRoute Dashboard
        $app->group(['prefix' => 'recent-logged', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->get('/', ['uses' => 'App\Http\Controllers\DashboardController@recentLogged']);
        });
		//SecureRoute Users
        //Personal details
        $app->group(['prefix' => 'personal-details', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
            $app->post('/', ['uses' => 'App\Http\Controllers\PersonalDetailsController@store']);
            $app->get('/{userId}', ['uses' => 'App\Http\Controllers\PersonalDetailsController@show']);
            $app->post('/store-additional', ['uses' => 'App\Http\Controllers\PersonalDetailsController@storeAdditional']);
        });

        //SecureRoute Users
        $app->group(['prefix' => 'users', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                //get from rigzone
                $app->get('/rigzone/{rigzoneId}', ['uses' => 'App\Http\Controllers\UsersController@getFromRigzone']);
                $app->post('/change-password', ['uses' => 'App\Http\Controllers\UsersController@changePassword']);

                $app->get('/project-inspectors', ['uses' => 'App\Http\Controllers\UsersController@indexInspectors']);
                $app->get('/', ['uses' => 'App\Http\Controllers\UsersController@index']);
                $app->post('/', ['uses' => 'App\Http\Controllers\UsersController@store']);
                $app->get('/{id}', ['uses' => 'App\Http\Controllers\UsersController@show']);
                $app->post('/{id}', ['uses' => 'App\Http\Controllers\UsersController@update']);
                $app->patch('/{id}', ['uses' => 'App\Http\Controllers\UsersController@restore']);
                $app->delete('/{id}', ['uses' => 'App\Http\Controllers\UsersController@destroy']);
        });

        //Delete profile image
        $app->group(['prefix' => 'deleteProfile', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
              $app->delete('/{userId}', ['uses' => 'App\Http\Controllers\UsersController@deleteProfile']);
        });

        //Delete resume
        $app->group(['prefix' => 'deleteResume', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->delete('/{userId}', ['uses' => 'App\Http\Controllers\UsersController@deleteResume']);
        });

        //Delete resume
        $app->group(['prefix' => 'contact-us', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->post('/', ['uses' => 'App\Http\Controllers\UsersController@contactUs']);
        });

        //SecureRoute Roles
        $app->group(['prefix' => 'roles', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->get('/', ['uses' => 'App\Http\Controllers\RolesController@index']);
                $app->post('/', ['uses' => 'App\Http\Controllers\RolesController@store']);
                $app->get('/{id}', ['uses' => 'App\Http\Controllers\RolesController@show']);
                $app->post('/{id}', ['uses' => 'App\Http\Controllers\RolesController@update']);
                $app->patch('/{id}', ['uses' => 'App\Http\Controllers\RolesController@restore']);
                $app->delete('/{id}', ['uses' => 'App\Http\Controllers\RolesController@destroy']);
        });
		
		//SecureRoute Media Collectiond
        $app->group(['prefix' => 'media-collections', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->get('/', ['uses' => 'App\Http\Controllers\MediaCollectionController@index']);
                $app->post('/', ['uses' => 'App\Http\Controllers\MediaCollectionController@store']);
                $app->get('/{id}', ['uses' => 'App\Http\Controllers\MediaCollectionController@show']);
                $app->post('/{id}', ['uses' => 'App\Http\Controllers\MediaCollectionController@update']);
                $app->delete('/{id}', ['uses' => 'App\Http\Controllers\MediaCollectionController@destroy']);
        });

        $app->group(['prefix'=>'media','middleware'=>['AddOrigin','SecureRoute']], function($app)  {
                $app->get('/all/move/{from}/{to}',['uses'=>'App\Http\Controllers\MediaController@move']);
                $app->get('/{id}/move/{to}',['uses'=>'App\Http\Controllers\MediaController@moveMedia']);
                $app->get('/',['uses'=>'App\Http\Controllers\MediaController@index']);
                $app->get('/{id}',['uses'=>'App\Http\Controllers\MediaController@show']);
                $app->delete('/{id}',['uses'=>'App\Http\Controllers\MediaController@destroy']);
        });		
        $app->get('secure', ['middleware' => 'SecureRoute', 'uses' => 'App\Http\Controllers\UsersController@index']);

        //SecureRoute Companies
        $app->group(['prefix' => 'companies', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->get('/upload',['uses'=>'App\Http\Controllers\CompaniesController@check']);
                $app->post('/upload',['uses'=>'App\Http\Controllers\CompaniesController@upload']);
                $app->delete('/delete-logo/{id}', ['uses' => 'App\Http\Controllers\CompaniesController@deleteLogo']);

                $app->get('/', ['uses' => 'App\Http\Controllers\CompaniesController@index']);
                $app->post('/', ['uses' => 'App\Http\Controllers\CompaniesController@store']);
                $app->get('/{id}', ['uses' => 'App\Http\Controllers\CompaniesController@show']);
                $app->post('/{id}', ['uses' => 'App\Http\Controllers\CompaniesController@update']);
                $app->delete('/{id}', ['uses' => 'App\Http\Controllers\CompaniesController@destroy']);
        });

        //SecureRoute Projects
        $app->group(['prefix' => 'projects', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->get('/staff/{projectId}', ['uses' => 'App\Http\Controllers\ProjectsController@getAllStaff']);
                $app->post('/staff', ['uses' => 'App\Http\Controllers\ProjectsController@storeStaff']);
                $app->post('/staff/{projectId}', ['uses' => 'App\Http\Controllers\ProjectsController@updateStaff']);
                $app->delete('/staff/{projectId}/{staffId}', ['uses' => 'App\Http\Controllers\ProjectsController@destroyStaff']);

                $app->get('/by-company/{companyId}', ['uses' => 'App\Http\Controllers\ProjectsController@getByCompanyId']);

                $app->get('/candidates/project/{projectId}', ['uses' => 'App\Http\Controllers\ProjectsController@getAllCandidatesInProject']);
                $app->post('/candidates', ['uses' => 'App\Http\Controllers\ProjectsController@storeCandidate']);
                $app->get('/candidates/{staffId}', ['uses' => 'App\Http\Controllers\ProjectsController@getAllCandidate']);
                $app->delete('/candidates/{staffId}/{userId}', ['uses' => 'App\Http\Controllers\ProjectsController@destroyCandidate']);



                $app->get('/activated', ['uses' => 'App\Http\Controllers\ProjectsController@getAllActive']);

                $app->post('/additional-fields/{projectId}', ['uses' => 'App\Http\Controllers\ProjectsController@storeAdditional']);
                $app->get('/', ['uses' => 'App\Http\Controllers\ProjectsController@index']);
                $app->post('/', ['uses' => 'App\Http\Controllers\ProjectsController@store']);
                $app->get('/{projectId}', ['uses' => 'App\Http\Controllers\ProjectsController@show']);
                $app->post('/{projectId}', ['uses' => 'App\Http\Controllers\ProjectsController@update']);
                $app->delete('/{projectId}', ['uses' => 'App\Http\Controllers\ProjectsController@destroy']);
        });

        //Teams
        $app->group(['prefix' => 'teams', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->get('/', ['uses' => 'App\Http\Controllers\TeamsController@index']);
                $app->post('/', ['uses' => 'App\Http\Controllers\TeamsController@store']);
                $app->get('/{id}', ['uses' => 'App\Http\Controllers\TeamsController@show']);
                $app->post('/{id}', ['uses' => 'App\Http\Controllers\TeamsController@update']);
                $app->delete('/{id}', ['uses' => 'App\Http\Controllers\TeamsController@destroy']);
                $app->post('/{id}/assign', ['uses' => 'App\Http\Controllers\TeamsController@assign']);
                $app->post('/{id}/revoke', ['uses' => 'App\Http\Controllers\TeamsController@revoked']);
        });

        //SecureRoute Innermetrix
        $app->group(['prefix' => 'innermetrix', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->post('/', ['uses' => 'App\Http\Controllers\InnermetrixController@store']);
                $app->get('/{userId}', ['uses' => 'App\Http\Controllers\InnermetrixController@show']);
                $app->post('/{userId}', ['uses' => 'App\Http\Controllers\InnermetrixController@update']);
                $app->delete('/{userId}', ['uses' => 'App\Http\Controllers\InnermetrixController@destroy']);
        });

        //SecureRoute Clients
        $app->group(['prefix' => 'clients', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->get('/', ['uses' => 'App\Http\Controllers\ClientsController@index']);
                $app->post('/', ['uses' => 'App\Http\Controllers\ClientsController@store']);
                $app->get('/{id}', ['uses' => 'App\Http\Controllers\ClientsController@show']);
                $app->post('/{id}', ['uses' => 'App\Http\Controllers\ClientsController@update']);
                $app->delete('/{id}', ['uses' => 'App\Http\Controllers\ClientsController@destroy']);
        });

        //SecureRoute Scoring Templates
        $app->group(['prefix' => 'scoring-templates', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                //$app->get('/assign/{templateId}/desired-job/{desiredJobProjectId}/{type}', ['uses' => 'App\Http\Controllers\ScoringTemplatesController@assignTemplateToProjectDesiredJob']);
                //$app->get('/revoke/{templateId}/desired-job/{desiredJobProjectId}/{type}', ['uses' => 'App\Http\Controllers\ScoringTemplatesController@deleteTemplateFromProjectDesiredJob']);
                $app->get('/', ['uses' => 'App\Http\Controllers\ScoringTemplatesController@index']);
                $app->post('/', ['uses' => 'App\Http\Controllers\ScoringTemplatesController@store']);
                $app->get('/{id}', ['uses' => 'App\Http\Controllers\ScoringTemplatesController@show']);
                $app->post('/{id}', ['uses' => 'App\Http\Controllers\ScoringTemplatesController@update']);
                $app->patch('/{id}', ['uses' => 'App\Http\Controllers\ScoringTemplatesController@restore']);
                $app->delete('/{id}', ['uses' => 'App\Http\Controllers\ScoringTemplatesController@destroy']);
        });

        //SecureRoute Scoring
        $app->group(['prefix' => 'scorings', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->get('/automatic/{userId}', ['uses' => 'App\Http\Controllers\ScoringsController@showAutomatic']);
                $app->get('/', ['uses' => 'App\Http\Controllers\ScoringsController@index']);
                $app->post('/', ['uses' => 'App\Http\Controllers\ScoringsController@store']);
                $app->get('/{userId}', ['uses' => 'App\Http\Controllers\ScoringsController@show']);
                //$app->post('/{id}', ['uses' => 'App\Http\Controllers\ScoringsController@update']);
                $app->delete('/{userId}', ['uses' => 'App\Http\Controllers\ScoringsController@destroy']);
        });

        //SecureRoute References
        $app->group(['prefix' => 'reference-qualifications', 'middleware' => ['AddOrigin', 'CheckRoute']], function ($app) {
                $app->post('/', ['uses' => 'App\Http\Controllers\ReferenceQualificationController@store']);
                $app->post('/note', ['uses' => 'App\Http\Controllers\ReferenceQualificationController@storeNote']);
                $app->get('/note/{referenceId}', ['uses' => 'App\Http\Controllers\ReferenceQualificationController@showNote']);
                $app->get('/{referenceId}', ['uses' => 'App\Http\Controllers\ReferenceQualificationController@show']);
                $app->post('/{referenceId}', ['uses' => 'App\Http\Controllers\ReferenceQualificationController@update']);
                $app->delete('/{referenceId}/{qualificationId}', ['uses' => 'App\Http\Controllers\ReferenceQualificationController@destroy']);
        });

        //SecureRoute References
        $app->group(['prefix' => 'reference-qualifications', 'middleware' => ['AddOrigin', 'SecureRoute']], function ($app) {
                $app->get('/send-audit/{referenceId}', ['uses' => 'App\Http\Controllers\ReferenceQualificationController@sendAuditForm']);
        });
});