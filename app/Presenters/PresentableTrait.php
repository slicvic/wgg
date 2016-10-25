<?php

namespace App\Presenters;

trait PresentableTrait
{
    /**
     * The presenter instance.
     *
     * @var BasePresenter
     */
    protected $presenter;

    /**
     * Get the presenter instance.
     *
     * @return BasePresenter
     */
    public function present()
    {
        if (!$this->presenterClassName || !class_exists($this->presenterClassName)) {
            throw new PresenterException('Presenter class name is not defined.');
        }

        if (!$this->presenter) {
            $this->presenter =  new $this->presenterClassName($this);
        }

        return $this->presenter;
    }
}
