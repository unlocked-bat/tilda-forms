1. Переименовать файл htaccess в .htaccess (с точкой). Находится в корне сайта.
2. Загрузить в корень сайта:
    - Папку lib
    - Файл mail.php 
3. Открыть файл mail.php заменить значение переменной $to_email (строка 44) на свою почту для получения писем.
4. Открыть html-файл с формой (pageXXXXXX.html): 
    - Заменить код <form id на <form enctype="multipart/form-data" id
    - Найти тег <input type="text" type="hidden" role="upwidget-uploader" ...> и полностью заменить его кодом:

<input type="file" name="files[]" multiple style="display: none;"><div class="t-upwidget-container__button t-text"></div><script>jQuery(document).ready(function($) {var btn = $('.t-upwidget-container__button'); var filesInput = $('[name="files[]"]'); var isMultiple = filesInput.attr('multiple') ? true : false; var text = ['Выбрать файл', 'Выбрать файлы']; btn.text(isMultiple ? text[1] : text[0]); btn.on('click', function(e) {e.preventDefault(); filesInput.click(); }); filesInput.on('change', function(e) {e.preventDefault(); var files = $(this)[0].files; if (isMultiple) {btn.text('Выбрано ' + files.length + ' файла(ов)'); } else {btn.text(files[0]['name']); } }); });</script>

5. Открыть файл /js/tilda-forms-1.0.min.js:
  Найти:
    $formurl='https://forms.tildacdn.com/procces/';$.ajax({type:"POST",url:$formurl,data:$jform.serialize(),dataType:"json",
	Заменить на:
    $formurl=window.location.origin;formData=new FormData($jform[0]);$.ajax({type:"POST",url:$formurl,data:formData,dataType:"json",contentType:false,processData:false, 




