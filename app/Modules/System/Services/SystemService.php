<?php

namespace Leaguesim\System\Services;

use Exception;
use Illuminate\Support\Facades\Artisan;

class SystemService
{

    /**
     * Uygulamayı tamamen sıfırlıyalım.
     * Boylece Hem sıfır başlangıç için database kurulumunu yapmış olacağız
     * hemde lig bitince tekrar oynamak için sıfırlamış olacağız :)
     * @return bool
     */
    public function resetAll(): bool
    {
        try {
            Artisan::call('migrate:rollback');
            Artisan::call('migrate');
        }catch (Exception $e){
            //LOG HERE
            return false;
        }
        return true;
    }

}
