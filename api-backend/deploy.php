<?php
namespace Deployer;

require 'recipe/laravel.php';


// Project repository
set('repository', 'git@gitlab.com:rosatom/hcap/api-backend.git');

set('default_stage', 'develop');

// [Optional] Allocate tty for git clone. Default value is false.
// set('git_tty', true);

// Shared files/dirs between deploys
//add('shared_dirs', ['node_modules']);

// Writable dirs by web server
//add('writable_dirs', []);
set('allow_anonymous_stats', false);
//set('http_user', 'deployer');

$host_dev = 'api.hcap.d.rusatom.dev';
$host_prod = 'api.hcap.rusatom.dev';

host($host_dev)
    ->set('application', $host_dev)
    ->user('deployer')
    ->set('branch', 'develop')
    ->stage('develop')
    ->set('keep_releases', 2)
    ->set('deploy_path', '/var/www/{{application}}')
;

host($host_prod)
    ->set('application', $host_prod)
    ->user('deployer')
    ->set('branch', 'master')
    ->stage('production')
    ->set('keep_releases', 5)
    ->set('deploy_path', '/var/www/{{application}}')
;

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
before('deploy:symlink', 'artisan:migrate');

before('deploy:symlink', 'artisan:laravel-admin');
#before('deploy:symlink', 'artisan:laravel-admin-grid-lightbox');
before('deploy:symlink', 'artisan:laravel-admin-ckeditor');
before('deploy:symlink', 'artisan:laravel-api-cors');
#before('deploy:symlink', 'artisan:laravel-admin-summernote');
#before('deploy:symlink', 'artisan:laravel-admin-daterangepicker');
#before('deploy:symlink', 'artisan:laravel-horizon');
//before('deploy:symlink', 'artisan:laravel-admin-chartjs');

//before('deploy:symlink', 'npm:run-prod');
before('deploy:symlink', 'deploy:public_disk');
before('deploy:symlink', 'artisan:cache:clear');


after('deploy:symlink', 'php-fpm:restart');
after('deploy:symlink', 'horizon:restart');


//////////////////////

desc('Restart PHP-FPM service');
task('php-fpm:restart', function () {
    run('sudo systemctl restart php-fpm');
});

desc('Restart Horizon service');
task('horizon:restart', function () {
    run('sudo systemctl restart laravel-horizon');
});

desc('Load and build npm');
task('npm:run-prod', function () {
    cd('{{release_path}}');
    run('npm i');
    run('npm run prod');
});

task('artisan:laravel-admin', function () {
    run('{{bin/php}} {{release_path}}/artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"');
})->desc('Publish LaravelAdmin');

//task('artisan:laravel-admin-grid-lightbox', function () {
//    run('{{bin/php}} {{release_path}}/artisan vendor:publish --tag=laravel-admin-grid-lightbox');
//})->desc('Publish laravel-admin-grid-lightbox');

task('artisan:laravel-admin-ckeditor', function () {
    run('{{bin/php}} {{release_path}}/artisan vendor:publish --tag=laravel-admin-ckeditor');
})->desc('Publish laravel-admin-ckeditor');

task('artisan:laravel-api-cors', function () {
    run('{{bin/php}} {{release_path}}/artisan vendor:publish --tag="cors"');
})->desc('Publish laravel-api-cors');

//task('artisan:laravel-admin-summernote', function () {
//    run('{{bin/php}} {{release_path}}/artisan vendor:publish --tag=laravel-admin-summernote');
//})->desc('Publish laravel-admin-summernote');

//task('artisan:laravel-admin-chartjs', function () {
//    run('{{bin/php}} {{release_path}}/artisan vendor:publish --tag=laravel-admin-chartjs');
//})->desc('Publish laravel-admin-chartjs');

//task('artisan:laravel-admin-daterangepicker', function () {
//    run('{{bin/php}} {{release_path}}/artisan vendor:publish --tag=laravel-admin-daterangepicker');
//})->desc('Publish laravel-admin-daterangepicker');

task('artisan:laravel-front-share', function () {
    run('{{bin/php}} {{release_path}}/artisan vendor:publish --provider="Jorenvh\Share\Providers\ShareServiceProvider"');
})->desc('Publish ShareServiceProvider');

task('artisan:laravel-horizon', function () {
    run('{{bin/php}} {{release_path}}/artisan vendor:publish --provider="Laravel\Horizon\HorizonServiceProvider"');
})->desc('Publish HorizonServiceProvider');
