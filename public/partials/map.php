 <h3><?php echo sprintf('%d %s', count($users_longlats), __('People', 'pooling')); ?></h3>
 <script type="text/javascript">
  function initM2ap() {
      var longlats = [], markers = [], location, search_area, in_area = [];
      var markersO = <?php echo json_encode($users_longlats); ?>;
      // console.log(markersO);
      // The location
      var uluru = {lat: <?php echo $center['lat']; ?>, lng: <?php echo $center['lng']; ?>};
      // The map, centered at location
      var map = new google.maps.Map(
          document.getElementById('map'),
          {
            zoom: 1,
            center: uluru,
            sensor: false
          }
      );
      search_area = {
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        center : uluru,
        radius : <?php echo POOLING_RADIUS; ?>,
        map: map
      }
      search_area = new google.maps.Circle(search_area);
      map.fitBounds(search_area.getBounds());
      // The marker, positioned at location
      var pinColor = "FFE641";
      var pingImage = {
          url: "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
          size: new google.maps.Size(175, 175),
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(0, 32)
      };
      var marker = new google.maps.Marker({position: uluru, map: map, icon: pingImage, zIndex: 999});

      for(var mark in markersO){
        longlats.push(new google.maps.LatLng(markersO[mark].lat, markersO[mark].lng));
      }

      for(var mark in longlats){
        markers.push(new google.maps.Marker({position: longlats[mark], map: map}));
      }
      map.fitBounds(search_area.getBounds());
  }
 </script>
<div id="map" class="embed-responsive embed-responsive-16by9"></div>
<div id="map-users" class="container-fluid">
    <?php foreach ($users_longlats as $key => $user_data): ?>
    <?php $is_current_user_entry = $user_data->user_id == $user->ID; ?>
        <div class="row">
        <div class="card col-lg mt-3" style="width: 100%;">
          <div class="card-body">
            <h5 class="card-title"><?php echo sprintf('%s %s', !$is_current_user_entry ? __('User','pooling') : __('You', 'pooling'), !$is_current_user_entry ? get_userdata($user_data->user_id)->user_login : '(' . __('User','pooling') . ' ' . get_userdata($user_data->user_id)->user_login . ')' ); ?></h5>
            <h6 class="card-subtitle mb-2 text-muted"><?php echo $usr->is_user_verified($user_data->user_id) ? __('Verified user', 'pooling') . '&nbsp;<i class="fa fa-check-circle is-verified-badge"></i>' : __('Not Verified user', 'pooling') . '&nbsp;<i class="fa fa-times-circle is-not-verified-badge"></i>'; ?></h6>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.
              <div class="card-needs">
                <h6 class="d-inline-block mr-2"><?php _e('Need','pooling'); ?></h6>
            <?php foreach ($user_data->needs as $key => $need_id): ?>
                <span class="badge badge-danger p-2" data-toggle="tooltip" data-placement="top" title="<?php _e('Need','pooling'); ?>"><?php echo PLGLib\StaticOptions::get_needs()[$need_id]; ?><i class="fa fa-medkit pl-1"></i></span>
            <?php endforeach;?>
            </div>
            <div class="card-needs">
                <h6 class="d-inline-block mr-2"><?php _e('Offer','pooling'); ?></h6>
            <?php foreach ($user_data->offers as $key => $need_id): ?>
                <span class="badge badge-success p-2" data-toggle="tooltip" data-placement="top" title="<?php _e('Offer','pooling'); ?>"><?php echo PLGLib\StaticOptions::get_offers()[$need_id]; ?><i class="fa fa-hands-helping pl-1"></i></span>
            <?php endforeach;?>
            </div>
            </p>
            <div id="request-actions" class="m-5">
                <?php 
                    $raise_modal_attrs = $is_current_user_entry ? '' : 'data-toggle="modal" data-target="#sendRequestModal"'; 
                    $disabled = $is_current_user_entry ? 'disabled' : '';
                ?>
              <button href="#" class="card-link btn btn-primary btn-block btn-lg " <?php echo $raise_modal_attrs; ?> data-user-id="<?php echo $user_data->user_id; ?>" <?php echo $disabled; ?>><?php _e('Help this user', 'pooling');?></button>
            <?php 
                // global $wp;
                // $url = home_url( add_query_arg( array(), $wp->request ) );
                // $needs_labels = array_map(function($id){ return \PLGLib\StaticOptions::get_needs()[$id]; }, $user_data->needs);
                // $text = sprintf('%s %s %s: %s %s', __('There\'s need for','pooling'), implode(',', $needs_labels), __('more info at','pooling'), $url, POOLING_HASHTAG);
                // \PLGLib\Helpers::share_buttons($url, $text);
            ?>
            </div>
          </div>
        </div>
        </div>
    <?php endforeach;?>
</div>
<div class="modal fade" id="sendRequestModal" tabindex="-1" role="dialog" aria-labelledby="sendRequestModalLabel" aria-hidden="true">
  <form action="" method="post">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="sendRequestModalLabel"><?php _e('Help request', 'pooling');?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
              <div class="row">
                  <div class="col-lg">
                      <p>
                          <?php _e('You are about to contact another person to offer your help. Which of these needs are you going to cover?', 'pooling');?>
                      </p>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg" id="form-fields">
                    <!-- ajax stuff in here -->
                  </div>
              </div>
          </div>
          <div class="container-fluid">
              <div class="row">
                  <div class="col-lg">
                      <p>
                          <span class="badge badge-info"><?php _e('Note');?>:</span>
                          <?php echo sprintf('%s <a href="/terms" target=_blank">%s</a> %s',
                              __('By Sending the request you accept & consent to these', 'pooling'),
                              __('terms', 'pooling'),
                              __('and the rules of the platform.', 'pooling')
                          ); ?>
                      </p>
                      <ul class="list-group">
                        <li class="list-group-item"><small><?php _e('Always keep distance equal or greater than 2 meters.', 'pooling');?></small></li>
                        <li class="list-group-item"><small><?php _e('Wash your hands & face before and after the transaction.', 'pooling');?></small></li>
                        <li class="list-group-item"><small><?php _e('If you have symptoms you are NOT allowed to contact any person.', 'pooling');?></small></li>
                        <li class="list-group-item"><small><?php _e('As an aid provider, you are sole responsible for the help you provide.', 'pooling');?></small></li>
                        <li class="list-group-item"><small><?php _e('The aid provided through this platform is always free.', 'pooling');?></small></li>
                      </ul>
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php _e('Close', 'pooling');?></button>
          <button type="submit" class="btn btn-primary"><?php _e('Send request', 'pooling');?></button>
        </div>
      </div>
    </div>
  </form>
</div>
