<?php

namespace App\Providers;
use App\DonationsCategories;
use Illuminate\Support\ServiceProvider;
use View;
class AppServiceProvider extends ServiceProvider
{
    use \App\Enum\Traits\CheckAdminUser;
    use \App\Enum\Traits\UserData;
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    
    public function boot()
    {


        //          $id            = \Auth::id();
      
        // $categories = DonationsCategories::get();
        // $donate_categories = $categories->pluck('name','id');
        // View::share([
        //     'donate_categories' =>$donate_categories,
        //     'userId'    => $id ? $id : 0,
        //     'isAdmin'   => $this->isAdmin(),
        //     'user_data' => $this->userData(),

        //     ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
