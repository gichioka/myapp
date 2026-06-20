<?php

namespace App\Observers;

use App\Models\Retirement;

class RetirementObserver
{
    /**
     * 退職レコード作成時 → 社員を退職済みにする
     */
    public function created(Retirement $retirement): void
    {
        $retirement->user?->update(['is_retired' => true]);
    }

    /**
     * 退職レコード削除時 → 他に退職レコードが残っていなければ在籍中に戻す
     */
    public function deleted(Retirement $retirement): void
    {
        $hasOtherRetirements = Retirement::where('user_id', $retirement->user_id)->exists();
        $retirement->user?->update(['is_retired' => $hasOtherRetirements]);
    }
}