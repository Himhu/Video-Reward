CKEDITOR.editorConfig = function (config) {
    config.language = 'zh-cn';
    config.image_previewText = ' ';
    config.height = 300;
    config.width = 'auto';
    config.toolbarGroups = [
        {name: 'document', groups: ['mode', 'document', 'doctools']},
        {name: 'styles', groups: ['Font', 'FontSize']},
        {name: 'colors'},
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
        {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi']},
        {name: 'insert'},
        {name: 'others'},
        {name: 'forms'},
        {name: 'links'},
        {name: 'clipboard', groups: ['clipboard', 'undo']},
        { name: 'insert', groups: [ 'EasyImageUpload' ] },
        {name: 'tools'},
    ];
    config.filebrowserImageUploadUrl = config.filebrowserImageUploadUrl || "/admin/ajax/uploadEditor";

    config.extraPlugins = 'button,panelbutton,colorbutton';

    config.removeButtons = 'Underline,Subscript,Superscript';

    config.format_tags = 'p;h1;h2;h3;pre';

    config.removeDialogTabs = 'image:advanced;link:advanced';

    config.colorButton_colors = '00923E,F8C100,28166F';

    config.colorButton_colors = 'FontColor1/FF9900,FontColor2/0066CC,FontColor3/F00';


    //设置编辑内元素的背景色的取值 plugins/colorbutton/plugin.js.
    config.colorButton_backStyle = {
        element : 'span',
        styles : { 'background-color' : '#(color)' }
    }
    config.colorButton_colors = '000,800000,8B4513,2F4F4F,008080,000080,4B0082,696969,B22222,A52A2A,DAA520,006400,40E0D0,0000CD,800080,808080,F00,FF8C00,FFD700,008000,0FF,00F,EE82EE,A9A9A9,FFA07A,FFA500,FFFF00,00FF00,AFEEEE,ADD8E6,DDA0DD,D3D3D3,FFF0F5,FAEBD7,FFFFE0,F0FFF0,F0FFFF,F0F8FF,E6E6FA,FFF'
//是否在选择颜色时显示“其他颜色”选项 plugins/colorbutton/plugin.js
    config.colorButton_enableMore = false
//区块的前景色预设值设置 plugins/colorbutton/plugin.js
    config.colorButton_foreStyle = {
        element : 'span',
        styles : { 'color' : '#(color)' }
    };
// CKEditor color palette available before version 4.6.2.
    config.colorButton_colors =
        '000,800000,8B4513,2F4F4F,008080,000080,4B0082,696969,' +
        'B22222,A52A2A,DAA520,006400,40E0D0,0000CD,800080,808080,' +
        'F00,FF8C00,FFD700,008000,0FF,00F,EE82EE,A9A9A9,' +
        'FFA07A,FFA500,FFFF00,00FF00,AFEEEE,ADD8E6,DDA0DD,D3D3D3,' +
        'FFF0F5,FAEBD7,FFFFE0,F0FFF0,F0FFFF,F0F8FF,E6E6FA,FFF';

    config.font_names = '微软雅黑/Microsoft YaHei;宋体/SimSun;新宋体/NSimSun;仿宋/FangSong;楷体/KaiTi;黑体/SimHei;' + config.font_names;

};
