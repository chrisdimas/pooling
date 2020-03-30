<div id="user-requests" class="container-fluid">
    <?php if(count($my_aid_requests) > 0): ?>
    <?php foreach ($my_aid_requests as $key => $aid_request_data): ?>
    <?php $is_my_offer = $aid_request_data->value->user_id == $user->ID; ?>
        <div class="row">
        <div class="card col-lg mt-3" style="width: 100%;">
          <div class="card-body">
            <h5 class="card-title">User <?php echo get_userdata((!$is_my_offer ? $aid_request_data->value->user_id : $aid_request_data->value->target_user_id) )->user_login; ?></h5>
            <div class="container">
                <div class="row">
            <h6 class="card-subtitle mb-2 text-muted"><?php echo $usr->is_user_verified($aid_request_data->value->target_user_id) ? __('Verified user', 'pooling') . '&nbsp;<i class="fa fa-check-circle is-verified-badge"></i>' : __('Not Verified user', 'pooling') . '&nbsp;<i class="fa fa-times-circle is-not-verified-badge"></i>'; ?></h6>
            <h6 class="card-subtitle mb-2 pl-2 text-muted"><?php echo $is_my_offer ? __('You offered help', 'pooling') . '&nbsp;<i class="fa fa-medkit offer-from-user"></i>' : __('Offered to You', 'pooling') . '&nbsp;<i class="fas fa-hands-helping offer-to-user"></i>'; ?></h6>
            
            </div>
            </div>
            <p class="card-text">
                <?php _e('You offered to help and cover this user for the following needs', 'pooling'); ?>
            <?php foreach ($aid_request_data->value->needs_cover as $key => $need_id): ?>
                <span class="badge badge-primary"><?php echo PLGLib\StaticOptions::get_needs()[$need_id]; ?></span>
            <?php endforeach;?>
            </p>
            <p>
                <?php 
                    echo sprintf('<span>%s</span>:', 
                        __('Status','pooling')
                    );
                ?>
                <span class="badge badge-<?php  echo $aid_request_data->value->target_accepted ? 'success' : 'warning'; ?>">
                    <?php echo $aid_request_data->value->target_accepted ? __('Offer Accepted', 'pooling') : __('Not Yet Accepted', 'pooling'); ?>
                </span>
                <?php if($aid_request_data->value->withdraw): ?>
                    <span class="badge badge-danger"><?php _e('withdrew','pooling'); ?></span>
                <?php endif; ?>
            </p>
            <p>
                <span class="text-dark d-block"><?php echo sprintf('%s: <span class="text-muted">%s</span>', __('Offer time','pooling'), wp_date(\PLGLib\Helpers::date_format(), $aid_request_data->value->created_timestamp)); ?></span>
                <span class="text-dark d-block"><?php echo sprintf('%s: <span class="text-muted">%s</span>', 
                    __('Accept time','pooling'),
                    $aid_request_data->value->target_accepted ? wp_date(\PLGLib\Helpers::date_format(), $aid_request_data->value->accept_timestamp) : '-'
                    ); ?></span>
                <?php  if($aid_request_data->value->withdraw): ?>
                    <span class="text-dark d-block"><?php echo sprintf('%s: <span class="text-muted">%s</span>', __('Withdraw time','pooling'), wp_date(\PLGLib\Helpers::date_format(), $aid_request_data->value->withdraw_timestamp)); ?></span>
                <?php endif; ?>
            </p>
            <div id="request-actions">
            <?php 
                $raise_modal_attrs = $aid_request_data->value->withdraw ? '' : 'data-toggle="modal" data-target="#withdrawModal"'; 
                $muted_text_class = $aid_request_data->value->withdraw ? 'text-muted' : 'text-primary';
                if($is_my_offer):
            ?>
            <a href="#" class="card-link <?php echo $muted_text_class; ?>" <?php echo $raise_modal_attrs; ?> data-user-id="<?php echo $aid_request_data->value->user_id; ?>" data-aid-request-id="<?php echo $aid_request_data->value->_id ?>"><?php _e('Withdraw this Aid Request', 'pooling');?></a>
            <?php else: ?>
            <?php 
                $raise_modal_attrs = $aid_request_data->value->target_accepted ? '' : 'data-toggle="modal" data-target="#acceptModal"'; 
                $muted_text_class = $aid_request_data->value->target_accepted ? 'text-muted' : 'text-primary';
            ?>
            <a href="#" class="card-link <?php echo $muted_text_class; ?>" <?php echo $raise_modal_attrs; ?> data-user-id="<?php echo $aid_request_data->value->user_id; ?>" data-aid-request-id="<?php echo $aid_request_data->value->_id ?>"><?php _e('Accept this request', 'pooling');?></a>
            <?php endif; ?>
            <a href="#" class="card-link text-danger"><?php _e('Report this user', 'pooling');?> <span class="badge badge-danger"><i class="fa fa-exclamation-circle"></i></span></a>
            </div>
          </div>
        </div>
        </div>
    <?php endforeach;?>
<?php else: ?>
    <div class="row">
        <div class="card col-lg mt-3 bg-info" style="width: 100%;">
          <div class="card-body">
            <h5 class="card-title text-light"><?php _e('You have no requests yet.','pooling'); ?></h5>
            <p class="card-text text-light"><?php _e('Check the map and offer your help. If you\'re only looking for help wait until someone offers help.','pooling'); ?></p>
            <a href="/map" class="btn btn-secondary"><?php _e('Go to map','pooling'); ?></a>
        </div>
    </div>
<?php endif; ?>

</div>
<div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="withdrawModalLabel" aria-hidden="true">
  <form action="" method="post">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="withdrawModalLabel"><?php _e('Withdraw Aid Offer', 'pooling');?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
              <div class="row">
                  <div class="col-lg">
                      <p id="body-text">
                          <?php _e('You are about to withdraw your help. Are you sure?', 'pooling');?>
                      </p>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg" id="form-fields">
                    <!-- ajax stuff in here -->
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php _e('Close', 'pooling');?></button>
          <button type="submit" class="btn btn-primary"><?php _e('Yes, I\'m sure', 'pooling');?></button>
        </div>
      </div>
    </div>
  </form>
</div>
<div class="modal fade" id="acceptModal" tabindex="-1" role="dialog" aria-labelledby="acceptModalLabel" aria-hidden="true">
  <form action="" method="post">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="acceptModalLabel"><?php _e('Withdraw Aid Offer', 'pooling');?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
              <div class="row">
                  <div class="col-lg">
                      <p id="body-text">
                          <?php _e('You are about to accept an aid request from another user. Are you sure?', 'pooling');?>
                      </p>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg" id="form-fields">
                    <!-- ajax stuff in here -->
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php _e('Close', 'pooling');?></button>
          <button type="submit" class="btn btn-primary"><?php _e('Yes, I\'m sure', 'pooling');?></button>
        </div>
      </div>
    </div>
  </form>
</div>
