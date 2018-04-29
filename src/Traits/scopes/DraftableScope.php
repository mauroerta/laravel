<?php

namespace ME\Traits\Scopes;

use Illuminate\Database\Eloquent\Scope;

class DraftableScope implements Scope
{
    /**
     * All of the extensions to be added to the builder.
     *
     * @var array
     */
    protected $extensions = ['Undraft', 'WithDrafted', 'WithoutDrafted', 'OnlyDrafted'];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereNull($model->getQualifiedDraftedAtColumn());
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }

        $builder->onDelete(function (Builder $builder) {
            $drafted_at_column = $this->getDraftedAtColumn($builder);
            $drafted_by_column = $this->getDraftedByColumn($builder);

            return $builder->update([
                $drafted_at_column => $builder->getModel()->freshTimestampString(),
                $drafted_by_column => null,
            ]);
        });
    }

    /**
     * Get the "drafted at" column for the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return string
     */
    protected function getDraftedAtColumn(Builder $builder)
    {
        if (count((array) $builder->getQuery()->joins) > 0) {
            return $builder->getModel()->getQualifiedDraftedAtColumn();
        }

        return $builder->getModel()->getDraftedAtColumn();
    }

    /**
     * Get the "drafted by" column for the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return string
     */
    protected function getDraftedByColumn(Builder $builder)
    {
        if (count((array) $builder->getQuery()->joins) > 0) {
            return $builder->getModel()->getQualifiedDraftedByColumn();
        }

        return $builder->getModel()->getDraftedByColumn();
    }

    /**
     * Add the undraft extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addUndraft(Builder $builder)
    {
        $builder->macro('undraft', function (Builder $builder) {
            $builder->withDrafted();

            return $builder->update([
                $builder->getModel()->getDraftedAtColumn() => null,
                $builder->getModel()->getDraftedByColumn() => null
            ]);
        });
    }

    /**
     * Add the with-drafted extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addWithDrafted(Builder $builder)
    {
        $builder->macro('withTrashed', function (Builder $builder, $withDrafted = true) {
            if (! $withDrafted) {
                return $builder->withoutDrafted();
            }

            return $builder->withoutGlobalScope($this);
        });
    }

    /**
     * Add the without-drafted extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addWithoutDrafted(Builder $builder)
    {
        $builder->macro('withoutDrafted', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->whereNull(
                $model->getQualifiedDraftedAtColumn()
            );

            return $builder;
        });
    }

    /**
     * Add the only-drafted extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addOnlyDrafted(Builder $builder)
    {
        $builder->macro('onlyDrafted', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->whereNotNull(
                $model->getQualifiedDraftedAtColumn()
            );

            return $builder;
        });
    }
}
