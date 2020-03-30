(function($) {
    $(document).ready(function() {
        $('#request-actions a').on('click',function(e){
          e.preventDefault();
        });
        $('#withdrawModal').on('show.bs.modal', function (event) {
            $('#myInput').trigger('focus');
              var button = $(event.relatedTarget); // Button that triggered the modal
              var recipient = button.data('user-id'); // Extract info from data-* attributes
              var aid_request_id = button.data('aid-request-id'); // Extract info from data-* attributes
              var modal = $(this);
              var form = modal.find('form');
              var form_fields = modal.find('#form-fields');
              var sendbtn = modal.find('button[type=submit]');
              sendbtn.prop("disabled", true);
              sendbtn.html(pooling_map_global.send_button_text);
              $.ajax({
                type: 'GET',
                url: pooling_map_global.ajax_url,
                data: { 
                  action: 'get_aid_request_id',
                  user_id: recipient,
                  aid_request_id: aid_request_id,
                  _ajax_nonce: pooling_map_global.nonce
                },
                dataType: 'json',
                beforeSend: function(data){
                    var spinner = `<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>`;
                    form_fields.html(spinner);
                    sendbtn.prop('disabled','disabled');
                },
                success: function(response){
                    // form_fields.html(typeof response.html == 'undefined' ? '' : response.html);
                    form_fields.html('');
                    sendbtn.prop('disabled','');
                },
                error: function(response){
                  form_fields.html(`<h3 class="badge badge-danger">${response.responseJSON.data[0].message}</h3>`);
                  sendbtn.html(pooling_map_global.send_button_text_fail);
                  sendbtn.prop('disabled','disabled');
                }
              });
              modal.find('.modal-title').text(pooling_map_global.modal_title + ' ' + recipient);
              form.off('submit').on('submit', function(e){
                var spinner = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...`;
                sendbtn.html(spinner);
                e.preventDefault();
                e.stopPropagation();
                $.ajax({
                  type: 'POST',
                  url: pooling_map_global.ajax_url,
                  data: { 
                    action: 'aid_request_withdraw',
                    user_id: recipient,
                    aid_request_id: aid_request_id,
                    _ajax_nonce: pooling_map_global.nonce
                  },
                  dataType: 'json',
                  success: function(response){
                      form_fields.html(`<h3 class="badge badge-success p-3 d-block">${pooling_map_global.aid_request_done}</h3>`);
                      sendbtn.html(pooling_map_global.send_button_text_done);
                      sendbtn.prop('disabled','disabled');
                      // console.log(response);
                      // modal.modal('hide');
                  },
                  error: function(response){
                    form_fields.html(`<h3 class="badge badge-danger p-3 d-block">${response.responseJSON.data[0].message}</h3>`);
                    sendbtn.html(pooling_map_global.send_button_text_fail);
                    sendbtn.prop('disabled','disabled');
                  }
                });
              });
        });
    });
})(jQuery);