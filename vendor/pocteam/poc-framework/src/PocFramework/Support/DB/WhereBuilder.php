<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 7/31/2017
 * Time: 4:00 PM
 */

namespace PocFramework\Support\DB;


use Illuminate\Database\Query\Builder;

/**
 * Helper for building common Builder closure depending on Builder::$operators
 *
 * @package PocFramework\Support\DB
 */
trait WhereBuilder
{
    /**
     * Build where clauses linked with 'and'
     *
     * @param array $conditions
     * @return callable
     */
    public function buildWhere(array $conditions): callable
    {
        return function (Builder $builder) use ($conditions) {
            foreach ($conditions as $condition) {
                $operator = strtolower($condition[1]);
                if ($operator === 'in') {
                    $builder->whereIn($condition[0], $condition[2]);
                } else if ($operator === 'not in') {
                    $builder->whereNotIn($condition[0], $condition[2]);
                } else if ($operator === 'not between') {
                    $builder->whereNotBetween($condition[0], $condition[2]);
                } else {
                    $builder->where($condition[0], $condition[1], $condition[2]);
                }
            }

            return $builder;
        };
    }

    /**
     * Build where clauses linked with 'or'
     *
     * @param array $conditions
     * @return callable
     */
    public function buildOrWhere(array $conditions): callable
    {
        return function (Builder $builder) use ($conditions) {
            foreach ($conditions as $condition) {
                $operator = strtolower($condition[1]);
                if ($operator === 'in') {
                    $builder->orWhereIn($condition[0], $condition[2]);
                } else if ($operator === 'not in') {
                    $builder->orWhereNotIn($condition[0], $condition[2]);
                } else if ($operator === 'not between') {
                    $builder->orWhereNotBetween($condition[0], $condition[2]);
                } else {
                    $builder->orWhere($condition[0], $condition[1], $condition[2]);
                }
            }
        };
    }
}