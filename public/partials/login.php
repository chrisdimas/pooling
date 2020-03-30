<div class="login-form">
    <form id="pooling_login_form"  class="pooling_form"action="" method="post">
        <fieldset>
            <div class="form-group">
                <label for="pooling_user_Login"><?php _e('Username'); ?></label>
                <input name="pooling_user_login" id="pooling_user_login" class="form-control" type="text"/>
            </div>
            <div class="form-group">
                <label for="pooling_user_pass"><?php _e('Password'); ?></label>
                <input name="pooling_user_pass" id="pooling_user_pass" class="form-control" type="password"/>
            </div>
            <div class="form-group">
                <input type="hidden" name="pooling_login_nonce" value="<?php echo wp_create_nonce('pooling-login-nonce'); ?>"/>
                <input id="pooling_login_submit" type="submit" value="<?php _e('Login','pooling'); ?>"/>
            </div>
        </fieldset>
    </form>
</div>