<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://cliengo.com
 * @since      1.0.0
 *
 * @package    Cliengo
 * @subpackage Cliengo/admin/partials
 */
?>
<script type="text/javascript">
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    var siteUrl = "<?php echo get_site_url(); ?>";
    var userEmail = "<?php echo wp_get_current_user()->user_email; ?>";
    // Get current user first and last name
    var userName = "<?php echo wp_get_current_user()->user_firstname . ' ' . wp_get_current_user()->user_lastname?>";
    // If user doesn't have first or second name configured, reset user name to show input placeholder
    if (userName.trim() === "") userName = null;
</script>
<div id="app" class="cliengo">
	<div class="container" style="margin-top: 5%" v-if="!loading.rendering">
		<div class="row col-lg-12" style="margin-bottom: 20px;">
			<?php echo '<img src="'.plugin_dir_url(__FILE__) . '../images/logo.png'.'" alt="">' ?>
            <a v-if="login.loggedIn" class="link-btn" @click="unlinkAccount()"><?php _e( 'Unlink Account', 'cliengo' ) ?> <i class="fa fa-sign-out logout-icon"></i></a>
		</div>
		<div class="row col-lg-12" v-if="!login.loggedIn">
			<div class="panel">
				<div class="panel-heading">
					<h4><?php _e( 'Do you already have a cliengo account?', 'cliengo' ) ?></h4>
				</div>
			  	<div class="panel-body">
			  		<div class="radio">
  						<label :class="{active: option_select == 'true', radio_label: true}">
					    	<input type="radio" v-model="option_select" value="true" @change="restoreForms">
					    	<p><?php _e( 'Yes', 'cliengo' ) ?></p>
					  	</label>
					</div>
					<div class="radio">
					  	<label :class="{active: option_select == 'false', radio_label: true}">
					    	<input type="radio" v-model="option_select" value="false" @change="restoreForms">
					    	<p><?php _e( 'I want to create one', 'cliengo' ) ?></p>
					  	</label>
					</div>
				</div>
			</div>

            <!-- Logged out message -->
            <div class="alert alert-dismissable alert-success" role="alert" v-if="messages.loggedOut.success">
                <button type="button" class="close" aria-label="Close" @click="clearMessages()">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                <strong>
                  <?php _e( 'Successfully uninstalled Cliengo Chatbot from your site', 'cliengo' ) ?>
                </strong>
            </div>
		</div>
	</div>
	<template v-if="!loading.rendering">
		<div class="container" style="margin-top: 2%" v-if="option_select == 'true' ">
			<div class="row col-lg-12">
                <!-- Log in section -->
				<div class="panel" v-show="!login.loggedIn && !login.tokenInputSelected">
					<div class="panel-heading">
						<h4><?php _e( 'Log into your Cliengo account', 'cliengo' ) ?></h4>
                        <h4 v-show="login.loggedIn"><?php _e( 'Chatbot Configuration', 'cliengo' ) ?></h4>
					</div>
				  	<div class="panel-body">
                        <!-- MESSAGES PANEL -->
                        <div class="alert alert-dismissable" :class="{ 'alert-danger': alertDanger, 'alert-success':alertSuccess }" role="alert" v-show="anyMessage(messages.login)">
                            <button type="button" class="close" aria-label="Close" @click="clearMessages()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong v-show="messages.login.invalidCredentials">
                                <?php _e( 'Invalid username/password', 'cliengo' ) ?>
                            </strong>
                            <strong v-show="messages.login.otherError">
                                <?php _e( 'Error logging in, please try again', 'cliengo' ) ?>.
                            </strong>
                        </div>
                        <!-- END MESSAGES PANEL -->
                        <!-- LOGIN FORM -->
                        <form class="form-horizontal" @submit="wordPressLogin" action="javascript:void(0);">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="email">
                                        <?php _e( 'Email', 'cliengo' ) ?>
                                    </label>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-wrapper">
                                        <input
                                            type="text"
                                            name="email"
                                            id="email"
                                            :class="['form-control', errors.loginEmail ? 'with-error' : null]"
                                            v-model="login.email"
                                            @keypress="errors.loginEmail = false"
                                        />
                                        <i class="fa fa-check input-icon input-success" v-if="emailRegex.test(login.email) && !errors.loginEmail"></i>
                                        <i class="fa fa-exclamation-triangle input-icon input-error" v-if="errors.loginEmail"></i>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <span v-if="!errors.loginEmail">
                                        <?php _e( 'hello@cliengo.com', 'cliengo' ) ?>
                                    </span>
                                    <span class="error-feedback" v-if="errors.loginEmail">
                                        <?php _e('Please enter an email containing @ and (.) mymail@gmail.com', 'cliengo') ?>
                                    </span>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-12">
                                    <label for="password">
                                        <?php _e( 'Password', 'cliengo' ) ?>
                                    </label>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-wrapper">
                                        <input
                                            :type="showPasswords.login ? 'text' : 'password'"
                                            name="password"
                                            id="password"
                                            :class="['form-control', errors.loginPassword ? 'with-error' : null]"
                                            v-model="login.password"
                                            @keypress="errors.loginPassword = false"
                                        />
                                        <i
                                            :class="[
                                                'fa', 
                                                showPasswords.login ? 'fa-eye-slash' : 'fa-eye', 
                                                'password-toggle',
                                                'input-icon',
                                                login.password && login.password.length >= 6 ? 'password-feedback' : null,
                                                errors.loginPassword ? 'password-feedback' : null
                                            ]"
                                            @click="togglePassword('login')"
                                        ></i>
                                        <i class="fa fa-check input-icon input-success" v-if="login.password && login.password.length >= 6 && !errors.loginPassword"></i>
                                        <i class="fa fa-exclamation-triangle input-icon input-error" v-if="errors.loginPassword"></i>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <span v-if="!errors.loginPassword">
                                        <?php _e('6 characters minimum', 'cliengo') ?>
                                    </span>
                                    <span class="error-feedback" v-if="errors.loginPassword">
                                        <?php _e('Enter a password with at least 6 digits', 'cliengo') ?>
                                    </span>
                                </div>
                            </div>
                            <p class="social-media-hint">
                                <?php _e( 'Did you register with your google or facebook account? <a href="#" @click="login.tokenInputSelected = true">Click here</a>', 'cliengo' ) ?>
                            </p>
                            <div class="row">
                                <div class="col-md-5" style="text-align:center;">
                                    <button type="submit" class="btn btn-purple" :disabled="loading.login">
                                        <span v-show="!loading.login">
                                            <?php _e( 'Log In', 'cliengo' ) ?>
                                        </span>
                                        <span v-show="loading.login">
                                            <div class="lds-ellipsis">
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                            </div>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <!-- END LOGIN FORM -->
                    </div>
				</div>

                <div class="panel" v-show="!login.loggedIn && login.tokenInputSelected">
                    <div class="panel-heading">
                        <h4><?php _e( 'Provide your token', 'cliengo' ) ?></h4>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-warning" role="alert">
                            <strong>
                              <?php _e( 'Login at <a href="https://app.cliengo.com/" target="_blank">Cliengo</a> to obtain your chatbot token under Chatbots -> Installation section', 'cliengo' ) ?>
                            </strong>
                        </div>
                        <div :class="{ 'alert':true, 'alert-dismissible':true, 'alert-danger': alertDanger,'alert-success':alertSuccess }" role="alert" v-show="anyMessage(messages.token)">
                            <button type="button" class="close" aria-label="Close" @click="clearMessages()"><span aria-hidden="true">&times;</span></button>
                            <strong v-show="messages.token.tokenValidError">
                              <?php _e( 'Chatbot token entered is incorrect', 'cliengo' ) ?>
                            </strong>
                            <strong v-show="messages.token.tokenUpdateError">
                              <?php _e( 'Chatbot token update failed', 'cliengo' ) ?>
                            </strong>
                        </div>
                        <div class="form-horizontal">
                            <div class="row">
                                <label for="chatbot_token" class="col-md-2 control-label" style="text-align: left;">Chatbot Token:
                                </label>
                                <div class="col-md-5">
                                    <input placeholder="E.g. 5bb7xxxxe4b0xxxxdc03xxxx-5bxxxx12e4bxxxxbdc03xxxx" type="text" name="chatbot_token" id="chatbot_token" class="form-control" v-model="chatbot_token">
                                </div>
                            </div>
                        </div>
                        <a class="btn btn-secondary" @click="login.tokenInputSelected = false"><i class="fa fa-arrow-left back-icon"></i><?php _e( 'Back to Login', 'cliengo' ) ?></a>
                        <button class="btn btn-purple" @click="updateToken()" style="margin-top: 10px;">
                          <?php _e( 'Save changes', 'cliengo' ) ?>
                        </button>
                    </div>
                </div>

                <!-- Logged in section -->
                <div v-if="login.loggedIn">
                    <!-- Chatbot selection  -->
                    <div class="panel">
                        <div class="panel-heading" style="padding-bottom:0;">
                            <h4><?php _e( 'Select the chatbot for this website', 'cliengo' ) ?></h4>
                        </div>
                        <div class="panel-body" style="padding-top:0;">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php _e( 'Select the chatbot you want to be displayed on this website and start managing your new customers.', 'cliengo' ) ?>
                                </div>
                            </div>
                            <!-- CHATBOT SELECTION FORM -->
                            <form class="form-horizontal" @submit="selectWebsite()" action="javascript:void(0);" v-show="login.showConfigForm">
                                <div class="row" style="margin-top: 10px;">
                                    <label for="selectedWebsite" class="col-md-6 control-label" style="text-align: left;">
                                      <?php _e( 'Select the chat', 'cliengo' ) ?>
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 select-chatbot">
                                        <div class="select-wrapper">
                                            <select id="selectedWebsite" class="form-control" v-model="selectedWebsiteId">
                                                <!-- null matches default app value, otherwise this default option doesn't get selected -->
                                                <option selected disabled value="null">
                                                <?php _e( 'Click to list websites...', 'cliengo' ) ?>
                                                </option>
                                                <option v-for="website in company.websites" :value="website.id">
                                                    {{ website.title }} - {{ website.url }}
                                                </option>
                                            </select>
                                            <i class="fa fa-sort-down select-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6" style="text-align:center; padding-bottom:20px;">
                                        <button type="submit" class="btn btn-purple" :disabled="loading.selectWeb || !selectedWebsiteId" style="margin-top: 10px;" v-if="!login.disableWebsiteSelector">
                                        <span v-show="!loading.selectWeb">
                                            <?php _e( 'Save', 'cliengo' ) ?>
                                        </span>
                                            <span v-show="loading.selectWeb">
                                            <div class="lds-ellipsis">
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                            </div>
                                        </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <!-- END CHATBOT SELECTION FORM -->
                        </div>
                    </div>
                     <!-- Account management-->
                     <div class="panel">
                        <div class="panel-heading" style="padding-bottom:0;">
                            <h4><?php _e( 'Now you can go to Cliengo and start managing your clients.', 'cliengo' ) ?></h4>
                        </div>
                        <div class="panel-body" style="padding-top:0;">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php _e( 'You have already installed the chatbot on your website, now you can go to  Cliengo and start managing your new clients and also the conversations in the chatbot.', 'cliengo' ) ?>
                                </div>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-md-6">
                                    <div style="display:flex; align-items: center;">
                                        <?php echo '<img src="'.plugin_dir_url(__FILE__) . '../images/chat-popup.png'.'" alt="chatbot popup">' ?>
                                        <p style="max-width: 175px;"><?php _e('This is how the chatbot widget looks on your website', 'cliengo')?></p>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-md-6" style="text-align: center; padding-bottom:20px;">
                                    <a class="btn btn-purple" style="margin-top:10px" href="https://app.cliengo.com" target="_blank">
                                        <?php _e( 'Go to Cliengo', 'cliengo' ) ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
		<div class="container" style="margin-top: 2%" v-if="option_select == 'false' ">
			<div class="row col-lg-12">
				<div class="panel">
					<div class="panel-heading">
						<h4><?php _e( 'Create an account at Cliengo, no credit card required.', 'cliengo' ) ?></h4>
					</div>
                    <div class="panel-body">
                        <!-- Error panel -->
                        <div :class="{ 'alert':true, 'alert-dismissible':true, 'alert-danger': alertDanger,'alert-success':alertSuccess }" role="alert" v-show="anyMessage(messages.reg)">
                            <button type="button" class="close" aria-label="Close" @click="clearMessages()"><span aria-hidden="true">&times;</span></button>
                            <strong v-show="messages.reg.shortPassword">
                              <?php _e( 'Your password should have at least 8 characters.', 'cliengo' ) ?>
                            </strong>
                            <strong v-show="messages.reg.invalidWebsite">
                              <?php _e( 'Please provide a valid website. E.g. www.mysite.com', 'cliengo' ) ?>
                            </strong>
                            <strong v-show="messages.reg.missingInfo">
                              <?php _e( 'Please fill out the entire form.', 'cliengo' ) ?>
                            </strong>
                            <strong v-show="messages.reg.serverError">
                                <!-- Show message returned by server -->
                              {{ messages.reg.serverError }}
                            </strong>
                        </div>

                        <!-- Registration form-->
                        <form class="form-horizontal" action="javascript:void(0);">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="name"><?php _e( 'Name', 'cliengo' ) ?>:</label>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-wrapper">
                                        <input
                                            type="text"
                                            name="name"
                                            id="name"
                                            :class="['form-control', errors.registrationName ? 'with-error' : null]"
                                            v-model="regData.name"
                                            @keypress="errors.registrationName = false"
                                        />
                                        <i class="fa fa-check input-icon input-success" v-if="regData.name && nameRegex.test(regData.name.toLowerCase()) && !errors.registrationName"></i>
                                        <i class="fa fa-exclamation-triangle input-icon input-error" v-if="errors.registrationName"></i>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <span v-if="!errors.registrationName">
                                        <?php _e( 'Ex. John Smith', 'cliengo' ) ?>
                                    </span>
                                    <span v-if="errors.registrationName" class="error-feedback">
                                        <?php _e('Write your name without symbols or numbers', 'cliengo') ?>
                                    </span>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-12">
                                    <label for="email"><?php _e( 'E-mail', 'cliengo' ) ?>:</label>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-wrapper">
                                        <input
                                            type="email"
                                            name="email"
                                            id="email"
                                            :class="['form-control', errors.registrationEmail ? 'with-error' : null]"
                                            v-model="regData.email"
                                            @keypress="errors.registrationEmail = false"
                                        />
                                        <i class="fa fa-check input-icon input-success" v-if="emailRegex.test(regData.email) && !errors.registrationEmail"></i>
                                        <i class="fa fa-exclamation-triangle input-icon input-error" v-if="errors.registrationEmail"></i>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <span v-if="!errors.registrationEmail">
                                        <?php _e('hello@cliengo.com', 'cliengo') ?>
                                    </span>
                                    <span v-if="errors.registrationEmail" class="error-feedback">
                                        <?php _e('Please enter an email containing @ and (.) mymail@gmail.com', 'cliengo') ?>
                                    </span>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-12">
                                    <label for="password"><?php _e( 'Password', 'cliengo' ) ?>:</label>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-wrapper">
                                        <input
                                            :type="showPasswords.registration ? 'text' : 'password'"
                                            name="password"
                                            id="password"
                                            :class="['form-control', errors.registrationPassword ? 'with-error' : null]"
                                            v-model="regData.password"
                                            @keypress="errors.registrationPassword = false"
                                        />
                                        <i
                                            :class="[
                                                'fa',
                                                showPasswords.registration ? 'fa-eye-slash' : 'fa-eye',
                                                regData.password && regData.password.length >= 8 ? 'password-feedback' : null,
                                                'password-toggle',
                                                'input-icon',
                                                errors.registrationPassword ? 'password-feedback' : null
                                            ]"
                                            @click="togglePassword('registration')"
                                        ></i>
                                        <i class="fa fa-check input-success input-icon" v-if="regData.password && regData.password.length >= 8 && !errors.registrationPassword"></i>
                                        <i class="fa fa-exclamation-triangle input-icon input-error" v-if="errors.registrationPassword"></i>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <span v-if="!errors.registrationPassword">
                                        <?php _e('8 characters minimum', 'cliengo') ?>
                                    </span>
                                    <span v-if="errors.registrationPassword" class="error-feedback">
                                        <?php _e('Enter a password with at least 8 digits', 'cliengo') ?>
                                    </span>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-12">
                                    <label for="website"><?php _e( 'Website', 'cliengo' ) ?>:</label>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-wrapper">
                                        <input
                                            id="site-url"
                                            type="text"
                                            name="website"
                                            :class="['form-control', errors.registrationWebsite ? 'with-error' : null]"
                                            v-model="regData.website"
                                            @keypress="errors.registrationWebsite = false"
                                        />
                                        <i class="fa fa-check input-icon input-success" v-if="websiteRegex.test(regData.website) && !errors.registrationWebsite"></i>
                                        <i class="fa fa-exclamation-triangle input-icon input-error" v-if="errors.registrationWebsite"></i>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <span v-if="!errors.registrationWebsite">
                                        <?php _e('www.mywebsite.com', 'cliengo') ?>
                                    </span>
                                    <span v-if="errors.registrationWebsite" class="error-feedback">
                                        <?php _e('Enter a website with a valid format (www.mywebsite.com)', 'cliengo') ?>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5" style="text-align:center;">
                                    <button class="btn btn-purple" :disabled="loading.reg" @click="registerAndSaveToken()" style="margin-top: 10px;">
                                        <span v-show="!loading.reg"><?php _e( 'Create account', 'cliengo' ) ?></span>
                                        <span v-show="loading.reg">
                                            <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
			</div>
		</div>
        <!-- Success modal -->
        <transition name="fade">
            <div class="cliengo-modal" v-if="showModal" @click="closeModal()">
                <div class="cliengo-modal-content" @click="prevent($event)">
                    <span class="close" @click="closeModal()">&times;</span>
                    <div class="cliengo-modal-body">
                        <h3><?php _e( 'Congratulations! you installed the Cliengo chatbot on your website', 'cliengo' ) ?></h3>
                        <?php echo '<img src="'.plugin_dir_url(__FILE__) . '../images/chatbot.svg'.'" alt="chatbot installed">' ?>
                        <p>
                            <?php _e( 'Now you can go to', 'cliengo' ) ?>
                                <a href="<?php echo get_site_url(); ?>" target="_blank"><?php echo get_site_url(); ?></a>
                            <?php _e( 'and have a try.', 'cliengo' ) ?>
                        </p>
                    </div>
                </div>
            </div>
        </transition>
	</template>
</div>
