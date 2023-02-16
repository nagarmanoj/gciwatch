<?php



/*

  |--------------------------------------------------------------------------

  | Admin Routes

  |--------------------------------------------------------------------------

  |

  | Here is where you can register admin routes for your application. These

  | routes are loaded by the RouteServiceProvider within a group which

  | contains the "web" middleware group. Now create something great!

  |

 */



Route::post('/update', 'UpdateController@step0')->name('update');

Route::get('/update/step1', 'UpdateController@step1')->name('update.step1');

Route::get('/update/step2', 'UpdateController@step2')->name('update.step2');



Route::get('/admin', 'AdminController@admin_dashboard')->name('admin.dashboard')->middleware(['auth', 'admin']);

Route::get('/admin/memoDetails/{id}', 'AdminController@getListingTypeId')->name('dashboard.memoDetails');

Route::post('/admin/memoDetailDA', 'AdminController@memoDaAjax')->name('dashboard.memoDA');

Route::post('/admin/stockChart', 'AdminController@stockChart')->name('dashboard.stockChart');

Route::post('/admin/warehouse_data', 'AdminController@warehouseData')->name('dashboard.warehouseData');

Route::post('/admin/Listing_type_id', 'AdminController@ListingTypeAjax')->name('dashboard.tistingType');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function() {

    //Update Routes



Route::post('/edit/email/steved', 'NewsletterController@emailTo')->name('edit.email');





    Route::resource('categories', 'CategoryController');

    Route::get('/categories/edit/{id}', 'CategoryController@edit')->name('categories.edit');

    Route::get('/categories/destroy/{id}', 'CategoryController@destroy')->name('categories.destroy');

    Route::post('/categories/featured', 'CategoryController@updateFeatured')->name('categories.featured');



    Route::get('getAllcategory', 'CategoryController@getAllcategory')->name('partners.getAllcategory');



    Route::resource('brands', 'BrandController');

    Route::get('/brands/edit/{id}', 'BrandController@edit')->name('brands.edit');

    Route::get('/brands/destroy/{id}', 'BrandController@destroy')->name('brands.destroy');



    Route::get('getAllbrand', 'BrandController@getAllbrand')->name('partners.getAllbrand');



    Route::get('/products/admin', 'ProductController@admin_products')->name('products.admin');

    // Route::get('/products/purchases', 'ProductController@purchases')->name('products.purchases');

    Route::get('/products/seller', 'ProductController@seller_products')->name('products.seller');

    Route::get('/products/all', 'ProductController@all_products')->name('products.all');

    Route::get('/products/viewproduct/{id}', 'ProductController@viewproduct')->name('products.viewproduct');

    Route::get('/products/activityproduct/{id}', 'ProductController@activityproduct')->name('products.activityproduct');

    Route::get('/products/create', 'ProductController@create')->name('products.create');

    Route::get('/products/admin/{id}/edit', 'ProductController@admin_product_edit')->name('products.admin.edit');

    Route::get('/products/seller/{id}/edit', 'ProductController@seller_product_edit')->name('products.seller.edit');

    Route::post('/products/todays_deal', 'ProductController@updateTodaysDeal')->name('products.todays_deal');

    Route::post('/products/featured', 'ProductController@updateFeatured')->name('products.featured');

    Route::post('/products/approved', 'ProductController@updateProductApproval')->name('products.approved');

    Route::post('/products/get_products_by_subcategory', 'ProductController@get_products_by_subcategory')->name('products.get_products_by_subcategory');

    Route::post('/bulk-product-delete', 'ProductController@bulk_product_delete')->name('bulk-product-delete');

    Route::post('/barcode_product_excel', 'ProductController@barcode_product_excel')->name('barcode_product_excel');
    //sku find in product/create

    Route::post('/Product/sku', 'ProductController@productskuretrieve')->name('product.sku.create.product');

    // product type for product_tax

    Route::post('/products/productPtype', 'ProductController@producttypesAjax')->name('products.ptype');

    Route::post('/products/Bracodes', 'ProductController@BarcodeAjax')->name('products.BarcodeAjax');
    Route::post('/products/Bracodes_label', 'ProductController@BarcodeAjaxLabel')->name('products.BarcodeAjaxLabel');

    // product Activity
    Route::get('/products/edit_activity', 'ProductController@edit_activity')->name('products.edit_activity');



    // barcode

    Route::get('/products/admin/barcode', 'BarcodeController@barcode')->name('products.barcode');

    Route::get('/products/admin/barcodestore', 'BarcodeController@barcode')->name('products.barcodestorelist');

    Route::get('/products/admin/barcodestore/{id}', 'BarcodeController@storeproid')->name('products.barcodestorelist');

    Route::post('/products/admin/barcodestore', 'BarcodeController@store')->name('products.barcodestore');



    // Job Orders

    Route::get('job_orders/', 'JobOrdersController@index')->name('job_orders.index');

    Route::get('job_orders/open', 'JobOrdersController@open')->name('job_orders.open');

    Route::get('job_orders/close', 'JobOrdersController@close')->name('job_orders.close');

    Route::post('/job_orders-export', 'JobOrdersController@export')->name('job_orders_export.index');

    Route::post('/job-orders-delete', 'JobOrdersController@bulk_job_orders_delete')->name('job_ordres_delete');

    Route::get('job_orders/view/{id}', 'JobOrdersController@view')->name('job_orders.view');

    Route::get('job_orders/activity/{id}', 'JobOrdersController@activity')->name('job_orders.activity');

    Route::get('/job_orders/add', 'JobOrdersController@store')->name('job_orders.create');

    Route::post('/job_orders/save', 'JobOrdersController@storeJoborders')->name('job_orders.save');

    Route::get('/job_orders/edit/{id}', 'JobOrdersController@job_ordersedit')->name('job_orders.edit');

    Route::post('/job_orders/update/{id}', 'JobOrdersController@job_ordersUpdate')->name('job_orders.update');

    Route::post('/job_orders/ajax', 'JobOrdersController@listingAjax')->name('listing.ajax');

    Route::get('/job_orders/destroy/{id}', 'JobOrdersController@jobordersDestroy')->name('job_orders.destroy');



  // Return ReturnCreate

    Route::get('return', 'ReturnController@return')->name('return.index');

    Route::post('/return-export', 'ReturnController@export')->name('return-export.index');

    Route::post('return/delete_section', 'ReturnController@delete_sction')->name('return.delete_section');

    Route::get('return/create', 'ReturnController@ReturnCreate')->name('return.create');

    Route::post('/return/save', 'ReturnController@saveReturn')->name('return.save');

    Route::get('/return/edit/{id}', 'ReturnController@returnedit')->name('return.edit');

    Route::post('/return/update/{id}', 'ReturnController@returnUpdate')->name('return.update');

    Route::get('/return/destroy/{id}', 'ReturnController@optionDestroy')->name('proreturn.destroy');

    Route::get('/return/item/{id}', 'ReturnController@itemDestroy')->name('itemreturn.destroy');

    Route::post('/return/product', 'ReturnController@proSearchAjax')->name('products.returnAjax');

    Route::post('/products/return', 'ReturnController@preReturn')->name('pro.return');



    // Transfer Create

    Route::get('transfer', 'TransfersController@transfer')->name('transfer.index');

    Route::post('/bulk-transfers-delete', 'TransfersController@transfers_delete')->name('bulk-transfers-delete');

    Route::post('/products/Transfersreturn', 'TransfersController@preReturn')->name('pro.transfers-return');

    Route::post('/transfers-export', 'TransfersController@export')->name('transfers-export.index');

    Route::get('transfer/create', 'TransfersController@transferCreate')->name('transfer.create');

    Route::post('/transfer/save', 'TransfersController@saveTransfer')->name('transfer.save');

    Route::get('/transfer/edit/{id}', 'TransfersController@transferedit')->name('transfer.edit');

    Route::post('/transfer/update/{id}', 'TransfersController@transferUpdate')->name('transfer.update');

    Route::post('/transfer/product', 'TransfersController@transferSearchAjax')->name('transfer.searchAjax');

    Route::get('/transfer/destroy/{id}', 'TransfersController@optionDestroy')->name('transfer.destroy');





     // Inventory run

     Route::get('product/inventory_run', 'InventoryrunController@inventoryrun')->name('inventory_run.index');
     Route::post('/products/inventory_run/view', 'InventoryrunController@preReturn')->name('inventory_run.return');

     Route::post('/inventory_run-export', 'InventoryrunController@export')->name('inventory_run-export.index');

     Route::get('inventory/create', 'InventoryrunController@InventoryrunCreate')->name('inventory_run.create');

     Route::post('/inventory/save', 'InventoryrunController@saveInventoryrun')->name('inventory_run.save');

     Route::post('products/InventoryrunAjax', 'InventoryrunController@InventoryrunAjax')->name('inventory_run.ajax');

     Route::get('/inventory/destroy/{id}', 'InventoryrunController@InventoryrunDestroy')->name('inventory_run.destroy');





    //  Route::get('transfer/create', 'TransfersController@transferCreate')->name('transfer.create');

    //  Route::post('/transfer/save', 'TransfersController@saveTransfer')->name('transfer.save');







    // model Ajax for product

    Route::post('products/ajaxunit', 'ProductController@productUnitAjax')->name('products.unitAjax');



    // model Ajax for ProductconditionController

    Route::post('products/ConditionAjax', 'ProductconditionController@productConditionAjax')->name('products.ConditionAjax');



    // model Ajax for BrandController

    Route::post('products/BrandAjax', 'BrandController@productBrandAjax')->name('products.BrandAjax');



    Route::post('products/Tag', 'ProductController@productTagAjax')->name('products.TagAjax');


    Route::post('products/Restock', 'ProductController@productRestock')->name('products.Restock');



    Route::post('products/warehouseAjax', 'WarehouseController@productwarehouseAjax')->name('products.WarehouseAjax');



    Route::post('Memo/MemoCompanyAjax', 'RetailResellerController@MemoCompanyAjax')->name('Memo.MemoCompanyAjax');


    Route::post('Memo/modelPro', 'MemoController@modelPro')->name('Memo.modelPro');



    // model Ajax for CategoryController

    Route::post('products/CategoryAjax', 'CategoryController@productCategoryAjax')->name('products.CategoryAjax');



    // model Ajax for CategoryController

    Route::post('products/SellerAjax', 'SellerController@productSellerAjax')->name('products.SellerAjax');



    // model Ajax for FilteredcolumnsController

    Route::post('products/FilterAjax', 'FilteredcolumnsController@productFilterAjax')->name('products.FilterAjax');

        // memo export

    Route::post('/memo-export', 'MemoController@export')->name('memo-export.index');



    Route::post('/product-export', 'ProductBulkUploadController@export')->name('product-export.index');



    Route::post('/product-csv-download', 'ProductBulkUploadController@DownloadProTypeCsv')->name('product-export.download');



    // purchage historu

    Route::get('purchases/index', 'PurchasesController@purchases')->name('purchases.index');

    Route::get('purchasespdf/{id}/','PurchasesController@generatePDF')->name('purchases.purchasespdf');

    Route::post('/purchases', 'PurchasesController@export')->name('purchases.export');

    // model Ajax for CategoryController

    Route::post('products/Camera', 'ProductController@mi_custom_uploader')->name('products.mi_custom_cam_image');

    Route::post('home/Camera', 'ProductController@mi_custom_index_uploader')->name('products.mi_custom_index_image');

    // purchage historu

    // Route::get('filteredColumns/list/{id}', 'FilteredcolumnsController@get_table_model')->name('FilteredColumns.get_table_model');









    Route::resource('sellers', 'SellerController');

    Route::get('sellers_ban/{id}', 'SellerController@ban')->name('sellers.ban');

    Route::get('/sellers/destroy/{id}', 'SellerController@destroy')->name('sellers.destroy');

    Route::post('/bulk-seller-delete', 'SellerController@bulk_seller_delete')->name('bulk-seller-delete');

    Route::get('/sellers/view/{id}/verification', 'SellerController@show_verification_request')->name('sellers.show_verification_request');

    Route::get('/sellers/approve/{id}', 'SellerController@approve_seller')->name('sellers.approve');

    Route::get('/sellers/reject/{id}', 'SellerController@reject_seller')->name('sellers.reject');

    Route::get('/sellers/login/{id}', 'SellerController@login')->name('sellers.login');

    Route::post('/sellers/payment_modal', 'SellerController@payment_modal')->name('sellers.payment_modal');

    Route::get('/seller/payments', 'PaymentController@payment_histories')->name('sellers.payment_histories');

    Route::get('/seller/payments/show/{id}', 'PaymentController@show')->name('sellers.payment_history');



    Route::resource('customers', 'CustomerController');

    Route::get('customers_ban/{customer}', 'CustomerController@ban')->name('customers.ban');

    Route::get('/customers/login/{id}', 'CustomerController@login')->name('customers.login');

    Route::get('/customers/destroy/{id}', 'CustomerController@destroy')->name('customers.destroy');

    Route::post('/bulk-customer-delete', 'CustomerController@bulk_customer_delete')->name('bulk-customer-delete');



    Route::get('/newsletter', 'NewsletterController@index')->name('newsletters.index');

    Route::post('/newsletter/send', 'NewsletterController@send')->name('newsletters.send');

    Route::post('/newsletter/test/smtp', 'NewsletterController@testEmail')->name('test.smtp');



    Route::resource('profile', 'ProfileController');



    Route::post('/business-settings/update', 'BusinessSettingsController@update')->name('business_settings.update');

    Route::post('/business-settings/update/activation', 'BusinessSettingsController@updateActivationSettings')->name('business_settings.update.activation');

    Route::get('/general-setting', 'BusinessSettingsController@general_setting')->name('general_setting.index');

    Route::get('/activation', 'BusinessSettingsController@activation')->name('activation.index');

    Route::get('/payment-method', 'BusinessSettingsController@payment_method')->name('payment_method.index');

    Route::get('/file_system', 'BusinessSettingsController@file_system')->name('file_system.index');

    Route::get('/social-login', 'BusinessSettingsController@social_login')->name('social_login.index');

    Route::get('/smtp-settings', 'BusinessSettingsController@smtp_settings')->name('smtp_settings.index');

    Route::get('/google-analytics', 'BusinessSettingsController@google_analytics')->name('google_analytics.index');

    Route::get('/google-recaptcha', 'BusinessSettingsController@google_recaptcha')->name('google_recaptcha.index');

    Route::get('/google-map', 'BusinessSettingsController@google_map')->name('google-map.index');

    Route::get('/google-firebase', 'BusinessSettingsController@google_firebase')->name('google-firebase.index');



    //Facebook Settings

    Route::get('/facebook-chat', 'BusinessSettingsController@facebook_chat')->name('facebook_chat.index');

    Route::post('/facebook_chat', 'BusinessSettingsController@facebook_chat_update')->name('facebook_chat.update');

    Route::get('/facebook-comment', 'BusinessSettingsController@facebook_comment')->name('facebook-comment');

    Route::post('/facebook-comment', 'BusinessSettingsController@facebook_comment_update')->name('facebook-comment.update');

    Route::post('/facebook_pixel', 'BusinessSettingsController@facebook_pixel_update')->name('facebook_pixel.update');



    Route::post('/env_key_update', 'BusinessSettingsController@env_key_update')->name('env_key_update.update');

    Route::post('/payment_method_update', 'BusinessSettingsController@payment_method_update')->name('payment_method.update');

    Route::post('/google_analytics', 'BusinessSettingsController@google_analytics_update')->name('google_analytics.update');

    Route::post('/google_recaptcha', 'BusinessSettingsController@google_recaptcha_update')->name('google_recaptcha.update');

    Route::post('/google-map', 'BusinessSettingsController@google_map_update')->name('google-map.update');

    Route::post('/google-firebase', 'BusinessSettingsController@google_firebase_update')->name('google-firebase.update');

    //Currency

    Route::get('/currency', 'CurrencyController@currency')->name('currency.index');

    Route::post('/currency/update', 'CurrencyController@updateCurrency')->name('currency.update');

    Route::post('/your-currency/update', 'CurrencyController@updateYourCurrency')->name('your_currency.update');

    Route::get('/currency/create', 'CurrencyController@create')->name('currency.create');

    Route::post('/currency/store', 'CurrencyController@store')->name('currency.store');

    Route::post('/currency/currency_edit', 'CurrencyController@edit')->name('currency.edit');

    Route::post('/currency/update_status', 'CurrencyController@update_status')->name('currency.update_status');



    //Tax

    Route::resource('tax', 'TaxController');

    Route::get('/tax/edit/{id}', 'TaxController@edit')->name('tax.edit');

    Route::get('/tax/destroy/{id}', 'TaxController@destroy')->name('tax.destroy');

    Route::post('tax-status', 'TaxController@change_tax_status')->name('taxes.tax-status');





    Route::get('/verification/form', 'BusinessSettingsController@seller_verification_form')->name('seller_verification_form.index');

    Route::post('/verification/form', 'BusinessSettingsController@seller_verification_form_update')->name('seller_verification_form.update');

    Route::get('/vendor_commission', 'BusinessSettingsController@vendor_commission')->name('business_settings.vendor_commission');

    Route::post('/vendor_commission_update', 'BusinessSettingsController@vendor_commission_update')->name('business_settings.vendor_commission.update');



    Route::resource('/languages', 'LanguageController');

    Route::post('/languages/{id}/update', 'LanguageController@update')->name('languages.update');

    Route::get('/languages/destroy/{id}', 'LanguageController@destroy')->name('languages.destroy');

    Route::post('/languages/update_rtl_status', 'LanguageController@update_rtl_status')->name('languages.update_rtl_status');

    Route::post('/languages/key_value_store', 'LanguageController@key_value_store')->name('languages.key_value_store');



    // website setting

    Route::group(['prefix' => 'website'], function() {

        Route::get('/footer', 'WebsiteController@footer')->name('website.footer');

        Route::get('/header', 'WebsiteController@header')->name('website.header');

        Route::get('/appearance', 'WebsiteController@appearance')->name('website.appearance');

        Route::get('/pages', 'WebsiteController@pages')->name('website.pages');

        Route::resource('custom-pages', 'PageController');

        Route::get('/custom-pages/edit/{id}', 'PageController@edit')->name('custom-pages.edit');

        Route::get('/custom-pages/destroy/{id}', 'PageController@destroy')->name('custom-pages.destroy');

    });



    Route::resource('roles', 'RoleController');

    Route::get('/roles/edit/{id}', 'RoleController@edit')->name('roles.edit');

    Route::get('/roles/destroy/{id}', 'RoleController@destroy')->name('roles.destroy');



    Route::resource('staffs', 'StaffController');
    Route::post('/bulk-user-delete', 'StaffController@bulk_order_delete')->name('bulk-user-delete');
    Route::get('/staffs/destroy/{id}', 'StaffController@destroy')->name('staffs.destroy');
    Route::get('/staffs/Activity/{id}', 'StaffController@activity')->name('staffs.activity');
    Route::post('/users-export', 'StaffController@export')->name('users_export.index');
    Route::post('/status-change', 'StaffController@status')->name('status.index');



    Route::resource('flash_deals', 'FlashDealController');

    Route::get('/flash_deals/edit/{id}', 'FlashDealController@edit')->name('flash_deals.edit');

    Route::get('/flash_deals/destroy/{id}', 'FlashDealController@destroy')->name('flash_deals.destroy');

    Route::post('/flash_deals/update_status', 'FlashDealController@update_status')->name('flash_deals.update_status');

    Route::post('/flash_deals/update_featured', 'FlashDealController@update_featured')->name('flash_deals.update_featured');

    Route::post('/flash_deals/product_discount', 'FlashDealController@product_discount')->name('flash_deals.product_discount');

    Route::post('/flash_deals/product_discount_edit', 'FlashDealController@product_discount_edit')->name('flash_deals.product_discount_edit');



    //Subscribers

    Route::get('/subscribers', 'SubscriberController@index')->name('subscribers.index');

    Route::get('/subscribers/destroy/{id}', 'SubscriberController@destroy')->name('subscriber.destroy');



    // Route::get('/orders', 'OrderController@admin_orders')->name('orders.index.admin');

    // Route::get('/orders/{id}/show', 'OrderController@show')->name('orders.show');

    // Route::get('/sales/{id}/show', 'OrderController@sales_show')->name('sales.show');

    // Route::get('/sales', 'OrderController@sales')->name('sales.index');

    // All Orders

    Route::get('/all_orders', 'OrderController@all_orders')->name('all_orders.index');

    Route::get('/all_orders/{id}/show', 'OrderController@all_orders_show')->name('all_orders.show');



    // Inhouse Orders

    Route::get('/inhouse-orders', 'OrderController@admin_orders')->name('inhouse_orders.index');

    Route::get('/inhouse-orders/{id}/show', 'OrderController@show')->name('inhouse_orders.show');



    // Seller Orders

    Route::get('/seller_orders', 'OrderController@seller_orders')->name('seller_orders.index');

    Route::get('/seller_orders/{id}/show', 'OrderController@seller_orders_show')->name('seller_orders.show');



    Route::post('/bulk-order-status', 'OrderController@bulk_order_status')->name('bulk-order-status');





    // Pickup point orders

    Route::get('orders_by_pickup_point', 'OrderController@pickup_point_order_index')->name('pick_up_point.order_index');

    Route::get('/orders_by_pickup_point/{id}/show', 'OrderController@pickup_point_order_sales_show')->name('pick_up_point.order_show');



    Route::get('/orders/destroy/{id}', 'OrderController@destroy')->name('orders.destroy');

    Route::post('/bulk-order-delete', 'OrderController@bulk_order_delete')->name('bulk-order-delete');



    Route::post('/pay_to_seller', 'CommissionController@pay_to_seller')->name('commissions.pay_to_seller');



    //Reports

    Route::get('/stock_report', 'ReportController@stock_report')->name('stock_report.index');
    Route::get('/profit_loss', 'ReportController@profit_loss')->name('profit_loss.index');
    Route::get('/short_stock_excel', 'ReportController@short_stock_excel')->name('short_stock_excel.index');

    Route::get('/supplier_excel', 'ReportController@supplier_excel')->name('supplier_excel.index');
    Route::get('/supplier_details_excel', 'ReportController@supplier_details_excel')->name('supplier_details_excel.index');

    Route::get('/seller_sale_report_excel', 'ReportController@seller_sale_report_excel')->name('seller_sale_report_excel.index');
    Route::get('/product_report_excel', 'ReportController@product_report_excel')->name('product_report_excel.index');
    Route::get('/customer_report_excel', 'ReportController@customer_report_excel')->name('customer_report_excel.index');
    Route::get('/short_stock', 'ReportController@short_stock')->name('short_stock.index');
    Route::get('/suppliers_report', 'ReportController@suppliers_report')->name('suppliers_report.index');
    Route::get('/supplier_details_report/{id}', 'ReportController@supplier_details_report')->name('supplier_details_report.index');
    Route::get('/best_sellers_report', 'ReportController@best_sellers_report')->name('best_sellers_report.index');
    Route::get('/Ccustomer_report', 'ReportController@customer')->name('Ccustomer_report.index');
    Route::get('/customer_report/{id}', 'ReportController@customer_report')->name('customer_reports.index');
    Route::get('/in_house_sale_report', 'ReportController@in_house_sale_report')->name('in_house_sale_report.index');

    Route::get('/seller_sale_report', 'ReportController@seller_sale_report')->name('seller_sale_report.index');
    Route::get('/product_report', 'ReportController@product_repot')->name('product_report.index');
    Route::get('/wish_report', 'ReportController@wish_report')->name('wish_report.index');

    Route::get('/user_search_report', 'ReportController@user_search_report')->name('user_search_report.index');

    Route::get('/wallet-history', 'ReportController@wallet_transaction_history')->name('wallet-history.index');
    Route::post('/reports/warehouse_data', 'ReportController@warehouseDataAjax')->name('reportAjax.warehouseData');

//attechmentexcel


// Route::get('/seller_sale_report_excel', 'ReportController@seller_sale_report_excel')->name('seller_sale_report_excel.index');







    //job orders reports
    Route::get('/agent_report', 'JobOrdersReportController@agent_report')->name('agent_report.index');
    Route::get('/agent_report-export', 'JobOrdersReportController@agent_report_export')->name('agent_report_export.index');
    Route::get('/client_report-export', 'JobOrdersReportController@client_report_export')->name('client_report_export.index');
    Route::get('/complete_report_export', 'JobOrdersReportController@complete_report_export')->name('complete_report_export.index');
    Route::get('/pendingJob_report_export', 'JobOrdersReportController@pendingJob_report_export')->name('pendingJob_report_export.index');
    Route::get('/onHoldJob_report_export', 'JobOrdersReportController@onHoldJob_report_export')->name('onHoldJob_report_export.index');
    Route::get('/openJob_report_export', 'JobOrdersReportController@openJob_report_export')->name('openJob_report_export.index');
    Route::get('/pastDueJob_report_export', 'JobOrdersReportController@pastDueJob_report_export')->name('pastDueJob_report_export.index');
    Route::get('/client_report', 'JobOrdersReportController@client_report')->name('client_report.index');
    Route::get('/complete_report', 'JobOrdersReportController@complete_report')->name('complete_report.index');
    Route::get('/pending_job_report', 'JobOrdersReportController@pending_job_report')->name('pending_job_report.index');
    Route::get('/OnHoldJob_report', 'JobOrdersReportController@OnHoldJob_report')->name('OnHoldJob_report.index');
    Route::get('/OpenJob_report', 'JobOrdersReportController@OpenJob_report')->name('OpenJob_report.index');
    Route::get('/PastDueJob_report', 'JobOrdersReportController@PastDueJob_report')->name('PastDueJob_report.index');


    //Blog Section

    Route::resource('blog-category', 'BlogCategoryController');

    Route::get('/blog-category/destroy/{id}', 'BlogCategoryController@destroy')->name('blog-category.destroy');

    Route::resource('blog', 'BlogController');

    Route::get('/blog/destroy/{id}', 'BlogController@destroy')->name('blog.destroy');

    Route::post('/blog/change-status', 'BlogController@change_status')->name('blog.change-status');



    //Coupons

    Route::resource('coupon', 'CouponController');

    Route::get('/coupon/destroy/{id}', 'CouponController@destroy')->name('coupon.destroy');



    //Reviews

    Route::get('/reviews', 'ReviewController@index')->name('reviews.index');

    Route::post('/reviews/published', 'ReviewController@updatePublished')->name('reviews.published');



    //Support_Ticket

    Route::get('support_ticket/', 'SupportTicketController@admin_index')->name('support_ticket.admin_index');

    Route::get('support_ticket/{id}/show', 'SupportTicketController@admin_show')->name('support_ticket.admin_show');

    Route::post('support_ticket/reply', 'SupportTicketController@admin_store')->name('support_ticket.admin_store');



    //Pickup_Points

    Route::resource('pick_up_points', 'PickupPointController');

    Route::get('/pick_up_points/edit/{id}', 'PickupPointController@edit')->name('pick_up_points.edit');

    Route::get('/pick_up_points/destroy/{id}', 'PickupPointController@destroy')->name('pick_up_points.destroy');



    //conversation of seller customer

    Route::get('conversations', 'ConversationController@admin_index')->name('conversations.admin_index');

    Route::get('conversations/{id}/show', 'ConversationController@admin_show')->name('conversations.admin_show');



    Route::post('/sellers/profile_modal', 'SellerController@profile_modal')->name('sellers.profile_modal');

    Route::post('/sellers/approved', 'SellerController@updateApproved')->name('sellers.approved');



    Route::resource('attributes', 'AttributeController');

    Route::get('/attributes/edit/{id}', 'AttributeController@edit')->name('attributes.edit');

    Route::get('/attributes/destroy/{id}', 'AttributeController@destroy')->name('attributes.destroy');



  //Partners

    Route::get('partners', 'SiteOptionsController@partners')->name('partners.index');

    Route::get('partners/create', 'SiteOptionsController@partnersCreate')->name('partners.create');

    Route::post('/partners/save', 'SiteOptionsController@savePartner')->name('partner.save');

    Route::get('/partners/edit/{id}', 'SiteOptionsController@partnersedit')->name('partners.edit');

    Route::post('/partners/update/{id}', 'SiteOptionsController@partnerUpdate')->name('partners.update');



    Route::get('getAllpartners', 'SiteOptionsController@getAllpartners')->name('partners.getAllpartners');

    Route::get('getAllmetal', 'SiteOptionsController@getAllmetal')->name('partners.getAllmetal');

    Route::get('getAllmodel', 'SiteOptionsController@getAllmodel')->name('partners.getAllmodel');

    Route::get('getAllsize', 'SiteOptionsController@getAllsize')->name('partners.getAllsize');

    Route::get('getAllunit', 'SiteOptionsController@getAllunit')->name('partners.getAllunit');





    // metal

    Route::get('metal', 'SiteOptionsController@metal')->name('metal.index');

    Route::get('metal/create', 'SiteOptionsController@metalCreate')->name('metal.create');

    Route::post('/metal/save', 'SiteOptionsController@saveMetal')->name('metal.save');

    Route::get('/metal/edit/{id}', 'SiteOptionsController@metaledit')->name('metal.edit');

    Route::post('/metal/update/{id}', 'SiteOptionsController@metalUpdate')->name('metal.update');



    // Model

    Route::get('model', 'SiteOptionsController@model')->name('model.index');

    Route::get('model/create', 'SiteOptionsController@modelCreate')->name('model.create');

    Route::post('/model/save', 'SiteOptionsController@saveModel')->name('model.save');

    Route::get('/model/edit/{id}', 'SiteOptionsController@modeledit')->name('model.edit');

    Route::post('/model/update/{id}', 'SiteOptionsController@modelUpdate')->name('model.update');



    // Size

    Route::get('size', 'SiteOptionsController@size')->name('size.index');

    Route::get('size/create', 'SiteOptionsController@sizeCreate')->name('size.create');

    Route::post('/size/save', 'SiteOptionsController@saveSize')->name('size.save');

    Route::get('/size/edit/{id}', 'SiteOptionsController@sizeedit')->name('size.edit');

    Route::post('/size/update/{id}', 'SiteOptionsController@sizeUpdate')->name('size.update');



    // Unit

    Route::get('unit', 'SiteOptionsController@unit')->name('unit.index');

    Route::get('unit/create', 'SiteOptionsController@unitCreate')->name('unit.create');

    Route::post('/unit/save', 'SiteOptionsController@saveUnit')->name('unit.save');

    Route::get('/unit/edit/{id}', 'SiteOptionsController@unitedit')->name('unit.edit');

    Route::post('/unit/update/{id}', 'SiteOptionsController@unitUpdate')->name('unit.update');



    // listingtype

    Route::get('listingtype', 'SiteOptionsController@listingtype')->name('Listingtype.index');

    Route::get('listingtype/create', 'SiteOptionsController@listingtypeCreate')->name('Listingtype.create');

    Route::post('/listingtype/save', 'SiteOptionsController@saveListingtype')->name('Listingtype.save');

    Route::get('/listingtype/edit/{id}', 'SiteOptionsController@listingtypeedit')->name('Listingtype.edit');

    Route::post('/listingtype/update/{id}', 'SiteOptionsController@listingtypeUpdate')->name('Listingtype.update');



    Route::get('/soption/destroy/{id}', 'SiteOptionsController@optionDestroy')->name('soption.destroy');



    // Sequence

    Route::get('sequence', 'SequenceController@sequence')->name('sequence.index');

    Route::get('sequence/create', 'SequenceController@SequenceCreate')->name('sequence.create');

    Route::post('/sequence/save', 'SequenceController@saveSequence')->name('sequence.save');

    Route::get('/sequence/edit/{id}', 'SequenceController@sequenceedit')->name('sequence.edit');

    Route::post('/sequence/update/{id}', 'SequenceController@sequenceUpdate')->name('sequence.update');

    Route::get('/sequence/destroy/{id}', 'SequenceController@optionDestroy')->name('seqoption.destroy');





    // retailreseller

    Route::get('retailreseller', 'RetailResellerController@retailreseller')->name('retailreseller.index');

    Route::get('retailreseller/create', 'RetailResellerController@RetailResellerCreate')->name('retailreseller.create');

    Route::post('/retailreseller/save', 'RetailResellerController@saveRetailReseller')->name('retailreseller.save');

    Route::get('/retailreseller/edit/{id}', 'RetailResellerController@retailreselleredit')->name('retailreseller.edit');

    Route::get('/retailreseller/activities/{id}', 'RetailResellerController@activities')->name('retailreseller.activities');

    Route::post('/retailreseller/update/{id}', 'RetailResellerController@retailresellerUpdate')->name('retailreseller.update');

    Route::get('/retailreseller/destroy/{id}', 'RetailResellerController@optionDestroy')->name('retailreselleroption.destroy');



    // Warehouse

    Route::get('warehouse', 'WarehouseController@warehouse')->name('warehouse.index');

    Route::get('warehouse/create', 'WarehouseController@WarehouseCreate')->name('warehouse.create');

    Route::post('/warehouse/save', 'WarehouseController@saveWarehouse')->name('warehouse.save');

    Route::get('/warehouse/edit/{id}', 'WarehouseController@warehouseedit')->name('warehouse.edit');

    Route::post('/warehouse/update/{id}', 'WarehouseController@warehouseUpdate')->name('warehouse.update');

    Route::get('/warehouse/destroy/{id}', 'WarehouseController@optionDestroy')->name('wareoption.destroy');



    // Condition

    Route::get('productcondition', 'ProductconditionController@productcondition')->name('productcondition.index');

    Route::get('productcondition/create', 'ProductconditionController@ProductconditionCreate')->name('productcondition.create');

    Route::post('/productcondition/save', 'ProductconditionController@saveProductcondition')->name('productcondition.save');

    Route::get('/productcondition/edit/{id}', 'ProductconditionController@productconditionedit')->name('productcondition.edit');

    Route::post('/productcondition/update/{id}', 'ProductconditionController@productconditionUpdate')->name('productcondition.update');

    Route::get('/productcondition/destroy/{id}', 'ProductconditionController@optionDestroy')->name('productcondition.destroy');



    Route::get('getAllcondition', 'ProductconditionController@getAllcondition')->name('partners.getAllcondition');



    // Producttype

    Route::get('Producttype', 'ProducttypeController@producttype')->name('Producttype.index');

    Route::get('Producttype/create', 'ProducttypeController@ProducttypeCreate')->name('Producttype.create');

    Route::post('/Producttype/save', 'ProducttypeController@saveProducttype')->name('Producttype.save');

    Route::get('/Producttype/edit/{id}', 'ProducttypeController@producttypeedit')->name('Producttype.edit');

    Route::post('/Producttype/update/{id}', 'ProducttypeController@producttypeUpdate')->name('Producttype.update');

    Route::get('/Producttype/destroy/{id}', 'ProducttypeController@optionDestroy')->name('protypoption.destroy');





    // Agent

    Route::get('agent', 'AgentController@agent')->name('agent.index');

    Route::get('agent/Activity/{id}', 'AgentController@activity')->name('agent.activity');

    Route::post('agent/agentajax', 'AgentController@AgentAjax')->name('agent.agentAjax');

    Route::get('agent/create', 'AgentController@AgentCreate')->name('agent.create');

    Route::post('/agent/save', 'AgentController@saveAgent')->name('agent.save');

    Route::get('/agent/edit/{id}', 'AgentController@agentedit')->name('agent.edit');

    Route::post('/agent/update/{id}', 'AgentController@agentUpdate')->name('agent.update');

    Route::get('/agent/destroy/{id}', 'AgentController@optionDestroy')->name('agent.destroy');

// expertis

    Route::get('expertise', 'ExpertiseController@expertise')->name('expertise.index');

    Route::get('expertise/create', 'ExpertiseController@ExpertiseCreate')->name('expertise.create');

    Route::post('/expertise/save', 'ExpertiseController@saveExpertise')->name('expertise.save');

    Route::get('/expertise/edit/{id}', 'ExpertiseController@expertiseedit')->name('expertise.edit');

    Route::post('/expertise/update/{id}', 'ExpertiseController@expertiseUpdate')->name('expertise.update');

    Route::get('/expertise/destroy/{id}', 'ExpertiseController@optionDestroy')->name('expertise.destroy');



    // MemoProduct

    Route::get('memo', 'MemoController@memo')->name('memo.index');
    Route::post('/memo-invoice-export', 'MemoController@invoice_export')->name('memo_invoice_export.index');
    Route::post('/memo-trade-export', 'MemoController@trade_export')->name('memo_trade_export.index');
    Route::post('/memo-tradeNGD-export', 'MemoController@tradeNGD_export')->name('memo_tradeNGD_export.index');
    Route::post('/memo-return-export', 'MemoController@return_export')->name('memo_return_export.index');

    Route::get('/memo/admin', 'MemoController@memo')->name('memo.admin');

    Route::get('closememo', 'MemoController@closememo')->name('memo.close');

    Route::get('memo/create', 'MemoController@MemoCreate')->name('memo.create');

    Route::post('/memo/save', 'MemoController@saveMemo')->name('memo.save');

    Route::get('/memo/edit/{id}', 'MemoController@memoedit')->name('memo.edit');

    Route::post('/memo/update/{id}', 'MemoController@memoUpdate')->name('memo.update');

    Route::get('/memo/destroy/{id}', 'MemoController@memoDestroy')->name('memo.destroy');

    Route::get('memopdf/{id}/{status}','MemoController@generatePDF')->name('memo.memopdf');





    // MemoProduct

    Route::get('memopayment', 'MemopaymentController@memopayment')->name('memopayment.index');

    Route::get('memopayment/create', 'MemopaymentController@MemopaymentCreate')->name('memopayment.create');

    Route::post('/memopayment/save', 'MemopaymentController@saveMemopayment')->name('memopayment.save');

    Route::get('/memopayment/edit/{id}', 'MemopaymentController@memopaymentedit')->name('memopayment.edit');

    Route::post('/memopayment/update/{id}', 'MemopaymentController@memopaymentUpdate')->name('memopayment.update');

    Route::get('/memopayment/destroy/{id}', 'MemopaymentController@memopaymentDestroy')->name('memopaymentoption.destroy');



    // ajax for memo

    Route::post('/memo/memotype', 'MemoController@memoAjax')->name('memo.memotype');





    // ajax for memoEdit

    Route::post('/memo/memoedittype', 'MemoController@memoeditAjax')->name('memo.memoedittype');



    // pdf for memo

    Route::get('pdf/{id}/{status}','PDFController@viewinvoice')->name('memo.viewinvoice');

    Route::get('generatePDF/{id}/{status}','PDFController@generatePDF')->name('memo.generatepdf');





    // memo status update

    Route::get('memo/invoice', 'MemoController@invoice')->name('memostatus.index');

    Route::get('memo/trade', 'MemoController@trade')->name('memotrade.index');

    Route::get('memo/return', 'MemoController@return')->name('memoreturns.index');

    Route::get('memo/trade-ngd', 'MemoController@trade_ngd')->name('memotrade_ngd.index');

    Route::get('memo/activitiinvoice/{id}', 'MemoController@activitiinvoice')->name('memo.activitiinvoice');

    // memo all list activity
    Route::get('return/activity/{id}', 'MemoController@returnactivity')->name('memo.returnactivity');





    // Appraisal

    Route::post('Appraisal/stockid', 'AppraisalController@AppraisalCreateStockId')->name('Appraisal.steved.AppraisalCreateStockId');  //created by steved

    Route::get('Appraisal', 'AppraisalController@appraisal')->name('Appraisal.index');

    Route::get('Appraisal/create', 'AppraisalController@AppraisalCreate')->name('Appraisal.create');

    Route::post('/Appraisal/ajax', 'AppraisalController@listingAjax')->name('Appraisal_listing.ajax');

    Route::post('/Appraisal/Stockajax', 'AppraisalController@StockIdAjax')->name('Appraisal_stock_id.ajax');

    Route::post('/Appraisal/view', 'AppraisalController@view')->name('Appraisal_view.ajax');

    Route::post('/Appraisal/save', 'AppraisalController@saveAppraisal')->name('Appraisal.save');

    Route::get('/Appraisal/edit/{id}', 'AppraisalController@appraisaledit')->name('Appraisal.edit');

    Route::post('/Appraisal/update/{id}', 'AppraisalController@appraisalUpdate')->name('Appraisal.update');

    Route::get('/Appraisal/destroy/{id}', 'AppraisalController@optionDestroy')->name('appraisal.destroy');

    // ajax for appraisal

    Route::post('/Appraisal/memotype', 'MemoController@appraisalAjax')->name('appraisal.appraisaltype');



    //Attribute Value

    Route::post('/store-attribute-value', 'AttributeController@store_attribute_value')->name('store-attribute-value');

    Route::get('/edit-attribute-value/{id}', 'AttributeController@edit_attribute_value')->name('edit-attribute-value');

    Route::post('/update-attribute-value/{id}', 'AttributeController@update_attribute_value')->name('update-attribute-value');

    Route::get('/destroy-attribute-value/{id}', 'AttributeController@destroy_attribute_value')->name('destroy-attribute-value');



    //Colors

    Route::get('/colors', 'AttributeController@colors')->name('colors');

    Route::post('/colors/store', 'AttributeController@store_color')->name('colors.store');

    Route::get('/colors/edit/{id}', 'AttributeController@edit_color')->name('colors.edit');

    Route::post('/colors/update/{id}', 'AttributeController@update_color')->name('colors.update');

    Route::get('/colors/destroy/{id}', 'AttributeController@destroy_color')->name('colors.destroy');



    Route::resource('addons', 'AddonController');

    Route::post('/addons/activation', 'AddonController@activation')->name('addons.activation');



    Route::get('/customer-bulk-upload/index', 'CustomerBulkUploadController@index')->name('customer_bulk_upload.index');

    Route::post('/bulk-user-upload', 'CustomerBulkUploadController@user_bulk_upload')->name('bulk_user_upload');

    Route::post('/bulk-customer-upload', 'CustomerBulkUploadController@customer_bulk_file')->name('bulk_customer_upload');

    Route::get('/user', 'CustomerBulkUploadController@pdf_download_user')->name('pdf.download_user');

    //Customer Package



    Route::resource('customer_packages', 'CustomerPackageController');

    Route::get('/customer_packages/edit/{id}', 'CustomerPackageController@edit')->name('customer_packages.edit');

    Route::get('/customer_packages/destroy/{id}', 'CustomerPackageController@destroy')->name('customer_packages.destroy');



    //Classified Products

    Route::get('/classified_products', 'CustomerProductController@customer_product_index')->name('classified_products');

    Route::post('/classified_products/published', 'CustomerProductController@updatePublished')->name('classified_products.published');







    //Shipping Configuration

    Route::get('/shipping_configuration', 'BusinessSettingsController@shipping_configuration')->name('shipping_configuration.index');

    Route::post('/shipping_configuration/update', 'BusinessSettingsController@shipping_configuration_update')->name('shipping_configuration.update');



    // Route::resource('pages', 'PageController');

    // Route::get('/pages/destroy/{id}', 'PageController@destroy')->name('pages.destroy');



    Route::resource('countries', 'CountryController');

    Route::post('/countries/status', 'CountryController@updateStatus')->name('countries.status');



    Route::resource('cities', 'CityController');

    Route::get('/cities/edit/{id}', 'CityController@edit')->name('cities.edit');

    Route::get('/cities/destroy/{id}', 'CityController@destroy')->name('cities.destroy');



    Route::view('/system/update', 'backend.system.update')->name('system_update');

     Route::get('/system/system_setting', 'SystemSettingController@showsaleTax')->name('system_setting');

     Route::post('/system/system_setting/save', 'SystemSettingController@syatemSettingSave')->name('system.setting.save');

    Route::view('/system/server-status', 'backend.system.server_status')->name('system_server');



    // uploaded files

    Route::any('/uploaded-files/file-info', 'AizUploadController@file_info')->name('uploaded-files.info');
    Route::get('/uploaded-files/product-upload', 'AizUploadController@product_upload')->name('products-files.info');
    Route::resource('/uploaded-files', 'AizUploadController');

    Route::get('/uploaded-files/destroy/{id}', 'AizUploadController@destroy')->name('uploaded-files.destroy');



    Route::get('/all-notification', 'NotificationController@index')->name('admin.all-notification');

    Route::post('/memo/payment/deposit', 'MemoController@memo_deposit_payment')->name('admin.memo.payment.deposit');

     Route::get('/memo/payment/receive', 'MemoController@memo_receive_payment_record')->name('admin.memo.payment.receive');



});
