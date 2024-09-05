jQuery(document).ready(function(){
    jQuery("#qcm-front-questions").on("click", "#question-nav-next", function() {
        var qcm_word_user_id = jQuery("#qcm_word_user_id").val();

        jQuery(".qcm-word-user-justif").each(function() {
            jQuery(this).prev("label").removeClass("requiered");
        });

        var theme = jQuery("#theme").val();
        var paged = jQuery(this).data("paged");
        var noResponseRequiered = false;
        var justifRequiered = false;

        jQuery(".qcm-word-user-response").each(function() {
            if (
                jQuery(this).is('input:text') && jQuery(this).val() != "" ||
                jQuery(this).is('input:checkbox') && jQuery(this).is(":checked") != "" ||
                jQuery(this).is('textarea') && jQuery(this).val() != ""
            ) {
                noResponseRequiered = true;
            }
        });

        jQuery(".qcm-word-user-justif").each(function() {
            if (jQuery(this).val() == "") {
                justifRequiered = true;
            }
        });

        if (noResponseRequiered !== true) {
            alert(translation.reponse_requise);

            return false;
        }

        if (justifRequiered === true) {
            jQuery(".qcm-word-user-justif").each(function() {
                jQuery(this).prev("label").addClass("requiered");
            });

            return false;
        }

        if (noResponseRequiered === true && justifRequiered === false) {
            var user_responses = new Array();

            jQuery(".qcmword-question").each(function() {
                var responses = new Array();
                var question_id = jQuery(this).data("question-id");
                var question_text = jQuery(this).data("question-text");
                var justif = jQuery(this).find(".qcm-word-user-justif").val();

                jQuery(this).find(".qcm-word-user-response").each(function() {
                    var response_id = jQuery(this).data("response-id");
                    var response_text = jQuery(this).data("response-text");
                    var response_type = jQuery(this).data("response-type");
                    var response_value = "";

                    if (jQuery(this).is(':checkbox') && jQuery(this).is(":checked")) {
                        response_value = translation.oui;
                    } else if (jQuery(this).is(':checkbox') && !jQuery(this).is(":checked")) {
                        response_value = translation.no;
                    } else {
                        response_value = jQuery(this).val();
                    }

                    responses.push({"response-id": response_id, "response-text": response_text, "response-type": response_type, "response-value": response_value});
                });

                user_responses.push({"qcm_word_user_id": qcm_word_user_id, "question-id": question_id, "question-text": question_text, "responses": responses, "justif": justif});
            });

            save_user_response(user_responses, theme, paged);
            get_listing_json(theme, paged);
        }
    });

    jQuery("#qcm-front-questions").on("click", "#question-nav-prev", function() {
        var theme = jQuery("#theme").val();
        var paged = jQuery(this).data("paged");
        
        get_listing_json(theme, paged);
    });

    jQuery("#qcmword-user-qcm-save").on("click", function() {
        jQuery("#error-message").html("");

        var theme = jQuery("#theme").val();
        var lastName = jQuery("#lastName").val();
        var firstName = jQuery("#firstName").val();
        var email = jQuery("#email").val();
        var phone = jQuery("#phone").val();
        var country = jQuery("#country").val();
        var city = jQuery("#city").val();

        var error_messages = new Array();
        if (lastName == "") {
            error_messages.push({"field": translation.nom, "message": translation.nom_requis });
        }

        if (firstName == "") {
            error_messages.push({"field": translation.prenom, "message": translation.prenom_requis });
        }

        if (phone == "") {
            error_messages.push({"field": translation.tel, "message": translation.tel_requis });
        }

        if (email == "") {
            error_messages.push({"field": translation.email, "message": translation.email_requis });
        }
        if (!String(email)
            .toLowerCase()
            .match(
              /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            )) {
                error_messages.push({"field": translation.email, "message": translation.email_invalid });
            }

        if (country == "") {
            error_messages.push({"field": translation.pays, "message": translation.pays_requis });
        }

        if (city == "") {
            error_messages.push({"field": translation.ville, "message": translation.ville_requis });
        }

        if (error_messages.length > 0) {
            var _html = "<ul>";
            error_messages.forEach(element => {
                _html += "<li class='requiered-label'>" + element.message + "</li>";
            });
            _html += "</ul>";

            jQuery("#error-message").html(_html);
            jQuery("#error-message").removeClass("hidden");
            return false;
        }

        jQuery.ajax({
            url: "/wp-json/qcm-word/api/v1/qcm-word-save-user-qcm",
            type: "POST",
            cache: false,
            data: JSON.stringify({"data": {"theme": theme, "lastName": lastName, "firstName": firstName, "email": email, "phone": phone, "country": country, "city": city}}),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                jQuery("#qcm_word_user_id").val(result);

                get_listing_json(theme, 1);
      
                return false;
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
              console.log("Error!");
            },
        });
    });
});

function get_listing_json(theme, paged) {
    var currentPage = paged;
    
    jQuery.ajax({
        url: "/wp-json/qcm-word/api/v1/qcm-word-listing?theme=" + theme + "&paged=" + paged,
        cache: false,
        success: function (result) {
            var json = jQuery.parseJSON(result);
            var data = json.data;
            var _html = "";

            if (data.length > 0) {
                var nextPage = 1 + currentPage;
                var prevPage = parseInt(currentPage) - 1;

                jQuery("#qcm-front-questions").html(_html);

                for(var i=0; i<data.length; i++) {
                    var post = data[i]["post"];
                    var responses = data[i]["responses"];
                    var justif_required = data[i]["justif_required"];

                    _html = '<article class="qcmword-question post-1 post type-post status-publish format-standard hentry category-non-classe ast-article-single" id="post-' 
                        + post["ID"] 
                        + '" data-question-id="' 
                        + post["ID"] 
                        + '" data-question-text="' 
                        + post["post_title"] + '">';
                    _html += '<div class="ast-post-format- ast-no-thumb single-layout-1">';
                    _html += '<header class="qcmword-entry-header entry-header">';
                    _html += '<h3 class="qcmword-entry-title entry-title" itemprop="headline">' + post.post_title + '</h3>';
                    _html += '</header>';
                    
                    _html += '<div class="qcmword-qcm">';
                    for (var j=0; j<responses.length; j++) {    
                        _html += '<div class="qcmword-qcm-responses">';
                        
                        if (responses[j]["qcmword-response-type"] == "checkbox") {
                            _html += '<input type="checkbox" id="response-' + j + '" name="response-' + j + '" class="qcm-word-user-response" data-response-id="' + responses[j]["qcm-word-response-id"] + '" data-response-text="' + responses[j]["qcmword-question-response"] + '" data-response-type="' + responses[j]["qcmword-response-type"] + '" />';
                            _html += '<label for="response-' + j + '" class="qcmword-qcm-response-label">' + responses[j]["qcmword-question-response"] + '</label>';
                            
                            if (responses[j]["qcmword-response-infobulle"] != "") {
                                _html += '<label class="qcmword-infobulle" title="' + responses[j]['qcmword-response-infobulle'] + '">?</label>';
                            }
                        } else if (responses[j]["qcmword-response-type"] == "text") {
                            _html += '<label for="response-' + j + '" class="qcmword-qcm-response-label">Merci de saisir votre réponse</label>';
                            _html += '<input type="text" id="response-' + j + '" name="response-' + j + '" class="qcmword-qcm-response-input-text qcm-word-user-response" data-response-id="' + responses[j]["qcm-word-response-id"] + '" data-response-text="" data-response-type="' + responses[j]["qcmword-response-type"] + '"/>';
                            
                            if (responses[j]["qcmword-response-infobulle"]) {
                                _html += '<label class="qcmword-infobulle" title="' + responses[j]['qcmword-response-infobulle'] + '">?</label>';
                            }
                        } else if (responses[j]["qcmword-response-type"] == "textarea") {
                            _html += '<label for="response-' + j + '" class="qcmword-qcm-response-label">Merci de saisir votre réponse</label>';
                            _html += '<textarea id="response-' + j + '" name="response-' + j + '" rows="5" class="qcmword-qcm-response-input-textarea qcm-word-user-response" data-response-id="' + responses[j]["qcm-word-response-id"] + '" data-response-text="" data-response-type="' + responses[j]["qcmword-response-type"] + '"></textarea>';
                            
                            if (responses[j]["qcmword-response-infobulle"]) {
                                _html += '<label class="qcmword-infobulle" title="' + responses[j]['qcmword-response-infobulle'] + '">?</label>';
                            }
                        }

                        _html += '</div>';
                    }

                    if (justif_required == "yes") {
                        _html += '<label class="col-12 qcmword-qcm-response-label">' + translation.justif_requise + '</label>';
                        _html += '<textarea class="qcm-word-user-justif width-full" id="justif-response-' + i + ' name="justif-response-' + i + '" rows="5"></textarea>';
                    }

                    _html += '</div>';
                    _html += '</article>';
                }

                if (prevPage <= 1) {
                    prevPage = 1;
                }

                jQuery("#qcm-front-questions").find("#question-nav-next").data("paged", nextPage);
                jQuery("#qcm-front-questions").find("#question-nav-prev").data("paged", prevPage);

                _html += '<div class="col-xs-1 center-block qcmword-page-btns">';
                _html += '<span class="btn btn-primary" id="question-nav-prev" data-paged="' + prevPage + '"><< ' + translation.precedent + '</span>';
                _html += '<span class="btn btn-primary" id="question-nav-next" data-paged="' + nextPage + '">' + translation.suivant + ' >></span>';
                _html += '</div>';

                jQuery("#qcm-front-questions").html(_html);
            } else {
                _html = '<article class="qcmword-question post-1 post type-post status-publish format-standard hentry category-non-classe ast-article-single">';
                _html += '<div class="ast-post-format- ast-no-thumb single-layout-1">';
                _html += '<header class="qcmword-entry-header entry-header">';
                _html += '<h3 class="qcmword-entry-title entry-title" itemprop="headline">' + translation.felicitation + '</h3>';
                _html += '</header>';

                _html += '<div class="qcmword-qcm-end">';
                _html += '<p>' + translation.message_sauvegarde_succes_1 + '</p>';
                _html += '<p>' + translation.message_sauvegarde_succes_2 + '</p>';
                _html += '</div>';

                _html += '</div>';
                _html += '</article>';

                jQuery("#qcm-front-questions").html(_html);
            }

            return false;
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log('erreur');
        }
    });
}

function save_user_response(user_responses, theme, paged) {
    jQuery.ajax({
        url: "/wp-json/qcm-word/api/v1/post-user-response",
        type: "POST",
        cache: false,
        data: JSON.stringify({"data": user_responses, "theme": theme}),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (result) {
          if (result == 0) {
            alert(translation.erreur);
          } else if (result == -1) {
            alert(translation.aucune_donnees);
          }
  
          return false;
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
          console.log("Error!");
        },
      });
}