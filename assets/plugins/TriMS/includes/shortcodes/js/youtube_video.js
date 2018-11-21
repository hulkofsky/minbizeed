/**
 * Add YouTube video shortcode js
 *
 * @package  Rotana
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 */
(function() {
   tinymce.create('tinymce.plugins.youtube_video', {
      init : function(ed, url) {
         ed.addButton('youtube_video', {
            title : 'YouTube Video',
            image : url+'/../images/youtube.png',
            onclick : function() {
               var video_id = prompt("YouTube Video ID", "");

               if (video_id != null){
                     ed.execCommand('mceInsertContent', false, '[youtube_video id="'+video_id+'"]');
                 }
            }
         });
      },
      createControl : function(n, cm) {
         return null;
      },
      getInfo : function() {
         return {
            longname : "YouTube Video",
            author : 'Triangle Mena',
            authorurl : 'http://trianglemena.com',
            infourl : '',
            version : "1.0"
         };
      }
   });
   tinymce.PluginManager.add('youtube_video', tinymce.plugins.youtube_video);
})();