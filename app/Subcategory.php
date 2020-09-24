<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    //
    public function subsubcategories()
    {
        return $this->hasMany(Subsubcategory::class);
    }
}
