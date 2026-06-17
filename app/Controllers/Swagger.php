<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;
use OpenApi\Generator;

class Swagger extends Controller
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Izinkan akses jika ENVIRONMENT bukan production ATAU jika SWAGGER_ENABLED bernilai true di .env
        $swaggerEnabled = filter_var(env('SWAGGER_ENABLED', false), FILTER_VALIDATE_BOOLEAN);

        if (ENVIRONMENT === 'production' && !$swaggerEnabled) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Proteksi HTTP Basic Auth jika SWAGGER_USER & SWAGGER_PASSWORD diatur di .env
        $authUsername = env('SWAGGER_USER');
        $authPassword = env('SWAGGER_PASSWORD');

        if (!empty($authUsername) && !empty($authPassword)) {
            $username = $request->getServer('PHP_AUTH_USER');
            $password = $request->getServer('PHP_AUTH_PW');

            if ($username !== $authUsername || $password !== $authPassword) {
                $response->setHeader('WWW-Authenticate', 'Basic realm="Pasangin API Docs"');
                $response->setStatusCode(401);
                $response->setBody('Unauthorized. Silakan masukkan kredensial yang valid.');
                $response->send();
                exit;
            }
        }
    }

    /**
     * Menampilkan Halaman Swagger UI
     */
    public function index()
    {
        $activeGroup = $this->request->getGet('group') ?? 'all';
        return view('swagger_ui', [
            'activeGroup' => $activeGroup
        ]);
    }

    /**
     * Memindai anotasi/atribut dan menghasilkan file JSON OpenAPI spesifikasi
     */
    public function json()
    {
        // Memindai folder app/Documentation untuk mengumpulkan dokumentasi API
        $generator = new Generator();
        $openapi = $generator->generate([
            APPPATH . 'Documentation'
        ]);

        $group = $this->request->getGet('group');
        
        if (!empty($group) && $group !== 'all') {
            $data = json_decode($openapi->toJson(), true);
            
            $groupMap = [
                'auth'            => ['Authentication', 'Authentication (Client)', 'Authentication & Recovery'],
                'about'           => ['About Application'],
                'agreement'       => ['Agreements & Terms'],
                'alamat'          => ['User Address'],
                'user-profile'    => ['User Profile & Account'],
                'cart'            => ['Cart (Keranjang)'],
                'category'        => ['Category (Kategori)'],
                'chat'            => ['Chat (Obrolan)'],
                'construction'    => ['Construction (Konstruksi)'],
                'renovation'      => ['Renovation (Renovasi)'],
                'content'         => ['General Content (Konten Umum)'],
                'contract'        => ['Contract (Kontrak)'],
                'design'          => ['Design (Desain)'],
                'job'             => ['Tukang Jobs & Progress'],
                'payment-webhook' => ['Payment Webhook (Notifikasi Pembayaran)'],
                'notification'    => ['Notification (Notifikasi)'],
                'orders'          => ['Orders (Pesanan)'],
                'payment'         => ['Payments (Pembayaran)'],
                'products'        => ['Products (Produk)'],
                'project'         => ['Projects (Proyek)'],
                'promo'           => ['Promos (Promo)'],
                'settings'        => ['Settings (Pengaturan)'],
                'supplier-auth'   => ['Authentication (Supplier)'],
                'supplier-banner' => ['Supplier Banner'],
                'supplier-ongkir' => ['Supplier Shipping Fee'],
                'supplier-orders' => ['Supplier Orders'],
                'supplier-rating' => ['Supplier Ratings'],
                'tukang-auth'     => ['Authentication (Tukang)'],
                'tukang-content'  => ['Tukang Content'],
                'tukang-rating'   => ['Tukang Ratings'],
                'tukang-wallet'   => ['Tukang Wallet'],
                'voucher'         => ['Vouchers'],
            ];
            
            $targetTags = $groupMap[strtolower($group)] ?? [];
            
            if (!empty($targetTags)) {
                // Filter paths
                $filteredPaths = [];
                if (isset($data['paths'])) {
                    foreach ($data['paths'] as $path => $methods) {
                        $filteredMethods = [];
                        foreach ($methods as $method => $details) {
                            $hasMatchingTag = false;
                            if (isset($details['tags'])) {
                                foreach ($details['tags'] as $tag) {
                                    if (in_array($tag, $targetTags)) {
                                        $hasMatchingTag = true;
                                        break;
                                    }
                                }
                            }
                            if ($hasMatchingTag) {
                                $filteredMethods[$method] = $details;
                            }
                        }
                        if (!empty($filteredMethods)) {
                            $filteredPaths[$path] = $filteredMethods;
                        }
                    }
                }
                $data['paths'] = $filteredPaths;
                
                // Filter metadata tags
                if (isset($data['tags'])) {
                    $data['tags'] = array_values(array_filter($data['tags'], function($tag) use ($targetTags) {
                        return in_array($tag['name'], $targetTags);
                    }));
                }
            }
            
            return $this->response
                ->setHeader('Content-Type', 'application/json')
                ->setBody(json_encode($data));
        }

        return $this->response
            ->setHeader('Content-Type', 'application/json')
            ->setBody($openapi->toJson());
    }
}
