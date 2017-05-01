<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * App\Entities\InappPurchase
 *
 * @property int $transaction_id
 * @property string $receipt
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\InappPurchase whereTransactionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\InappPurchase whereReceipt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\InappPurchase whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\InappPurchase whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $id
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\InappPurchase whereId($value)
 */
class InappPurchase extends Model implements Transformable
{
    use TransformableTrait;
    protected $table = 'inapp_purchase';
    protected $fillable = ['transaction_id', 'receipt', 'os', 'app_name'];

}
