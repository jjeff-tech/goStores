<!-- Header starts -->
    <div class="header">
        <div class="header_left">
            <h1 class="logo"><a href="<?php echo BASE_URL; ?>" title="iScripts GoStores">iScripts GoStores</a></h1>
        </div>
        <div class="header_right">
            <!-- Header top sub menu starts -->
            <div class="top_submenu">
                <ul>
                     <?php if(LibSession::get('userID')!="") { ?>
                    <li class="welcome">Welcome <span class="user_clr"><?php echo LibSession::get('firstName'); ?></span></li>
                    <li><a href="<?php echo ConfigUrl::base(); ?>user/dashboard">My Account </a></li>
                    <li><a href="<?php echo ConfigUrl::base(); ?>index/logout">Logout</a></li>
                        <?php } ?>
                    <li class="livechat"><a href="#" class="chatlink">LIVE CHAT</a><span class="chat_online">ONLINE</span></li>
                    <li class="phno">1-(800)-569-5538</li>
                    <li class="email"><a href="mailto:support@iscriptscloud.com?Subject=iscripts%20cloud%20support">support@iscriptscloud.com</a></li>
                </ul>
            </div>
            <!-- Header top sub menu starts -->

			<div style="clear:both;float:right;margin:20px 0 25px 0;">
				<div class="l_float" style="margin-right:10px; ">
				<!-- New dropdown menu -->

					<ul class="sf-menu">
			<li class="current">
				<a href="#a">Products</a>
				<ul>
					<li> <a href="<?php echo ConfigUrl::base(); ?>product/multicart">Multicart</a></li>
					<li><a href="<?php echo ConfigUrl::base(); ?>product/eswap">eSwap</a></li>
					<li><a href="<?php echo ConfigUrl::base(); ?>product/socialware">SocialWare</a></li>
				</ul>
			</li>
			<li><a href="<?php echo ConfigUrl::base(); ?>whycloud">Why iScripts GoStores?</a></li>
			<li> <a href="<?php echo ConfigUrl::base(); ?>aboutus">About Us</a></li>
			<li><a href="<?php echo ConfigUrl::base(); ?>contactus">Contact Us</a></li>
		</ul>

		<!-- New dropdown menu ends -->

				</div>
				<div class="l_float">
					<!-- New login box comes here -->
                                         <?php if(LibSession::get('userID')=="") { ?>
						<div id="loginContainer">
                <a href="#" id="loginButton"><span>Sign In</span><em></em></a>
                <?php $setLoginDisplay = (isset($_SESSION['errorLogin']) && !empty($_SESSION
['errorLogin'])) ? ' style="display: block;"' : ''; ?>
                <div style="clear:both"></div>
                <div id="loginBox">
                    <form id="loginForm" onsubmit="return loginuseraction();">
                        <div class="errorBox" id="jqLoginError">&nbsp;</div>
                        <fieldset id="body">
                            <fieldset>
                                <label for="email">Email Address</label>
                                <input type="text" name="txtUsername" id="txtUsername" />
                            </fieldset>
                            <fieldset>
                                <label for="password">Password</label>
                                <input type="password" name="txtPassword" id="txtPassword" />
                            </fieldset>
                            <input type="submit" id="login" value="Sign in" />
                            <!--<label for="checkbox"><input type="checkbox" id="checkbox" />Remember me</label>-->
                        </fieldset>
                        <span><a href="<?php echo ConfigUrl::base(); ?>index/forgotpwd">Forgot your password?</a></span>
                    </form>
                </div>
            </div>
            <?php } ?>
					<!-- New login box ends here -->
				</div>
			<div class="clear"></div>
			</div>

            <!-- Header top sub menu starts -->
			<!--
            <div class="top_menu">

			<div class="l_float">

			    <!-- <ul>
                <ul id="nav-one" class="dropmenu">
                    <!--<li><a href="#">Products</a></li>
                    <li class="haschildren">
                        <a class="" href="#item3">Products</a>
                        <ul style="z-index: 1; display: none;" class="submenu">
                            <li><a href="<?php echo ConfigUrl::base(); ?>product/multicart">Multicart</a></li>
                            <li><a href="<?php echo ConfigUrl::base(); ?>product/eswap">eSwap</a></li>
                            <li><a href="<?php echo ConfigUrl::base(); ?>product/socialware">Socialware</a></li>
                        </ul>
                    </li>
                    <li><a href="<?php echo ConfigUrl::base(); ?>whycloud">Why iScripts GoStores?</a></li>
                    <!--<li><a href="<?php echo ConfigUrl::base(); ?>index/aboutus">Support</a></li>
                    <li><a href="<?php echo ConfigUrl::base(); ?>aboutus">About Us</a></li>
                    <li><a href="<?php echo ConfigUrl::base(); ?>contactus">Contact Us</a></li>

                </ul>
				</div>
				<div class="btn_contain r_float" style="display:none;">



                        <!-- User login area
                        <?php if(LibSession::get('userID')=="") { ?>
                        <div class="sign_btn">
                            <a href="javascript:void(0)" class="signin">
                                <span>Sign in</span></a>
                                <?php $setLoginDisplay = (isset($_SESSION['errorLogin']) && !empty($_SESSION['errorLogin'])) ? ' style="display: block;"' : ''; ?>
                            <div id="signin_menu"<?php echo $setLoginDisplay; ?> class="droploginbox">
                                <form method="post" id="frmuserLogin" onsubmit="return loginuseraction();">
                                    <div class="errorBox" id="jqLoginError">&nbsp;</div>
                                    <table width="100%" cellpadding="0" cellspacing="3" border="0">
                                        <tr>
                                            <td align="left"><label for="username">Email address</label></td>
                                            <td align="left"><input id="txtUsername" class="login_text_box" name="txtUsername" value="" title="username" tabindex="4" type="text"/></td>
                                        </tr>
                                        <tr>
                                            <td align="left"><label for="password">Password</label></td>

                                            <td align="left"><input id="txtPassword" class="login_text_box" name="txtPassword" value="" title="password" tabindex="5" type="password" /></td>
                                        </tr>

                                        <tr>
                                            <td align="left"><input id="submitLogin" class="button_orange" name="submitLogin" value="Sign in" tabindex="6" type="submit"/></td>
                                            <td align="left"> <!--<input id="remember" name="remember_me" value="1" tabindex="7" type="checkbox"/>-->
                                                <!--<label for="remember">Remember me</label>
                                            </td>
                                        </tr>
                                        <!--
                                        <tr>
                                            <td align="left" colspan="2">
                                                <p class="forgot"> <a href="<?php echo ConfigUrl::base()?>index/forgotpwd" id="resend_password_link">Forgot your password?</a> </p>
                                            </td>

                                        </tr>
                                        <p class="forgot-username"> <A id=forgot_username_link title="If you remember your password, try logging in with your email"
href="#">Forgot your username?</A> </p>
                                    </table>
                                </form>
                            </div>
                            <script type="text/javascript">
                                $(document).ready(function() {

                                    $(".signin").click(function(e) {
                                        e.preventDefault();
                                        $("div#signin_menu").toggle();
                                        $(".signin").toggleClass("menu-open");
                                    });

                                    $("div#signin_menu").mouseup(function() {
                                        return false
                                    });
                                    $(document).mouseup(function(e) {
                                        if($(e.target).parent("a.signin").length==0) {
                                            $(".signin").removeClass("menu-open");
                                            $("div#signin_menu").hide();

                                        }
                                    });

                                });
                            </script><script type='text/javascript'>
                                //                                $(function() {
                                //                                    $('#forgot_username_link').tipsy({gravity: 'w'});
                                //                                });
                            </script>


                        </div>
                            <?php } ?>
                        <!-- User login area


				</div>

                <div class="clear"></div>
            </div>
-->

            <!-- Header top sub menu starts -->


        </div>
        <div class="clear"></div>
    </div>
    <!-- Header ends -->