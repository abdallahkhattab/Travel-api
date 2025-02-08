<?php

namespace App\Observers;

use App\Models\Travel;

class TravelObserver
{
    /**
     * Handle the Travel "creating" event.
     */
    public function creating(Travel $travel): void
    {
        //  $travel->slug = Str::slug($travel->name) .'-' . uniqid();
        $travel->slug = str($travel->name)->slug().'-'.uniqid();
    }
}
