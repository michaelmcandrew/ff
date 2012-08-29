(function ($) {
    Drupal.behaviors.webform_signup = {
	attach: function(context, settings) {
	    $(document).ready(function(){  
		    if(document.getElementById('webform-client-form-5')) {
			var crmURL ="/civicrm/ajax/rest?className=CRM_Contact_Page_AJAX&fnName=getContactList&json=1&context=education&org=1";
			$("#edit-submitted-civicrm-1-contact-1-fieldset-fieldset-university-civicrm-1-contact-1-cg5-custom-20").autocomplete(  crmURL,{                      
				width        : 250,
				selectFirst  : false,
				minChars     : 1,
				matchContains: true
			    }).result( function(event, data, formatted) {
			
				});

			//defining constant
			//constant for div element
			//var leaving_year_div = 'civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-cg13-custom-25';//What year are you in?
			//var education_div = 'civicrm-1-contact-1-fieldset-fieldset--civicrm-1-contact-1-cg5-custom-17';
			var level_two = 'form-item-submitted-civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-level-two';
			var level_three = 'form-item-submitted-civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-level-three';
			var level_five = 'form-item-submitted-civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-level-five';
			var workskills = 'form-item-submitted-civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-workskills';
			var short_courses = 'form-item-submitted-civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-short-courses';
			var degree = 'form-item-submitted-civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-degree';
			var foundation_degree = 'form-item-submitted-civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-foundation-degree';
			var higher_national_certification = 'form-item-submitted-civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-higher-national-certification';
			var higher_national_diploma = 'form-item-submitted-civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-higher-national-diploma';
			var full_part_time_div = 'civicrm-1-contact-1-fieldset-fieldset--qualifications--civicrm-1-contact-1-cg4-custom-14';
			var institution_div = 'civicrm-1-contact-1-fieldset-fieldset--university--civicrm-1-contact-1-cg5-custom-20';
			//var foundation_degree_id = 'civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-cg14-custom-26-12';
			var full_time_div = 'civicrm-1-contact-1-fieldset-fieldset-employment-apprenticeships-and-training-civicrm-1-contact-1-cg6-custom-15-1';//Full Time job checkbox val
			var part_time_div = 'civicrm-1-contact-1-fieldset-fieldset-employment-apprenticeships-and-training-civicrm-1-contact-1-cg6-custom-15-2';//Part Time job checkbox val
			var apprenticeship_div = 'civicrm-1-contact-1-fieldset-fieldset-employment-apprenticeships-and-training-civicrm-1-contact-1-cg6-custom-15-3';//Apprenticeship checkbox val
			var job_details_div = 'civicrm-1-contact-1-fieldset-fieldset--employment-apprenticeships-and-training--civicrm-1-contact-1-cg7-custom-23';
			//var job_details_div = 'civicrm-1-contact-1-fieldset-fieldset--employment-apprenticeships-and-training--civicrm-1-contact-1-cg7-custom-16';//old
			var time_div = 'civicrm-1-contact-1-fieldset-fieldset--employment-apprenticeships-and-training--civicrm-1-contact-1-cg7-custom-17';
			//var activity_div = 'civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-cg19-custom-32';
			var other_div = 'civicrm-1-contact-1-fieldset-fieldset--other-activities--civicrm-1-contact-1-cg8-custom-24';
			//var other_div = 'civicrm-1-contact-1-fieldset-fieldset--other-activities--civicrm-1-contact-1-cg8-custom-19';//old
			var outside_uk_div = 'civicrm-1-contact-1-fieldset-fieldset--university--civicrm-1-contact-1-cg5-custom-22';
			var outside_uk_val ='civicrm-1-contact-1-fieldset-fieldset-university-civicrm-1-contact-1-cg5-custom-22-1';

			//constant for div element value
			var education_val = 'civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13';
			var employment_val = 'civicrm-1-contact-1-fieldset-fieldset-employment-apprenticeships-and-training-civicrm-1-contact-1-cg6-custom-15';
			var btec_val = 'civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-4';
			var level_two_val = 'civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-5';
			var level_three_val = 'civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-6';
			var level_five_val = 'civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-7';
			var workskills_val = 'civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-8';
			var short_courses_val = 'civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-9';
			var activity = 'civicrm-1-contact-1-fieldset-fieldset--other-activities--civicrm-1-contact-1-cg8-custom-18';//Other activity div
			//var full_part_time_val = 'civicrm-1-contact-1-fieldset-fieldset-civicrm-1-contact-1-cg15-custom-27';
			var apprenticeship_val = 'civicrm-1-contact-1-fieldset-fieldset-employment-apprenticeships-and-training-civicrm-1-contact-1-cg7-custom-23';//Job detail textbox val
			//var apprenticeship_val = 'civicrm-1-contact-1-fieldset-fieldset-employment-apprenticeships-and-training-civicrm-1-contact-1-cg7-custom-16';//old Job detail textbox val
			var degree_val = 'civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-11';
			var foundation_degree_val = 'civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-12';
			var higher_national_certification_val = 'civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-13';
			var higher_national_diploma_val = 'civicrm-1-contact-1-fieldset-fieldset-qualifications-civicrm-1-contact-1-cg3-custom-13-14';
			var institution_val  = 'civicrm-1-contact-1-fieldset-fieldset-university-civicrm-1-contact-1-cg5-custom-20';
			var other_val = 'civicrm-1-contact-1-fieldset-fieldset-other-activities-civicrm-1-contact-1-cg8-custom-24';
			//var other_val = 'civicrm-1-contact-1-fieldset-fieldset-other-activities-civicrm-1-contact-1-cg8-custom-19';//old
			var note_div = 'civicrm-1-contact-1-fieldset-fieldset--employment-apprenticeships-and-training--note';

			var university_fieldset = 'civicrm-1-contact-1-fieldset-fieldset--university';
			var activities_fieldset = 'civicrm-1-contact-1-fieldset-fieldset--other-activities';

			//onload hide field
			$("."+level_two).hide();
			$("."+level_three).hide();
			$("."+level_five).hide();
			$("."+workskills).hide();
			$("."+short_courses).hide();
			$("."+degree).hide();
			$("."+foundation_degree).hide();
			$("."+higher_national_certification).hide();
			$("."+higher_national_diploma).hide();
			$("#webform-component-"+full_part_time_div).hide();
			$("#webform-component-"+outside_uk_div).hide();
			$("#webform-component-"+university_fieldset).hide();
			$("#webform-component-"+institution_div).hide();
			$("#webform-component-"+job_details_div).hide();
			$("#webform-component-"+time_div).hide();
			$("#webform-component-"+other_div).hide();

			$("#webform-component-"+note_div).hide();

			//Onload Selection
			//Education
			if($("#edit-submitted-"+education_val+" input").is(":checked")) {
			    $("#webform-component-"+full_part_time_div).show();
			    $("#webform-component-"+activities_fieldset).hide();
			    $("#webform-component-"+activity).hide();
			    $("#webform-component-"+other_div).hide();
			}

			//Employment, Apprenticeships and Training
			if($("#edit-submitted-"+employment_val+" input").is(":checked")) {
			    $("#webform-component-"+activities_fieldset).hide();
			    $("#webform-component-"+activity).hide();
			    $("#webform-component-"+other_div).hide();
			}

			//BTech
			if($("#edit-submitted-"+btec_val).is(":checked")) {
			    $("."+level_two).show();
			    $("."+level_three).show();
			    $("."+level_five).show();
			    $("."+workskills).show();
			    $("."+short_courses).show();  
			    $("#webform-component-"+full_part_time_div).show();
			}

			//Year Radio selected 12 or 13
			if($("input:radio[name='submitted[civicrm_1_contact_1_fieldset_fieldset][basic_info][civicrm_1_contact_1_cg2_custom_12]']:checked").val() == 'twelve' || $("input:radio[name='submitted[civicrm_1_contact_1_fieldset_fieldset][basic_info][civicrm_1_contact_1_cg2_custom_12]']:checked").val() == 'thirteen'){
			    $("."+degree).show();
			    $("."+foundation_degree).show();
			    $("."+higher_national_certification).show();
			    $("."+higher_national_diploma).show();
			}

			//Degree Or Foundation degree
			if($("#edit-submitted-"+foundation_degree_val).is(":checked") || $("#edit-submitted-"+degree_val).is(":checked")) {
			    $("#webform-component-"+university_fieldset).show();
			    $("#webform-component-"+institution_div).show();

			    $("#webform-component-"+outside_uk_div).show();
			    if($("#edit-submitted-"+outside_uk_val).is(":checked")) {
				$("#edit-submitted-"+institution_val).attr('readonly', true);
			    }
			    else {
				if(!$("#edit-submitted-"+outside_uk_val).is(":checked")) {
				    $("#edit-submitted-"+institution_val).attr('readonly', false);
				}
			    }
			}

			//when part or full time selected
			if($("#edit-submitted-"+full_time_div).is(":checked") || $("#edit-submitted-"+part_time_div ).is(":checked") ) {
			    $("#webform-component-"+job_details_div).show();
			}

			//when Apprenticeship and part time selected
			if($("#edit-submitted-"+apprenticeship_div).is(":checked") && $("#edit-submitted-"+part_time_div ).is(":checked") ) {
			    $("#webform-component-"+time_div).show();
			}

			//other selected
			//if($("#edit-submitted-"+activity_div+" option:selected").val() == "other") {
			//if($("#edit-submitted-"+other_val).val() != '') {
			if($("input:radio[name='submitted[civicrm_1_contact_1_fieldset_fieldset][other_activities][civicrm_1_contact_1_cg8_custom_18]']:checked").val() == 'other') {
			    if( !$("#edit-submitted-"+education_val+" input").is(":checked") && !$("#edit-submitted-"+employment_val+" input").is(":checked") ) {
				$("#webform-component-"+other_div).show();
			    }
			}

			//Radio button changes for What year are you in?
			$("input:radio[name='submitted[civicrm_1_contact_1_fieldset_fieldset][basic_info][civicrm_1_contact_1_cg2_custom_12]']").click(function() {
				if($(this).val() == 'eleven'){
				    //$("#webform-component-"+education_div).show();
				    $("."+degree).slideUp();
				    $("."+foundation_degree).slideUp();
				    $("."+higher_national_certification).slideUp();
				    $("."+higher_national_diploma).slideUp();
				    $("#edit-submitted-"+degree_val).removeAttr('checked');
				    $("#edit-submitted-"+foundation_degree_val).removeAttr('checked');
				    $("#edit-submitted-"+higher_national_certification_val).removeAttr('checked');
				    $("#edit-submitted-"+higher_national_diploma_val).removeAttr('checked');
				    $("#edit-submitted-"+institution_val).val('');
				    //$("#edit-submitted-"+full_part_time_val).val('');
				    $("#webform-component-"+institution_div).slideUp();
				    $("#webform-component-"+university_fieldset).slideUp();
				    $("#webform-component-"+full_part_time_div).slideUp();
				    $("#edit-submitted-"+outside_uk_val).removeAttr('checked');
				    $("#webform-component-"+outside_uk_div).slideUp();

				    if( !$("#edit-submitted-"+education_val+" input").is(":checked") && !$("#edit-submitted-"+employment_val+" input").is(":checked") ) {
					$("#webform-component-"+activities_fieldset).slideDown();
				    	$("#webform-component-"+activity).slideDown();
				    }
				}
				else if($(this).val() == 'twelve' || $(this).val() == 'thirteen'){
				    //$("#webform-component-"+education_div).show();
				    $("."+degree).slideDown();
				    $("."+foundation_degree).slideDown();
				    $("."+higher_national_certification).slideDown();
				    $("."+higher_national_diploma).slideDown();
				}
			    });

			//Manage B-TEC options
			$("#edit-submitted-"+btec_val).click(function() {
				if($("#edit-submitted-"+btec_val).is(":checked")) {
				    $("."+level_two).slideDown();
				    $("."+level_three).slideDown();
				    $("."+level_five).slideDown();
				    $("."+workskills).slideDown();
				    $("."+short_courses).slideDown();
				}
				else {
				    $("#edit-submitted-"+level_two_val).removeAttr('checked');
				    $("#edit-submitted-"+level_three_val).removeAttr('checked');
				    $("#edit-submitted-"+level_five_val).removeAttr('checked');
				    $("#edit-submitted-"+workskills_val).removeAttr('checked');
				    $("#edit-submitted-"+short_courses_val).removeAttr('checked');
				    $("."+level_two).slideUp();
				    $("."+level_three).slideUp();
				    $("."+level_five).slideUp();
				    $("."+workskills).slideUp();
				    $("."+short_courses).slideUp();
				}
			    });

			//Manage Qualification selection
			$("#edit-submitted-"+education_val+" input").click(function() {
				if($("#edit-submitted-"+education_val+" input").is(":checked")) {
				    var full_radio  = $('input[name = "submitted[civicrm_1_contact_1_fieldset_fieldset][qualifications][civicrm_1_contact_1_cg4_custom_14]"]');
				    full_radio.filter('[value=full-time]').removeAttr("checked");
				    full_radio.filter('[value=part-time]').removeAttr("checked");
				    $("#webform-component-"+full_part_time_div).slideDown();

				    var activity_radio  = $('input[name = "submitted[civicrm_1_contact_1_fieldset_fieldset][other_activities][civicrm_1_contact_1_cg8_custom_18]"]');
				    activity_radio.filter('[value=family-commitments]').removeAttr("checked");
				    activity_radio.filter('[value=gap-year]').removeAttr("checked");
				    activity_radio.filter('[value=looking-for-work]').removeAttr("checked");
				    activity_radio.filter('[value=havent-decided]').removeAttr("checked");
				    activity_radio.filter('[value=other]').removeAttr("checked");
				    $("#edit-submitted-"+other_val).val('');
				    $("#webform-component-"+other_div).slideUp();
				    $("#webform-component-"+activities_fieldset).slideUp();
				    $("#webform-component-"+activity).slideUp();
				    //flag=1;
				}else {
				    //$("#edit-submitted-"+activity_div).children("option:selected").removeAttr("selected");
				    var full_radio  = $('input[name = "submitted[civicrm_1_contact_1_fieldset_fieldset][qualifications][civicrm_1_contact_1_cg4_custom_14]"]');
				    full_radio.filter('[value=full-time]').removeAttr("checked");
				    full_radio.filter('[value=part-time]').removeAttr("checked");
				    $("#webform-component-"+full_part_time_div).slideUp();

				    //Check for Employment, Apprenticeships and Training values
				    if(!$("#edit-submitted-"+employment_val+" input").is(":checked")) {
					var activity_radio  = $('input[name = "submitted[civicrm_1_contact_1_fieldset_fieldset][other_activities][civicrm_1_contact_1_cg8_custom_18]"]');
					activity_radio.filter('[value=family-commitments]').removeAttr("checked");
					activity_radio.filter('[value=gap-year]').removeAttr("checked");
					activity_radio.filter('[value=looking-for-work]').removeAttr("checked");
					activity_radio.filter('[value=havent-decided]').removeAttr("checked");
					activity_radio.filter('[value=other]').removeAttr("checked");
					$("#webform-component-"+activities_fieldset).slideDown();
					$("#webform-component-"+activity).slideDown(1000);
				    }
				}

				//Manage Other activities based on Education section checkbox and Full/Part Time radio button
				// if($("#edit-submitted-"+education_val+" input").not(":checked") && $("input:radio[name='submitted[civicrm_1_contact_1_fieldset_fieldset][civicrm_1_contact_1_cg4_custom_14]']:checked").val("")) {
				//     var activity_radio  = $('input[name = "submitted[civicrm_1_contact_1_fieldset_fieldset][civicrm_1_contact_1_cg8_custom_18]"]');
				//     activity_radio.filter('[value=family-commitments]').removeAttr("checked");
				//     activity_radio.filter('[value=gap-year]').removeAttr("checked");
				//     activity_radio.filter('[value=looking-for-work]').removeAttr("checked");
				//     activity_radio.filter('[value=havent-decided]').removeAttr("checked");
				//     activity_radio.filter('[value=other]').removeAttr("checked");
				//     $("#edit-submitted-"+other_val).val('');
				//     $("#webform-component-"+other_div).hide();
				//     $("#webform-component-"+activity).show();
				// }

			    });

			//Manage Employment, Apprenticeships and Training Selection
			$("#edit-submitted-"+employment_val+" input").click(function() {
				if($("#edit-submitted-"+employment_val+" input").is(":checked")) {
				    var activity_radio  = $('input[name = "submitted[civicrm_1_contact_1_fieldset_fieldset][other_activities][civicrm_1_contact_1_cg8_custom_18]"]');
				    activity_radio.filter('[value=family-commitments]').removeAttr("checked");
				    activity_radio.filter('[value=gap-year]').removeAttr("checked");
				    activity_radio.filter('[value=looking-for-work]').removeAttr("checked");
				    activity_radio.filter('[value=havent-decided]').removeAttr("checked");
				    activity_radio.filter('[value=other]').removeAttr("checked");
				    $("#edit-submitted-"+other_val).val('');
				    $("#webform-component-"+other_div).slideUp();
				    $("#webform-component-"+activities_fieldset).slideUp();
				    $("#webform-component-"+activity).slideUp();
				}else {
				    //Check for Employment, Apprenticeships and Training values
				    if(!$("#edit-submitted-"+education_val+" input").is(":checked")) {
					var activity_radio  = $('input[name = "submitted[civicrm_1_contact_1_fieldset_fieldset][other_activities][civicrm_1_contact_1_cg8_custom_18]"]');
					activity_radio.filter('[value=family-commitments]').removeAttr("checked");
					activity_radio.filter('[value=gap-year]').removeAttr("checked");
					activity_radio.filter('[value=looking-for-work]').removeAttr("checked");
					activity_radio.filter('[value=havent-decided]').removeAttr("checked");
					activity_radio.filter('[value=other]').removeAttr("checked");
					$("#webform-component-"+activities_fieldset).slideDown();
					$("#webform-component-"+activity).slideDown(1000);
				    }
				}
			    });


			//Manage Other activities based on Education section checkbox and Full/Part Time radio button
			// $("input:radio[name='submitted[civicrm_1_contact_1_fieldset_fieldset][civicrm_1_contact_1_cg4_custom_14]']").click(function() {
			// 	if(($("#edit-submitted-"+education_val+" input").is(":checked")) && ($(this).val() != "")) {
			// 	    var activity_radio  = $('input[name = "submitted[civicrm_1_contact_1_fieldset_fieldset][civicrm_1_contact_1_cg8_custom_18]"]');
			// 	    activity_radio.filter('[value=family-commitments]').removeAttr("checked");
			// 	    activity_radio.filter('[value=gap-year]').removeAttr("checked");
			// 	    activity_radio.filter('[value=looking-for-work]').removeAttr("checked");
			// 	    activity_radio.filter('[value=havent-decided]').removeAttr("checked");
			// 	    activity_radio.filter('[value=other]').removeAttr("checked");
			// 	    $("#edit-submitted-"+other_val).val('');
			// 	    $("#webform-component-"+other_div).hide();
			// 	    $("#webform-component-"+activity).hide();
			// 	}				
			//     });

			//Manage institution based on Degree checkbox
			$("#edit-submitted-"+degree_val).click(function() {
				if($("#edit-submitted-"+degree_val).is(":checked")) {
				    $("#edit-submitted-"+institution_val).val('');
				    $("#webform-component-"+institution_div).slideDown();
				    $("#webform-component-"+outside_uk_div).slideDown();
				    $("#webform-component-"+university_fieldset).slideDown();
				}
				else {
				    if(!$("#edit-submitted-"+foundation_degree_val).is(":checked")) {
					$("#webform-component-"+institution_div).slideUp();
				    $("#edit-submitted-"+outside_uk_val).removeAttr('checked');
				    $("#webform-component-"+outside_uk_div).slideUp();
					$("#webform-component-"+university_fieldset).slideUp();
				    }
				}
			    });

			//Manage institution based on Foundation degree checkbox
			$("#edit-submitted-"+foundation_degree_val).click(function() {
				if($("#edit-submitted-"+foundation_degree_val).is(":checked")) {
				    $("#edit-submitted-"+institution_val).val('');
				    $("#webform-component-"+institution_div).slideDown();
				    $("#webform-component-"+outside_uk_div).slideDown();
				    $("#webform-component-"+university_fieldset).slideDown();

				}
				else {
				    if(!$("#edit-submitted-"+degree_val).is(":checked")) {
					$("#webform-component-"+institution_div).slideUp();
				    $("#edit-submitted-"+outside_uk_val).removeAttr('checked');
				    $("#webform-component-"+outside_uk_div).slideUp();
					$("#webform-component-"+university_fieldset).slideUp();
				    }
				}
			    });

			//Manage outside the uk university
			$("#edit-submitted-"+outside_uk_val).click(function() {
				if($("#edit-submitted-"+outside_uk_val).is(":checked")) {
				    $("#edit-submitted-"+institution_val).val('');
				    $("#edit-submitted-"+institution_val).attr('readonly', true);
				}
				else {
				    if(!$("#edit-submitted-"+outside_uk_val).is(":checked")) {
					$("#edit-submitted-"+institution_val).attr('readonly', false);
				}
				    
				}
			    });


			//Manage alertbox based on Apprenticeship checkbox
			$("#edit-submitted-"+apprenticeship_div).click(function() {
				if($("#edit-submitted-"+apprenticeship_div).is(":checked")) {
				    //alert("Please select any qualifications that you will be doing as part of the apprenticeship in \"Are you going on to do one or more of the following qualifications?\" section");
				    $("#webform-component-"+note_div).slideDown();
				}
				else {
				    $("#webform-component-"+note_div).slideUp();
				}
			    });

			//Manage Job details based on Full/Part time checkbox
			$("#edit-submitted-"+full_time_div).click(function() {
				if($("#edit-submitted-"+full_time_div).is(":checked")) {
				    $("#webform-component-"+job_details_div).slideDown();
				}
				else {
				    if(!$("#edit-submitted-"+part_time_div).is(":checked")) {
					$("#webform-component-"+job_details_div).slideUp();
				    }
				    if(!$("#edit-submitted-"+part_time_div).is(":checked") && !$("#edit-submitted-"+full_time_div).is(":checked")) {
					$("#edit-submitted-"+apprenticeship_val).val('');
				    }
				}
			    });

			$("#edit-submitted-"+part_time_div).click(function() {
				if($("#edit-submitted-"+part_time_div).is(":checked")) {
				    $("#webform-component-"+job_details_div).slideDown();
				}
				else {
				    if(!$("#edit-submitted-"+full_time_div).is(":checked")) {
					$("#webform-component-"+job_details_div).slideUp();
				    }
				    if(!$("#edit-submitted-"+part_time_div).is(":checked") && !$("#edit-submitted-"+full_time_div).is(":checked")) {
					$("#edit-submitted-"+apprenticeship_val).val('');
				    }
				}
			    });			

			//Manage Time Question based on Part time and Apprenticeship checkbox
			$("#edit-submitted-"+part_time_div).click(function() {
				if($("#edit-submitted-"+part_time_div).is(":checked") && $("#edit-submitted-"+apprenticeship_div).is(":checked")) {
				    $("#webform-component-"+time_div).slideDown();
				}
				else {
				    var time  = $('input[name = "submitted[civicrm_1_contact_1_fieldset_fieldset][employment_apprenticeships_and_training][civicrm_1_contact_1_cg7_custom_17]"]');
				    time.filter('[value=part-time-a]').removeAttr("checked");
				    time.filter('[value=apprenticeship-a]').removeAttr("checked");
				    $("#webform-component-"+time_div).slideUp();
				}
			    });
			
			$("#edit-submitted-"+apprenticeship_div).click(function() {
				if($("#edit-submitted-"+part_time_div).is(":checked") && $("#edit-submitted-"+apprenticeship_div).is(":checked")) {
				    $("#webform-component-"+time_div).slideDown();
				}
				else {
				    var time  = $('input[name = "submitted[civicrm_1_contact_1_fieldset_fieldset][employment_apprenticeships_and_training][civicrm_1_contact_1_cg7_custom_17]"]');
				    time.filter('[value=part-time-a]').removeAttr("checked");
				    time .filter('[value=apprenticeship-a]').removeAttr("checked");
				    $("#webform-component-"+time_div).slideUp();
				}
			    });


			//Manage other details text box based on other radio button
			//$("#edit-submitted-"+activity_div).change(function(){
			$("input:radio[name='submitted[civicrm_1_contact_1_fieldset_fieldset][other_activities][civicrm_1_contact_1_cg8_custom_18]']").click(function() {
				if($(this).val() == 'other'){
				    $("#webform-component-"+other_div).slideDown();
				}
				else {
				    $("#edit-submitted-"+other_val).val('');
				    $("#webform-component-"+other_div).slideUp();
				}
			    });

			//Validation for URL and B-TEC Option

			$('.simple-dialog').click(function(){
				$.ajax({
					type: "POST",
					    url: '../popup',
					    async:false,
					    success: function( data ) {
					    setTimeout(function(){
						    $('#simple-dialog-container').html(data);
						}, 4500);

					}
				    });
			    });

		    }
		    if(document.getElementById('webform-client-form-8')) {
			$('.simple-dialog').click(function(){
				$.ajax({
					type: "POST",
					    url: '../popup',
					    async:false,
					    success: function( data ) {
					    setTimeout(function(){
						    $('#simple-dialog-container').html(data);
						}, 4500);

					}
				    });
			    });
		    }

		    if(document.getElementById('node-6')) {
			var previousurl =  document.referrer;
			pathArray = previousurl.split( '/' );
			//var chkurl = pathArray[5];//local
			var chkurl = pathArray[3];
			if(chkurl == 'student-ark') {
			    setTimeout(function(){
				    window.location.replace(previousurl);
				}, 3000);
			}
		    }

		});
	}
    };
})(jQuery);
