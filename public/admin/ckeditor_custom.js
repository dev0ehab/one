function CKEDITORRtl(name)
{
    CKEDITOR.replace(name,{
        filebrowserUploadMethod: 'form',
        contentsLangDirection:'rtl',
        language: 'ar',
        removePlugins:'image,flash,iframe,smiley,about',
    });
}

function CKEDITORLtr(name)
{
    CKEDITOR.replace(name,{
        filebrowserUploadMethod: 'form',
        contentsLangDirection:'ltr',
        language: 'ar',
        removePlugins:'image,flash,iframe,smiley,about',
    });
}