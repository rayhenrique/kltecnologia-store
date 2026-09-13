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
        $extension = pathinfo($order->product->file_path, PATHINFO_EXTENSION);
        $filename = Str::slug($order->product->title).($extension ? '.'.$extension : '');

        return Storage::disk('digital_products')->download($order->product->file_path, $filename);
    }
}
