<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Condition;


class ValuationHistory extends Model
{
    use HasFactory;

    protected $fillable = [

        'condition_id',
        'rule_id',
        'deduction_id',
        'valuation_from_wayke',
        'offer_from_bilbolaget',
        'regNo'

    ];

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }

    public function rule(): BelongsTo
    {
        return $this->belongsTo(Rule::class);
    }

    public function deduction(): BelongsTo
    {
        return $this->belongsTo(Deduction::class);
    }

    public function saveValuation($conditionId, $valuationFromWayke, $offerFromBilbolaget, $regNo, $ruleId, $deductionId)
    {
        $newValuation = new ValuationHistory();

        $newValuation->condition_id = $conditionId;
        $newValuation->valuation_from_wayke = $valuationFromWayke;
        $newValuation->offer_from_bilbolaget = $offerFromBilbolaget;
        $newValuation->regNo = $regNo;
        $newValuation->rule_id = $ruleId;
        $newValuation->deduction_id = $deductionId;

        $newValuation->save();

    }
}
