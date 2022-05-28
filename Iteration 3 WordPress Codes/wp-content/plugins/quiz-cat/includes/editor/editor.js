/* jshint asi: true */

jQuery(document).ready(function($){
	
	//DISABLE DESCRIPTION METABOX FROM DRAGGABLE ( WILL BREAK THE WYSIHTML )
	$('.meta-box-sortables').sortable({
		disabled: true
	})

	$('.postbox .hndle').css('cursor', 'pointer');
	
	fca_qc_attach_radio_selects()

	if ( $('.fca_qc_custom_css').length > 0 ) {
		wp.codeEditor.initialize( $( '.fca_qc_custom_css' ), fcaQcAdminData.code_editor )
	}

	////////////////
	// SET DEFAULTS
	////////////////

	var question_number = $( '.fca_qc_question_item' ).length
	var result_number = $('.fca_qc_result_item ').length

	//SET THE SLUG IF ITS EMPTY
	if ( $('#post_name').val() === '' ) {
		$('#post_name').val( $('#post_ID').val() )
	}
	
	//SHOW METABOXES IN CASE HIDDEN IN FREE FOR SOME REASON
	if ( $('#fca_qc_quiz_type_meta_box').length === 0 ) {
		$('#fca_qc_questions_meta_box, #fca_qc_add_result_meta_box').show()
	}
	
	//SET TRANSLATIONS FOR ON/OFF SWITCHES
	$( '.onoffswitch-inner' ).each( function(){
		$(this).attr('data-content-on', fcaQcAdminData.on_string )
		$(this).attr('data-content-off', fcaQcAdminData.off_string )
	})
	
	//HIDE THE INDIVIDUAL QUESTION AND RESULT INPUTS
	if ( $( '.fca_qc_question_input_div' ).length > 1 ) {
		$( '.fca_qc_question_input_div' ).hide()
	}
	if ( $( '.fca_qc_result_input_div' ).length > 1 ) {
		$( '.fca_qc_result_input_div' ).hide()
	}
	
	//SET UP SAVE AND PREVIEW BUTTONS, THEN HIDE THE PUBLISHING METABOX
	var saveButton = '<button type="submit" class="button-primary" id="fca_qc_submit_button">' + fcaQcAdminData.save_string + '</buttton>'
	var previewButton = '<button type="button" class="button-secondary" id="fca_qc_preview_button">' + fcaQcAdminData.preview_string + '</buttton>'

	$( '#normal-sortables' ).append( saveButton )

	var loadState = ''
	$( '.postbox .inside textarea, .fca_qc_input_wide, .fca_qc_text_input' ).each( function( i, textarea ){ loadState += textarea.value } )
	$( '.postbox .inside .onoffswitch-checkbox' ).each( function( i, checkbox ){ loadState += checkbox.checked } )

	$( window ).bind( 'beforeunload', function( e ){
		var unloadState = ''
		$( '.postbox .inside textarea, .fca_qc_input_wide, .fca_qc_text_input' ).each( function( i, textarea ){ unloadState += textarea.value } )
		$( '.postbox .inside .onoffswitch-checkbox' ).each( function( i, checkbox ){ unloadState += checkbox.checked } )
		if( loadState != unloadState ){
			return true
		} else {
			e = null
		}
	})

	$('#fca_qc_submit_button').click(function(event) {
		$( window ).unbind( 'beforeunload' )
	
		// Add target
		var thisForm = $(this).closest('form')
		thisForm.removeAttr('target')

		// Remove preview url
		$('#fca_qc_quiz_preview_url').val('')

	})


	$('#post').submit(function(event) {

		// Submit questions
		fca_qc_save_question_json( $('#post') )

		// Submit results
		fca_qc_save_result_json( $('#post') )

	})
	 
	$( '#normal-sortables' ).append( previewButton )
	$('#fca_qc_preview_button').click(function(event) {
		
		event.preventDefault()

		// Add target
		var thisForm = $(this).closest('form')
		thisForm.prop('target', '_blank')

		// Submit questions
		fca_qc_save_question_json( thisForm )

		// Submit results
		fca_qc_save_result_json( thisForm )

		thisForm.submit()

	})	
	$( '#submitdiv' ).hide()
	
	//HIDE "ADD IMAGE" BUTTONS IF IMAGE HAS BEEN SET
	$('.fca_qc_image').each(function(index){
		if ( $(this).attr('src') !== '' ) {
			$(this).siblings('.fca_qc_quiz_image_upload_btn').hide()
		}
		
	})
	
	// ACTIVATE TOOLTIPS
	jQuery.widget.bridge( 'jQueryUITooltipFCAQC', jQuery.ui.tooltip )
	$('.fca_qc_tooltip').jQueryUITooltipFCAQC({
		position: { my: 'left', at: 'right+2' }
	})
		
	//SHOW OUR MAIN DIV AFTER WE'RE DONE WITH DOM CHANGES
	$( '#wpbody-content').show()

	////////////////
	// ON CLICK EVENT HANDLERS
	////////////////
	
	//THE SELECT QUIZ SCREEN BUTTONS
	$('#fca-quiz-select-multiplechoice').click(function(e){
		selectQuiz('mc')
		setScoreRanges()
	})
	$('#fca-quiz-select-personality').click(function(e){
		selectQuiz('pt')
		setScoreRanges()
	})
	$('#fca-quiz-select-weighted').click(function(e){
		selectQuiz('wq')
		setScoreRanges()
	})

	//NAVIGATION
	$('#editor-nav').click()

	$('#editor-nav').click(function(){
		$('.nav-tab-active').removeClass('nav-tab-active')
		$(this).addClass('nav-tab-active').blur()
		selectQuiz( $('#fca_qc_quiz_type').val() )
	}).click()
	
	$('#settings-nav').click(function(){
		$('.nav-tab-active').removeClass('nav-tab-active')
		$(this).addClass('nav-tab-active').blur()

		$('#fca_qc_description_meta_box, #fca_qc_weighted_questions_meta_box, #fca_qc_add_weighted_result_meta_box, #fca_qc_questions_meta_box, #fca_qc_add_result_meta_box, #fca_qc_personality_questions_meta_box, #fca_qc_add_personality_result_meta_box, #fca_qc_quiz_appearance_meta_box, #fca_qc_startbtn_appearance_meta_box, #fca_qc_answers_appearance_meta_box, #fca_qc_rwpanel_appearance_meta_box, #fca_qc_translations_meta_box, #fca_qc_custom_css_meta_box').hide()
		$('#fca_qc_quiz_settings_meta_box, #fca_qc_quiz_timer_meta_box, #fca_qc_social_sharing_meta_box, #fca_qc_email_optin_meta_box').show()

	})

	$('#appearance-nav').click(function(){
		$('.nav-tab-active').removeClass('nav-tab-active')
		$(this).addClass('nav-tab-active').blur()

		$('#fca_qc_description_meta_box, #fca_qc_weighted_questions_meta_box, #fca_qc_add_weighted_result_meta_box, #fca_qc_questions_meta_box, #fca_qc_add_result_meta_box, #fca_qc_personality_questions_meta_box, #fca_qc_add_personality_result_meta_box, #fca_qc_quiz_settings_meta_box, #fca_qc_quiz_timer_meta_box, #fca_qc_social_sharing_meta_box, #fca_qc_translations_meta_box, #fca_qc_email_optin_meta_box').hide()
		$('#fca_qc_quiz_appearance_meta_box, #fca_qc_startbtn_appearance_meta_box, #fca_qc_answers_appearance_meta_box, #fca_qc_rwpanel_appearance_meta_box, #fca_qc_custom_css_meta_box').show()
		
		// ALWAYS HIDE RWPANEL METABOX IF IT'S NOT MULTIPLE CHOICE
		if ( $('#fca_qc_quiz_type').val() === 'mc' ){
			$('.fca_qc_hide_answers_input').each( function(){
				if ( $(this).prop('checked') && $(this).val() === 'hide' ) {
					$('#fca_qc_rwpanel_appearance_meta_box').hide()
				} else {
					$('#fca_qc_rwpanel_appearance_meta_box').show()
				}
			})
		} else {
			$('#fca_qc_rwpanel_appearance_meta_box').hide()
		}
	})

	$('#translations-nav').click(function(){
		$('.nav-tab-active').removeClass('nav-tab-active')
		$(this).addClass('nav-tab-active').blur()

		$('#fca_qc_quiz_settings_meta_box, #fca_qc_quiz_timer_meta_box, #fca_qc_social_sharing_meta_box, #fca_qc_email_optin_meta_box, #fca_qc_description_meta_box, #fca_qc_weighted_questions_meta_box, #fca_qc_add_weighted_result_meta_box, #fca_qc_questions_meta_box, #fca_qc_add_result_meta_box, #fca_qc_personality_questions_meta_box, #fca_qc_add_personality_result_meta_box, #fca_qc_quiz_appearance_meta_box, #fca_qc_startbtn_appearance_meta_box, #fca_qc_answers_appearance_meta_box, #fca_qc_rwpanel_appearance_meta_box, #fca_qc_custom_css_meta_box').hide()
		$('#fca_qc_translations_meta_box').show()

	})

	//THE ADD QUESTION BUTTON
	$( '#fca_qc_add_question_btn' ).click(function() {

		question_number = question_number + 1

		$( '.fca_qc_question_input_div' ).hide()
		var div_to_append = fcaQcAdminData.questionDiv.replace(/{{QUESTION_NUMBER}}/g, question_number)
		while ( div_to_append.indexOf('{{ID}}') !== -1 ) {
			div_to_append = div_to_append.replace(/{{ID}}/, fca_qc_new_GUID() )
		}

		$(this).siblings( '.fca_qc_sortable_questions' ).append(div_to_append)
		
		add_drag_and_drop_sort()
		add_question_heading_text_handlers()
		add_question_and_result_click_toggles()
		attach_delete_button_handlers()
		attach_image_upload_handlers()
		attach_add_answer_button_handlers_free()
		setScoreRanges()
		setQuestionNumbers( $(this).siblings( '.fca_qc_sortable_questions' ) )
		
		$('#fca_qc_explanations').change()
		
	})
	
	//THE ADD RESULT BUTTON
	$( '#fca_qc_add_result_btn' ).click(function() {

		result_number = result_number + 1
		
		$( '.fca_qc_result_input_div' ).hide()
		var div_to_append = fcaQcAdminData.resultDiv.replace(/{{RESULT_NUMBER}}/g, result_number )
		div_to_append = div_to_append.replace(/{{ID}}/g, fca_qc_new_GUID() )
		$(this).siblings('.fca_qc_sortable_results').append(div_to_append)
		add_drag_and_drop_sort()
		add_question_and_result_click_toggles()
		attach_delete_button_handlers()
		attach_image_upload_handlers()
		add_result_heading_text_handlers()
		setScoreRanges()
		fca_attach_wysiwyg()
	})
	
	//MAKES SHORTCODE INPUT AUTO-SELECT THE TEXT WHEN YOU CLICK IT
	$('.fca_qc_shortcode_input').click(function(e) {
		this.select()
	})
	$('#fca_qc_shortcode_label').click(function(e) {
		$('.fca_qc_shortcode_input').select()
	})
	
	
	//MAKES CLICKING LABELS AUTO-SELECT THE NEXT ITEM
	$('.fca_qc_admin_label').click(function(e) {
		$( this ).next().focus()
	})
	
	add_drag_and_drop_sort()
	add_question_and_result_click_toggles()
	add_question_heading_text_handlers()
	add_result_heading_text_handlers()
	attach_delete_button_handlers()
	attach_add_answer_button_handlers_free()
	setScoreRanges()
	attach_image_upload_handlers()

})

//GLOBAL FUNCTIONS

//SELECT QUIZ

function selectQuiz(quizType){

	var $ = jQuery

	$('#fca_qc_quiz_settings_meta_box, #fca_qc_quiz_timer_meta_box, #fca_qc_social_sharing_meta_box, #fca_qc_email_optin_meta_box, #fca_qc_quiz_appearance_meta_box, #fca_qc_startbtn_appearance_meta_box, #fca_qc_answers_appearance_meta_box, #fca_qc_rwpanel_appearance_meta_box, #fca_qc_translations_meta_box, #fca_qc_custom_css_meta_box').hide()
	$('#fca_qc_description_meta_box, #fca_qc_quiz_type_meta_box').show()

	if ( quizType === 'mc' ){
		$('#fca_qc_quiz_type').val(quizType)
		$('#fca_qc_quiz_type_meta_box').hide()
		$('#fca_qc_add_personality_result_meta_box, #fca_qc_personality_questions_meta_box').hide()
		$('#fca_qc_answer_mode_tr').show()
		$('#fca_qc_questions_meta_box, #fca_qc_add_result_meta_box').show()
		$('#fca_qc_hints_toggle_tr').show()
		$('#fca_qc_weighted_questions_meta_box, #fca_qc_add_weighted_result_meta_box').hide()
	} 
	if ( quizType === 'pt' ){
		$('#fca_qc_quiz_type').val(quizType)
		$('#fca_qc_quiz_type_meta_box').hide()
		$('#fca_qc_questions_meta_box, #fca_qc_add_result_meta_box').hide()
		$('#fca_qc_answer_mode_tr').hide()
		$('#fca_qc_add_personality_result_meta_box, #fca_qc_personality_questions_meta_box').show()
		$('#fca_qc_hints_toggle_tr').hide()
		$('#fca_qc_weighted_questions_meta_box, #fca_qc_add_weighted_result_meta_box').hide()
	} 
	if ( quizType === 'wq' ){
		$('#fca_qc_quiz_type').val(quizType)
		$('#fca_qc_quiz_type_meta_box').hide()
		$('#fca_qc_add_personality_result_meta_box, #fca_qc_personality_questions_meta_box').hide()
		$('#fca_qc_questions_meta_box, #fca_qc_add_result_meta_box').hide()
		$('#fca_qc_answer_mode_tr').hide()
		$('#fca_qc_hints_toggle_tr').hide()
		$('#fca_qc_weighted_questions_meta_box, #fca_qc_add_weighted_result_meta_box').show()
	} 

}

//THE ADD ANSWER BUTTON
function attach_add_answer_button_handlers_free() {
	var $ = jQuery
	$('.fca_qc_add_answer_btn').unbind( 'click' )

	$('.fca_qc_add_answer_btn').click(function() {
		
		var answer_number = parseInt( $(this).siblings('.fca_qc_answer_input_div').length + 1 )
		var question_number = parseInt( $(this).closest('.fca_qc_question_item').attr('id').replace(/[^0-9.]/g, "") )
		
		var div_to_append = fcaQcAdminData.answerDiv.replace(/{{ANSWER_NUMBER}}/g, answer_number )
		div_to_append = div_to_append.replace(/{{QUESTION_NUMBER}}/g, question_number )
		div_to_append = div_to_append.replace(/{{ID}}/g, fca_qc_new_GUID() )
		
		$(this).before(div_to_append)
		attach_delete_button_handlers()

		attach_image_upload_handlers()
		
	})
}


//THE DELETE QUESTION BUTTON
function attach_delete_button_handlers() {
	var $ = jQuery
	
	$('.fca_qc_delete_icon').unbind( 'click' )
	
	$('.fca_qc_delete_icon_confirm').click( function(){	
		
		$( this ).closest( '.' + $(this).data('target') ).remove()
		
		if ( $(this.parentNode).hasClass('fca_qc_personality_result_item') ) {
			add_result_personality_result_text_handlers()
		} else {
			setScoreRanges ()
		}
	})
	
	$('.fca_qc_delete_icon_cancel').click( function(){
		$(this).hide().siblings('.fca_qc_delete_icon').hide()
		$(this).siblings('.fca_qc_delete_button').show()
	})
	
	$('.fca_qc_delete_button').click( function(){
		$(this).siblings('.fca_qc_delete_icon').show()
		$(this).hide()
	})
}
//MAKES RESULT HEADINGS AUTOMATICALLY SHOW THE RESULT TITLE FROM THE INPUT BELOW IT
function add_result_heading_text_handlers() {
	var $ = jQuery
	$('.fca_qc_quiz_result').unbind( 'keyup' )

	$( '.fca_qc_quiz_result' ).keyup( function() {
		$( this ).closest( '.fca_qc_result_input_div').siblings( '.fca_qc_result_label').children( '.fca_qc_result_score_title' ).html( $( this ).val() )
	})	
	setScoreRanges()
}
//MAKES QUESTION HEADINGS AUTOMATICALLY SHOW THE QUESTION FROM THE INPUT BELOW IT
function add_question_heading_text_handlers() {
	var $ = jQuery
	$( '.fca_qc_question_text' ).unbind( 'keyup' )
	$( '.fca_qc_question_text' ).keyup( function() {
		$( this ).closest( '.fca_qc_question_input_div').prev().children( '.fca_qc_quiz_heading_text' ).html( $( this ).val() )
	})	
}
//MAKES QUESTION AND RESULT LABELS TOGGLE THE INPUT VISIBILITY ON CLICK
function add_question_and_result_click_toggles() {
	var $ = jQuery
	$( '.fca_qc_question_item, .fca_qc_result_item' ).unbind( 'click' )

	$( '.fca_qc_question_item' ).click( function(e) {
		var trash = $(e.target).hasClass('fca_qc_delete_icon')
		if ( dragCheck === false && !trash ) {
			$( '.fca_qc_question_input_div' ).not($( this ).find( '.fca_qc_question_input_div' )).hide()
			$( this ).find( '.fca_qc_question_input_div' ).toggle( 'fast' )
		}
			
	})	
	$( '.fca_qc_result_item' ).click( function(e) {
		var trash = $(e.target).hasClass('fca_qc_delete_icon')
		if ( dragCheck === false && !trash ) {
			$( '.fca_qc_result_input_div' ).not($( this ).find( '.fca_qc_result_input_div' )).hide()
			$( this ).find( '.fca_qc_result_input_div' ).toggle( 'fast' )	
		}
				
	})	
	
	$( '.fca_qc_question_input_div, .fca_qc_result_input_div, .fca_qc_delete_icon' ).bind( 'click', function(e) {
		e.stopPropagation()
	})
	
}

////////////////
// HELPER FUNCTIONS
////////////////

function fca_qc_save_question_json( thisForm ) {
	
	var $ = jQuery

	$('#editor-nav').click()
	
	var questions = []
	
	var $questionList = $( '.fca_qc_question_item:visible' )

	$( $questionList ).each(function(){
		
		//FIND CORRECT ANSWER LOCATION
		correctAnswer = 1
		answer = 1
		$(this).find('.fca_qc_answer_input_div').each( function(){
			if ( $(this).find('.fca_qc_answer_header').text() === 'Correct Answer' ){
				correctAnswer = answer
				var div_to_append = fcaQcAdminData.answerDiv.replace(/{{CORRECT_ANSWER}}/g, correctAnswer )
			}
			answer+=1
		})

		var answers = []
		$(this).find('.fca_qc_answer_input_div').each(function(){
			
			answers.push({
				"answer": $(this).find('.fca_qc_question_texta').val(),
				"img": $(this).find('.fca_qc_image_input').val(),
				"id": $(this).find('.fca_qc_id').val(),
				"points": $(this).find('.fca_qc_weighted_question_points').val(),
				"hint":	$(this).find('.fca_qc_hint_tr .fca_qc_question_texta').val(),
				"results":	$(this).find('.fca_qc_multiselect').val()
			})
		})
			
		questions.push({
			"question": $(this).find('.fca_qc_question_text').val(),
			"img": $(this).find('.fca_qc_image_input').val(),
			"correct": correctAnswer,
			"id": $(this).find('.fca_qc_id').val(),
			"answers" : answers		
		})
		
	})
	
	$('#fca_qc_questions_json').val( JSON.stringify( questions ) )
	
}


function fca_qc_save_result_json( thisForm ) {
	
	var $ = jQuery
	var results = []
	
	$( '.fca_qc_result_item:visible' ).each( function() {

		results.push({
			"title": $(this).find('.fca_qc_quiz_result').val(),
			"desc": $(this).find('.fca-wysiwyg-html').val(),
			"img": $(this).find('.fca_qc_image').attr('src'),
			"min": $(this).find('.fca_qc_result_min').val(),
			"max":	$(this).find('.fca_qc_result_max').val(),
			"url":	$(this).find('.fca_qc_url_input').val(),
			"tags":	$(this).find('.fca_qc_results_tags').val(),
			"groups":	$(this).find('.fca_qc_mailchimp_groups').val(),
			"id":	$(this).find('.fca_qc_id').val(),
		})
		
	})

	$('#fca_qc_results_json').val( JSON.stringify( results ) )

}


//FINDS RANGE OF RESULTS FOR EACH RESULT AUTOMATICALLY.
//results -> based on question count, divided by result count, with rounding to cover all
//e.g. 5 ANSWERS, 3 RESULTS = [0-1],[2-3],[4-5]
//at max ( equal to questions ) -> remove ability to add more
//when question or result count changes, have to re-calculate
function setScoreRanges() {
	var $ = jQuery
	var maxPoints = $( '.fca_qc_question_item:visible' ).length
	var resultCount = $( '.fca_qc_result_item:visible' ).length
	//WEIGHTED ANSWERS, SUM THE MAX
	if ( $('#fca_qc_quiz_type').val() === 'wq' ) {
		var maximumPerQuestion = []
		$( '.fca_qc_question_item:visible' ).each(function(){
			var maximumForThisQuestion = 0
			$(this).find('.fca_qc_weighted_question_points').each(function(){
				if ( Number( $(this).val() ) > maximumForThisQuestion ) {
					maximumForThisQuestion = $(this).val()
				}
			})
			maximumPerQuestion.push( maximumForThisQuestion )
		})
		maxPoints = maximumPerQuestion.reduce( function( accumulator, current ) {
			return ( Number(accumulator) + Number(current) )
		})
	}
	var remainder = maxPoints % resultCount
	$( '.fca_qc_result_item:visible' ).each( function( index, item ){
		var min = 0
		if ( index > 0 ) {
			min = 1 + Number( $(item).prev().find('.fca_qc_result_max').val() )
		}
		if ( remainder ){
			if ( index <= remainder ){
				var max = min + ( Math.floor( maxPoints / resultCount ) )
			} else {
				var max = ( min - 1 ) + ( Math.floor( maxPoints / resultCount ) )
			}
		} else {
			var max = ( index + 1 ) * ( Math.floor( maxPoints / resultCount ) )
		}
		$(item).find( '.fca_qc_result_min' ).val( min )
		$(item).find( '.fca_qc_result_max' ).val( max )	
		var displayString = min + '-' + max + ' ' + fcaQcAdminData.points_string + ': '
		if ( min === max ) {
			 displayString = min + ' ' + fcaQcAdminData.points_string + ': '
		}
		if ( min > max ){
			displayString = fcaQcAdminData.unused_string
		}
		$(item).find('.fca_qc_result_score_value').html( displayString )
	})
}


function setQuestionNumbers( $set ){
	var $ = jQuery
	var n = 1;
	$set.find('.fca_qc_question_item').each(function() {
		$(this).find( '.fca_qc_quiz_heading_question_number' ).html( fcaQcAdminData.question_string + ' ' + n + ': ')
		n = n + 1
	})
}

////////////////
// MEDIA UPLOAD
////////////////
		
function attach_image_upload_handlers() {
	var $ = jQuery
	//ACTION WHEN CLICKING IMAGE UPLOAD
	$('.fca_qc_quiz_image_upload_btn, .fca_qc_image, .fca_qc_quiz_image_change_btn').unbind( 'click' )
	//HANDLER FOR RESULTS AND META IMAGES
	$('.fca_qc_quiz_image_upload_btn, .fca_qc_image, .fca_qc_quiz_image_change_btn').click(function(e) {
		
		e.preventDefault()
		var $this = $( this )
		
		//IF WE CLICK ON THE IMAGE VS THE BUTTON IT HAS TO WORK A LITTLE DIFFERENTLY
		if ( $(this).hasClass( 'fca_qc_quiz_image_change_btn' ) ) {
			$this = $( this.parentNode ).siblings('.fca_qc_quiz_image_upload_btn')
		} else if ( $(this).hasClass( 'fca_qc_image' ) ) {
			$this = $( this ).siblings('.fca_qc_quiz_image_upload_btn')
		}
		
		var wpmedia = wp.media( {
			frame: "post",
			title: fcaQcAdminData.selectImage_string,
			multiple: false
		}).open()
			.on('insert', function(){
				//GET VALUE FROM WP MEDIA UPLOAD THING
				var image = wpmedia.state().get('selection').first()
				var display = wpmedia.state().display( image ).toJSON()
				image = image.toJSON()
				//Do something with attachment.id and/or attachment.url here
				var image_url = image.sizes[display.size].url
	
				//ASSIGN VALUE
				if ( image_url ) {
					$this.siblings('.fca_qc_image_input').attr('value', image_url)
					$this.siblings('.fca_qc_image').attr('src',image_url)	
					//UNHIDE THE REMOVE AND CHANGE IMAGE BUTTONS
					$this.siblings('.fca_qc_image_hover_controls').find('.fca_qc_quiz_image_change_btn').show()
					$this.siblings('.fca_qc_image_hover_controls').find('.fca_qc_quiz_image_revert_btn').show()
				}

				$this.hide()
				
			})
	})
	
	//ACTION WHEN CLICKING REMOVE IMAGE
	$('.fca_qc_quiz_image_revert_btn').unbind( 'click' )
	$('.fca_qc_quiz_image_revert_btn').click( function(e) {
		$( this.parentNode ).siblings('.fca_qc_image_input').attr('value', '')
		$( this.parentNode ).siblings('.fca_qc_image').attr('src', '' )
		$( this.parentNode ).siblings('.fca_qc_quiz_image_upload_btn').show()
		$( this ).hide()
		$( this ).siblings( '.fca_qc_quiz_image_upload_btn' ).hide()
		
	})
}

//DRAG AND DROP SUPPORT
var dragCheck = false;
var screen_y = 0
function add_drag_and_drop_sort() {
	var $ = jQuery

	// fix moving screen glitch on drag
	$( '.fca_qc_sortable_questions, .fca_qc_sortable_results' ).on( 'mouseover', function(){
		screen_y = window.scrollY
	})

	$( '.fca_qc_sortable_results, .fca_qc_sortable_questions, .fca_qc_question_input_div' ).sortable({
		revert: true,
		cancel: ':input,button, .fca-wysiwyg-html',
		start: function( e ){
			window.scrollTo( 0, screen_y )
			// On drag set that flag to true
			dragCheck = true
		},
		stop: function( ){
			// On stop of dragging reset the flag back to false
			dragCheck = false
			//REDRAW WYSIWYG CAUSE IT BREAKS DRAG
			if( $( this ).hasClass( 'fca_qc_sortable_results' ) ){
				fca_attach_wysiwyg()
			}
		}

	})

	$( '.fca_qc_sortable_results' ).unbind( 'sortupdate' )
	$( '.fca_qc_sortable_results' ).on( 'sortupdate', function( event, ui ) {
		setScoreRanges()
	})


	$( '.fca_qc_sortable_questions' ).unbind( 'sortupdate' )
	$( '.fca_qc_sortable_questions' ).on( 'sortupdate', function( event, ui ) {
		setQuestionNumbers( $(this) )
	})

}

//GUID Generation ( http://stackoverflow.com/questions/105034/create-guid-uuid-in-javascript/21963136#21963136 )
var fca_qc_hash_seed = []
for (var i=0; i<256; i++) { 
	fca_qc_hash_seed[i] = (i<16?'0':'')+(i).toString(16)
}
function fca_qc_new_GUID() {
	var d0 = Math.random()*0x100000000>>>0
	var d1 = Math.random()*0x100000000>>>0
	var d2 = Math.random()*0x100000000>>>0
	var d3 = Math.random()*0x100000000>>>0
	
	return fca_qc_hash_seed[d0&0xff]+fca_qc_hash_seed[d0>>8&0xff]+fca_qc_hash_seed[d0>>16&0xff]+fca_qc_hash_seed[d0>>24&0xff]+'-'+
	fca_qc_hash_seed[d1&0xff]+fca_qc_hash_seed[d1>>8&0xff]+'-'+fca_qc_hash_seed[d1>>16&0x0f|0x40]+fca_qc_hash_seed[d1>>24&0xff]+'-'+
	fca_qc_hash_seed[d2&0x3f|0x80]+fca_qc_hash_seed[d2>>8&0xff]+'-'+fca_qc_hash_seed[d2>>16&0xff]+fca_qc_hash_seed[d2>>24&0xff]+
	fca_qc_hash_seed[d3&0xff]+fca_qc_hash_seed[d3>>8&0xff]+fca_qc_hash_seed[d3>>16&0xff]+fca_qc_hash_seed[d3>>24&0xff]
}
//RADIO INPUT SELECT JS	
function fca_qc_attach_radio_selects() {
	var $ = jQuery
	$('.qc_radio_input').change(function() {
		$(this).closest('.radio-toggle').children('label').removeClass('selected')
		$(this).closest('label').addClass('selected')
	})
}