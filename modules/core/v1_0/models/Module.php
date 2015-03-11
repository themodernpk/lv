<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Module extends Eloquent
{
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];
    protected $softDelete = true;

    protected $guarded = array('id');

    protected $fillable = [
        'name',
        'version',
        'active'
    ];


    //------------------------------------------------------------
    //------------------------------------------------------------
    //------------------------------------------------------------


}