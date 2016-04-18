<?php

$this->app->group(['middleware' => ''], function () {

    //SecureRoute Users
    $this->app->group(['prefix' => 'jobs', 'middleware' => ['AddOrigin', 'SecureRoute']], function () {
        $this->app->get('/', ['uses' => 'Jke\Jobs\Controllers\DesiredJobsController@index']);
        $this->app->post('/', ['uses' => 'Jke\Jobs\Controllers\DesiredJobsController@store']);
        $this->app->get('/{userId}', ['uses' => 'Jke\Jobs\Controllers\DesiredJobsController@show']);
        $this->app->delete('/{userId}/{desiredJobId}', ['uses' => 'Jke\Jobs\Controllers\DesiredJobsController@destroy']);
    });

    //SecureRoute Users
    $this->app->group(['prefix' => 'experiences', 'middleware' => ['AddOrigin', 'SecureRoute']], function () {
        $this->app->get('/', ['uses' => 'Jke\Jobs\Controllers\ExperiencesController@index']);
        $this->app->post('/', ['uses' => 'Jke\Jobs\Controllers\ExperiencesController@store']);
        $this->app->get('/{userId}', ['uses' => 'Jke\Jobs\Controllers\ExperiencesController@show']);
        $this->app->post('/{userId}', ['uses' => 'Jke\Jobs\Controllers\ExperiencesController@update']);
        $this->app->delete('/{userId}/{experienceId}', ['uses' => 'Jke\Jobs\Controllers\ExperiencesController@destroy']);
        $this->app->get('/{userId}/{experienceId}', ['uses' => 'Jke\Jobs\Controllers\ExperiencesController@showExperience']);
    });

    //SecureRoute Users
    $this->app->group(['prefix' => 'qualifications', 'middleware' => ['AddOrigin', 'SecureRoute']], function () {
        $this->app->get('/', ['uses' => 'Jke\Jobs\Controllers\QualificationsController@index']);
        $this->app->post('/', ['uses' => 'Jke\Jobs\Controllers\QualificationsController@store']);
        $this->app->get('/{userId}', ['uses' => 'Jke\Jobs\Controllers\QualificationsController@show']);
        $this->app->post('/{userId}', ['uses' => 'Jke\Jobs\Controllers\QualificationsController@update']);
        $this->app->delete('/{userId}/{qualificationId}', ['uses' => 'Jke\Jobs\Controllers\QualificationsController@destroy']);
    });

    //SecureRoute Users
    $this->app->group(['prefix' => 'certificates', 'middleware' => ['AddOrigin', 'SecureRoute']], function () {
        $this->app->get('/', ['uses' => 'Jke\Jobs\Controllers\CertificatesController@index']);
        $this->app->post('/', ['uses' => 'Jke\Jobs\Controllers\CertificatesController@store']);
        $this->app->get('/{userId}', ['uses' => 'Jke\Jobs\Controllers\CertificatesController@show']);
        $this->app->post('/{userId}', ['uses' => 'Jke\Jobs\Controllers\CertificatesController@update']);
        $this->app->delete('/{userId}/{certificateId}', ['uses' => 'Jke\Jobs\Controllers\CertificatesController@destroy']);
        $this->app->get('/{userId}/{certificateId}', ['uses' => 'Jke\Jobs\Controllers\CertificatesController@showCertificate']);
    });

    //SecureRoute References
    $this->app->group(['prefix' => 'references', 'middleware' => ['AddOrigin', 'CheckRoute']], function () {
        $this->app->get('/user/{referenceId}', ['uses' => 'Jke\Jobs\Controllers\ReferencesController@getUserByReference']);
    });

    //SecureRoute Users
    $this->app->group(['prefix' => 'references', 'middleware' => ['AddOrigin', 'SecureRoute']], function () {
        $this->app->post('/verified', ['uses' => 'Jke\Jobs\Controllers\ReferencesController@referenceVerified']);
        $this->app->get('/', ['uses' => 'Jke\Jobs\Controllers\ReferencesController@index']);
        $this->app->post('/', ['uses' => 'Jke\Jobs\Controllers\ReferencesController@store']);
        $this->app->get('/{userId}', ['uses' => 'Jke\Jobs\Controllers\ReferencesController@show']);
        $this->app->post('/{userId}', ['uses' => 'Jke\Jobs\Controllers\ReferencesController@update']);
        $this->app->delete('/{userId}/{referenceId}', ['uses' => 'Jke\Jobs\Controllers\ReferencesController@destroy']);
        $this->app->get('/{userId}/{referenceId}', ['uses' => 'Jke\Jobs\Controllers\ReferencesController@showReference']);
    });
});
