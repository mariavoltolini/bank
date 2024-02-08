<?php

namespace App\Providers;

use App\Repositories\EloquentUsersRepository;
use App\Contracts\UsersRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesProvider extends ServiceProvider
{
    public array $bindings = [
        UsersRepository::class => EloquentUsersRepository::class
    ];
}
