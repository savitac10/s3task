<div class="container">
	<?php $this->load->view('includes/flash_alert');?>
	<div class="row">
		<!-- Files List Start -->
		<div class="col-lg-8">
			<table id="fileList" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
			  	<thead>
				    <tr>
						<th class="th-sm">Sl No.</th>
						<th class="th-sm">File Name</th>
						<th class="th-sm">Added On</th>
				    </tr>
				</thead>
				<?php if(!empty($files)){?>
			    <tbody>
			    	<?php foreach ($files as $key => $value) {?>
			    	<tr>
						<td><?= $key+1;?></td>
						<td><a href="<?php echo $value->file_link;?>" title="Click on link download the file."><?= $value->file_link;?></a></td>
						<td><?= $value->created_at;?></td>
			    	</tr>
			    	<?php } ?>
				</tbody>
				<?php } ?>
			</table>
		</div>
		<!-- File List End -->
		<!-- Upload File Start-->
		<div class="col-lg-4">
		    <div class="card">
			  	<div class="card-header bg-primary text-white">Upload New file to S3 Bucket</div>
			  	<div class="card-body">
			  		<form action="<?= base_url('user/file_upload') ?>" method="POST" enctype= "multipart/form-data">
			        <div class="form-group">
			        	<label>Choose File<i></i></label>
			        	<input type="file" name="doc_file" accept=".doc,.docx,.pdf" required><br>
			        	<small class="text-muted">Allowed file types are doc, docs and pdf format</small>
			        </div>
			        <button type="submit" class="btn btn-primary">Upload</button>
			        <button type="reset" class="btn btn-danger">Reset</button>
			        </form>
			  	</div>
			</div>
		</div>
		<!-- Upload File End -->
	</div>
</div>
<!-- Datatable Function Start-->
<script type="text/javascript">
	$(document).ready(function() {
    	$('#fileList').DataTable();
	});
</script>
<!-- Datatable Function End -->