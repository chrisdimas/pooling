<h3><?php _e("Profile information", "pooling");?></h3>
<form action="" method="post">
    <div class="form-group">
        <label for="first_name"><?php _e("First name");?></label>
        <input type="text" name="first_name" id="first_name" value="<?php echo esc_attr($this->get_user_meta('first_name', $user)); ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label for="last_name"><?php _e("Last name");?></label>
        <input type="text" name="last_name" id="last_name" value="<?php echo esc_attr($this->get_user_meta('last_name', $user)); ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label for="user_login"><?php _e("Username");?></label>
        <input type="text" name="user_login" id="user_login" value="<?php echo esc_attr($user->user_login); ?>" class="form-control" disabled/>
    </div>
    <div class="form-group">
        <label for="user_email"><?php _e("e-mail");?></label>
        <input type="text" name="user_email" id="user_email" value="<?php echo esc_attr($user->user_email); ?>" class="form-control" disabled/>
    </div>
    <div class="form-group">
        <label for="phone"><?php _e("Phone");?></label>
        <input type="text" name="phone" id="phone" value="<?php echo esc_attr($this->get_user_meta('phone', $user)); ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label for="mobile"><?php _e("Mobile");?></label>
        <input type="text" name="mobile" id="mobile" value="<?php echo esc_attr($this->get_user_meta('mobile', $user)); ?>" class="form-control" required="required" readonly/>
    </div>
    <div class="form-group">
        <label for="year_birth"><?php _e("Year of birth");?></label>
        <input type="number" min="1900" max="2020" ste="1" name="year_birth" id="year_birth" value="<?php echo esc_attr($this->get_user_meta('year_birth', $user)); ?>" class="form-control" required="required"/>
    </div>
    <div class="form-group">
        <label for="address"><?php _e("Address");?></label>
        <input type="text" name="address" id="address" value="<?php echo esc_attr($this->get_user_meta('address', $user)); ?>" class="form-control" required="required"/>
    </div>
    <div class="form-group">
        <label for="postalcode"><?php _e("Postal Code");?></label>
        <input type="text" name="postalcode" id="postalcode" value="<?php echo esc_attr($this->get_user_meta('postalcode', $user)); ?>" class="form-control" required="required"/>
    </div>
    <div class="form-group">
        <label for="city"><?php _e("City");?></label>
        <input type="text" name="city" id="city" value="<?php echo esc_attr($this->get_user_meta('city', $user)); ?>" class="form-control" required="required"/>
    </div>
    <div class="form-group">
        <label for="state"><?php _e("State/Province");?></label>
        <input type="text" name="state" id="state" value="<?php echo esc_attr($this->get_user_meta('state', $user)); ?>" class="form-control" required="required"/>
    </div>
    <div class="form-group">
        <label for="country"><?php _e("Country");?></label>
        <select name="country" id="country" class="custom-select custom-select-lg mb-3" required="required"><?php echo \PLGLib\StaticOptions::get_countries_html($this->get_user_meta('country', $user)); ?></select>
    </div>
    <div class="form-group">
        <label for="profession"><?php _e("Profession");?></label>
        <select name="profession" class="custom-select custom-select-lg mb-3" id="profession" required="required"><?php
echo \PLGLib\StaticOptions::get_profession_options_html($this->get_user_meta('profession', $user));
        ?></select>
    </div>
    <div class="form-group">
        <label for="own_transport" class="form-check-label"><?php _e("Own transport mean");?></label>
        <input type="checkbox" name="own_transport" value="1" <?php checked($this->get_user_meta('own_transport', $user));?>>
    </div>
    <input type="hidden" name="lng" id="lng" value="<?php echo esc_attr($this->get_user_meta('lng', $user)); ?>"/>
    <input type="hidden" name="lat" id="lat" value="<?php echo esc_attr($this->get_user_meta('lat', $user)); ?>"/>
    <input type="hidden" name="map_url" id="map_url" value="<?php echo esc_attr($this->get_user_meta('map_url', $user)); ?>"/>
    <div class="form-group">
        <label for="needs"><?php _e("Needs", 'pooling');?></label>
        <select name="needs[]" id="needs" class="select2" multiple="multiple">
            <?php echo \PLGLib\StaticOptions::get_needs_html($this->get_user_meta('needs', $user)); ?>
        </select>
    </div>
    <div class="form-group">
        <label for="offers"><?php _e("Offers", 'pooling');?></label>
        <select name="offers[]" id="offers" class="select2" multiple="multiple">
            <?php echo \PLGLib\StaticOptions::get_offers_html($this->get_user_meta('offers', $user)); ?>
        </select>
    </div>
    <div class="form-group">
        <input type="hidden" name="pooling_action" value="update_account">
        <input type="submit" value="<?php _e('Update Your Account');?>"/>
    </div>
</form>