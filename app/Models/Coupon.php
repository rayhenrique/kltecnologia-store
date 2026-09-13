<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'product_id',
        'max_uses',
        'times_used',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'discount_type' => 'percentage',
        'min_order_amount' => 0.00,
        'times_used' => 0,
        'is_active' => true,
    ];

    protected static function booted(): void
    {
        static::saving(function (self $coupon): void {
            $coupon->code = strtoupper(trim($coupon->code));
        });
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && Carbon::now()->isAfter($this->expires_at);
    }

    public function hasStarted(): bool
    {
        return $this->starts_at === null || Carbon::now()->isAfter($this->starts_at);
    }

    public function hasReachedLimit(): bool
    {
        return $this->max_uses !== null && $this->times_used >= $this->max_uses;
    }

    public function isApplicableToStorewide(): bool
    {
        return $this->product_id === null;
    }

    /**
     * @param  Collection<int, Product>  $products
     * @return array{valid: bool, discount_amount: float, message: string}
     */
    public function evaluate(Collection $products, float $subtotal): array
    {
        if (! $this->is_active) {
            return [
                'valid' => false,
                'discount_amount' => 0.0,
                'message' => 'Este cupom está temporariamente inativo.',
            ];
        }

        if (! $this->hasStarted()) {
            $date = $this->starts_at?->format('d/m/Y H:i');

            return [
                'valid' => false,
                'discount_amount' => 0.0,
                'message' => "Este cupom só será válido a partir de {$date}.",
            ];
        }

        if ($this->isExpired()) {
            return [
                'valid' => false,
                'discount_amount' => 0.0,
                'message' => 'Este cupom expirou.',
            ];
        }

        if ($this->hasReachedLimit()) {
            return [
                'valid' => false,
                'discount_amount' => 0.0,
                'message' => 'O limite máximo de utilizações deste cupom foi atingido.',
            ];
        }

        if ($this->min_order_amount > 0 && $subtotal < (float) $this->min_order_amount) {
            $minFormatted = 'R$ '.number_format((float) $this->min_order_amount, 2, ',', '.');

            return [
                'valid' => false,
                'discount_amount' => 0.0,
                'message' => "O valor mínimo de compra para este cupom é {$minFormatted}.",
            ];
        }

        // Se o cupom for específico de um produto
        if (! $this->isApplicableToStorewide()) {
            $matchingProduct = $products->firstWhere('id', $this->product_id);
            if (! $matchingProduct) {
                $targetTitle = $this->product?->title ?? 'produto específico';

                return [
                    'valid' => false,
                    'discount_amount' => 0.0,
                    'message' => "Este cupom é exclusivo para o produto: {$targetTitle}.",
                ];
            }

            $eligibleAmount = (float) $matchingProduct->price;
        } else {
            $eligibleAmount = $subtotal;
        }

        // Cálculo do Desconto
        if ($this->discount_type === 'percentage') {
            $discount = round($eligibleAmount * ((float) $this->discount_value / 100), 2);
            $discount = min($discount, $subtotal);
            $discountText = number_format((float) $this->discount_value, 0).'%';
        } else {
            $discount = min((float) $this->discount_value, $subtotal);
            $discountText = 'R$ '.number_format((float) $this->discount_value, 2, ',', '.');
        }

        return [
            'valid' => true,
            'discount_amount' => max(0.0, $discount),
            'message' => "Cupom de {$discountText} aplicado com sucesso!",
        ];
    }

    public function incrementUsage(): void
    {
        $this->increment('times_used');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'max_uses' => 'integer',
            'times_used' => 'integer',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
}
