<div class="grid_3">
  	<div class="container">
		<form method="post">
            <div class="form-item">
				<label>Full Name<font color="F82828">*</font></label>
				<input type="full_name" name="full_name" class="form-text" required />
				<span><?php echo form_error('full_name'); ?></span>
			</div>
			<div class="form-item">
				<label>Email<font color="F82828">*</font></label>
				<input type="text" name="email" class="form-text" required />
				<span><?php echo form_error('email'); ?></span>
			</div>
            <div class="form-item">
				<label>Password<font color="F82828">*</font></label>
				<input type="password" name="password" class="form-text" required />
				<span><?php echo form_error('password'); ?></span>
			</div>
			<div class="form-item">
				<label>Mobile No.<font color="F82828">*</font></label>
				<input type="user_mobile" name="user_mobile" class="form-text" required />
				<span><?php echo form_error('user_mobile'); ?></span>
			</div>
			<div class="form-item">
				<input type="submit" name="login" value="Submit" />
				<input type="button" onclick="location.href='<?php echo base_url()?>'" value="Back" />
			</div>
			<div class="clearfix"> </div>
		</form>
	</div>
</div>