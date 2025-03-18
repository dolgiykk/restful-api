<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected Request $request;

    /** @var Builder<Model> */
    protected Builder $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return string[]
     */
    abstract protected function filters(): array;

    /**
     * @param Builder<Model> $builder
     * @return Builder<Model>
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->filters() as $field => $type) {
            if ($this->request->has($field) && $this->request->filled($field)) {
                /** @var string $inputField */
                $inputField = $this->request->input($field);

                if (method_exists($this, $field)) {
                    $this->{$field}($inputField);
                } else {
                    $this->defaultFilter($field, $inputField, $type);
                }
            }
        }

        return $this->builder;
    }

    /**
     * @param string $field
     * @param string $value
     * @param string $type
     * @return void
     */
    protected function defaultFilter(string $field, string $value, string $type): void
    {
        switch ($type) {
            case 'like':
                $this->builder->where($field, 'LIKE', '%'.$value.'%');
                break;
            case 'date':
                $this->builder->whereDate($field, $value);
                break;
            default:
                $this->builder->where($field, $value);
                break;
        }
    }
}
