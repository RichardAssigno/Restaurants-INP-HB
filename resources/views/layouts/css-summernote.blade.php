
<link rel="stylesheet" href="{{asset("assets/summernote/css/summernote.min.css")}}">
<!-- codemirror -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.41.0/codemirror.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.41.0/theme/blackboard.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.41.0/theme/monokai.min.css">

<style>
    /* CSS pour l'éditeur Summernote sans bordures */
    .note-editor .note-editable {
        background-color: #ffffff;
        border: none !important;
        box-shadow: none !important;
        padding: 10px;
    }

    .note-editor .note-editable {
        border-radius: 5px;
    }

    .note-editor {
        border: none !important;
    }

    /* Enlever les bordures des tableaux */
    .note-editor table {
        border: none !important;
        border-collapse: collapse;
    }

    .note-editor table td,
    .note-editor table th {
        border: none !important;
    }

    /* Espacement entre les cellules du tableau */
    .note-editor table td,
    .note-editor table th {
        padding: 8px;
    }



</style>
