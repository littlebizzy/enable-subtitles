jQuery(document).ready(function($) {

	$('#title').on('keydown', function(e) {
		if (9 === e.keyCode && !e.ctrlKey && !e.altKey && !e.shiftKey) {
			$('#enbstl-subtitle').focus();
			e.preventDefault();
		}
	});

	$('#enbstl-subtitle').on('keydown.editor-focus', function(e) {
		if (9 === e.keyCode && !e.ctrlKey && !e.altKey && !e.shiftKey) {
			var editor, $textarea;
			editor = 'undefined' !== typeof tinymce && tinymce.get( 'content' );
			$textarea = $('#content');
			if (editor && !editor.isHidden()) {
				editor.focus();
			} else if ($textarea.length) {
				$textarea.focus();
			} else {
				return;
			}
			e.preventDefault();
		}
	});

	$('#enbstl-subtitle').attr('data-placeholder', $('#enbstl-subtitle').attr('placeholder'));

	$('#enbstl-subtitle').focus(function() {
		$('#enbstl-subtitle').attr('placeholder', '');
	});

	$('#enbstl-subtitle').blur(function() {
		$('#enbstl-subtitle').attr('placeholder', $('#enbstl-subtitle').attr('data-placeholder'));
	});

});