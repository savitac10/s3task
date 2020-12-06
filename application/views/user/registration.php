<div class="container">

    <!-- Include Flash Data File -->
    <?php $this->load->view('includes/flash_alert'); ?>
    
    <!-- Registration Form Details -->
    <form action="<?= base_url('user/registration')?>" method="post">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" value="<?= set_value('name'); ?>" class="form-control <?= (form_error('name') == "" ? '':'is-invalid') ?>" placeholder="Enter Full Name" required>
            <?= form_error('name'); ?>        
        </div>
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
        <div class="form-group">
            <label>Password Confirmation</label>
            <input type="password" name="passconf" value="<?= set_value('passconf'); ?>" class="form-control <?= (form_error('passconf') == "" ? '':'is-invalid') ?>" placeholder="Password Confirmation" required>
            <?= form_error('passconf'); ?> 
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
        <button type="reset" class="btn btn-danger">Reset</button>
    </form>
</div>
<br>