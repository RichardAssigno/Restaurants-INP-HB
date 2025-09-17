<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.41.0/codemirror.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.41.0/mode/xml/xml.min.js"></script>

<!-- JS de Summernote -->
<script src="{{asset("assets/summernote/js/summernote.min.js")}}"></script>
<script>

    $(document).ready(function() {
        $('#content').summernote({
            height: 500,
            codemirror: {
                mode: "text/html",
                htmlMode: true,
                lineNumbers: true,
                theme: "monokai",
            },
            styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'pre', 'blockquote'],
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']], // Active le support des tables
                ['view', ['codeview', 'help']] // Ajoute le mode "Code View"
            ]
        });
    });


</script>
