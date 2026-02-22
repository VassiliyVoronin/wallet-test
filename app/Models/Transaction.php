<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id ID пользователя
 * @property int $wallet_id ID кошелька
 * @property string|null $ext_tx_id Внешний идентификатор транзакции
 * @property string $type Key типа транзакции
 * @property string $status Key статуса транзакции
 * @property numeric $amount Сумма транзакции
 * @property string $tx_date Дата/время транзакции
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Wallet $wallet
 * @property-read User $user
 * @method static Builder<static>|Transaction newModelQuery()
 * @method static Builder<static>|Transaction newQuery()
 * @method static Builder<static>|Transaction onlyTrashed()
 * @method static Builder<static>|Transaction query()
 * @method static Builder<static>|Transaction whereAmount($value)
 * @method static Builder<static>|Transaction whereCreatedAt($value)
 * @method static Builder<static>|Transaction whereDeletedAt($value)
 * @method static Builder<static>|Transaction whereExtTxId($value)
 * @method static Builder<static>|Transaction whereId($value)
 * @method static Builder<static>|Transaction whereStatus($value)
 * @method static Builder<static>|Transaction whereTxDate($value)
 * @method static Builder<static>|Transaction whereType($value)
 * @method static Builder<static>|Transaction whereUpdatedAt($value)
 * @method static Builder<static>|Transaction whereUserId($value)
 * @method static Builder<static>|Transaction whereWalletId($value)
 * @method static Builder<static>|Transaction withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Transaction withoutTrashed()
 * @mixin Eloquent
 */
class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    const TYPES = ['deposit' => 'Зачисление', 'withdraw' => 'Списание'];
    const STATUSES = ['pending' => 'Ожидание', 'confirmed' => 'Подтверждено', 'failed' => 'Ошибка'];

    protected $fillable = ['user_id', 'wallet_id', 'ext_tx_id', 'type', 'status', 'amount', 'tx_date'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $hidden = ['deleted_at'];

    /**
     * @return BelongsTo
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
