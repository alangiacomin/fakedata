<?php

namespace App\Cqrs\App\Infrastructure\Mappers;

use App\Cqrs\App\Domain\Entities\DomainItem;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 * @template TDomain of DomainItem
 */
abstract class Mapper
{
    /**
     * @param  TModel  $model
     * @return TDomain
     */
    abstract public static function toDomain(Model $model): DomainItem;

    /**
     * @param  TDomain  $domain
     * @return TModel
     */
    abstract public static function toPersistence(DomainItem $domain): Model;
}
