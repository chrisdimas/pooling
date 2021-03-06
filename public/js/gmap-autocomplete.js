(function($) {
    $(document).ready(function() {
        if(jQuery('#address')[0]){
            console.log('asd');
            //Attach the autocomplete to the DOM element
            var billing_autocomplete = new google.maps.places.Autocomplete(jQuery('#address')[0], {
                // types: ['(cities)'],
                componentRestrictions: {
                    country: 'gr'
                }
            });
            //Define what information we want back from the API
            billing_autocomplete.setFields(['address_components','geometry']);
            //Define a handler which fires when an address is chosen from the autocomplete
            billing_autocomplete.addListener('place_changed', function() {
                var place = billing_autocomplete.getPlace();
                if (place.address_components) {
                    // console.log(place);
                    var street_number = place.address_components[0].short_name;
                    var street_name = place.address_components[1].short_name
                    var suburb = place.address_components[3].short_name;
                    var city = place.address_components[2].short_name;
                    var country = place.address_components[4];
                    var postcode = place.address_components[5].short_name;
                    var lng = place.geometry.location.lng();
                    var lat = place.geometry.location.lat();
                    var url = place.url;
                    if (street_number && street_name && suburb && city && postcode && country.short_name) {
                        jQuery('#address').val(street_name + ' ' + street_number);
                        jQuery('#state').val(suburb);
                        jQuery('#city').val(city);
                        jQuery('#country').val(country.short_name);
                        // jQuery('#country').html(country.long_name);
                        jQuery('#postalcode').val(postcode);
                        jQuery('#lng').val(lng);
                        jQuery('#lat').val(lat);
                        jQuery('#map_url').val(url);
                    }
                }
            });
        }
    });
})(jQuery);
