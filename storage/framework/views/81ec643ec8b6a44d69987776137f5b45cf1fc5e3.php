<?php $__env->startSection('content'); ?>
<section class="content-header">
  <h1>
    Apiculteurs
  </h1>
</section>
<div class="content">
  <div class="box box-primary">
    <div class="box-body">
      <div class="row" style="padding-left: 20px">
        <?php echo $__env->make('users.show_fields', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <!-- Ajout de la liste des ruches lié à l'utilisateur -->
        <br />

        <div class="ruche_table">
          <?php echo $__env->make('users.ruches_list', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>  
        </div>
        <!-- ================================================ -->
        <a href="<?php echo route('users.index'); ?>" class="btn btn-default">Back</a>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>