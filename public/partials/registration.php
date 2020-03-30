<h3><?php _e("Profile information", "pooling");?></h3>
<form action="" method="post">
    <div class="form-group">
        <label for="first_name"><?php _e("First name");?></label>
        <input type="text" name="first_name" id="first_name" value="" class="form-control" required/>
        <span class="description"><?php _e("Please enter your first name.");?></span>
    </div>
    <div class="form-group">
        <label for="last_name"><?php _e("Last name");?></label>
        <input type="text" name="last_name" id="last_name" value="" class="form-control" required/>
        <span class="description"><?php _e("Please enter your last name.");?></span>
    </div>
    <div class="form-group">
        <label for="user_email"><?php _e("e-mail");?></label>
        <input type="text" name="user_email" id="user_email" value="" class="form-control" required/>
    </div>
    <div class="form-group">
        <label for="user_login"><?php _e("Username");?></label>
        <input type="text" name="user_login" id="user_login" value="" class="form-control" required/>
    </div>
    <div class="form-group">
        <label for="user_password"><?php _e("Password");?></label>
        <input type="password" name="user_password" id="user_password" value="" class="form-control" required/>
    </div>
    <div class="form-group">
        <label for="password_confirm"><?php _e("Password Confirm");?></label>
        <input type="password" name="password_confirm" id="password_confirm" value="" class="form-control" required/>
    </div>
    <div class="form-group">
        <label for="phone"><?php _e("Phone");?></label>
        <input type="text" name="phone" id="phone" value="" class="form-control"/>
        <span class="description"><?php _e("Please enter your phone.");?></span>
    </div>
    <div class="form-group">
        <label for="mobile"><?php _e("Mobile");?></label>
        <input type="text" name="mobile" id="mobile" value="" class="form-control" required="required"/>
        <span class="description"><?php _e("Please enter your mobile.");?></span>
    </div>
    <div class="form-group">
        <label for="year_birth"><?php _e("Year of birth");?></label>
        <input type="number" min="1900" max="2020" ste="1" name="year_birth" id="year_birth" value="" class="form-control" required="required"/>
        <span class="description"><?php _e("Please enter year birth.");?></span>
    </div>
    <div class="form-group">
        <label for="address"><?php _e("Address");?></label>
        <input type="text" name="address" id="address" value="" class="form-control" required="required"/>
        <span class="description"><?php _e("Please enter your address.");?></span>
    </div>
    <div class="form-group">
    <label for="postalcode"><?php _e("Postal Code");?></label>
        <input type="text" name="postalcode" id="postalcode" value="" class="form-control" required="required"/>
        <span class="description"><?php _e("Please enter your postal code.");?></span>
    </div>
    <div class="form-group">
        <label for="city"><?php _e("City");?></label>
        <input type="text" name="city" id="city" value="" class="form-control" required="required"/>
        <span class="description"><?php _e("Please enter your city.");?></span>
    </div>
    <div class="form-group">
        <label for="state"><?php _e("State/Province");?></label>
        <input type="text" name="state" id="state" value="" class="form-control" required="required"/>
        <span class="description"><?php _e("Please enter State/Province.");?></span>
    </div>
    <div class="form-group">
        <label for="country"><?php _e("Country");?></label>
        <select name="country" id="country" required="required"><?php echo \PLGLib\StaticOptions::get_countries_html($this->get_user_meta('country', $user)); ?></select>
        <span class="description"><?php _e("Please enter your country.");?></span>
    </div>
    <div class="form-group">
        <label for="profession"><?php _e("Profession");?></label>
        <select name="profession" class="" id="profession" required="required"><?php
echo \PLGLib\StaticOptions::get_profession_options_html($this->get_user_meta('profession', $user));
        ?></select>
            
        <span class="description"><?php _e("Please enter your profession.");?></span>
    </div>
    <div class="form-group">
        <label for="own_transport"><?php _e("Own transport mean");?></label>
        <input type="checkbox" name="own_transport" value="1" <?php checked($this->get_user_meta('own_transport', $user));?>>
    </div>
    <input type="hidden" name="lng" id="lng" value="<?php echo esc_attr($this->get_user_meta('lng', $user)); ?>"/>
    <input type="hidden" name="lat" id="lat" value="<?php echo esc_attr($this->get_user_meta('lat', $user)); ?>"/>
    <input type="hidden" name="map_url" id="map_url" value="<?php echo esc_attr($this->get_user_meta('map_url', $user)); ?>"/>
    <div class="form-group">
        <?php _e("Needs", 'pooling');?>
            <select name="needs[]" id="needs" class="select2" multiple="multiple" required="required">
            <?php echo \PLGLib\StaticOptions::get_needs_html($this->get_user_meta('needs', $user)); ?>
        </select>
    </div>
    <div class="form-group">
        <label for="offer"><?php _e("Offers", 'pooling');?></label>
        <select name="offers[]" id="offers" class="select2" multiple="multiple" required="required">
            <?php echo \PLGLib\StaticOptions::get_offers_html($this->get_user_meta('offers', $user)); ?>
        </select>
    </div>
    <div class="form-group">
        <input type="hidden" name="pooling_register_nonce" value="<?php echo wp_create_nonce('pooling-register-nonce'); ?>"/>
        <input type="hidden" name="pooling_action" value="register_account">
        <input type="submit" class="form-control" value="<?php _e('Register');?>"/>
    </div>
    </table>
</form>