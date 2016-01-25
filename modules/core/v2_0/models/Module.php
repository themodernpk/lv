<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Module extends Eloquent
{
    /* ****** Code Completed till 10th april */
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];
    protected $softDelete = true;

    protected $guarded = array('id');

    protected $fillable = [
        'name',
        'version',
        'active'
    ];

    //--------------------------
    /* ******\ Code Completed till 10th april */
}