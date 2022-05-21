<?php if(isset($error_message) && $error_message !=''){ ?>
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="toast">
        <div class="toast-header">

            <strong class="me-auto">E.D.T</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body text-danger text-end">
            <?php echo $error_message ?>
        </div>
    </div>
<?php }?>

<?php if(isset($success_message) && $success_message !=''){  ?>
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="toast">
        <div class="toast-header">
            <strong class="me-auto">E.D.T</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body text-end">
            <?php echo $success_message ?>
        </div>
    </div>
<?php }?>

