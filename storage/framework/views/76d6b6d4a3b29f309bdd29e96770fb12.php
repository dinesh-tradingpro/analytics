<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title><?php echo e($title ?? config('app.name')); ?></title>


<link rel="icon" type="image/png" href="assets/logo/logo-00.png" sizes="any">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<!-- Chart.js for analytics dashboards -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
<?php echo app('flux')->fluxAppearance(); ?><?php /**PATH /Users/dineshkumarvalan/git/work/TPAnalytics/resources/views/partials/head.blade.php ENDPATH**/ ?>