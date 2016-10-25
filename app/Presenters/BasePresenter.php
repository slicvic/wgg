<?php

namespace App\Presenters;

use Illuminate\Database\Eloquent\Model;

abstract class BasePresenter
{
    /**
     * The model instance.
     *
     * @var Model
     */
    protected $model;

    /**
     * Constructor.
     *
     * @param Model  $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}
