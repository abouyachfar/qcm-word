jQuery(document).ready(function(){
    jQuery("#btn-new-response").on("click", function() {
        var response_count = jQuery(".response-item").length + 1;
        var _html = '';

        _html += '<div class="response-item">';
        _html += '<div style="border: 1px solid #c3c4c7">';
        _html += '<h2>' + translation.reponse + ' ' + response_count + '</h2>';
        _html += '</div>';

        _html += '<div style="width: 96.8%;border: 1px solid #c3c4c7;padding: 15px;margin: 15px 0;margin-top: 0px;border-top: none;">';
        _html += '<div class="qcmword-response-item">';
        _html += '<div style="width: 100%">';
        _html += '<div class="meta-box-item-title">';
        _html += '<label style="width: 100%"><b>' + translation.type_du_champ + '</b></label>';
        _html += '</div>',

        _html += '<div class="meta-box-itel-content">';
        _html += '<select id="qcmword-response-type-' + response_count + '" name="qcmword-response-type-' + response_count + '" style="width: 25%">';
        _html += '<option value="checkbox" >' + translation.case_a_cocher + '</option>';
        _html += '<option value="text" >' + translation.texte + '</option>';
        _html += '<option value="textarea" >' + translation.text_multilignes + '</option>';
        _html += '</select>';
        _html += '</div>';
        _html += '</div>';

        _html += '<div style="width: 100%">';          
        _html += '<div class="meta-box-item-title" style="width: 100%">';
        _html += '<label style="width: 100%"><b>' + translation.texte + '</b></label>';
        _html += '</div>';
        _html += '<div class="meta-box-itel-content" style="width: 100%">';
        _html += '<textarea id="qcmword-question-response-' + response_count + '" name="qcmword-question-response-' + response_count + '" style="width: 100%" rows="5"></textarea>';
        _html += '</div>';
        _html += '</div>';

        _html += '<div style="width: 100%">';
        _html += '<div class="meta-box-item-title" style="width: 100%">';
        _html += '<label style="width: 100%"><b>' + translation.InfoBulle + '</b></label>';
        _html += '</div>';
        _html += '<div class="meta-box-itel-content" style="width: 100%">';
        _html += '<textarea id="qcmword-response-infobulle-' + response_count + '" name="qcmword-response-infobulle-' + response_count + '" style="width: 100%" rows="5"></textarea>';
        _html += '</div>';
        _html += '</div>';

        _html += '</div>';
        _html += '</div>';
        _html += '</div>';
        _html += '</div>';

        jQuery("#responses").append(_html);
        jQuery("#nbr-response-item").val(response_count);
    })
});