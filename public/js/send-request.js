(function($) {
    $('#request-actions a.card-link').on('click',function(e){
      e.preventDefault();
    });
    $(document).ready(function() {
        $('#sendRequestModal').on('show.bs.modal', function (event) {
            $('#myInput').trigger('focus');
              var button = $(event.relatedTarget); // Button that triggered the modal
              var recipient = button.data('user-id'); // Extract info from data-* attributes
              var modal = $(this);
              var form = modal.find('form');
              var form_fields = modal.find('#form-fields');
              var sendbtn = modal.find('button[type=submit]');
              sendbtn.prop("disabled", true);
              sendbtn.html(pooling_map_global_send_request.send_button_text);
              $.ajax({
                type: 'GET',
                url: pooling_map_global_send_request.ajax_url,
                data: { 
                  action: 'get_needs',
                  user_id: recipient,
                  _ajax_nonce: pooling_map_global_send_request.nonce
                },
                dataType: 'json',
                success: function(response){
                    var spinner = `<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>`;
                    form_fields.html(spinner);
                    var html = '';
                    for(var i in response){
                      group = `<input type="checkbox"  class="form-check-input needs" id="request-needs-${i}" name="need" value="${i}"/><label for="request-needs-${i}" class="form-check-label">${response[i]}</label>`;
                      html += `<div class="form-check">${group}</div>`;
                    }
                    form_fields.html(html);
                    form_fields.find('input:checkbox').on('click',function() {
                      if ($(this).is(':checked')) {
                        sendbtn.prop("disabled", false);
                      } else {
                      if (form_fields.find('.needs').filter(':checked').length < 1){
                        sendbtn.attr('disabled',true);}
                      }
                    });
                }
              });
              modal.find('.modal-title').text(pooling_map_global_send_request.modal_title + ' ' + recipient);
              form.off('submit').on('submit', function(e){
                var spinner = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...`;
                sendbtn.html(spinner);
                e.preventDefault();
                e.stopPropagation();
                $.ajax({
                  type: 'POST',
                  url: pooling_map_global_send_request.ajax_url,
                  data: { 
                    action: 'aid_request',
                    needs: form.serializeArray(),
                    user_id: recipient,
                    _ajax_nonce: pooling_map_global_send_request.nonce
                  },
                  dataType: 'json',
                  success: function(response){
                      form_fields.html(`<h3 class="badge badge-success p-3 d-block">${pooling_map_global_send_request.aid_request_done}</h3>`);
                      sendbtn.html(pooling_map_global_send_request.send_button_text_done);
                      sendbtn.prop('disabled','disabled');
                      console.log(response);
                      // modal.modal('hide');
                  },
                  error: function(response){
                    form_fields.html(`<h3 class="badge badge-danger p-3 d-block">${response.responseJSON.data[0].message}</h3>`);
                    sendbtn.html(pooling_map_global_send_request.send_button_text_fail);
                    sendbtn.prop('disabled','disabled');
                  }
                });
              });
        });
    });
})(jQuery);