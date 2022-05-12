<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $device = false;

    public function __construct()
    {
        $agent = new \Jenssegers\Agent\Agent;

        if ($agent->isDesktop()) {
            $this->device = 'desktop';
        } else {
            $this->device = 'mobile';
        }
    }
}
