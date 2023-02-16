<!doctype html>
	@if(\App\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
		<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	@else
		<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	@endif
	<head>
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="app-url" content="{{ getBaseURL() }}">
		<meta name="file-base-url" content="{{ getFileBaseURL() }}">
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Favicon -->
		<link rel="icon" href="{{ uploaded_asset(get_setting('site_icon')) }}">
		<title>{{ get_setting('website_name').' | '.get_setting('site_motto') }}</title>
		<!-- google font -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
		<!-- select2 js -->
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.4/datatables.min.css"/>
		<link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" rel="stylesheet">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<!-- aiz core css -->
		<link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css') }}">
	    @if(\App\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
		    <link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-rtl.min.css') }}">
	    @endif
		<link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css') }}">
		<style>
	        body 
			{
            	font-size: 12px;
	        }
	    </style>
		<script>
	    	var AIZ = AIZ || {};
	        AIZ.local = {
	            nothing_selected: '{{ translate('Nothing selected') }}',
	            nothing_found: '{{ translate('Nothing found') }}',
	            choose_file: '{{ translate('Choose file') }}',
	            file_selected: '{{ translate('File selected') }}',
	            files_selected: '{{ translate('Files selected') }}',
	            add_more_files: '{{ translate('Add more files') }}',
	            adding_more_files: '{{ translate('Adding more files') }}',
	            drop_files_here_paste_or: '{{ translate('Drop files here, paste or') }}',
	            browse: '{{ translate('Browse') }}',
	            upload_complete: '{{ translate('Upload complete') }}',
	            upload_paused: '{{ translate('Upload paused') }}',
	            resume_upload: '{{ translate('Resume upload') }}',
	            pause_upload: '{{ translate('Pause upload') }}',
	            retry_upload: '{{ translate('Retry upload') }}',
	            cancel_upload: '{{ translate('Cancel upload') }}',
	            uploading: '{{ translate('Uploading') }}',
	            processing: '{{ translate('Processing') }}',
	            complete: '{{ translate('Complete') }}',
				file: '{{ translate('File') }}',
	            files: '{{ translate('Files') }}',
	        }
		</script>
	</head>
	<body class="">
		<div class="aiz-main-wrapper mi_product_list">
	        @include('backend.inc.admin_sidenav')
    
        @if(Route::currentRouteName() == "products.all" || Route::currentRouteName() == 'inventory_run.index')
            @php
            $extraClass = "productRedesign";
            @endphp
        @else
            @php
            $extraClass = "";
            @endphp
        @endif

		<div class="aiz-content-wrapper {{$extraClass}}">

            @include('backend.inc.admin_nav')

			<div class="aiz-main-content">

				<div class="px-15px px-lg-25px">

                    @yield('content')

				</div>

				<!--<div class="bg-white text-center py-3 px-15px px-lg-25px mt-auto">-->

				<!--	<p class="mb-0">&copy; {{ get_setting('site_name') }} v{{ get_setting('current_version') }}</p>-->

				<!--</div>-->

			</div><!-- .aiz-main-content -->

		</div><!-- .aiz-content-wrapper -->

	</div><!-- .aiz-main-wrapper -->



    @yield('modal')





	<script src="{{ static_asset('assets/js/vendors.js') }}" ></script>

	<script src="{{ static_asset('assets/js/aiz-core.js') }}" ></script>

		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.4/datatables.min.js"></script>

		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



    @yield('script')



    <script type="text/javascript">

	    @foreach (session('flash_notification', collect())->toArray() as $message)

	        AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');

	    @endforeach





        if ($('#lang-change').length > 0) {

            $('#lang-change .dropdown-menu a').each(function() {

                $(this).on('click', function(e){

                    e.preventDefault();

                    var $this = $(this);

                    var locale = $this.data('flag');

                    $.post('{{ route('language.change') }}',{_token:'{{ csrf_token() }}', locale:locale}, function(data){

                        location.reload();

                    });



                });

            });

        }

        function menuSearch(){

			var filter, item;

			filter = $("#menu-search").val().toUpperCase();

			items = $("#main-menu").find("a");

			items = items.filter(function(i,item){

				if($(item).find(".aiz-side-nav-text")[0].innerText.toUpperCase().indexOf(filter) > -1 && $(item).attr('href') !== '#'){

					return item;

				}

			});



			if(filter !== ''){

				$("#main-menu").addClass('d-none');

				$("#search-menu").html('')

				if(items.length > 0){

					for (i = 0; i < items.length; i++) {

						const text = $(items[i]).find(".aiz-side-nav-text")[0].innerText;

						const link = $(items[i]).attr('href');

						 $("#search-menu").append(`<li class="aiz-side-nav-item"><a href="${link}" class="aiz-side-nav-link"><i class="las la-ellipsis-h aiz-side-nav-icon"></i><span>${text}</span></a></li`);

					}

				}else{

					$("#search-menu").html(`<li class="aiz-side-nav-item"><span	class="text-center text-muted d-block">{{ translate('Nothing Found') }}</span></li>`);

				}

			}else{

				$("#main-menu").removeClass('d-none');

				$("#search-menu").html('')

			}

        }

    </script>





		<script type="text/javascript">



		$(document).ready(function () {

		    $(".typeboxes").click(function(){

		        var boxTypeVal = $(this).val();

						var closestValue = $(this).closest('.boxCheckItem').find('.cls_Dynamic_box');

						if(boxTypeVal == 2){

							closestValue.addClass('showtypebox');

						}else{

							closestValue.removeClass('showtypebox');

						}

		     });



		});



		</script>



		<script type="text/javascript">

			$(document).ready(function () {

			$('#product_typecustom').on('change', function() {

				var _token   = $(this).closest('#choice_form').find('input[name="_token"]').val();

				var procustID   = $(this).closest('#choice_form').find('.prodCustomid').val();

				if (procustID < 1) {

					procustID = 0;

				}

				$('.producttypecustom').html('');

				var typeId = $(this).val();

			      $.ajax({

							 url: '{{ route('products.ptype') }}',

							 dataType: "json",

				        type:"POST",

								data:{

					          id: typeId,

					          _token: _token,

										proid: procustID

					        },

			        success:function(response){

			          if(response) {

			           if(response.status == 'success'){

									 // console.log(response.stock_id);

									 $('.producttypecustom').html(response.html);

									 $(".customRefresh").selectpicker("refresh");

									 $('#stock_id_custom').val(response.stock_id);

									 $('#seqId').val(response.seqId);

								// 	 console.log(response.seqId);

									 var codeProductCost = response.cost_code_multiplier;

									 var product_cost = $('#product_cost').val();

									 if(product_cost > 0 && codeProductCost > 0){

										 var totalcodeProductCost = parseFloat(product_cost) * parseFloat(codeProductCost);

										 $('#cost_code').val(totalcodeProductCost.toFixed(3))

									 }

									 $('#cost_code_multiplier_custom').val(codeProductCost);

								 }

			          }

			        }

			      });

			   });

				 $('#product_cost').on('change', function() {

					  var product_cost = $(this).val();

						var codeProductCost = $('#cost_code_multiplier_custom').val();

						if(product_cost > 0 && codeProductCost > 0){

							var totalcodeProductCost = parseFloat(product_cost) * parseFloat(codeProductCost);

							$('#cost_code').val(totalcodeProductCost.toFixed(3))

						}



					 });

			});

		</script>

		<script type="text/javascript">

		$(document).ready(function ()

		{

			$('#select_product_type').on('change', function ()

			{

				var types = $(this).val();

				console.log(types);



				var _token = $(this).closest('#memo_status').find('input[name="_token"]').val();

				$.ajax(

				{

					url: '{{ route('memo.memotype') }}',

					dataType: "json",

					type: "POST",

					data:

					{

						_token: _token,

						type: types

					},

					success: function (response)

					{

						if (response)

						{
							if (response.status == 'success')

							{
								$('#select_product').html(response.html);
								$('#select_product').selectpicker("refresh");
							}

						}

					}

				});

			});

			$('#select_product').on('change', function ()

			{
					var stockData = $("#select_product_type").val();
					if(stockData == "stock_id"){
			    var product_quantity = $(this).find(':selected').data('qty');

			    $('#qty').attr('max' ,product_quantity);

			    $('#qty').val(product_quantity);

				var product_price = $(this).find(':selected').data('price');

				var qtyData = $('#qty').val();

				if (product_price > 0)

				{

					product_price = product_price.toFixed(2);

					$('#unit_price').val(product_price);

					var rowTotal = parseFloat(product_price) * parseFloat(qtyData);

					rowTotal = rowTotal.toFixed(2);

					$('#row_total').val(rowTotal);

					// alert(rowTotal);

				}
			}else{
				$("#unit_price").val("");
				$("#row_total").val("");
			}

			});

			$('#qty').on('change', function ()

			{

				var changePrice = $(this).val();

				var pPrice = $('#unit_price').val();

				var priceChangeQty = parseFloat(pPrice) * parseFloat(changePrice);

				priceChangeQty = priceChangeQty.toFixed(2);

				$('#row_total').val(priceChangeQty);

			});



			//   add memo

			$("#productApend").click(function(){

				 $('tr#empty-row').remove();

				 var apndQty =  $('#qty').val();

				 var apndRowTotal =  $('#row_total').val();
				 if(apndRowTotal > 0){
					 apndRowTotal ='$ '+addCommas((parseFloat(apndRowTotal).toFixed(2)).toString());//comma seperated with decomal string
				 }else{
					 apndRowTotal = '$ '+0;
				 }

				 let control=0;
				 var apndPrice = $('#unit_price').val();
				 var apndProductId =  $('#select_product').val();
				 var apndProducttxt =  $('#select_product option:selected').text();
				 var apndProductimg =  $('#select_product option:selected').data('image');
				 var apndProductDesc =  $('#select_product option:selected').data('desc');

			var apndHtml = '<tr class="selectes-product-append"><td style="width:10%;"><input type="checkbox" class="removeCheckedMemodata" value="'+apndProductId+'"></td><td><img src="'+apndProductimg+'" style="width:40px;height:50px;"></td><td>'+apndProducttxt+' <input type="hidden" name="memoitems['+apndProductId+'][price]" value="'+apndPrice+'"> <input type="hidden" name="memoitems['+apndProductId+'][stock]" value="'+apndProducttxt+'"></td><td class="desc_box">'+apndProductDesc+'</td>  <td>'+apndQty+'<input type="hidden" name="memoitems['+apndProductId+'][quantity]" value="'+apndQty+'"></td> <td>Memo</td> <td>'+apndRowTotal+'<input type="hidden" class="row_total_memo" name="memoitems['+apndProductId+'][rowtotal]" value="'+apndRowTotal+'"></td></tr>';

                    $( ".removeCheckedMemodata" ).each(function() {
                                    if($(this).val()==apndProductId){
                                      control=1;
                                    }

                                    if(control==1){
                                    return false;
                                    }
                            });

                            if(control==0){
                            $(".cls-product-list").append(apndHtml);
                    					calculateMemoTotal();
                    				control=0;
                            }else{
                                alert("You have allready added this product");
                                return false;
                            }
                });







				//remove checked memo in create memo

				$(document).on('click',"button#remove_checked_memo",function(){

					$(".removeCheckedMemodata").each(function() {

						if($(this).is(":checked"))

						 $(this).closest('tr').remove();

						 calculateMemoTotal();

					});

				});



				$('#shipping_text').on('change', function ()

				{



					calculateMemoTotal();

				});

		});





function addCommas(nStr){

 nStr += '';

 var x = nStr.split('.');

 var x1 = x[0];

 var x2 = x.length > 1 ? '.' + x[1] : '';

 var rgx = /(\d+)(\d{3})/;

 while (rgx.test(x1)) {

  x1 = x1.replace(rgx, '$1' + ',' + '$2');

 }

 return x1 + x2;

}











		function calculateMemoTotal(){

			var memoSubTotal = 0;



			 var customerReatailResaler =$('.company_name_select-customer option:selected').data('customer-group');

			 var saletaxPercantage =$('.company_name_select-customer option:selected').data('saletax');

			//  saletaxPercantage = parseFloat((saletaxPercantage.replace('%', ''))); //remove %

			//  console.log(saletaxPercantage);





			$(".row_total_memo").each(function() {

				var rowCount = $(this).val();             //given raw value

				rowCount = (rowCount.replace('$ ', ''));

				rowCount = parseFloat(rowCount.replace(/,/g, ''));

		      //console.log("remove comma ",rowCount);

				memoSubTotal = parseFloat(rowCount) + parseFloat(memoSubTotal);

			});

			var shippinfTxt = $('#shipping_text').val();

			shippinfTxt = (shippinfTxt.replace('$', '')); //remove $

			shippinfTxt = (shippinfTxt.replace(' ', '')); //remove $

			shippinfTxt = parseFloat(shippinfTxt.replace(/,/g, ''));



			var salesTax = $('#sale_tax_text').val();



//

// 			salesTax = (salesTax.replace('$', '')); //remove $

// 			salesTax = (salesTax.replace(' ', '')); //remove $

// 			salesTax = parseFloat(salesTax.replace(/,/g, ''));





// 			if(customerReatailResaler=='retail'){

//               salesTax =  parseFloat(memoSubTotal)*saletaxPercantage*0.01;

//               }

            // var memoTotal = parseFloat(shippinfTxt) + parseFloat(memoSubTotal)+salesTax;

            var memoTotal = parseFloat(shippinfTxt) + parseFloat(memoSubTotal);



				memoSubTotal =addCommas((parseFloat(memoSubTotal).toFixed(2)).toString());//comma seperated with decomal string



			memoTotal =addCommas((parseFloat(memoTotal).toFixed(2)).toString());//comma seperated with decomal string





			$('#cls_total').val('$ '+memoSubTotal);

			$('#cls_total_final').val('$ '+memoTotal);





		  //  $('#sale_tax_text').val('$ '+salesTax);



			$('#shipping_text').val('$ '+shippinfTxt);



		}



	</script>



	<script type="text/javascript">

	jQuery(document).ready(function ($) {

	  $("#checkAll").change(function () {

			if($(this).prop("checked")){

				$(".memoCheckbtn").show();

			}else{

				$(".memoCheckbtn").hide();

			}

			$(".memocheck").prop('checked', $(this).prop("checked"));

	  });

	});



	  function memocheckChanged()

	  {

	      if($('.memocheck').is(":checked"))

	          $(".memoCheckbtn").show();

	      else

	          $(".memoCheckbtn").hide();

	  }



		$(".memo-handle-action").click(function(event) {

			event.preventDefault();

			var _token =$(this).closest('#memo_status').find('input[name="_token"]').val();

			 var selected = new Array();

			$("#itemtable .memocheck:checkbox:checked").each(function(){

				 selected.push(this.value);

			 });

			 if (selected.length > 0) {

				 var memogetDetails = selected.join(",");

				 var buttonmemoAction = $(this).attr('data-action');

				//  console.log('btn action ',buttonmemoAction);

				//  console.log('btn detail ',memogetDetails);



				//  console.log(memogetDetails,'----',buttonmemoAction);

						 $.ajax({

							 type: "POST",

							 url: "{{ route('memo.memoedittype') }}",

							 data: {

								 items: memogetDetails,

								 _token: _token,

								 action: buttonmemoAction

							 },

							 success: function(result) {

								    // alert(result);

							     console.log(result.message);

							     alert(result.message);



							 },

							 error: function(result) {

								 alert('error');

							 }

						 });

          }

		 });



	</script>

	<script type="text/javascript">

	$(document).ready(function ()

	{

		$('#payment_term').on('change', function ()

		{

			var term_days = $(this).find(':selected').data('days');

			var date = new Date();

		 	var resultDate = date.setDate(date. getDate() + term_days);

			var currpayment= new Date(resultDate)

			var dd = String(currpayment.getDate()).padStart(2, '0');

			var mm = String(currpayment.getMonth() + 1).padStart(2, '0'); //January is 0!

			var yyyy = currpayment.getFullYear();

			var todayTermDate = yyyy+'-'+mm+'-'+dd;

			$('.due_date').val(todayTermDate);

// 			alert(todayTermDate);

		});

	});

	</script>

	<script type="text/javascript">



				$(document).ready(function() {

						$(document).on('click','.memo_checkbox',function(){

							manageCheckbox();

						});

				});

				function manageCheckbox(){

					var memoCheckID = [];

					$.each($("input[name='all_memo[]']:checked"), function(){

							memoCheckID.push($(this).val());

					});

					var memoData =	JSON.stringify(memoCheckID);

					$('#checkox_momo').val(memoData);

					if(memoCheckID.length > 0){

						$('#exportDemo-Id').removeAttr('disabled');

					}else{

						$('#exportDemo-Id').attr('disabled',true);

					}

				}





					$(document).on('click','.select_count',function() {

				     if($(this).is(':checked')){

							 $('.memo_checkbox').prop('checked', true);

						 }else{

							 $('.memo_checkbox').prop('checked', false);

						 }

						 manageCheckbox();

					});



	</script>



	<script type="text/javascript">

				$(document).ready(function() {

						$(document).on('change','#product_type',function(){

							$('#pro_type').trigger('click');

						});
						$(document).on('change','#product_model',function(){
							$('#btn_model').trigger('click');

						});
						$(document).on('change','#memoStatus',function(){
							$('#memostatusbtn').trigger('click');

						});
						$(document).on('change','#brand_repot',function(){
							$('#brandRbtn').trigger('click');

						});
						$(document).on('change','#stock_filter_type',function(){
							$('#stock_filter_btn').trigger('click');

						});
						$(document).on('change','#warehouse_values',function(){
							$('#values_filterbtn').trigger('click');

						});
						$(document).on('change','#partner_repot',function(){
							$('#partnerRbtn').trigger('click');

						});

						$(document).on('change','#warehouse_id',function(){

							$('#warehouse_type').trigger('click');

						});
						$(document).on('change','#agent_id',function(){
							$('#Agent_type').trigger('click');
						});

						$(document).on('change','#reference_id',function(){

							$('#reference_type').trigger('click');

						});

						$(document).on('change','#supplier_id',function(){

							$('#supplier_type').trigger('click');

						});

						$(document).on('change','#purchases_pagi',function(){

							$('#purchases_pagi_sub').trigger('click');

						});

						$(document).on('change','#availability',function(){

							$('#pro_warehouse').trigger('click');

						});

				});

	</script>

		<script type="text/javascript">

		//  $(document).ready(function() {

		// 	 if($('#ProductDataTable').length > 0){

		// 			$('#ProductDataTable').DataTable({

        //                 "bPaginate": false,
        //                 "searching": false,
        //                 "columnDefs": [ {
        //                       "targets": 'no-sort',
        //                       "orderable": false,
        //                 } ]

		// 			});

		// 		}

		// 	});

		</script>


		<script type="text/javascript">

	 $(document).ready(function() {

		 // if($('#memo_data').length > 0){
			// 	$('#memo_data').DataTable({
			// 	  "bPaginate": false,
			// 	  "searching": false,
			// 	 "oLanguage": {"sZeroRecords": "", "sEmptyTable": ""}
			// 	});
			// }

		 // if($('#invoce_datatable').length > 0){
			// 	$('#invoce_datatable').DataTable({
			// 	  "bPaginate": false,
			// 	  "searching": false,
			// 	 	"oLanguage": {"sZeroRecords": "", "sEmptyTable": ""}
			// 	});
			// }

		 // if($('#trade_datatable').length > 0){
			// 	$('#trade_datatable').DataTable({
			// 	  "bPaginate": false,
			// 	  "searching": false,
			// 	 	"oLanguage": {"sZeroRecords": "", "sEmptyTable": ""}
			// 	});
			// }



		 // if($('#Job_orders_all').length > 0){
			// 	$('#Job_orders_all').DataTable({
			// 	  "bPaginate": false,
			// 	  "searching": false,
			// 	});
			// }

		 if($('#purchasesTbl').length > 0){
				$('#purchasesTbl').DataTable({
				  "bPaginate": false,
				  "searching": false,
				 	"oLanguage": {"sZeroRecords": "", "sEmptyTable": ""}
				});
			}



		});

	</script>

	<script type="text/javascript">

	$(document).ready(function ()

	{

		$('#search_pro_stock').on('change', function ()

		{

			var proStockId = $(this).val();

			$.ajax(

			{

				url: '{{ route('products.BarcodeAjax') }}',

				dataType: "json",

				type: "POST",

				data:

				{

					"_token": "{{ csrf_token() }}",

					"proStock": proStockId

				},

				success: function (response)

				{

					if (response)

					{

						if (response.status == 'success')

						{

							$('#StockDatahtmlappend tbody').append(response.html);

						}

					}

				}

			});

		});

	});

	</script>

	<script>

			 $(document).ready(function () {

					 $(document).on('click','.removeStockData',function () {

							$(this).closest('tr').remove();

					 });

			 });

			 $(document).ready(function () {

					 $(document).on('click','.optionsubmit',function () {

							var typereq =  $(this).data('typereq');

							console.log(typereq);

							if(typereq == 'model'){

								$('#low_stock').css("display","block")

							}else{

								$('#low_stock').css("display","none")

							}

					 });





			  setTimeout(function() {

                 var $thisdiv = $('.edit-customer_name');

                    var html = '';

			       var customername =  $thisdiv.find(':selected').attr('data-customernameedit');

                    var officeaddress =  $thisdiv.find(':selected').attr('data-office_addressedit');

                    var city =  $thisdiv.find(':selected').attr('data-cityedit');

                    var state =  $thisdiv.find(':selected').attr('data-stateedit');

                    var zipcode =  $thisdiv.find(':selected').attr('data-zipcodeedit');

                    var phone =  $thisdiv.find(':selected').attr('data-phoneedit');

                    var email =  $thisdiv.find(':selected').attr('data-emailedit');









			         html = '<textarea name="all_edit_company" style="color: black; font-size: 20px;"  class="form-control edit-text-area" readonly>'+customername+'\n'+officeaddress+'\n'+city+', '+state+' '+zipcode+'\n'+phone+'\n'+email+'</textarea>';

			         $('.select_customer_textarea').append(html);

                }, 2000);







			 });
			 $(document).ready(function(){
				 $('#excel_download').on('click' , function(){
					 var ids=$('.barcode_id').val();
					 alert(ids);
				 })
			 })

			// $(document).ready(function () {
			// 	$(".excel_download").on('click',function(){
			// 		var id=$("#product_id_barcode").val();
			// 		$.ajax({
			// 			headers: {
			// 				 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			// 		 	},
			// 			type:'post',
			// 			url:"{{route('barcode_product_excel')}}",
			// 			data:{id :id},
			// 			success:function(response)
			// 			{
			// 				alert(response);
			// 			},
			// 			error:function(data){
			// 				alert("gfghgfhgf");
			// 			}
			// 		})
			// 	});
			// });
			 $(document).ready(function () {

					 $(document).on('click','.genratepropdf',function () {

							$('.pdfprint').val('1');

							$('.triggerbarcode').trigger('click');

							return false;

					 });

			 });

			 $(document).ready(function () {

					 $(document).on('click','.protrash',function () {
							$(this).closest('tr').remove();
							AllProFiCalc();
			        return false;
					 });

					 function AllProFiCalc()
					 {
					 tfootcost = 0;
					 tfootstotal = 0;
					 tfootqty = 0;
					 $(".returnItems").each(function() {
							var cost = $(this).find('.rprocost').data('cost');
							var subtotal = $(this).find('.rprocost2').data('subtotal');
							var qty = $(this).find('.rproqty').val();
							rCost = parseFloat(cost);
							rQty = parseInt(qty);

						tfootcost = tfootcost + cost;
						tfootstotal = tfootstotal + subtotal;
						tfootqty = tfootqty + rQty;
						})
						var formatter = new Intl.NumberFormat('en-US', {
						 style: 'currency',
						 currency: 'USD',
					 });
						$('.tfootcost').text(formatter.format(tfootcost));
						$('.tfootstotal').text(formatter.format(tfootstotal));
						$('.tfootqty').text(tfootqty);
					}



					 $(document).on('click','.mi_custom_uploader',function () {

						  setTimeout(function(){$('.uploaded_file_sec').trigger('click')}, 1500);

					 });



					  $(document).on('click','.uppy-DashboardItem-previewImg',function () {

					     var imgTitle = $(this).attr('alt');

					      $(this).closest('li.is-complete').toggleClass('selected_miifile');

					     $('.aiz-uploader-select[title="'+imgTitle+'"]').first().trigger('click');



					 });



			 });

			 $(document).on("change","#select_product_type",function(){
				 var changeByVal = $(this).val();
				 if(changeByVal == "model"){
					 $("#productApend").hide();
					 $("#modelApend").show();
				 }else if(changeByVal == "stock_id"){
					 $("#productApend").show();
					 $("#modelApend").hide();
				 }
			 });

			 $(document).ready(function(){
				 $(".modelOpen").hide();
				 $(".addModeNpro").click(function() {
					 $('tr#empty-row').remove();
					 let control = 0;
					 var str = "";
					 var stock = "";
					 var price = "";
					 var apndModelHtml ="";
					 $(".micustomModel td input:checked").each(function() {
					   str = jQuery(this).attr("value");
					   stock = jQuery(this).data("stock");
					   var apndProductId =  $('#select_product').val();
					   var apndProductDesc =  $('#select_product option:selected').data('desc');
					   price = jQuery(this).closest(".micustomModel").find(".model_by_sale_ids").val();
						 apndModelHtml += '<tr class="selectes-product-append"><td style="width:10%;"><input type="checkbox" class="removeCheckedMemodata" value="'+str+'"></td><td><img src="" style="width:65px;"></td><td>'+stock+' <input type="hidden" name="memoitems['+str+'][price]" value="'+price+'"> <input type="hidden" name="memoitems['+str+'][stock]" value="'+stock+'"></td><td class="desc_box">'+apndProductDesc+'</td>  <td>1<input type="hidden" name="memoitems['+str+'][quantity]" value="1"></td> <td>Memo</td> <td>'+price+'<input type="hidden" class="row_total_memo" name="memoitems['+str+'][rowtotal]" value="'+price+'"></td></tr>';
			 	})
					// apndModelHtml = apndModelHtml.substring(0, apndModelHtml.length - 1);
					// console.log(apndModelHtml);
					$( ".removeCheckedMemodata" ).each(function() {
													if($(this).val()==str){
														control=1;
													}

													if(control==1){
													return false;
													}
									});
					if(control==0){
					$(".cls-product-list").append(apndModelHtml);
						calculateMemoTotal();
						control=0;
					}else{
							alert("You have allready added this product");
							return false;
					}
					$('#add_ajax_model').modal('hide');
				})
			 });

			 $(document).on("click","#modelApend", function(){
				var modelNum = $("#select_product option:selected").text();
				 $.ajax({
					 headers: {
							 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
						 url: "{{route('Memo.modelPro')}}",
						 type: "POST",
      		   dataType: "json",
						 data: {model : modelNum},
						 success: function( response ) {
								 $(".apendPromodelHtml").html(response.modelHtml);
						 }
						})
			 });


	 </script>

</body>

</html>
