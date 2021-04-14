<?php $__env->startSection('content'); ?>
<!-- this portion is for demo only -->
<!-- <style type="text/css">

  nav.navbar a.menu-btn {
    padding: 12 !important;
  }
  .color-switcher {
      background-color: #fff;
      border: 1px solid #e5e5e5;
      border-radius: 2px;
      padding: 10px;
      position: fixed;
      top: 150px;
      transition: all 0.4s ease 0s;
      width: 150px;
      z-index: 99999;
  }
  .hide-color-switcher {
      right: -150px;
  }
  .show-color-switcher {
      right: -1px;
  }
  .color-switcher a.switcher-button {
      background: #fff;
      border-top: #e5e5e5;
      border-right: #e5e5e5;
      border-bottom: #e5e5e5;
      border-left: #e5e5e5;
      border-style: solid solid solid solid;
      border-width: 1px 1px 1px 1px;
      border-radius: 2px;
      color: #161616;
      cursor: pointer;
      font-size: 22px;
      width: 45px;
      height: 45px;
      line-height: 43px;
      position: absolute;
      top: 24px;
      left: -44px;
      text-align: center;
  }
  .color-switcher a.switcher-button i {
    line-height: 40px
  }
  .color-switcher .color-switcher-title {
      color: #666;
      padding: 0px 0 8px;
  }
  .color-switcher .color-switcher-title:after {
      content: "";
      display: block;
      height: 1px;
      margin: 14px 0 0;
      position: relative;
      width: 20px;
  }
  .color-switcher .color-list a.color {
      cursor: pointer;
      display: inline-block;
      height: 30px;
      margin: 10px 0 0 1px;
      width: 28px;
  }
  .purple-theme {
      background-color: #7c5cc4;
  }
  .green-theme {
      background-color: #1abc9c;
  }
  .blue-theme {
      background-color: #3498db;
  }
  .dark-theme {
      background-color: #34495e;
  }
</style>
<div class="color-switcher hide-color-switcher">
    <a class="switcher-button"><i class="fa fa-cog fa-spin"></i></a>
    <h5><?php echo e(trans('file.Theme')); ?></h5>
    <div class="color-list">
        <a class="color purple-theme" title="purple" data-color="default.css"></a>
        <a class="color green-theme" title="green" data-color="green.css"></a>
        <a class="color blue-theme" title="blue" data-color="blue.css"></a>
        <a class="color dark-theme" title="dark" data-color="dark.css"></a>
    </div>
</div> -->

<?php if(session()->has('not_permitted')): ?>
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('not_permitted')); ?></div>
<?php endif; ?>
<?php if(session()->has('message')): ?>
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('message')); ?></div>
<?php endif; ?>
      <div class="row">
        <div class="container-fluid">
          <div class="col-md-12">
            <div class="brand-text float-left mt-4">
                <h3><?php echo e(trans('file.welcome')); ?> <span><?php echo e(Auth::user()->name); ?></span> </h3>
            </div>
            
          </div>
        </div>
      </div>
      <!-- Counts Section -->
      <section class="dashboard-counts">
        
                            <?php // $customer = DB::table('customers')->find($sale->customer_id); ?>
                            
                            <?php //$supplier = DB::table('suppliers')->find($purchase->supplier_id); ?>
                            
                            <?php //$customer = DB::table('customers')->find($quotation->customer_id); ?>
                            
                        <?php //$product = DB::table('products')->find($sale->product_id); ?>
                        
                        <?php //$product = DB::table('products')->find($sale->product_id); ?>
                        
                        <?php //$product = DB::table('products')->find($sale->product_id); ?>
                        
      </section>

<script type="text/javascript">
    // Show and hide color-switcher
    $(".color-switcher .switcher-button").on('click', function() {
        $(".color-switcher").toggleClass("show-color-switcher", "hide-color-switcher", 300);
    });

    // Color Skins
    $('a.color').on('click', function() {
        /*var title = $(this).attr('title');
        $('#style-colors').attr('href', 'css/skin-' + title + '.css');
        return false;*/
        $.get('setting/general_setting/change-theme/' + $(this).data('color'), function(data) {
        });
        var style_link= $('#custom-style').attr('href').replace(/([^-]*)$/, $(this).data('color') );
        $('#custom-style').attr('href', style_link);
    });

    $(".date-btn").on("click", function() {
        $(".date-btn").removeClass("active");
        $(this).addClass("active");
        var start_date = $(this).data('start_date');
        var end_date = $(this).data('end_date');
        $.get('dashboard-filter/' + start_date + '/' + end_date, function(data) {
            dashboardFilter(data);
        });
    });

    function dashboardFilter(data){
        $('.revenue-data').hide();
        $('.revenue-data').html(parseFloat(data[0]).toFixed(2));
        $('.revenue-data').show(500);

        $('.return-data').hide();
        $('.return-data').html(parseFloat(data[1]).toFixed(2));
        $('.return-data').show(500);

        $('.profit-data').hide();
        $('.profit-data').html(parseFloat(data[2]).toFixed(2));
        $('.profit-data').show(500);

        $('.purchase_return-data').hide();
        $('.purchase_return-data').html(parseFloat(data[3]).toFixed(2));
        $('.purchase_return-data').show(500);
    }
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/mihai/work/practice/indira/salepropos/resources/views/index.blade.php ENDPATH**/ ?>