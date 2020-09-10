<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'my_project');

// Project repository
set('repository', 'git@github.com:Ghaffaru15/acabest-backend.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);


// Hosts

host('54.172.102.173')
    ->user('deployer')
    ->identityFile('~/.ssh/acabest_ec2_staging')
    ->set('deploy_path', '/var/www/html/acabest_backend');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

task('reload:nginx', function () {
    run('sudo systemctl restart nginx');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// restart nginx after deploy
after('deploy', 'reload:nginx');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

