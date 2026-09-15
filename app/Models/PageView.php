<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

class PageView extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'url',
        'route_name',
        'viewable_type',
        'viewable_id',
        'visitor_hash',
        'referer',
        'device_type',
        'visited_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @param  Builder<PageView>  $query
     * @return Builder<PageView>
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('visited_at', Carbon::today());
    }

    /**
     * @param  Builder<PageView>  $query
     * @return Builder<PageView>
     */
    public function scopeYesterday(Builder $query): Builder
    {
        return $query->whereDate('visited_at', Carbon::yesterday());
    }

    /**
     * @param  Builder<PageView>  $query
     * @return Builder<PageView>
     */
    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->where('visited_at', '>=', Carbon::now()->startOfMonth());
    }

    /**
     * @param  Builder<PageView>  $query
     * @return Builder<PageView>
     */
    public function scopeLastDays(Builder $query, int $days = 14): Builder
    {
        return $query->where('visited_at', '>=', Carbon::now()->subDays($days)->startOfDay());
    }
}
