<div class="grid_3">
  	<div class="container">
	  	<?php 
			if($this->session->flashdata('error_login'))
			{
				echo $this->session->flashdata('error_login');
			}
		?>
		<?php 
			if($this->session->flashdata('message'))
			{
				echo "<br>";
				echo $this->session->flashdata('message');
			}
		?>
		<form method="post">
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
				<input type="submit" name="login_button" value="Login" />
				<input type="button" onclick="location.href='<?php echo base_url()?>frontend/user/login_guest'" name="login_button" value="Continue as Guest" />
				<a href="<?php echo base_url() ?>frontend/user/registration">Register</a>
			</div>
			<div class="clearfix"> </div>
		</form>
	</div>
</div>