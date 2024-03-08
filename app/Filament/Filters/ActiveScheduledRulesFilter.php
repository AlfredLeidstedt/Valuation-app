<?php

namespace App\Filament\Filters;

use Carbon\Carbon;
use Filament\Tables\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;


class ActiveScheduledRulesFilter extends BaseFilter
{
    public static function make(?string $name = null): static
    {
        return new static($name ?? 'active_scheduled_rules');
    }

    public static function checkIfRuleIsActiveToday($data) {

        $today = Carbon::today();

        
        if(
            $today >= $data['startdate'] && $today <= $data['enddate']
        ) {
            return true;
        } else {
            return false;
        }
    }

    public function apply(Builder $query, array $data = []): Builder
    {
        $ruleIsAtiveToday = $this->checkIfRuleIsActiveToday($data);

        return $query->where('isScheduled', true)
                     ->where('isActive', true)
                     ->whereDate('ruleIsActiveToday', $ruleIsAtiveToday);
    }

     
}