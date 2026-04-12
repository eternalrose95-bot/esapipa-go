<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Artisan;

Route::redirect('/', 'dashboard');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->name('admin.')->group(function () {


    /**
     * Order Download
     */
    Route::get('/{id}/order', function ($id) {
        $order = Order::find($id);

        return Pdf::loadView('pdf.purchase-order', [
            'order' => $order
        ])->stream();
        // ->stream();
        // ->download('Order - #' . sprintf('%04d',  $order->id) . '.pdf')
    })->name('order-download');


    /**
     * Quotation Download
     */
    Route::get('/{id}/quotation', function ($id) {
        $quotation = Quotation::find($id);

        return Pdf::loadView('pdf.quotation', [
            'quotation' => $quotation
        ])->stream();
        // ->stream();
        // ->download('Order - #' . sprintf('%04d',  $order->id) . '.pdf')
    })->name('quotation-download');


    /**
     * Invoice Download
     */
    Route::get('/{id}/invoice', function ($id) {
        $invoice = Invoice::find($id);

        return Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice
        ])->stream();
        // ->stream();
        // ->download('Order - #' . sprintf('%04d',  $order->id) . '.pdf')
    })->name('invoice-download');



    Route::get('/dashboard', Admin\Dashboard::class)->name('dashboard');
    // Route::get('/accounts-summary', Admin\AccountsSummary::class)->name('accounts-summary');

    Route::prefix('users')->middleware('permission:manage roles')->name('users.')->group(function () {
        Route::get('/', Admin\Users\Index::class)->name('index');
        Route::get('/create', Admin\Users\Create::class)->name('create');
        Route::get('{id}/edit', Admin\Users\Edit::class)->name('edit');
    });
    Route::prefix('banks')->middleware('permission:manage roles')->name('banks.')->group(function () {
        Route::get('/', Admin\Banks\Index::class)->name('index');
        Route::get('/create', Admin\Banks\Create::class)->name('create');
        Route::get('{id}/edit', Admin\Banks\Edit::class)->name('edit');
    });

    Route::prefix('brands')->middleware('permission:manage products')->name('brands.')->group(function () {
        Route::get('/', Admin\Brands\Index::class)->name('index');
        Route::get('/create', Admin\Brands\Create::class)->name('create');
        Route::get('{id}/edit', Admin\Brands\Edit::class)->name('edit');
    });

    Route::prefix('clients')->middleware('permission:manage clients')->name('clients.')->group(function () {
        Route::get('/', Admin\Clients\Index::class)->name('index');
        Route::get('/create', Admin\Clients\Create::class)->name('create');
        Route::get('{id}/edit', Admin\Clients\Edit::class)->name('edit');
    });

    Route::prefix('credit-notes')->middleware('permission:manage credit notes')->name('credit-notes.')->group(function () {
        Route::get('/', Admin\CreditNotes\Index::class)->name('index');
        Route::get('/create', Admin\CreditNotes\Create::class)->name('create');
        Route::get('{id}/edit', Admin\CreditNotes\Edit::class)->name('edit');
    });

    Route::prefix('delivery-notes')->middleware('permission:manage delivery notes')->name('delivery-notes.')->group(function () {
        Route::get('/', Admin\DeliveryNotes\Index::class)->name('index');
        Route::get('/create', Admin\DeliveryNotes\Create::class)->name('create');
        Route::get('{id}/edit', Admin\DeliveryNotes\Edit::class)->name('edit');
    });

    Route::prefix('invoices')->middleware('permission:manage invoices')->name('invoices.')->group(function () {
        Route::get('/', Admin\Invoices\Index::class)->name('index');
        Route::get('/create', Admin\Invoices\Create::class)->name('create');
        Route::get('{id}/edit', Admin\Invoices\Edit::class)->name('edit');
    });

    Route::prefix('orders')->middleware('permission:manage orders')->name('orders.')->group(function () {
        Route::get('/', Admin\Orders\Index::class)->name('index');
        Route::get('/create', Admin\Orders\Create::class)->name('create');
        Route::get('{id}/edit', Admin\Orders\Edit::class)->name('edit');
    });

    Route::prefix('product-categories')->middleware('permission:manage products')->name('product-categories.')->group(function () {
        Route::get('/', Admin\ProductCategories\Index::class)->name('index');
        Route::get('/create', Admin\ProductCategories\Create::class)->name('create');
        Route::get('{id}/edit', Admin\ProductCategories\Edit::class)->name('edit');
    });

    Route::prefix('products')->middleware('permission:manage products')->name('products.')->group(function () {
        Route::get('/', Admin\Products\Index::class)->name('index');
        Route::get('/create', Admin\Products\Create::class)->name('create');
        Route::get('{id}/edit', Admin\Products\Edit::class)->name('edit');
    });

    Route::prefix('purchases')->middleware('permission:manage purchases')->name('purchases.')->group(function () {
        Route::get('/', Admin\Purchases\Index::class)->name('index');
        Route::get('/create', Admin\Purchases\Create::class)->name('create');
        Route::get('{id}/edit', Admin\Purchases\Edit::class)->name('edit');
    });

    Route::prefix('operational-expenses')->middleware('permission:manage purchases')->name('operational-expenses.')->group(function () {
        Route::get('/', Admin\OperationalExpenses\Index::class)->name('index');
        Route::get('/create', Admin\OperationalExpenses\Create::class)->name('create');
        Route::get('{id}/edit', Admin\OperationalExpenses\Edit::class)->name('edit');
    });

    Route::prefix('quotations')->middleware('permission:manage quotations')->name('quotations.')->group(function () {
        Route::get('/', Admin\Quotations\Index::class)->name('index');
        Route::get('/create', Admin\Quotations\Create::class)->name('create');
        Route::get('{id}/edit', Admin\Quotations\Edit::class)->name('edit');
    });

    Route::prefix('roles')->middleware('permission:manage roles')->name('roles.')->group(function () {
        Route::get('/', Admin\Roles\Index::class)->name('index');
        Route::get('/create', Admin\Roles\Create::class)->name('create');
        Route::get('{id}/edit', Admin\Roles\Edit::class)->name('edit');
    });

    Route::prefix('sales')->middleware('permission:manage sales')->name('sales.')->group(function () {
        Route::get('/', Admin\Sales\Index::class)->name('index');
        Route::get('/create', Admin\Sales\Create::class)->name('create');
        Route::get('{id}/edit', Admin\Sales\Edit::class)->name('edit');
        Route::get('{id}/invoice', function ($id) {
            $sale = \App\Models\Sale::findOrFail($id);
            $invoice = \App\Models\Invoice::create([
                'invoice_date' => $sale->sale_date,
                'client_id' => $sale->client_id,
                'subtotal' => $sale->subtotal,
                'tax_percentage' => $sale->tax_percentage,
                'tax_amount' => $sale->tax_amount,
                'grand_total' => $sale->grand_total,
            ]);

            foreach ($sale->products as $product) {
                $invoice->products()->attach($product->id, [
                    'quantity' => $product->pivot->quantity,
                    'unit_price' => $product->pivot->unit_price,
                    'discount_percentage' => $product->pivot->discount_percentage ?? 0,
                ]);
            }

            return Pdf::loadView('pdf.invoice', [
                'invoice' => $invoice
            ])->stream();
        })->name('invoice');
    });

    Route::prefix('suppliers')->middleware('permission:manage suppliers')->name('suppliers.')->group(function () {
        Route::get('/', Admin\Suppliers\Index::class)->name('index');
        Route::get('/create', Admin\Suppliers\Create::class)->name('create');
        Route::get('{id}/edit', Admin\Suppliers\Edit::class)->name('edit');
    });
    Route::prefix('employees')->middleware('permission:manage employees')->name('employees.')->group(function () {
        Route::get('/', Admin\Employees\Index::class)->name('index');
        Route::get('/create', Admin\Employees\Create::class)->name('create');
        Route::get('{employee}/edit', Admin\Employees\Edit::class)->name('edit');
    });
    Route::prefix('delivery-notes')->middleware('permission:manage delivery notes')->name('delivery-notes.')->group(function () {
        Route::get('/', Admin\DeliveryNotes\Index::class)->name('index');
        Route::get('/create', Admin\DeliveryNotes\Create::class)->name('create');
        Route::get('{deliveryNote}/edit', Admin\DeliveryNotes\Edit::class)->name('edit');
        Route::get('{id}/pdf', function ($id) {
            $deliveryNote = \App\Models\DeliveryNote::findOrFail($id);
            return Pdf::loadView('pdf.delivery-note', [
                'deliveryNote' => $deliveryNote
            ])->stream();
        })->name('pdf');
    });
    Route::prefix('units')->middleware('permission:manage products')->name('units.')->group(function () {
        Route::get('/', Admin\Units\Index::class)->name('index');
        Route::get('/create', Admin\Units\Create::class)->name('create');
        Route::get('{id}/edit', Admin\Units\Edit::class)->name('edit');
    });
    Route::prefix('sizes')->middleware('permission:manage products')->name('sizes.')->group(function () {
        Route::get('/', Admin\Sizes\Index::class)->name('index');
        Route::get('/create', Admin\Sizes\Create::class)->name('create');
        Route::get('{id}/edit', Admin\Sizes\Edit::class)->name('edit');
    });
    Route::prefix('sale-payments')->middleware('permission:manage payments')->name('sale-payments.')->group(function () {
        Route::get('/', Admin\SalePayments\Index::class)->name('index');
        Route::get('/create', Admin\SalePayments\Create::class)->name('create');
        Route::get('{id}/edit', Admin\SalePayments\Edit::class)->name('edit');
    });
    Route::prefix('purchase-payments')->middleware('permission:manage payments')->name('purchase-payments.')->group(function () {
        Route::get('/', Admin\PurchasePayments\Index::class)->name('index');
        Route::get('/create', Admin\PurchasePayments\Create::class)->name('create');
        Route::get('{id}/edit', Admin\PurchasePayments\Edit::class)->name('edit');
    });

    Route::prefix('cash-accounts')->middleware('permission:manage cash accounts')->name('cash-accounts.')->group(function () {
        Route::get('/', Admin\CashAccounts\Index::class)->name('index');
        Route::get('/create', Admin\CashAccounts\Create::class)->name('create');
        Route::get('{id}/edit', Admin\CashAccounts\Edit::class)->name('edit');
        Route::get('{id}', Admin\CashAccounts\Show::class)->name('show');
    });

    Route::get('/setup', function () {
        Artisan::call('migrate:fresh', [
            '--force' => true,
        ]);

        Artisan::call('db:seed', [
            '--force' => true,
        ]);

        return 'Setup berhasil dijalankan (migrate:fresh + seed)';
    });
});
