/**
 * Add VOD video shortcode js
 *
 * @package  Rotana
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 */
(function () {
    tinymce.create('tinymce.plugins.vod_video', {
        init: function (ed, url) {
            ed.addButton('vod_video', {
                title: 'VOD Video',
                image: url + '/../images/vod.png',
                onclick: function () {
                    var video_id = prompt("VOD Video ID", "");
                    var autoplay = confirm("Autoplay?", "");
                    if(autoplay){
                        autoplay=1;
                    }else{
                        autoplay=0;
                    }
                    if (video_id != null) {
                        ed.execCommand('mceInsertContent', false, '[vod_video id="' + video_id + '" autoplay="'+autoplay+'"]');
                    }
                }
            });
        },
        createControl: function (n, cm) {
            return null;
        },
        getInfo: function () {
            return {
                longname: "VOD Video",
                author: 'Triangle Mena',
                authorurl: 'http://trianglemena.com',
                infourl: '',
                version: "1.0"
            };
        }
    });
    tinymce.PluginManager.add('vod_video', tinymce.plugins.vod_video);
})();