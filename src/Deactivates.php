<?php
namespace AppArk\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Deactivates
 *
 * @package App\Models\Traits
 *
 * @method static Builder|static withDeactivated()
 * @method static Builder|static onlyDeactivated()
 * @method static Builder|static onlyActivated()
 */
trait Deactivates
{
    /**
     * 仅限只能在model中调用
     *
     * @param string $name
     * @return $this
     */
    abstract public function setConnection($name);

    /**
     * Boot the deactivate trait for a model.
     *
     * @return void
     */
    public static function bootDeactivates()
    {
        static::addGlobalScope(new DeactivatedScope());
    }

    /**
     * Get the name of the "deactivated_at" column.
     *
     * @return string
     */
    public function getDeactivateAtColumn()
    {
        return defined('static::DEACTIVATED_AT') ? static::DEACTIVATED_AT : 'deactivated_at';
    }

    /**
     * Get the fully qualified "deactivated_at" column.
     *
     * @return string
     */
    public function getQualifiedDeactivateAtColumn()
    {
        return $this->getTable() . '.' . $this->getDeactivateAtColumn();
    }

    /**
     * Determine if the model has been deactivated.
     *
     * @return bool
     */
    public function deactivated()
    {
        return !is_null($this->{$this->getDeactivateAtColumn()});
    }

    /**
     * Activate a model instance.
     *
     * @return mixed
     */
    public function activate()
    {
        if ($this->fireModelEvent('activating') === false) {
            return false;
        }

        $this->{$this->getDeactivateAtColumn()} = null;

        $this->fireModelEvent('activated', false);

        return $this->save();
    }

    /**
     * Deactivate a model instance.
     * @return bool
     */
    public function deactivate()
    {
        if ($this->fireModelEvent('deactivating') === false) {
            return false;
        }

        $this->{$this->getDeactivateAtColumn()} = $this->freshTimestamp();

        $this->fireModelEvent('deactivated', false);

        return $this->save();
    }
}
