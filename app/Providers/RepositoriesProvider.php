<?php

namespace App\Providers;

use App\Contracts\TransactionsRepository;
use App\Contracts\WalletsRepository;
use App\Repositories\EloquentTransactionsRepository;
use App\Repositories\EloquentUsersRepository;
use App\Contracts\UsersRepository;
use App\Repositories\EloquentWalletsRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesProvider extends ServiceProvider
{
    public array $bindings = [
        UsersRepository::class => EloquentUsersRepository::class,
        TransactionsRepository::class => EloquentTransactionsRepository::class,
        WalletsRepository::class => EloquentWalletsRepository::class
    ];
}
