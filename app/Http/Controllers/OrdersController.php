<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\WooCommerceController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 50; // Número de pedidos por página
        $currentPage = $request->input('page', 1);

        try {
            $woocommerce = WooCommerceController::getWooCommerceClient();

            // Construir parámetros para la solicitud con filtros
            $queryParams = [
                'per_page' => $perPage,
                'page' => $currentPage,
                'orderby' => 'date',
                'order' => 'desc',
            ];

            if ($search = $request->input('search')) {
                $queryParams['search'] = $search;
            }

            if (($status = $request->input('status')) && $status !== 'all') {
                $queryParams['status'] = $status;
            }

            // Registrar los parámetros de la solicitud para depuración
            Log::info('Solicitando pedidos de WooCommerce con los siguientes parámetros:', $queryParams);

            // Petición para obtener los datos paginados
            $response = $woocommerce->get('orders', $queryParams);

            // Obtener encabezados de la respuesta
            $headers = $woocommerce->http->getResponse()->getHeaders();

            // Registrar los encabezados de la respuesta para depuración
            Log::info('Encabezados de la respuesta de WooCommerce:', $headers);

            // Extraer `x-wp-total` y `x-wp-totalpages` o `pages` de los encabezados
            if (isset($headers['x-wp-total'])) {
                $totalWooOrders = (int) $headers['x-wp-total'];
            } else {
                $totalWooOrders = count($response);
                Log::warning('Encabezado x-wp-total no encontrado. Usando count($response) como fallback.');
            }

            if (isset($headers['x-wp-totalpages'])) {
                $totalPages = (int) $headers['x-wp-totalpages'];
            } elseif (isset($headers['pages'])) {
                $totalPages = (int) $headers['pages'];
            } else {
                $totalPages = 1;
                Log::warning('Encabezado x-wp-totalpages o pages no encontrado. Estableciendo totalPages a 1.');
            }

            // Log completa de la respuesta para depuración
            Log::info('Respuesta completa de WooCommerce:', (array) $response);

            // Definir traducciones de estados
            $statusTranslations = [
                'pending' => 'Pendiente',
                'processing' => 'Procesando',
                'completed' => 'Completado',
                'on-hold' => 'En espera',
                'cancelled' => 'Cancelado',
                // Añade otras traducciones según sea necesario
            ];

            // Mapear los pedidos para incluir nombre completo y otros campos formateados
            $orders = collect($response)->map(function ($order) use ($statusTranslations) {
                // Combinar nombre y apellidos
                $nombre = trim(($order->billing->first_name ?? '') . ' ' . ($order->billing->last_name ?? ''));

                // Traducir estado al español
                $status = strtolower($order->status ?? 'N/A');
                $estado = isset($statusTranslations[$status]) ? $statusTranslations[$status] : ucfirst($status);

                // Formatear total como $21.000
                $totalFormatted = isset($order->total) ? '$' . number_format($order->total, 0, ',', '.') : 'N/A';

                // Formatear fecha como 'd/m/Y H:i'
                $fecha = isset($order->date_created) ? Carbon::parse($order->date_created)->format('d/m/Y H:i') : 'N/A';

                return [
                    'id' => $order->id,
                    'nombre' => $nombre,
                    'estado' => $estado,
                    'total' => $totalFormatted,
                    'fecha' => $fecha,
                    'email' => $order->billing->email ?? 'N/A',
                    'metodo_pago' => $order->payment_method_title ?? 'N/A',
                ];
            });

            return view('pedidos.lista', [
                'orders' => $orders,
                'totalWooOrders' => $totalWooOrders,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
                'perPage' => $perPage,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al cargar los pedidos de WooCommerce: ' . $e->getMessage());

            return view('pedidos.lista', [
                'orders' => [],
                'totalWooOrders' => 0,
                'currentPage' => 1,
                'totalPages' => 1,
                'perPage' => $perPage,
            ])->withErrors('Error al cargar los pedidos: ' . $e->getMessage());
        }
    }
}
