<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Automattic\WooCommerce\Client;

class WooCommerceController extends Controller
{
    public function showSettings()
    {
        $settings = Setting::whereIn('key', ['woocommerce_url', 'woocommerce_consumer_key', 'woocommerce_secret_key'])->get();
        return view('ajustes.wc-credenciales', ['settings' => $settings]);
    }

    public function saveSettings(Request $request)
    {
        $request->validate([
            'woocommerce_url' => 'required|url',
            'woocommerce_consumer_key' => 'required',
            'woocommerce_secret_key' => 'required',
        ]);

        Setting::updateOrCreate(['key' => 'woocommerce_url'], ['value' => $request->woocommerce_url]);
        Setting::updateOrCreate(['key' => 'woocommerce_consumer_key'], ['value' => $request->woocommerce_consumer_key]);
        Setting::updateOrCreate(['key' => 'woocommerce_secret_key'], ['value' => $request->woocommerce_secret_key]);

        return response()->json(['message' => 'Configuración guardada correctamente'], 200);
    }

    public function verifyConnection()
    {
        $url = Setting::where('key', 'woocommerce_url')->value('value');
        $consumerKey = Setting::where('key', 'woocommerce_consumer_key')->value('value');
        $secretKey = Setting::where('key', 'woocommerce_secret_key')->value('value');

        try {
            $woocommerce = new Client($url, $consumerKey, $secretKey, ['version' => 'wc/v3']);
            $woocommerce->get('products');  // Simple test to fetch products
            return response()->json(['message' => 'Conexión exitosa'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Falló la conexión: ' . $e->getMessage()], 500);
        }
    }

    public static function getWooCommerceClient()
    {
        $url = Setting::where('key', 'woocommerce_url')->value('value');
        $consumerKey = Setting::where('key', 'woocommerce_consumer_key')->value('value');
        $secretKey = Setting::where('key', 'woocommerce_secret_key')->value('value');

        if (!$url || !$consumerKey || !$secretKey) {
            throw new \Exception('Configuración de WooCommerce no encontrada o inválida.');
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception('URL de WooCommerce no válida.');
        }

        return new Client($url, $consumerKey, $secretKey, ['version' => 'wc/v3']);
    }
}
