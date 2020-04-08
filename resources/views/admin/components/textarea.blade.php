<div class="form-group form-float">
    <div class="form-line">
        <textarea rows="5" class="form-control" id="{{$name}}" name="{{$name}}" {{isset($required) ? 'required' : ''}} >{{old($name,isset($value) ? $value : '')}}</textarea>
        <label class="form-label" for="{{$name}}">{{$label}}</label>
    </div>
</div>
@push('css')
    <style>
        textarea{
            height: auto;
        }
    </style>
@endpush

@if(isset($tinymce) && $tinymce=='first')
    @push('scripts')
        <script src="/dashboard/vendors/tinymce/jquery.tinymce.min.js"></script>
        <script src="/dashboard/vendors/tinymce/tinymce.min.js"></script>
        <script>
            var editor = {
                path_absolute : "/",
                selector: ".tinymce-editor",
                height: 200,
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern"
                ],
                content_css : "/dashboard/css/tinymce.css",
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                extended_valid_elements : "iframe[src|frameborder|style|scrolling|class|width|height|name|align]",
                relative_urls: false,
                remove_script_host : false,
                convert_urls : true,
                file_browser_callback : function(field_name, url, type, win) {
                    var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                    var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;
                    var cmsURL = editor.path_absolute + 'files?field_name=' + field_name;
                    if (type == 'image') {
                        cmsURL = cmsURL + "&type=Images";
                    } else {
                        cmsURL = cmsURL + "&type=Files";
                    }

                    tinyMCE.activeEditor.windowManager.open({
                        file : cmsURL,
                        title : 'Filemanager',
                        width : x * 0.8,
                        height : y * 0.8,
                        resizable : "yes",
                        close_previous : "no"
                    });
                }
            };

            tinymce.init(editor);
        </script>
    @endpush
@endif