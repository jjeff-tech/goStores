<div class="jqInnerLoginFormDiv popup" style="display: none;">
	<form id="loginInnerForm1"  onsubmit="return loginuseractionfrominner();">
		<div class="popup-hd01">
			<h6>Login</h6>
                        <a href="#" class="jqInnerLoginClose"><img src="<?php echo IMAGE_URL; ?>close-icon.png"></a>
		</div>
		<div class="errorBox" id="jqLoginError">&nbsp;</div>
		<fieldset id="body">
			<fieldset>
				<span for="email">Email Address</span>
				<input type="text" name="txtUsernameInner" id="txtUsernameInner" class="popup-txt"/>
			</fieldset>
			<fieldset>
				<span for="password">Password</span>
				<input type="password" name="txtPasswordInner" id="txtPasswordInner" class="popup-txt" />
			</fieldset>
			<input type="hidden" name="locFlag" value="1">
			<span>&nbsp;</span>
			<input type="submit" class="loginFromInner popup-btn" value="Sign in"/>
                        <p><a href="<?php echo ConfigUrl::base(); ?>index/forgotpwd" target="_blank">Forgot your password?</a></p>
			<!--<label for="checkbox"><input type="checkbox" id="checkbox" />Remember me</label>-->
		</fieldset>

		<div id="jqInnerLoginError" class="popup-msg"></div>
	</form>
</div>