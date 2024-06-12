//<!--//Feito Pelo Dj Mib - www.djmib.net

var urlRegExp = /\b(https?:\/\/)?((?:[-a-z0-9]{1,63}\.)+[-a-z0-9]{2,63}|(?:[0-9]{1,3}\.){3}[0-9]{1,3})(:[0-9]{1,5})?(\/[!$-/0-9:;=@_\':;!a-z\x7f-\xff]*?)?(\?[!$-/0-9:;=@_\':;!a-z\x7f-\xff]+?)?(#[!$-/0-9:;=@_\':;!a-z\x7f-\xff]+?)?(?=[?.!,;:\"]?(\s|$))/g;
// var urlRegExp = /(?:(?=(?:http|https):)([a-zA-Z][-+.a-zA-Z\d]*):(?:((?:[-_.!~*'()a-zA-Z\d;?:@&=+$,]|%[a-fA-F\d]{2})(?:[-_.!~*'()a-zA-Z\d;\/?:@&=+$,\[\]]|%[a-fA-F\d]{2})*)|(?:(?:\/\/(?:(?:(?:((?:[-_.!~*'()a-zA-Z\d;:&=+$,]|%[a-fA-F\d]{2})*)@)?(?:((?:(?:(?:[a-zA-Z\d](?:[-a-zA-Z\d]*[a-zA-Z\d])?)\.)*(?:[a-zA-Z](?:[-a-zA-Z\d]*[a-zA-Z\d])?)\.?|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}|\[(?:(?:[a-fA-F\d]{1,4}:)*(?:[a-fA-F\d]{1,4}|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})|(?:(?:[a-fA-F\d]{1,4}:)*[a-fA-F\d]{1,4})?::(?:(?:[a-fA-F\d]{1,4}:)*(?:[a-fA-F\d]{1,4}|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}))?)\]))(?::(\d*))?))?|((?:[-_.!~*'()a-zA-Z\d$,;:@&=+]|%[a-fA-F\d]{2})+))|(?!\/\/))(\/(?:[-_.!~*'()a-zA-Z\d:@&=+$,]|%[a-fA-F\d]{2})*(?:;(?:[-_.!~*'()a-zA-Z\d:@&=+$,]|%[a-fA-F\d]{2})*)*(?:\/(?:[-_.!~*'()a-zA-Z\d:@&=+$,]|%[a-fA-F\d]{2})*(?:;(?:[-_.!~*'()a-zA-Z\d:@&=+$,]|%[a-fA-F\d]{2})*)*)*)?)(?:\?((?:[-_.!~*'()a-zA-Z\d;\/?:@&=+$,\[\]]|%[a-fA-F\d]{2})*))?)(?:\#((?:[-_.!~*'()a-zA-Z\d;\/?:@&=+$,\[\]]|%[a-fA-F\d]{2})*))?)/img;
// var urlRegExp = /((ftp|http|https):\/\/)?(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/ig;
function isValidURL(url){
	if(urlRegExp.test(url)){
		return true;
	}else{
		return false;
	}
}

function trataURL(url){
	if(isValidURL(url)){
		return url;
	}else{
		url = "http://"+url;
		if(isValidURL(url)){
			return url;
		}
	}
	return false;
}

			// $.get("/previewlink?url="+url, function(response){
			// 	})
			// 	.done(function(response){
			// 		if(typeof(response)=="object")
			// 			txt.replace(url, "<a href=\""+response.url+"\">"+response.host+"</a>");
			// 	});
function encontraURL(txt){
	var foi = false;
	txt = txt.replace(urlRegExp, function(url){
		if(trataURL(url)){
			foi = true;
			return '<a href="'+trataURL(url)+'">'+url+'</a>';	
		}
		return url;
	});
	if(foi)
		return txt;
	return false;
}

function editorAltera(evt){
	var editor = $(this)[0];
	editor.removeListener("change", editorAltera);
	var texto = encontraURL(evt.editor.getData());
	if(texto!=false){
		editor.setData(texto);
	}
	editor.on("change", editorAltera);
}
var objteste;

$(function() {
    
	/**
	 * Menu Principal do Topo
	 * 
	 * SmartMenus jQuery init
	 */
	$('#main-menu').smartmenus({
		subMenusSubOffsetX: 1,
		subMenusSubOffsetY: -8
	});
	$('#main-menu').smartmenus('keyboardSetHotkey', 123, 'shiftKey');

	// SmartMenu mobile menu toggle button
	var $mainMenuState = $('#main-menu-state');
	if ($mainMenuState.length) {
		// animate mobile menu
		$mainMenuState.change(function(e) {
		var $menu = $('#main-menu');
		if (this.checked) {
			$menu.hide().slideDown(250, function() { $menu.css('display', ''); });
		} else {
			$menu.show().slideUp(250, function() { $menu.css('display', ''); });
		}
		});
		// hide mobile menu beforeunload
		$(window).bind('beforeunload unload', function() {
			if ($mainMenuState[0].checked) {
				$mainMenuState[0].click();
			}
		});
	}

	//Fixando Menu na Posição

	$(window).scroll(function () {
        if($(this).scrollTop()>$("#ilhapqt>header").outerHeight()){
			$("nav.main-nav").addClass("menufixado");
			$(".nav-brand").show(400);
		}else if($(this).scrollTop()<($("#ilhapqt>header").outerHeight()-50)){
			$("nav.main-nav").removeClass("menufixado");
			$(".nav-brand").hide(400);
		}
    });

	var destaquesPQT = $("#noticiasdestaques");
	if(destaquesPQT.length){
		$.getScript(strPaginaDir+"unitegallery/themes/grid/ug-theme-grid.min.js")
			.done(function(script, status){
				// $.getJSON(strPaginaDir+"noticiasdestaques/", function(response){})
				// 	.done(function(response){
				// destaquesPQT.html(response.noticias);
			// });
			
				destaquesPQT.unitegallery({
					gallery_theme: "grid",
					gallery_height:250,
					gallery_autoplay:true,
					gallery_play_interval: 5000,
					gallery_pause_on_mouseover: true,
					slider_enable_zoom_panel: false,
					slider_enable_fullscreen_button: false,
					slider_textpanel_bg_color:"#000000",
					slider_textpanel_bg_opacity: .8,
					grid_num_cols: 1,
				});
			});
	}

	var parceirosPQT = $("#carouselparceiros");
	if(parceirosPQT.length){
		$.getScript(strPaginaDir+"unitegallery/themes/carousel/ug-theme-carousel.min.js")
			.done(function(script, status){
				parceirosPQT.unitegallery({
					gallery_theme: "carousel",
					tile_enable_textpanel:true,
					tile_textpanel_title_text_align: "center",
					tile_textpanel_always_on:true,
					tile_as_link:true,
					tile_link_newpage: false,
					
				});
			});
	}

	var galeriaPQT = $("#galeriapqt");
	if(galeriaPQT.length){
		$.getScript(strPaginaDir+"unitegallery/themes/tiles/ug-theme-tiles.min.js")
			.done(function(script, status){
				galeriaPQT.unitegallery({
					tile_enable_image_effect:true,
					tile_image_effect_reverse:true,
					tiles_type: "justified",
				});
			});
	}

	var noticiaTextarea = $("#noticia");
	if(noticiaTextarea.length){
			$.getScript(strPaginaDir+"ckeditor/ckeditor.js")
			.done(function(script, status){
				if(status=="success"){
					var editor = CKEDITOR.replace('noticia',{
						customConfig: strPaginaDir+'ckeditor/config.js',
					});

					// editor.on("change", editorAltera);
					
					$.getScript(strPaginaDir+"ckfinder/ckfinder.js")
					.done(function(script, status){
						if(status=="success"){
							CKFinder.setupCKEditor();
						}
					});
				}
			});
	}

	var elementUpload = $("#traditional-uploader");
	if(elementUpload.length){
		var traditionalUploader = new qq.FineUploader({
			element: elementUpload[0],
			request: {
				endpoint: strPaginaDir+'uploadendpoint'
			},
			deleteFile: {
				enabled: true,
				endpoint: strPaginaDir+'uploadendpoint'
			},
			retry: {
				enableAuto: true,
				maxAutoAttempts: 1
			},
			session: {
				endpoint: imagensListaPreCarrega,
				refreshOnRequest: true
			}
		});
	}

	function redimencionaImagemAmpliada(){
		if($("input:disabled").length){
			var fotoAmpliada = $("input:disabled").first();
			var largura = fotoAmpliada.attr("data-big-width");
			var altura = fotoAmpliada.attr("data-big-height");
			var imagemProporcao = Math.round(fotoAmpliada.width() * altura / largura);
			fotoAmpliada.css("height",imagemProporcao);
		}
	}
	redimencionaImagemAmpliada();
	
	$(window).resize(function(){
		redimencionaImagemAmpliada();
		try{
			for(var x in graficosEleicoes){
				graficosEleicoes[x][0].draw(graficosEleicoes[x][1],graficosEleicoes[x][2]);
			}
		}catch(e){}
	});
	
	
});

//-->