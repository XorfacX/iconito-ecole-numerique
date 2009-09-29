
<script>
var format = '{$format}';
function sendPhoto( album, photo, ext, nom ) {ldelim}
	var align='';
	var size='_s128';
	var popup = false;
	var form = getRef('form');
	
	if( form.align[0].checked ) align='';
	if( form.align[1].checked ) align='L';
	if( form.align[2].checked ) align='C';
	if( form.align[3].checked ) align='R';

	if( form.size[0].checked ) size='_s64';
	if( form.size[1].checked ) size='_240';
	if( form.size[2].checked ) size='_480';

	if( form.zoom.checked ) popup = urlBase+'static/album/'+album+'/'+photo+'.'+ext;
	
	img_width=img_height='';
	switch (format) {ldelim}
		case 'wiki' :
			window.opener.add_photo('{$field}',urlBase+'static/album/'+album+'/'+photo+size+'.'+ext+'|'+nom+'|'+align, popup);
			break;

		case 'dokuwiki' :
			window.opener.add_photo ('{$field}',urlBase+'static/album/'+album+'/'+photo+size+'.'+ext,nom,align,popup);
			break;

		case 'fckeditor' :
		case 'html' :
			var html = '<img alt="'+nom+'" border="0" src="'+urlBase+'static/album/'+album+'/'+photo+size+'.'+ext+'"';
			if 			(align == 'L')	html += ' align="left"';
			else if (align == 'R')	html += ' align="right"';
			html += '/>';
			if (popup)
				html = '<a target="_blank" href="'+urlBase+'static/album/'+album+'/'+photo+'.'+ext+'">'+html+'</a>';
			if (format == 'fckeditor')
				window.opener.add_photo_fckeditor ('{$field}', html);
			else
				window.opener.add_html ('{$field}', html);
			break;
			
		default :
			alert ('Format '+format+' non g�r�');
			break;
	{rdelim}
		
	if( ! form.multi.checked ) self.close();
{rdelim}
</script>


<div id="header">
<form name="form" id="form">

<span style="white-space: nowrap;">
<b>{i18n key="album.popup.align"}</b>

<input id="align-none" type="radio" name="align" value="" checked />
<label for="align-none"><img src="{copixresource path="img/album/album_popup_align_none.gif"}" alt="{i18n key="album.popup.align_none"}" /></label>

<input id="align-left" type="radio" name="align" value="left" />
<label for="align-left"><img src="{copixresource path="img/album/album_popup_align_left.gif"}" alt="{i18n key="album.popup.align_left"}" /></label>

<input id="align-center" type="radio" name="align" value="center" />
<label for="align-center"><img src="{copixresource path="img/album/album_popup_align_center.gif"}" alt="{i18n key="album.popup.align_center"}" /></label>

<input id="align-right" type="radio" name="align" value="right" />
<label for="align-right"><img src="{copixresource path="img/album/album_popup_align_right.gif"}" alt="{i18n key="album.popup.align_right"}" /></label>
&nbsp;&nbsp;|&nbsp;&nbsp;
</span>

<span style="white-space: nowrap;">
<b>{i18n key="album.popup.size"}</b>
<input id="size-small" type="radio" name="size" value="small" checked />
<label for="size-small"><img src="{copixresource path="img/album/album_popup_size_s.gif"}" alt="{i18n key="album.popup.size_small"}" /></label>
<input id="size-medium" type="radio" name="size" value="medium" />
<label for="size-medium"><img src="{copixresource path="img/album/album_popup_size_m.gif"}" alt="{i18n key="album.popup.size_middle"}" /></label>
<input id="size-large" type="radio" name="size" value="large" />
<label for="size-large"><img src="{copixresource path="img/album/album_popup_size_l.gif"}" alt="{i18n key="album.popup.size_large"}" /></label>
&nbsp;&nbsp;|&nbsp;&nbsp;
</span>

<span style="white-space: nowrap;">
<b>{i18n key="album.popup.zoom"}</b>
<input id="zoom-yes" type="checkbox" name="zoom" value="yes" />
<label for="zoom-yes"><img src="{copixresource path="img/album/album_popup_zoom.gif"}" alt="{i18n key="album.popup.zoom_yes"}" /></label>
&nbsp;&nbsp;|&nbsp;&nbsp;
</span>

<span style="white-space: nowrap;">
<b>{i18n key="album.popup.multi"}</b>
<input id="multi-yes" type="checkbox" name="multi" value="yes" />
<label for="multi-yes"><img src="{copixresource path="img/album/album_popup_multi.gif"}" alt="{i18n key="album.popup.multi_yes"}" /></label>
</span>

</form>
</div>


{$PHOTOS}


