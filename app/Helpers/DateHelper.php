<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Ajoute X jours ouvrables (sans dimanche) à une date
     */
    public static function addBusinessDays(Carbon $date, int $days): Carbon
    {
        $date  = $date->copy();
        $added = 0;

        while ($added < $days) {
            $date->addDay();
            // On exclut uniquement le dimanche
            if ($date->dayOfWeek !== Carbon::SUNDAY) {
                $added++;
            }
        }

        return $date;
    }
}