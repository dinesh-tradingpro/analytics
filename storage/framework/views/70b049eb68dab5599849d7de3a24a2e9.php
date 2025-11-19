<?php if (isset($component)) { $__componentOriginal1e2e4872f6356878e593a4247723b360 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1e2e4872f6356878e593a4247723b360 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.modern','data' => ['title' => __('Dashboard')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.modern'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Dashboard'))]); ?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('dashboard', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-977534393-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1e2e4872f6356878e593a4247723b360)): ?>
<?php $attributes = $__attributesOriginal1e2e4872f6356878e593a4247723b360; ?>
<?php unset($__attributesOriginal1e2e4872f6356878e593a4247723b360); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1e2e4872f6356878e593a4247723b360)): ?>
<?php $component = $__componentOriginal1e2e4872f6356878e593a4247723b360; ?>
<?php unset($__componentOriginal1e2e4872f6356878e593a4247723b360); ?>
<?php endif; ?><?php /**PATH /Users/dineshkumarvalan/git/work/TPAnalytics/resources/views/dashboard.blade.php ENDPATH**/ ?>