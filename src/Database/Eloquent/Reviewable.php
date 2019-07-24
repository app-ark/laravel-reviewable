<?php
namespace AppArk\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Reviewable
 *
 * @package AppArk\Database\Eloquent\Traits
 *
 * @method static Builder|static withRejected
 * @method static Builder|static withoutRejected
 * @method static Builder|static onlyRejected
 * @method static Builder|static withReviewed
 * @method static Builder|static withoutReviewed
 * @method static Builder|static onlyReviewed
 */
trait Reviewable
{
    /**
     * 仅限只能在model中调用
     *
     * @param string $name
     * @return $this
     */
    abstract public function setConnection($name);

    /**
     * Boot the review trait for a model.
     *
     * @return void
     */
    public static function bootReviewable()
    {
        static::addGlobalScope(new ReviewableScope());
    }

    /**
     * Get the name of the "review" column.
     *
     * @return string
     */
    public function getReviewableColumn()
    {
        return defined(static::class . '::REVIEW') ? static::REVIEW : 'review';
    }

    /**
     * Get the fully qualified "review" column.
     *
     * @return string
     */
    public function getQualifiedReviewableColumn()
    {
        return $this->getTable() . '.' . $this->getReviewableColumn();
    }

    /**
     * 是否为已拒绝
     *
     * @return bool
     */
    public function rejected()
    {
        return $this->{$this->getReviewableColumn()} == -1;
    }

    /**
     * 是否为已通过
     *
     * @return bool
     */
    public function reviewed()
    {
        return $this->{$this->getReviewableColumn()} == 1;
    }


    /**
     * 是否待审核
     *
     * @return bool
     */
    public function unreviewed()
    {
        return $this->{$this->getReviewableColumn()} == 0;
    }

    /**
     * 审核通过
     *
     * @return mixed
     */
    public function review()
    {
        if ($this->fireModelEvent('reviewing') === false) {
            return false;
        }

        $this->{$this->getReviewableColumn()} = 1;

        $this->fireModelEvent('reviewed', false);

        return $this->save();
    }

    /**
     * 标记为待审核
     *
     * @return mixed
     */
    public function unreview()
    {
        if ($this->fireModelEvent('unreviewing') === false) {
            return false;
        }

        $this->{$this->getReviewableColumn()} = 0;

        $this->fireModelEvent('unreviewed', false);

        return $this->save();
    }

    /**
     * 审核拒绝
     *
     * @return bool
     */
    public function reject()
    {
        if ($this->fireModelEvent('rejecting') === false) {
            return false;
        }

        $this->{$this->getReviewableColumn()} = -1;

        $this->fireModelEvent('rejected', false);

        return $this->save();
    }
}
