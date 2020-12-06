<?php
    $success_flashData = $this->session->flashdata('success_flashData');
    $error_flashData = $this->session->flashdata('error_flashData');
    $warning_flashData = $this->session->flashdata('warning_flashData');

    if ($success_flashData !== NULL) {
        echo '<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$success_flashData.'</div>';
    }

    if ($error_flashData !== NULL) {
        echo '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$error_flashData.'</div>';
    }

    if ($warning_flashData !== NULL) {
        echo '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$warning_flashData.'</div>';
    }
?>