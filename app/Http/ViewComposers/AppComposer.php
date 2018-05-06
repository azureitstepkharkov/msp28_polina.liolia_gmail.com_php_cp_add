<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\models\Task;
use Illuminate\Support\Carbon;

class AppComposer
{
    protected $tasks_expires;
    protected $date;
    /**
     * Create a new app composer.
     *
     * @param  empty
     * @return void
     */
    public function __construct()
    {
        // Dependencies automatically resolved by service container...
        if (Auth::check()) {
            $user = Auth::User();
            if ($user->hasRole('Admin')) {
                $this->tasks_expires = Task::all()->sortBy('end');
                //->partition(function ($val, $key) {
//               return $key < 5;
//           });
            } elseif ($user->hasRole('ProjectMan')) {
                $this->tasks_expires = $user->pm_tasks->sortBy('end');
//            ->partition(function ($val, $key) {
//                return $key < 5;
//            });
            } elseif ($user->hasRole('Client')) {
                $this->tasks_expires = $user->clients_tasks->sortBy('end');
//            ->partition(function ($val, $key) {
//                return $key < 5;
//            });
            } elseif ($user->hasRole('Programmer')) {
                $this->tasks_expires = $user->tasks->sortBy('end');
//            ->partition(function ($val, $key) {
//                return $key < 5;
//            });
            } else {
                $this->tasks_expires = Task::all()->sortBy('end');
//            ->partition(function ($val, $key) {
//                return $key < 5;
//            });
            }
            $this->date = date("d-m-Y");
        }
    }


    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['tasks' => $this->tasks_expires, 'date' => $this->date]);
    }
}