<div class="container">

    <!-- Include Flash Data File -->
    <?php $this->load->view('includes/flash_alert'); ?>
    
    <!-- Login Form Details -->
    <form action="<?= base_url('user/login')?>" method="post">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= set_value('email'); ?>" class="form-control <?= (form_error('email') == "" ? '':'is-invalid') ?>" placeholder="Enter Email" required> 
            <?= form_error('email'); ?>            
        </div>      
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" value="<?= set_value('password'); ?>" class="form-control <?= (form_error('password') == "" ? '':'is-invalid') ?>" placeholder="Password" required>
            <?= form_error('password'); ?> 
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <button type="reset" class="btn btn-danger">Reset</button>
    </form>
</div>