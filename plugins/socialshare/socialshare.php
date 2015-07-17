<?php
/**
* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim
*/

// no direct access
defined( '_JEXEC' ) OR  die( 'Restricted access' );

class plgPaycartsocialshare extends Rb_Plugin
{
	public $options = array('Facebook','Twitter','Googleplus','Pinterest');
	public $meta    = array();
	
	function onPaycartViewBeforeRender($view,$task)
	{
		if($view instanceof PaycartSiteBaseViewProduct && $task == 'display'){
			$productId = JFactory::getApplication()->input->get('product_id',0,'INT');

			//if product doesn't exist then do nothing
			if(!$productId){
				return array();
			}

			$product   = PaycartProduct::getInstance($productId);
			$image	   = $product->getCoverMedia();
			$productDetails = $product->toArray();
			$productDetails['image'] = '';
			
			if(isset($image['optimized']) && !empty($image['optimized'])){
				$productDetails['image'] = $image['optimized'];
			}
			$script = '';			
			
			$html = '<style>
						.pc-social-buttons > div, .pc-social-buttons > iframe, .pc-social-buttons > a {margin-right: 4px !important;}
						.fb-like-btn {display: inline-block; vertical-align : super !important;}
						.fb-like-btn iframe{ max-width:none !important; }
					</style>
					<div class="pc-social-buttons">';
			
			foreach ($this->options as $option){
				$functionName = '_add'.$option.'Button';
				list($html,$script) = $this->$functionName($html,$productDetails,$script);
			}
			
			$html .= '</div>';
			
			ob_start();
			?>			
			<script>
				jQuery(window).load(function() {   
				 <?php echo $script;?>
				 });
			</script>				
			<?php 
			$js = ob_get_contents();
			ob_end_clean();
			
			//Add meta details like open graph of fb etc in head
			$document = Rb_Factory::getDocument();
			$meta = '';						
			if(!empty($this->meta)){
				foreach($this->meta as $k => $v){
					$document->addCustomTag($v);
				}
			}
			
			return array('pc-product-addons' => $html.$js);
		}
	}
	
	function _addFacebookButton($html,$productDetails,$script)
	{
		if(!$this->params->get('display_fb')){
			return array($html,$script);
		}
		
		$options = '';
		$xfbml_options = '';
		$fb_style = $this->params->get('fb_style');
		$fb_send  = $this->params->get('fb_send');
		$fb_faces = $this->params->get('fb_faces');
		$fb_verb  = $this->params->get('fb_verb');
		$fb_theme = $this->params->get('fb_theme');
		//$fb_tag = $this->params->get('fb_tag','xfbml');
		$fb_admin = $this->params->get('fb_admin');
		$url      = JURI::current();		
				
		switch ($fb_style){
			case 0 : $options='layout=standard&amp;width=400&amp;'; 
					 $xfbml_options.='data-layout="standard" ';			
					 break;
					 
			case 1 : $options='layout=button_count&amp; width=115&amp;'; 
					 $xfbml_options.='data-layout="button_count" ';
					 break;
			case 2 : $options='layout=box_count&amp; width=115&amp;'; 
					 $xfbml_options.='data-layout="box_count" ';
					 break;
			case 3 : $options='layout=button&amp; width=115&amp;'; 
					 $xfbml_options.='data-layout="button" ';
					 break;
		}
		
		if($fb_faces == 0){
			$options .= 'show_faces=false&amp;'; 
			$xfbml_options .= 'data-show-faces="false" ';
		}else{
			$options .= 'show_faces=true&amp;'; 
			$xfbml_options .= 'data-show-faces="false" ';
		}
		
		if($fb_send == 1){ $xfbml_options.='data-send="true" ';}
		
		if($fb_verb == 0){
			$options.='action=like&amp;'; 
			$xfbml_options .= 'data-action="like"';
		}else{
			$options.='action=recommend&amp;'; 
			$xfbml_options.='data-action="recommend" ';
		}
		
		if($fb_theme){
			$options.='colorscheme=light&amp;';
			$xfbml_options.='data-colorscheme="light" ';
		}else{
			$options.='colorscheme=dark&amp;'; 
			$xfbml_options.='data-colorscheme="dark" ';
		}

		$html .= "<div class='fb-like-btn'>";
//		if($fb_tag =="iframe"){
//			$html .= '<iframe
//						src="http'.$this->https.'://www.facebook.com/plugins/like.php?href='.urlencode($url).'&amp;send=false&amp;'.$options.'height=30"
//						scrolling="no"
//						frameborder="0"
//						style="border:none; overflow:hidden;"
//						allowTransparency="true">
//					  </iframe>';
//		}else{
		$html   .= '<div class="fb-like" data-href="'.$url.'" '.$xfbml_options.'></div><div id="fb-root"></div>';
		$langTag = str_replace('-','_',JFactory::getLanguage()->get('tag'));
		$script .= '(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s); js.id = id;
						js.src = "//connect.facebook.net/'.$langTag.'/all.js#xfbml=1";
						fjs.parentNode.insertBefore(js, fjs);
					}(document, \'script\', \'facebook-jssdk\'));';
//		}
		
		$this->meta['property="og:title"']='<meta property="og:title" content="'.htmlspecialchars(Rb_Factory::getDocument()->getTitle(), ENT_COMPAT,'UTF-8').'"/> ';
		$this->meta['property="og:type"']='<meta property="og:type" content="product"/> ';
		$this->meta['property="og:url"']='<meta property="og:url" content="'.$url.'" />';
		$this->meta['property="og:description"']='<meta property="og:description" content="'.htmlspecialchars(Rb_Factory::getDocument()->getDescription(), ENT_COMPAT,'UTF-8').'"/> ';
		$this->meta['property="og:site_name"']='<meta property="og:site_name" content="'.htmlspecialchars(JFactory::getConfig()->get('sitename'), ENT_COMPAT,'UTF-8').'"/> ';
		if(!empty($productDetails['image'])){
			$this->meta['property="og:image"']='<meta property="og:image" content="'.$productDetails['image'].'" /> ';
		}
		if(!empty($plugin->params['admin'])){
			$this->meta['property="fb:admins"']='<meta property="fb:admins" content="'.htmlspecialchars($fb_admin, ENT_COMPAT,'UTF-8').'" />';
		}
		
		$html .= "</div>";
		
		return array($html,$script);
	}
	
	function _addTwitterButton($html,$productDetails,$script)
	{
		if(!$this->params->get('display_twitter')){
			return array($html,$script);
		}

		$twitterText = $this->params->get('twitter_text');
		$mention     = $this->params->get('twitter_mention','');
		$hashtags    = $this->params->get('twitter_hashtags','');

		$html   .= '<a href="http://twitter.com/share" class="twitter-share-button" 
					   data-text="'.Rb_Factory::getDocument()->getTitle().' '.JText::_($twitterText).'"
		               data-url="'.JURI::current().'"
					   data-count="'.$this->params->get('twitter_count').'" 
					   data-via="'.$mention.'" 
					   data-hashtags="'.$hashtags.'"></a>';
		
		$script .= "!function(d,s,id){
						 var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
						 if(!d.getElementById(id)){
						 	js=d.createElement(s);
						 	js.id=id;
						 	js.src=p+'://platform.twitter.com/widgets.js';
						 	fjs.parentNode.insertBefore(js,fjs);
						}
					}(document, 'script', 'twitter-wjs');";
		
		return array($html,$script);
	}
	
	function _addGoogleplusButton($html,$productDetails,$script)
	{
		if(!$this->params->get('display_google')){
			return array($html,$script);
		}
		
		$html .= '<div class="g-plus" data-action="share" data-annotation="'.$this->params->get('google_count').'"  data-href="'.JURI::current().'"></div>';
		
		//Like for reference : https://developers.google.com/+/web/api/supported-languages
		$tag   = JFactory::getLanguage()->get('tag');
		if(!in_array($tag,array('zh-CN','zh-TW','en-GB','en-US','pt-BR','pt-PT'))) $tag=strtolower(substr($tag,0,2));
		
		$script .= "(function() { 
						window.___gcfg = {
					      lang: '".$tag."',
					      parsetags: 'onload'
				   		 };
						var po = document.createElement('script'); 
						po.type = 'text/javascript'; 
						po.async = true; 
						po.src = 'https://apis.google.com/js/plusone.js'; 
						var s = document.getElementsByTagName('script')[0]; 
						s.parentNode.insertBefore(po, s); 
					})();";
				
		return array($html,$script);
	}
	
	function _addPinterestButton($html,$productDetails,$script)
	{
		if(!$this->params->get('display_pinterest')){
			return array($html,$script);
		}
		
		$html .= '<a href="https://pinterest.com/pin/create/button/?url='.urlencode(JURI::current()).'&media='.urlencode($productDetails['image']).
		         '&description='.htmlspecialchars(Rb_Factory::getDocument()->getTitle(), ENT_COMPAT,'UTF-8').'" 
		         class="pin-it-button" count-layout="'.$this->params->get('pinterest_count','none').'"><img border="0" src="http://assets.pinterest.com/images/PinExt.png" title="'.JText::_('PLG_PAYCART_SOCIAL_SHARE_PIN_IT').'" /></a>';
		
		$script .= "(function(d){
					    var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
					    p.type = 'text/javascript';
					    p.async = true;
					    p.src = '//assets.pinterest.com/js/pinit.js';
					    f.parentNode.insertBefore(p, f);
					}(document));";
		
		return array($html,$script);
	}
}
