<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function __invoke(Order $order): StreamedResponse
    {
        Gate::authorize('download', $order);

        if (! $order->product->file_path || ! Storage::disk('digital_products')->exists($order->product->file_path)) {
            abort(404, 'O arquivo deste produto está sendo atualizado pelo administrador. Por favor, contate o suporte.');
        }

        $extension = pathinfo((string) $order->product->file_path, PATHINFO_EXTENSION);
        $filename = Str::slug($order->product->title).($extension ? '.'.$extension : '');

        return Storage::disk('digital_products')->download($order->product->file_path, $filename);
    }
}
