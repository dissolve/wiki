<?php
/*
 * ----------------------------------------------------------------------------
 * 'IndieWeb' style sheet for CSS2-capable browsers.
 *       Loosely based on the monobook style
 *
 * @Version 5.0.0
 * @Author Paul Y. Gu, <gu.paul@gmail.com>
 * @Copyright paulgu.com 2007 - http://www.paulgu.com/
 * @License: GPL (http://www.gnu.org/copyleft/gpl.html)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * ----------------------------------------------------------------------------
 */

if( !defined( 'MEDIAWIKI' ) )
    die( -1 );

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @addtogroup Skins
 */
class SkinIndieWeb extends SkinTemplate {
	/** Using IndieWeb */
	var $skinname = 'indieweb', $stylename = 'indieweb',
		$template = 'IndieWebTemplate', $useHeadElement = false;

	function setupSkinUserCss( OutputPage $out ) {
		global $wgHandheldStyle;

		parent::setupSkinUserCss( $out );

		// Append to the default screen common & print styles...
		$out->addStyle( 'indieweb/gumax_main.css', 'screen' );
		if( $wgHandheldStyle ) {
			// Currently in testing... try 'chick/main.css'
			$out->addStyle( $wgHandheldStyle, 'handheld' );
		}

		$out->addStyle( 'indieweb/IE50Fixes.css', 'screen', 'lt IE 5.5000' );
		$out->addStyle( 'indieweb/IE55Fixes.css', 'screen', 'IE 5.5000' );
		$out->addStyle( 'indieweb/IE60Fixes.css', 'screen', 'IE 6' );
		$out->addStyle( 'indieweb/IE70Fixes.css', 'screen', 'IE 7' );

		$out->addStyle( 'indieweb/gumax_rtl.css', 'screen', '', 'rtl' );
		$out->addStyle( 'indieweb/gumax_print.css', 'print' );
	}
}

/**
 * @todo document
 * @addtogroup Skins
 */
class IndieWebTemplate extends QuickTemplate {
    var $skin;
    /**
     * Template filter callback for IndieWeb skin.
     * Takes an associative array of data set from a SkinTemplate-based
     * class, and a wrapper for MediaWiki's localization database, and
     * outputs a formatted page.
     *
     * @access private
     */
    function execute() {
      global $wgPingback, $wgWebmention;
        // Suppress warnings to prevent notices about missing indexes in $this->data
        wfSuppressWarnings();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="<?php $this->text('xhtmldefaultnamespace') ?>" <?php
    foreach($this->data['xhtmlnamespaces'] as $tag => $ns) {
        ?>xmlns:<?php echo "{$tag}=\"{$ns}\" ";
    } ?>xml:lang="<?php $this->text('lang') ?>" lang="<?php $this->text('lang') ?>" dir="<?php $this->text('dir') ?>">

<head>
    <meta http-equiv="Content-Type" content="<?php $this->text('mimetype') ?>; charset=<?php $this->text('charset') ?>" />
    <?php $this->html('headlinks') ?>
    <title><?php $this->text('pagetitle') ?></title>
    <style type="text/css" media="screen,projection">/*<![CDATA[*/ @import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/gumax_main.css?<?php echo $GLOBALS['wgStyleVersion'] ?>"; /*]]>*/</style>
    <link rel="stylesheet" type="text/css" <?php if(empty($this->data['printable']) ) { ?>media="print"<?php } ?> href="<?php $this->text('stylepath') ?>/common/commonPrint.css?<?php echo $GLOBALS['wgStyleVersion'] ?>" />
    <link rel="stylesheet" type="text/css" media="handheld" href="<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/handheld.css?<?php echo $GLOBALS['wgStyleVersion'] ?>" />
    <link rel="stylesheet" type="text/css" media="print" href="<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/print.css?<?php echo $GLOBALS['wgStyleVersion'] ?>" />
    <!--[if lt IE 5.5000]><style type="text/css">@import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/IE50Fixes.css?<?php echo $GLOBALS['wgStyleVersion'] ?>";</style><![endif]-->
    <!--[if IE 5.5000]><style type="text/css">@import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/IE55Fixes.css?<?php echo $GLOBALS['wgStyleVersion'] ?>";</style><![endif]-->
    <!--[if IE 6]><style type="text/css">@import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/IE60Fixes.css?<?php echo $GLOBALS['wgStyleVersion'] ?>";</style><![endif]-->
    <!--[if IE 7]><style type="text/css">@import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/IE70Fixes.css?<?php echo $GLOBALS['wgStyleVersion'] ?>";</style><![endif]-->
    <!--[if lt IE 7]><script type="<?php $this->text('jsmimetype') ?>" src="<?php $this->text('stylepath') ?>/common/IEFixes.js?<?php echo $GLOBALS['wgStyleVersion'] ?>"></script>
    <meta http-equiv="imagetoolbar" content="no" /><![endif]-->
	<meta property="og:title" content="<?php $this->text('pagetitle') ?>" />
	<meta property="og:image" content="https://indiewebcamp.com/wiki/skins/indieweb/indiewebcamp-logo-500px.png" />
	<meta property="og:site_name" content="IndieWebCamp" />
	<meta property="fb:admins" content="11500459,31600719,214611" />
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/irc/apple-touch-icon-57x57-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/irc/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/irc/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/irc/apple-touch-icon-144x144-precomposed.png">

    <?php print Skin::makeGlobalVariablesScript( $this->data ); ?>

    <script type="<?php $this->text('jsmimetype') ?>" src="<?php $this->text('stylepath' ) ?>/common/wikibits.js?<?php echo $GLOBALS['wgStyleVersion'] ?>"><!-- wikibits js --></script>
    <?php    if($this->data['jsvarurl'  ]) { ?>
        <script type="<?php $this->text('jsmimetype') ?>" src="<?php $this->text('jsvarurl'  ) ?>"><!-- site js --></script>
    <?php    } ?>
    <?php    if($this->data['pagecss'   ]) { ?>
        <style type="text/css"><?php $this->html('pagecss'   ) ?></style>
    <?php    }
        if($this->data['usercss'   ]) { ?>
        <style type="text/css"><?php $this->html('usercss'   ) ?></style>
    <?php    }
        if($this->data['userjs'    ]) { ?>
        <script type="<?php $this->text('jsmimetype') ?>" src="<?php $this->text('userjs' ) ?>"></script>
    <?php    }
        if($this->data['userjsprev']) { ?>
        <script type="<?php $this->text('jsmimetype') ?>"><?php $this->html('userjsprev') ?></script>
    <?php    }
    if($this->data['trackbackhtml']) print $this->data['trackbackhtml']; ?>
    <?php if(isset($wgPingback)) { ?>
      <link rel="pingback" href="<?= $wgPingback ?>" />
    <?php } ?>
    <?php if(isset($wgWebmention)) { ?>
      <link href="<?= $wgWebmention ?>" rel="webmention" />
    <?php } ?>
    
    <!-- Head Scripts -->
    <?php $this->html('headscripts') ?>

</head>

<body <?php if($this->data['body_ondblclick']) { ?>ondblclick="<?php $this->text('body_ondblclick') ?>"<?php } ?>
<?php if($this->data['body_onload'    ]) { ?>onload="<?php     $this->text('body_onload')     ?>"<?php } ?>
 class="mediawiki <?php $this->text('nsclass') ?> <?php $this->text('dir') ?> <?php $this->text('pageclass') ?>">

<div class="gumax-center" align="center">

    <!-- ===== Header ===== -->
    <div id="gumax-header">
    	<div id="header-text">IndieWebCamp is a 2-day creator camp focused on growing the independent web</div>
        <a id="topHeader"></a>

    </div> <!-- end of header DIV -->
    <!-- ===== end of Header ===== -->


    <!-- ===== gumax-page-actions ===== -->
    <div id="gumax-page-actions">
			<?php $this->contentActionBox(); ?>
    </div>
    <!-- ===== end of gumax-page-actions ===== -->
    

<div id="gumax-rbox" class="middle-content" align="left">
<div class="gumax-rbcontentwrap"><div class="gumax-rbcontent">

    <!-- =================== gumax-page =================== -->
    <div id="gumax-page">
    <?php if($this->data['sitenotice']) { ?><div id="siteNotice"><?php $this->html('sitenotice') ?></div><?php } ?>

    <!-- ===== Content body ===== -->
    <div id="gumax-content-body">
    
    <table id="gumax-content-body-table"><tr><td class="gumax-content-left">
    <!-- Navigation Menu -->
	<?php $this->topNavigationBox(); ?>
	<div class="gumax-p-navigation-spacer"></div>
	<div class="visualClear"></div>

    </td><td class="gumax-content-right"> <!-- Main Content TD -->

    <!-- Main Content -->
    <div id="content">
    
        <a name="top" id="top"></a>
        <?php if($this->data['sitenotice']) { ?><div id="siteNotice"><?php $this->html('sitenotice') ?></div><?php } ?>
        <h1 class="firstHeading"><?php $this->data['displaytitle']!=""?$this->html('title'):$this->text('title') ?></h1>
        <div id= "bodyContent" class="gumax-bodyContent">
            <?php /* <h3 id="siteSub"><?php $this->msg('tagline') ?></h3> */ ?>
            <div id="contentSub"><?php if( $this->data['title'] != 'Home' ) { $this->html('subtitle'); } ?></div>
            <?php if($this->data['undelete']) { ?><div id="contentSub2"><?php $this->html('undelete') ?></div><?php } ?>
            <?php if($this->data['newtalk'] ) { ?><div class="usermessage"><?php $this->html('newtalk')  ?></div><?php } ?>
            <?php if(0 && $this->data['showjumplinks']) { ?><div id="jump-to-nav"><?php $this->msg('jumpto') ?> <a href="#column-one"><?php $this->msg('jumptonavigation') ?></a>, <a href="#searchInput"><?php $this->msg('jumptosearch') ?></a></div><?php } ?>
            <!-- start content -->
            <?php $this->html('bodytext') ?>
            <?php if($this->data['catlinks']) { ?><div id="catlinks"><?php $this->html('catlinks') ?></div><?php } ?>
            <!-- end content -->
            <div class="visualClear"></div>
        </div>
    </div>
    <!-- end of Main Content -->
    </td></tr></table>
    </div>
    <!-- ===== end of Content body ===== -->

    </div> <!-- end of gumax-page DIV -->
    <!-- =================== end of gumax-page =================== -->
</div></div>
<div class="gumax-rbbot"><div><div></div></div></div></div>
</div>
</div>

  <div class="bottom">
    <!-- ===== gumax-page-actions ===== -->
    <div id="gumax-page-actions">
      <div id="gumax-content-actions">
        <div class="middle-content">
        <ul>
            <?php $lastkey = end(array_keys($this->data['content_actions'])) ?>
            <?php foreach($this->data['content_actions'] as $key => $action) { ?>
               <li id="ca-<?php echo Sanitizer::escapeId($key) ?>" <?php
                   if($action['class']) { ?>class="<?php echo htmlspecialchars($action['class']) ?>"<?php } ?>
               ><a href="<?php echo htmlspecialchars($action['href']). '"';
                                        # We don't want to give the watch tab an accesskey if the
                                        # page is being edited, because that conflicts with the
                                        # accesskey on the watch checkbox.  We also don't want to
                                        # give the edit tab an accesskey, because that's fairly su-
                                        # perfluous and conflicts with an accesskey (Ctrl-E) often
                                        # used for editing in Safari.
				echo '>';
                   echo htmlspecialchars($action['text']) ?></a> <?php
                   	// echo '<!-- '; echo var_dump($this->skin); echo ' -->';
                   if($key != $lastkey) //echo "&#8226;" ?></li>
            <?php } ?>

            <!-- show back to top link only if the body is longer than the window height -->
            <!--
            <script type="text/javascript">
                var winheight = parseInt(document.documentElement.clientHeight)
                var boheight = parseInt(document.body.clientHeight)
                if(winheight <= boheight) {
                    document.write('<li><a href="#" onclick="window.scrollTo(0,0);return false;" title="Back to the top of the page">Back to top</a></li>');
                }
            </script>
            -->
            <!-- end of show back to top link only -->

        </ul>
        </div>
      </div>
    </div>
    <!-- ===== end of gumax-page-actions ===== -->

    <!-- =================== gumax-page-footer =================== -->
    <div id="gumax-page-footer">

      <div class="middle-content">
        <!-- personal tools  -->
        <div id="gumax-personal-tools">
            <ul>
              <?php if($this->data['notspecialpage']) { foreach( array( 'whatlinkshere', 'recentchangeslinked' ) as $special ) { ?>
		 <li id="t-<?php echo $special?>"><a href="<?php
                echo htmlspecialchars($this->data['nav_urls'][$special]['href'])
                ?>"><?php echo $this->msg($special) ?></a> | </li>
              <?php } } ?><?php if($this->data['feeds']) { ?>
                  <li id="feedlinks"><?php foreach($this->data['feeds'] as $key => $feed) {
                  ?><span id="feed-<?php echo Sanitizer::escapeId($key) ?>"><a href="<?php
                  echo htmlspecialchars($feed['href']) ?>"><?php echo htmlspecialchars($feed['text'])?></a>&nbsp;</span><?php } ?> | </li> <?php } ?>
              <?php foreach( array('contributions', 'blockip', 'emailuser', 'upload', 'specialpages') as $special ) { ?> <?php
                  if($this->data['nav_urls'][$special]) {?><li id="t-<?php echo $special ?>"><a href="<?php
                  echo htmlspecialchars($this->data['nav_urls'][$special]['href'])
                  ?>"><?php $this->msg($special) ?></a> <?php
                      if($special != 'specialpages') echo "|" ?> </li>
                <?php } ?>
              <?php }

                if(!empty($this->data['nav_urls']['permalink']['href'])) { ?>
                        <li id="t-permalink"> | <a href="<?php echo htmlspecialchars($this->data['nav_urls']['permalink']['href'])
                        ?>"><?php $this->msg('permalink') ?></a></li><?php
                } elseif ($this->data['nav_urls']['permalink']['href'] === '') { ?>
                        <li id="t-ispermalink"> | <?php $this->msg('permalink') ?></li><?php
                }

                wfRunHooks( 'IndieWebTemplateToolboxEnd', array( &$this ) ); ?>

            </ul>


            <!-- Login -->
            <div id="gumax-footer-login">
                <ul>
                  <?php $lastkey = end(array_keys($this->data['personal_urls'])) ?>
                  <?php foreach($this->data['personal_urls'] as $key => $item) {
	                  ?><li id="gumax-pt-<?php echo Sanitizer::escapeId($key) ?>"><a href="<?php
	                   echo htmlspecialchars($item['href']) ?>"<?php
	                  if(!empty($item['class'])) { ?> class="<?php
	                   echo htmlspecialchars($item['class']) ?>"<?php } ?>><?php
	                   echo htmlspecialchars($item['text']) ?></a>
	                   <?php if($key != $lastkey) echo "|" ?></li>
                 <?php } ?>
                </ul>
            </div>
            <!-- end of Login -->

        </div> <!-- end of personal-tools DIV -->
        <!-- end of personal tools  -->

        <!-- gumax-footer -->
        <div id="gumax-footer">
            <div id="gumax-f-message">
                <?php if($this->data['lastmod']) { ?><span id="f-lastmod"><?php    $this->html('lastmod')    ?></span>
                <?php } ?><?php if($this->data['viewcount' ]) { ?><span id="f-viewcount"><?php  $this->html('viewcount')  ?> </span>
                <?php } ?>
            </div>
                <?php
			/*
            <ul id="gumax-f-list">
                        $footerlinks = array(
                            'numberofwatchingusers', 'credits',
                            'privacy', 'about', 'disclaimer', 'tagline',
                        );
                        foreach( $footerlinks as $aLink ) {
                            if( isset( $this->data[$aLink] ) && $this->data[$aLink] ) {
                ?>				<li id="<?php echo$aLink?>"><?php $this->html($aLink) ?> | </li>
                <?php 		}
                        }
        		<li>Design by Aaron Parecki | </li>
                <li id="f-designby"><a href="http://paulgu.com">Skin by Paul Gu</a></li>
            </ul>
			*/
                ?>
        </div>
        <!-- end of gumax-footer -->
        <?php $this->html('bottomscripts'); /* JS call to runBodyOnloadHook */ ?>
      </div>
        
    </div>
    <!-- =================== end of gumax-page-footer =================== -->
  </div>

<?php
include(dirname(__FILE__).'/sponsors.php');
?>

    <?php $this->html('reporttime') ?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16359758-21']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</body></html>
<?php
	wfRestoreWarnings();
    } // end of execute() method

	/*************************************************************************************************/
	// GuMax Functions
	/*************************************************************************************************/

	function loginBox() {
?>
	<!-- Login -->
	<div id="gumax-p-login">
		<ul>
<?php		$lastkey = end(array_keys($this->data['personal_urls'])) ?>
<?php		foreach($this->data['personal_urls'] as $key => $item) { ?>
				<li id="pt-<?php echo Sanitizer::escapeId($key) ?>"<?php
					if ($item['active']) { ?> class="active"<?php } ?>><a href="<?php
					echo htmlspecialchars($item['href']) ?>"   <?php
					if(!empty($item['class'])) { ?> class="<?php
					echo htmlspecialchars($item['class']) ?>"<?php } ?>><?php
					echo htmlspecialchars($item['text']) ?></a></li>
<?php				if($key != $lastkey) echo "<li>&#47;</li>" ?>
<?php		} ?>
		</ul>
	</div>
	<!-- end of Login -->
<?php
	}

	/*************************************************************************************************/
	function logoBox() {
?>
	<!-- gumax-p-logo -->
	<div id="gumax-p-logo">
		<div id="p-logo">
			<a style="background-image: url(<?php $this->text('logopath') ?>);" <?php
				?>href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href'])?>" <?php
				?>title="<?php $this->msg('mainpage') ?>"></a>
		</div>
	</div>
	<script type="<?php $this->text('jsmimetype') ?>"> if (window.isMSIE55) fixalpha(); </script>
	<!-- end of gumax-p-logo -->
<?php
	}

	/*************************************************************************************************/
	function searchBox() {
?>
	<!-- Search -->
                <form action="<?php $this->text('searchaction') ?>" id="searchform" style="display: inline-block; float: right;">
                    <input id="searchInput" name="search" type="text" <?php
                        if($this->haveMsg('accesskey-search')) {
                            ?>accesskey="<?php $this->msg('accesskey-search') ?>"<?php }
                        if( isset( $this->data['search'] ) ) {
                            ?> value="<?php $this->text('search') ?>"<?php } ?> />
                    <input type='submit' name="fulltext" class="searchButton" id="mw-searchButton" value="<?php $this->msg('searchbutton') ?>" />
                </form>
	<!-- end of Search -->
<?php
	}

	/*************************************************************************************************/
	function topNavigationBox() {
?>
	<!-- Navigation Menu -->
    <div id="gumax-p-navigation-wrapper">

    <div id="main-logo-wrapper">
      <a href="/" id="main-logo"><img src="https://indiewebcamp.com/wiki/skins/indieweb/indiewebcamp-logo-500px.png" width="155" alt="IndieWebCamp"></a>
    </div>
	<div id="gumax-p-navigation">
<?php	$counter = 0; ?>
<?php	foreach ($this->data['sidebar'] as $bar => $cont) { ?>
<?php		$counter++; ?>
<?php		if ( $counter == 1 ) { ?>
				<div class="gumax-portlet gumax-p-navigation">
					<h5><?php $out = wfMsg( $bar ); if (wfEmptyMsg($bar, $out)) echo $bar; else echo $out; ?></h5>
	<?php			if ( is_array( $cont ) ) { ?>

							<ul>
	<?php						foreach($cont as $key => $val) { ?>
									<li id="<?php echo Sanitizer::escapeId($val['id']) ?>"<?php
										if ( $val['active'] ) { ?> class="active" <?php }
									?>><a href="<?php echo htmlspecialchars($val['href']) ?>"   ><?php echo htmlspecialchars($val['text']) ?></a></li>
	<?php						} ?>
							</ul>

	<?php			} else {
						# allow raw HTML block to be defined by extensions
						print $cont;
					} ?>
				</div>
<?php		} ?>
<?php	} ?>
	</div>
	<!-- end of Navigation Menu -->
<?php
	}

	/*************************************************************************************************/
	function sidebarNavigationBox() {
?>
	<!-- Navigation Menu -->
	<div id="gumax-p-navigation-sidebar">
<?php	$counter = 0; ?>
<?php	foreach ($this->data['sidebar'] as $bar => $cont) { ?>
<?php		$counter++; ?>
<?php		if ( $counter > 1 ) { ?>
				<div class="generated-sidebar gumax-portlet-sidebar">
					<h5><?php $out = wfMsg( $bar ); if (wfEmptyMsg($bar, $out)) echo $bar; else echo $out; ?></h5>
	<?php			if ( is_array( $cont ) ) { ?>

							<ul>
	<?php						foreach($cont as $key => $val) { ?>
									<li id="<?php echo Sanitizer::escapeId($val['id']) ?>"<?php
										if ( $val['active'] ) { ?> class="active" <?php }
									?>><a href="<?php echo htmlspecialchars($val['href']) ?>"   ><?php echo htmlspecialchars($val['text']) ?></a></li>
	<?php						} ?>
							</ul>

	<?php			} else {
						# allow raw HTML block to be defined by extensions
						print $cont;
					} ?>
				</div>
<?php		} ?>
<?php	} ?>
	</div>
	<!-- end of Navigation Menu -->
<?php
	}

	/*************************************************************************************************/
	function languageBox() {
		if( $this->data['language_urls'] ) {
?>
	<div id="p-lang" class="portlet">
		<h5><?php $this->msg('otherlanguages') ?></h5>
		<div class="pBody">
			<ul>
<?php		foreach($this->data['language_urls'] as $langlink) { ?>
				<li class="<?php echo htmlspecialchars($langlink['class'])?>"><?php
				?><a href="<?php echo htmlspecialchars($langlink['href']) ?>"><?php echo $langlink['text'] ?></a></li>
<?php		} ?>
			</ul>
		</div>
	</div>
<?php
		}
	}

	/*************************************************************************************************/
	function contentActionBox() {
		global $wgUser;
		$skin = $wgUser->getSkin();
?>

	<!-- gumax-content-actions -->
	<?php //if($this->data['loggedin']==1) { ?>
    <div id="gumax-content-actions" style="text-align: left">
        <div class="middle-content">
        <ul style="margin-left: 40px;">
		<?php
			foreach($this->data['content_actions'] as $key => $tab) {
				echo '
			 <li id="' . Sanitizer::escapeId( "ca-$key" ) . '"';
				if( $tab['class'] ) {
					echo ' class="'.htmlspecialchars($tab['class']).'"';
				}
				echo '><a href="'.htmlspecialchars($tab['href']).'"';
				# We don't want to give the watch tab an accesskey if the
				# page is being edited, because that conflicts with the
				# accesskey on the watch checkbox.  We also don't want to
				# give the edit tab an accesskey, because that's fairly su-
				# perfluous and conflicts with an accesskey (Ctrl-E) often
				# used for editing in Safari.

				echo '>'.htmlspecialchars($tab['text']).'</a></li>';
			} 
                 // grab log-out item   
                
                 $logout_item = $this->data['personal_urls']['logout'];
                    echo '
                 <li id="' . Sanitizer::escapeId( "ca-logout" ) . '"';
				if( $logout_item['class'] ) {
					echo ' class="'.htmlspecialchars($logout_item['class']).'"';
				}
				echo '><a href="'.htmlspecialchars($logout_item['href']).'"';
                echo '>'.htmlspecialchars($logout_item['text']).'</a></li>';?>

                

	<?php $this->loginBox(); ?>
	<?php $this->searchBox(); ?>
		</ul>
	  </div>
	</div>
	<?php //} ?>
	<!-- end of gumax-content-actions -->
<?php
	}

	/*************************************************************************************************/
	function articlePictureBox() {
?>
<?php
		$pageClasses = preg_split("/[\s]+/", $this->data['pageclass']); /* echo $this->data['pageclass']; */
		foreach($pageClasses as $item){ if(strpos($item, "page-") !== false){ $page_class = $item; /* echo $page_class; */ } }

		$file_ext_collection = array('.jpg', '.gif', '.png');
		$found = false;
		foreach ($file_ext_collection as $file_ext)
		{
			$gumax_article_picture_file = $this->data['stylepath'] . '/' . $this->data['stylename'] . '/images/pages/' . $page_class . $file_ext;
			if (file_exists( $_SERVER['DOCUMENT_ROOT'] . '/' .$gumax_article_picture_file)) {
				$found = true;
				break;
			}
		}
		// default article picture
		if(!$found) {
			$gumax_article_picture_file = $this->data['stylepath'] . '/' . $this->data['stylename'] . '/images/pages/' . 'page-Default.gif';
			if (file_exists( $_SERVER['DOCUMENT_ROOT'] . '/' .$gumax_article_picture_file)) {
				$found = true;
			}
		}
		if($found) { ?>
		<!-- gumax-article-picture -->
			<div id="gumax-article-picture">
				<a style="background-image: url(<?php echo $gumax_article_picture_file ?>);" <?php
					?>href="<?php echo htmlspecialchars( $GLOBALS['wgTitle']->getLocalURL() )?>" <?php
					?>title="<?php $this->data['displaytitle']!=""?$this->html('title'):$this->text('title') ?>"></a>
			</div>
			<div class="gumax-article-picture-spacer"></div>
		<!-- end of gumax-article-picture -->
<?php
		}
	}

	/*************************************************************************************************/
	function contentBox() {
?>
	<!-- gumax-content-body -->
		<div id="gumax-column-content">
	<div id="content">
		<a name="top" id="top"></a>
		<?php if($this->data['sitenotice']) { ?><div id="siteNotice"><?php $this->html('sitenotice') ?></div><?php } ?>
		<h1 id="firstHeading" class="firstHeading gumax-firstHeading" ><?php $this->html('title') ?></h1>
		<div id="bodyContent" class="gumax-bodyContent">
			<h3 id="siteSub"><?php $this->msg('tagline') ?></h3>
			<div id="contentSub"><?php $this->html('subtitle') ?></div>
			<?php if($this->data['undelete']) { ?><div id="contentSub2"><?php     $this->html('undelete') ?></div><?php } ?>
			<?php if($this->data['newtalk'] ) { ?><div class="usermessage"><?php $this->html('newtalk')  ?></div><?php } ?>
			<?php if($this->data['showjumplinks']) { ?><div id="jump-to-nav"><?php $this->msg('jumpto') ?> <a href="#column-one"><?php $this->msg('jumptonavigation') ?></a>, <a href="#searchInput"><?php $this->msg('jumptosearch') ?></a></div><?php } ?>
			<!-- start content -->
			<?php $this->html('bodytext') ?>
			<?php if($this->data['catlinks']) { $this->html('catlinks'); } ?>
			<!-- end content -->
			<?php if($this->data['dataAfterContent']) { $this->html ('dataAfterContent'); } ?>
			<div class="visualClear"></div>
		</div>
	</div>
		</div>
	<!-- end of gumax-content-body -->
<?php
	}

	/*************************************************************************************************/
	function toolbox() {
?>
	<!-- personal tools  -->
	<div id="gumax-personal-tools">
		<h5><?php $this->msg('toolbox') ?></h5>
		<ul>
<?php
		if($this->data['notspecialpage']) { ?>
				<li id="t-whatlinkshere"><a href="<?php
				echo htmlspecialchars($this->data['nav_urls']['whatlinkshere']['href'])
				?>"   ><?php $this->msg('whatlinkshere') ?></a></li>
				<li>&#47;</li>
<?php
			if( $this->data['nav_urls']['recentchangeslinked'] ) { ?>
				<li id="t-recentchangeslinked"><a href="<?php
				echo htmlspecialchars($this->data['nav_urls']['recentchangeslinked']['href'])
				?>"   ><?php $this->msg('recentchangeslinked') ?></a></li>
				<li>&#47;</li>
<?php 		}
		}
		if(isset($this->data['nav_urls']['trackbacklink'])) { ?>
			<li id="t-trackbacklink"><a href="<?php
				echo htmlspecialchars($this->data['nav_urls']['trackbacklink']['href'])
				?>"   ><?php $this->msg('trackbacklink') ?></a></li>
				<li>&#47;</li>
<?php 	}
		if($this->data['feeds']) { ?>
			<li id="feedlinks"><?php foreach($this->data['feeds'] as $key => $feed) {
					?><span id="feed-<?php echo Sanitizer::escapeId($key) ?>"><a href="<?php
					echo htmlspecialchars($feed['href']) ?>"   ><?php echo htmlspecialchars($feed['text'])?></a>&nbsp;</span>
					<?php } ?></li>
					<li>&#47;</li><?php
		}

		foreach( array('contributions', 'log', 'blockip', 'emailuser', 'upload', 'specialpages') as $special ) {
			if($this->data['nav_urls'][$special]) {
				?><li id="t-<?php echo $special ?>"><a href="<?php echo htmlspecialchars($this->data['nav_urls'][$special]['href'])
				?>"   ><?php $this->msg($special) ?></a></li>
<?php			if($special != 'specialpages') echo "<li>&#47;</li>"; ?>
<?php		}
		}

		if(!empty($this->data['nav_urls']['print']['href'])) { ?>
				<li>&#47;</li>
				<li id="t-print"><a href="<?php echo htmlspecialchars($this->data['nav_urls']['print']['href'])
				?>"   ><?php $this->msg('printableversion') ?></a></li><?php
		}

		if(!empty($this->data['nav_urls']['permalink']['href'])) { ?>
				<li>&#47;</li>
				<li id="t-permalink"><a href="<?php echo htmlspecialchars($this->data['nav_urls']['permalink']['href'])
				?>"   ><?php $this->msg('permalink') ?></a></li><?php
		} elseif ($this->data['nav_urls']['permalink']['href'] === '') { ?>
				<li>&#47;</li>
				<li id="t-ispermalink"<?php echo $this->skin->tooltip('t-ispermalink') ?>><?php $this->msg('permalink') ?></li><?php
		}

		wfRunHooks( 'GuMaxTemplateToolboxEnd', array( &$this ) );
		wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this ) );
?>
		</ul>
	</div>
	<!-- end of personal tools  -->
<?php
	}

	/*************************************************************************************************/

	function gumaxLastModified() {
		global $wgLang, $wgArticle;
		if ( !$wgArticle ) return '';

		if( $this->mRevisionId ) {
			$timestamp = Revision::getTimestampFromId( $this->mRevisionId, $wgArticle->getId() );
		} else {
			$timestamp = $wgArticle->getTimestamp();
		}
		if ( $timestamp ) {
			$d = $wgLang->date( $timestamp, true );
			$t = $wgLang->time( $timestamp, true );
			//$s = ' ' . wfMsg( 'lastmodifiedat', $d, $t );
			$s = 'modified on ' . $d . ' at ' . $t;
		} else {
			$s = '';
		}
		//if ( wfGetLB()->getLaggedSlaveMode() ) {
		//	$s .= ' <strong>' . wfMsg( 'laggedslavemode' ) . '</strong>';
		//}
		return $s;
	}

	/*************************************************************************************************/

	function gumaxViewcount() {
		global $wgDisableCounters;
		if ( $wgDisableCounters ) return '';

		global $wgLang, $wgArticle;
		if ( is_object( $wgArticle ) ) {
			$viewcount = $wgLang->formatNum( $wgArticle->getCount() );
			if ( $viewcount ) {
				//$viewcount = wfMsg( "viewcount", $viewcount );
				$viewcount = $viewcount . " views";
			} else {
				$viewcount = '';
			}
		} else {
			$viewcount = '';
		}
		return $viewcount;
	}

	/*************************************************************************************************/

	function gumaxMessage() {
?>
		<div id="gumax-f-message">
			<?php if($this->data['lastmod']) { ?><span id="f-lastmod"><?php $this->html('lastmod') ?></span>
			<?php } ?><?php if($this->data['viewcount']) { ?><span id="f-viewcount"><?php  $this->html('viewcount') ?> </span>
			<?php } ?>
		</div>
<?php
	}

	/*************************************************************************************************/
	function footerBox() {
?>
	<!-- gumax-f-list -->
	<div id="gumax-f-list">
		<ul>
			<?php
					$footerlinks = array(
						'numberofwatchingusers', 'credits',
						'privacy', 'about', 'disclaimer', 'tagline',
					);
					foreach( $footerlinks as $aLink ) {
						if( isset( $this->data[$aLink] ) && $this->data[$aLink] ) {
			?>				<li id="<?php echo$aLink?>"><?php $this->html($aLink) ?></li>
							<?php		echo "<li>/</li>"	?>
			<?php 		}
					}
			?>
			<li id="f-poweredby"><a href="http://mediawiki.org" target="_blank">Powered by MediaWiki</a></li>
			<li>&#47;</li>
			<li id="f-designedby"><a href="http://paulgu.com" target="_blank">Designed by Paul Gu</a></li>
		</ul>
	</div>
	<!-- end of gumax-f-list -->
<?php
	}

	/*************************************************************************************************/


} // end of class

