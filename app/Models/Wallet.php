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
 * @property numeric $available_sum Доступная сумма
 * @property numeric $frozen_sum Замороженная сумма
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User $user
 * @method static Builder<static>|Wallet newModelQuery()
 * @method static Builder<static>|Wallet newQuery()
 * @method static Builder<static>|Wallet onlyTrashed()
 * @method static Builder<static>|Wallet query()
 * @method static Builder<static>|Wallet whereAvailableSum($value)
 * @method static Builder<static>|Wallet whereCreatedAt($value)
 * @method static Builder<static>|Wallet whereDeletedAt($value)
 * @method static Builder<static>|Wallet whereFrozenSum($value)
 * @method static Builder<static>|Wallet whereId($value)
 * @method static Builder<static>|Wallet whereUpdatedAt($value)
 * @method static Builder<static>|Wallet whereUserId($value)
 * @method static Builder<static>|Wallet withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Wallet withoutTrashed()
 * @mixin Eloquent
 */
class Wallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'available_sum', 'frozen_sum'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $hidden = ['deleted_at'];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
