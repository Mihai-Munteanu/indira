 <?php $__env->startSection('content'); ?>
<?php if(session()->has('message')): ?>
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('message')); ?></div>
<?php endif; ?>
<?php if(session()->has('not_permitted')): ?>
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('not_permitted')); ?></div>
<?php endif; ?>

<section>
    <div class="container-fluid">
        <?php if(in_array("purchases-add", $all_permission)): ?>
            <a href="<?php echo e(route('purchases.create')); ?>" class="btn btn-info"><i class="dripicons-plus"></i> <?php echo e(trans('file.Add Purchase')); ?></a>&nbsp;
            
        <?php endif; ?>
    </div>
    <div class="table-responsive">

        <table id="purchase-table" class="table purchase-list" style="width: 100%">
            <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th><?php echo e(trans('Date')); ?></th>
                    <th><?php echo e(trans('Reference')); ?></th>
                    <th><?php echo e(trans('Supplier')); ?></th>
                    <th><?php echo e(trans('Currency')); ?></th>
                    <th><?php echo e(trans('Estimated Delivery Date')); ?></th>
                    <th><?php echo e(trans('Shipping Cost')); ?></th>
                    <th><?php echo e(trans('Total, Tax Excluded')); ?></th>
                    <th><?php echo e(trans('Total Tax')); ?></th>
                    <th><?php echo e(trans('Customs Fee')); ?></th>
                    <th><?php echo e(trans('Total Cost')); ?></th>
                    <th><?php echo e(trans('Grand Total')); ?></th>
                    <th class="not-exported"><?php echo e(trans('file.action')); ?></th>
                    
                    
                </tr>
            </thead>

            <tfoot class="tfoot active">
                <th></th>
                <th><?php echo e(trans('file.Total')); ?></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tfoot>
        </table>
    </div>
</section>

<div id="purchase-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        <div class="container mt-3 pb-2 border-bottom">
            <div class="row">
                <div class="col-md-3">
                    <button id="print-btn" type="button" class="btn btn-default btn-sm d-print-none"><i class="dripicons-print"></i> <?php echo e(trans('file.Print')); ?></button>
                </div>
                <div class="col-md-6">
                    <h3 id="exampleModalLabel" class="modal-title text-center container-fluid"><?php echo e($general_setting->site_title); ?></h3>
                </div>
                <div class="col-md-3">
                    <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close" class="close d-print-none"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="col-md-12 text-center">
                    <i style="font-size: 15px;"><?php echo e(trans('file.Purchase Details')); ?></i>
                </div>
            </div>
        </div>
            <div id="purchase-content" class="modal-body"></div>
            <br>
            <table class="table table-bordered product-purchase-list">
                <thead>
                    <th>#</th>
                    <th><?php echo e(trans('file.product')); ?></th>
                    <th>Sku Code</th>
                    <th>Supplier</th>
                    <th>Supplier Sku code</th>
                    <th>URL</th>
                    <th>Category</th>
                    <th>Qty</th>
                    <th>Cost</th>
                    <th>Price</th>
                </thead>

                <tbody>
                </tbody>
            </table>
            <div id="purchase-footer" class="modal-body"></div>
      </div>
    </div>
</div>







<script type="text/javascript">

    $("ul#purchase").siblings('a').attr('aria-expanded','true');
    $("ul#purchase").addClass("show");
    $("ul#purchase #purchase-list-menu").addClass("active");

    var public_key = <?php echo json_encode($lims_pos_setting_data->stripe_public_key) ?>;
    var all_permission = <?php echo json_encode($all_permission) ?>;
    var purchase_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function confirmDelete() {
        if (confirm("Are you sure want to delete?")) {
            return true;
        }
        return false;
    }

    function confirmDeletePayment() {
        if (confirm("Are you sure want to delete? If you delete this money will be refunded")) {
            return true;
        }
        return false;
    }

    $(document).on("click", "tr.purchase-link td:not(:first-child, :last-child)", function(){
        var purchase = $(this).parent().data('purchase');
        purchaseDetails(purchase);
    });

    $(document).on("click", ".view", function(){
        var purchase = $(this).parent().parent().parent().parent().parent().data('purchase');
        purchaseDetails(purchase);
    });

    $("#print-btn").on("click", function(){
          var divToPrint=document.getElementById('purchase-details');
          var newWin=window.open('','Print-Window');
          newWin.document.open();
          newWin.document.write('<link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css"><style type="text/css">@media  print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
          newWin.document.close();
          setTimeout(function(){newWin.close();},10);
    });

    $(document).on("click", "table.purchase-list tbody .add-payment", function(event) {
        $("#cheque").hide();
        $(".card-element").hide();
        $('select[name="paid_by_id"]').val(1);
        rowindex = $(this).closest('tr').index();
        var purchase_id = $(this).data('id').toString();
        var balance = $('table.purchase-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(8)').text();
        balance = parseFloat(balance.replace(/,/g, ''));
        $('input[name="amount"]').val(balance);
        $('input[name="balance"]').val(balance);
        $('input[name="paying_amount"]').val(balance);
        $('input[name="purchase_id"]').val(purchase_id);
    });

    $(document).on("click", "table.purchase-list tbody .get-payment", function(event) {
        var id = $(this).data('id').toString();
        $.get('purchases/getpayment/' + id, function(data) {
            $(".payment-list tbody").remove();
            var newBody = $("<tbody>");
            payment_date  = data[0];
            payment_reference = data[1];
            paid_amount = data[2];
            paying_method = data[3];
            payment_id = data[4];
            payment_note = data[5];
            cheque_no = data[6];
            change = data[7];
            paying_amount = data[8];
            account_name = data[9];
            account_id = data[10];

            $.each(payment_date, function(index){
                var newRow = $("<tr>");
                var cols = '';

                cols += '<td>' + payment_date[index] + '</td>';
                cols += '<td>' + payment_reference[index] + '</td>';
                cols += '<td>' + account_name[index] + '</td>';
                cols += '<td>' + paid_amount[index] + '</td>';
                cols += '<td>' + paying_method[index] + '</td>';
                cols += '<td><div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu"><li><button type="button" class="btn btn-link edit-btn" data-id="' + payment_id[index] +'" data-clicked=false data-toggle="modal" data-target="#edit-payment"><i class="dripicons-document-edit"></i> Edit</button></li><li class="divider"></li><?php echo e(Form::open(['route' => 'purchase.delete-payment', 'method' => 'post'] )); ?><li><input type="hidden" name="id" value="' + payment_id[index] + '" /> <button type="submit" class="btn btn-link" onclick="return confirmDeletePayment()"><i class="dripicons-trash"></i> Delete</button></li><?php echo e(Form::close()); ?></ul></div></td>'
                newRow.append(cols);
                newBody.append(newRow);
                $("table.payment-list").append(newBody);
            });
            $('#view-payment').modal('show');
        });
    });

    $(document).on("click", "table.payment-list .edit-btn", function(event) {
        $(".edit-btn").attr('data-clicked', true);
        $(".card-element").hide();
        $("#edit-cheque").hide();
        $('#edit-payment select[name="edit_paid_by_id"]').prop('disabled', false);
        var id = $(this).data('id').toString();
        $.each(payment_id, function(index){
            if(payment_id[index] == parseFloat(id)){
                $('input[name="payment_id"]').val(payment_id[index]);
                $('#edit-payment select[name="account_id"]').val(account_id[index]);
                if(paying_method[index] == 'Cash')
                    $('select[name="edit_paid_by_id"]').val(1);
                else if(paying_method[index] == 'Credit Card'){
                    $('select[name="edit_paid_by_id"]').val(3);
                    $.getScript( "public/vendor/stripe/checkout.js" );
                    $(".card-element").show();
                    $("#edit-cheque").hide();
                    $('#edit-payment select[name="edit_paid_by_id"]').prop('disabled', true);
                }
                else{
                    $('select[name="edit_paid_by_id"]').val(4);
                    $("#edit-cheque").show();
                    $('input[name="edit_cheque_no"]').val(cheque_no[index]);
                    $('input[name="edit_cheque_no"]').attr('required', true);
                }
                $('input[name="edit_date"]').val(payment_date[index]);
                $("#payment_reference").html(payment_reference[index]);
                $('input[name="edit_amount"]').val(paid_amount[index]);
                $('input[name="edit_paying_amount"]').val(paying_amount[index]);
                $('.change').text(change[index]);
                $('textarea[name="edit_payment_note"]').val(payment_note[index]);
                return false;
            }
        });
        $('.selectpicker').selectpicker('refresh');
        $('#view-payment').modal('hide');
    });

    $('select[name="paid_by_id"]').on("change", function() {
        var id = $('select[name="paid_by_id"]').val();
        $('input[name="cheque_no"]').attr('required', false);
        $(".payment-form").off("submit");
        if (id == 3) {
            $.getScript( "public/vendor/stripe/checkout.js" );
            $(".card-element").show();
            $("#cheque").hide();
        } else if (id == 4) {
            $("#cheque").show();
            $(".card-element").hide();
            $('input[name="cheque_no"]').attr('required', true);
        } else {
            $(".card-element").hide();
            $("#cheque").hide();
        }
    });

    $('input[name="paying_amount"]').on("input", function() {
        $(".change").text(parseFloat( $(this).val() - $('input[name="amount"]').val() ).toFixed(2));
    });

    $('input[name="amount"]').on("input", function() {
        if( $(this).val() > parseFloat($('input[name="paying_amount"]').val()) ) {
            alert('Paying amount cannot be bigger than recieved amount');
            $(this).val('');
        }
        else if( $(this).val() > parseFloat($('input[name="balance"]').val()) ) {
            alert('Paying amount cannot be bigger than due amount');
            $(this).val('');
        }
        $(".change").text(parseFloat($('input[name="paying_amount"]').val() - $(this).val()).toFixed(2));
    });

    $('select[name="edit_paid_by_id"]').on("change", function() {
        var id = $('select[name="edit_paid_by_id"]').val();
        $('input[name="edit_cheque_no"]').attr('required', false);
        $(".payment-form").off("submit");
        if (id == 3) {
            $(".edit-btn").attr('data-clicked', true);
            $.getScript( "public/vendor/stripe/checkout.js" );
            $(".card-element").show();
            $("#edit-cheque").hide();
        } else if (id == 4) {
            $("#edit-cheque").show();
            $(".card-element").hide();
            $('input[name="edit_cheque_no"]').attr('required', true);
        } else {
            $(".card-element").hide();
            $("#edit-cheque").hide();
        }
    });

    $('input[name="edit_amount"]').on("input", function() {
        if( $(this).val() > parseFloat($('input[name="edit_paying_amount"]').val()) ) {
            alert('Paying amount cannot be bigger than recieved amount');
            $(this).val('');
        }
        $(".change").text(parseFloat($('input[name="edit_paying_amount"]').val() - $(this).val()).toFixed(2));
    });

    $('input[name="edit_paying_amount"]').on("input", function() {
        $(".change").text(parseFloat( $(this).val() - $('input[name="edit_amount"]').val() ).toFixed(2));
    });

    $('#purchase-table').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax":{
            url:"/purchases/purchase-data",
            data:{
                all_permission: all_permission
            },
            dataType: "json",
            type:"post",
            /*success:function(data){
                console.log(data);
            }*/
        },
        "createdRow": function( row, data, dataIndex ) {
            $(row).addClass('purchase-link');
//            console.log(data)
            $(row).attr('data-purchase', JSON.stringify(data));
        },
        "columns": [
            {"data": "key"},
            {"data": "date"},
            {"data": "reference_no"},
            {"data": "supplier"},
            {"data": "currency"},
            {"data": "estimated_delivery_date"},
            {"data": "shipping_cost"},
            {"data": "total_tax_exluded"},
            {"data": "total_tax"},
            {"data": "customs_fee"},
            {"data": "total_cost"},
            {"data": "grand_total"},
            {"data": "options"},
        ],
        'language': {
            /*'searchPlaceholder': "<?php echo e(trans('file.Type date or purchase reference...')); ?>",*/
            'lengthMenu': '_MENU_ <?php echo e(trans("file.records per page")); ?>',
             "info":      '<small><?php echo e(trans("file.Showing")); ?> _START_ - _END_ (_TOTAL_)</small>',
            "search":  '<?php echo e(trans("file.Search")); ?>',
            'paginate': {
                    'previous': '<i class="dripicons-chevron-left"></i>',
                    'next': '<i class="dripicons-chevron-right"></i>'
            }
        },
        order:[['1', 'desc']],
        'columnDefs': [
            {
                "orderable": false,
                'targets': [0, 1, 12]
            },
            {
                'render': function(data, type, row, meta){
                    if(type === 'display'){
                        data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                    }

                   return data;
                },
                'checkboxes': {
                   'selectRow': true,
                   'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                },
                'targets': [0]
            }
        ],
        'select': { style: 'multi',  selector: 'td:first-child'},
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"row"lfB>rtip',
        buttons: [
            {
                extend: 'pdf',
                text: '<?php echo e(trans("file.PDF")); ?>',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum(dt, true);
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                    datatable_sum(dt, false);
                },
                footer:true
            },
            {
                extend: 'csv',
                text: '<?php echo e(trans("file.CSV")); ?>',
                exportOptions: {
                    columns: ':visible:not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum(dt, true);
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                    datatable_sum(dt, false);
                },
                footer:true
            },
            {
                extend: 'print',
                text: '<?php echo e(trans("file.Print")); ?>',
                exportOptions: {
                    columns: ':visible:not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum(dt, true);
                    $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                    datatable_sum(dt, false);
                },
                footer:true
            },
            {
                text: '<?php echo e(trans("file.delete")); ?>',
                className: 'buttons-delete',
                action: function ( e, dt, node, config ) {
                    if(user_verified == '1') {
                        purchase_id.length = 0;
                        $(':checkbox:checked').each(function(i){
                            if(i){
                                var purchase = $(this).closest('tr').data('purchase');
                                purchase_id[i-1] = purchase[3];
                            }
                        });
                        if(purchase_id.length && confirm("Are you sure want to delete?")) {
                            $.ajax({
                                type:'POST',
                                url:'purchases/deletebyselection',
                                data:{
                                    purchaseIdArray: purchase_id
                                },
                                success:function(data) {
                                    alert(data);
                                    //dt.rows({ page: 'current', selected: true }).deselect();
                                    dt.rows({ page: 'current', selected: true }).remove().draw(false);
                                }
                            });
                        }
                        else if(!purchase_id.length)
                            alert('Nothing is selected!');
                    }
                    else
                        alert('This feature is disable for demo!');
                }
            },
            {
                extend: 'colvis',
                text: '<?php echo e(trans("file.Column visibility")); ?>',
                columns: ':gt(0)'
            },
        ],
        drawCallback: function () {
            var api = this.api();
            datatable_sum(api, false);
        }
    } );

    function datatable_sum(dt_selector, is_calling_first) {
        if (dt_selector.rows( '.selected' ).any() && is_calling_first) {
            var rows = dt_selector.rows( '.selected' ).indexes();


            $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 7 ).footer() ).html(dt_selector.cells( rows, 7, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 8 ).footer() ).html(dt_selector.cells( rows, 8, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 9 ).footer() ).html(dt_selector.cells( rows, 9, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 10 ).footer() ).html(dt_selector.cells( rows, 10, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 11 ).footer() ).html(dt_selector.cells( rows, 11, { page: 'current' } ).data().sum().toFixed(2));

        }
        else {

            $( dt_selector.column( 6 ).footer() ).html(dt_selector.column( 6, {page:'current'} ).data().sum().toFixed(2));
            $( dt_selector.column( 7 ).footer() ).html(dt_selector.column( 7, {page:'current'} ).data().sum().toFixed(2));
            $( dt_selector.column( 8 ).footer() ).html(dt_selector.column( 8, {page:'current'} ).data().sum().toFixed(2));
            $( dt_selector.column( 9 ).footer() ).html(dt_selector.column( 9, {page:'current'} ).data().sum().toFixed(2));
            $( dt_selector.column( 10 ).footer() ).html(dt_selector.column( 10, {page:'current'} ).data().sum().toFixed(2));
            $( dt_selector.column( 11 ).footer() ).html(dt_selector.column( 11, {page:'current'} ).data().sum().toFixed(2));

        }
    }

    function purchaseDetails(purchase){
//console.log(purchase)
        var htmltext = `
            <strong><?php echo e(trans("file.Date")); ?>: </strong>${purchase.date}<br>
            <strong><?php echo e(trans("file.reference")); ?>: </strong>${purchase.reference_no}<br>
            <strong>Supplier: </strong>${purchase.supplier}<br>
            <br>
                
                `;



        $.get('purchases/product_purchase/' + purchase.id, function(data){
            $(".product-purchase-list tbody").remove();

            var newBody = $("<tbody>");
            $.each(data, function(index, purchaseProduct){
                const product = purchaseProduct.product
                //const category = purchaseProduct.category
                var newRow = $("<tr>");
                var cols = '';

                cols += '<td><strong>' + (index+1) + '</strong></td>';
                cols += `<td><strong> ${product.name}</strong></td>`;
                cols += `<td><strong> ${product.code}</strong></td>`;
                cols += `<td><strong> ${product.supplier_id}</strong></td>`;
                cols += `<td><strong> ${product.supplier_sku_code}</strong></td>`;
                cols += `<td><strong> ${product.url}</strong></td>`;
                cols += `<td><strong> ${product.category_id}</strong></td>`;
                cols += `<td><strong> ${product.qty}</strong></td>`;
                cols += `<td><strong> ${product.cost}</strong></td>`;
                cols += `<td><strong> ${product.price}</strong></td>`;
                newRow.append(cols);
                newBody.append(newRow);
            });

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=7><strong><?php echo e(trans("file.Total")); ?>:</strong></td>';
            //cols += `<td>${ purchase.total_qty }</td>`;
            cols += `<td>${ purchase.total_cost }</td>`;
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong><?php echo e(trans("file.Order Tax")); ?>:</strong></td>';
            cols += `<td>${purchase.order_tax} ${purchase.order_tax_rate}% }</td>`;
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong><?php echo e(trans("file.Order Discount")); ?>:</strong></td>';
            cols += '<td>' + purchase.order_discount + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong><?php echo e(trans("file.Shipping Cost")); ?>:</strong></td>';
            cols += '<td>' + purchase.shipping_cost + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong><?php echo e(trans("file.grand total")); ?>:</strong></td>';
            cols += '<td>' + purchase.grand_total + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

             $("table.product-purchase-list").append(newBody);
        });
console.log(purchase.user_id);
        var htmlfooter = '<p><strong><?php echo e(trans("file.Note")); ?>:</strong> '+purchase.note+'</p><strong><?php echo e(trans("file.Created By")); ?>:</strong><br>'+ purchase.user_id;

        $('#purchase-content').html(htmltext);
        $('#purchase-footer').html(htmlfooter);
        $('#purchase-details').modal('show');
    }



    $(document).on('submit', '.payment-form', function(e) {
        if( $('input[name="paying_amount"]').val() < parseFloat($('#amount').val()) ) {
            alert('Paying amount cannot be bigger than recieved amount');
            $('input[name="amount"]').val('');
            $(".change").text(parseFloat( $('input[name="paying_amount"]').val() - $('#amount').val() ).toFixed(2));
            e.preventDefault();
        }
        else if( $('input[name="edit_paying_amount"]').val() < parseFloat($('input[name="edit_amount"]').val()) ) {
            alert('Paying amount cannot be bigger than recieved amount');
            $('input[name="edit_amount"]').val('');
            $(".change").text(parseFloat( $('input[name="edit_paying_amount"]').val() - $('input[name="edit_amount"]').val() ).toFixed(2));
            e.preventDefault();
        }

        $('#edit-payment select[name="edit_paid_by_id"]').prop('disabled', false);
    });

    if(all_permission.indexOf("purchases-delete") == -1)
        $('.buttons-delete').addClass('d-none');


</script>
<?php $__env->stopSection(); ?> <?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/mihai/work/practice/indira/salepropos/resources/views/purchase/index.blade.php ENDPATH**/ ?>