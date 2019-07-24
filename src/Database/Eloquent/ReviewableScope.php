<?php
namespace AppArk\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ReviewableScope implements Scope
{
    protected $extensions = [
        'WithRejected',
        'WithoutRejected',
        'OnlyRejected',
        'WithReviewed',
        'WithoutReviewed',
        'OnlyReviewed',
    ];

    public function apply(Builder $builder, Model $model)
    {
        $builder->onlyReviewed();
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param Builder $builder
     */
    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }

    /**
     * Get the "deactivated_at" column for the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return string
     */
    protected function getReviewableColumn(Builder $builder)
    {
        /**
         * @var \Illuminate\Database\Eloquent\Model|Deactivates $model
         */
        $model = $builder->getModel();
        if (count($builder->getQuery()->joins) > 0) {
            return $model->getQualifiedReviewableColumn();
        }

        return $model->getReviewableColumn();
    }


    /**
     * Add the with-rejected extension to the builder.
     *
     * @param Builder $builder
     */
    protected function addAllReviewable(Builder $builder)
    {
        $builder->macro('allReviewable', function (Builder $builder) {
            return $builder;
        });
    }

    /**
     * Add the with-rejected extension to the builder.
     *
     * @param Builder $builder
     */
    protected function addWithRejected(Builder $builder)
    {
        $builder->macro('withRejected', function (Builder $builder) {
            return $builder;
        });
    }

    /**
     * Add the only-deactivated extension to the builder.
     *
     * @param Builder $builder
     */
    protected function addOnlyRejected(Builder $builder)
    {
        $builder->macro('onlyRejected', function (Builder $builder) {
            /**
             * @var \Illuminate\Database\Eloquent\Model|Reviewable $model
             */
            $model = $builder->getModel();

            $builder->where(
                $model->getQualifiedReviewableColumn(),
                -1
            );

            return $builder;
        });
    }

    /**
     * Add the only-deactivated extension to the builder.
     *
     * @param Builder $builder
     */
    protected function addWithoutRejected(Builder $builder)
    {
        $builder->macro('withoutRejected', function (Builder $builder) {
            /**
             * @var \Illuminate\Database\Eloquent\Model|Reviewable $model
             */
            $model = $builder->getModel();

            $builder->where(
                $model->getQualifiedReviewableColumn(),
                '<>',
                -1
            );

            return $builder;
        });
    }


    /**
     * Add the with-reviewed extension to the builder.
     *
     * @param Builder $builder
     */
    protected function addWithReviewed(Builder $builder)
    {
        $builder->macro('withReviewed', function (Builder $builder) {
            return $builder;
        });
    }

    /**
     * Add the only-deactivated extension to the builder.
     *
     * @param Builder $builder
     */
    protected function addOnlyReviewed(Builder $builder)
    {
        $builder->macro('onlyReviewed', function (Builder $builder) {
            /**
             * @var \Illuminate\Database\Eloquent\Model|Reviewable $model
             */
            $model = $builder->getModel();

            $builder->where(
                $model->getQualifiedReviewableColumn(),
                1
            );

            return $builder;
        });
    }

    /**
     * Add the only-deactivated extension to the builder.
     *
     * @param Builder $builder
     */
    protected function addWithoutReviewed(Builder $builder)
    {
        $builder->macro('withoutReviewed', function (Builder $builder) {
            /**
             * @var \Illuminate\Database\Eloquent\Model|Reviewable $model
             */
            $model = $builder->getModel();

            $builder->where(
                $model->getQualifiedReviewableColumn(),
                '<>',
                1
            );

            return $builder;
        });
    }

    /**
     * Add the Review extension to the builder.
     *
     * @param Builder $builder
     */
    protected function addReview(Builder $builder)
    {
        $builder->macro('review', function (Builder $builder) {
            $builder->allReviewable();

            return $builder->update([$builder->getModel()->getReviewableColumn() => 1]);
        });
    }


    /**
     * Add the un-Review extension to the builder.
     *
     * @param Builder $builder
     */
    protected function addUnreview(Builder $builder)
    {
        $builder->macro('unreview', function (Builder $builder) {
            $builder->allReviewable();

            return $builder->update([$builder->getModel()->getReviewableColumn() => 0]);
        });
    }

    /**
     * Add the reject extension to the builder.
     *
     * @param Builder $builder
     */
    protected function addReject(Builder $builder)
    {
        $builder->macro('reject', function (Builder $builder) {
            $builder->allReviewable();

            return $builder->update([$builder->getModel()->getReviewableColumn() => -1]);
        });
    }
}
