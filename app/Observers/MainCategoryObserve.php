<?php

namespace App\Observers;

use App\Models\maincategories;

class MainCategoryObserve
{
    /**
     * Handle the maincategories "created" event.
     *
     * @param  \App\Models\maincategories  $maincategories
     * @return void
     */
    public function created(maincategories $maincategories)
    {
        //
    }

    /**
     * Handle the maincategories "updated" event.
     *
     * @param  \App\Models\maincategories  $maincategories
     * @return void
     */
    public function updated(maincategories $maincategories)
    {
      $vendors=  $maincategories -> vendors();
        $vendors->update(['active'=> $maincategories->active]);

        $subcat = $maincategories -> SubCategories();
        $subcat -> update(['active'=> $maincategories-> active]);
    }

    /**
     * Handle the maincategories "deleted" event.
     *
     * @param  \App\Models\maincategories  $maincategories
     * @return void
     */
    public function deleted(maincategories $maincategories)
    {
        //
    }

    /**
     * Handle the maincategories "restored" event.
     *
     * @param  \App\Models\maincategories  $maincategories
     * @return void
     */
    public function restored(maincategories $maincategories)
    {
        //
    }

    /**
     * Handle the maincategories "force deleted" event.
     *
     * @param  \App\Models\maincategories  $maincategories
     * @return void
     */
    public function forceDeleted(maincategories $maincategories)
    {
        //
    }
}
