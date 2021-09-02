<?php
/**
 * Created by PhpStorm.
 * User: zys
 * Date: 2021/9/1
 * Time: 15:08
 */

namespace Really4you\LaravelOptimistic;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait Optimistic
{
    /**
     * Get the version column.
     *
     * @return string
     */
    public function getVersionAt()
    {
        return isset($this->versionAt) ? $this->versionAt : OptimisticStrategy::VERSION_AT;
    }

    /**
     * rewrite: Perform a model update operation.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return bool
     */
    protected function performUpdate(Builder $query)
    {
        // If the updating event returns false, we will cancel the update operation so
        // developers can hook Validation systems into their models and cancel this
        // operation if the model does not pass validation. Otherwise, we update.
        if ($this->fireModelEvent('updating') === false) {
            return false;
        }

        // First we need to create a fresh query instance and touch the creation and
        // update timestamp on the model which are maintained by us for developer
        // convenience. Then we will just continue saving the model instances.
        if ($this->usesTimestamps()) {
            $this->updateTimestamps();
        }

        // Once we have run the update operation, we will fire the "updated" event for
        // this model instance. This will allow developers to hook into these after
        // models are updated, giving them a chance to do any special processing.
        $dirty = $this->getDirty();

        if (count($dirty) > 0) {
            // set optimistic where condition
            $query->versionAt($this);
            // set version column condition
            $dirty[$this->getVersionAt()] = $this->increment($this->getVersionAt());


            $this->setKeysForSaveQuery($query)->update($dirty);

            $this->syncChanges();

            $this->fireModelEvent('updated', false);
        }

        return true;
    }

    /**
     * scope for optimistic
     *
     * @param $query
     * @param Model $model
     *
     * @return mixed
     */
    public function scopeVersionAt($query, Model $model)
    {
        return $query->where($this->getVersionAt(),$model->getAttribute($this->getVersionAt()));
    }

    public static function bootOptimistic()
    {
        static::updating(function (Model $model) {
            //
        });
    }

    /**
     * model booting event
     *
     * @param $callback
     */
    public static function booting($callback)
    {
        static::registerModelEvent('booting', $callback);
    }

    /**
     * model booted event
     *
     * @param $callback
     */
    public static function booted($callback)
    {
        static::registerModelEvent('booted', $callback);
    }
}
