<?php

/**
* @file
* Default theme implementation to display a single Drupal page.
*
* Available variables:
*
* General utility variables:
* - $base_path: The base URL path of the Drupal installation. At the very
*   least, this will always default to /.
* - $directory: The directory the template is located in, e.g. modules/system
*   or themes/bartik.
* - $is_front: TRUE if the current page is the front page.
* - $logged_in: TRUE if the user is registered and signed in.
* - $is_admin: TRUE if the user has permission to access administration pages.
*
* Site identity:
* - $front_page: The URL of the front page. Use this instead of $base_path,
*   when linking to the front page. This includes the language domain or
*   prefix.
* - $logo: The path to the logo image, as defined in theme configuration.
* - $site_name: The name of the site, empty when display has been disabled
*   in theme settings.
* - $site_slogan: The slogan of the site, empty when display has been disabled
*   in theme settings.
*
* Navigation:
* - $main_menu (array): An array containing the Main menu links for the
*   site, if they have been configured.
* - $secondary_menu (array): An array containing the Secondary menu links for
*   the site, if they have been configured.
* - $breadcrumb: The breadcrumb trail for the current page.
*
* Page content (in order of occurrence in the default page.tpl.php):
* - $title_prefix (array): An array containing additional output populated by
*   modules, intended to be displayed in front of the main title tag that
*   appears in the template.
* - $title: The page title, for use in the actual HTML content.
* - $title_suffix (array): An array containing additional output populated by
*   modules, intended to be displayed after the main title tag that appears in
*   the template.
* - $messages: HTML for status and error messages. Should be displayed
*   prominently.
* - $tabs (array): Tabs linking to any sub-pages beneath the current page
*   (e.g., the view and edit tabs when displaying a node).
* - $action_links (array): Actions local to the page, such as 'Add menu' on the
*   menu administration interface.
* - $feed_icons: A string of all feed icons for the current page.
* - $node: The node object, if there is an automatically-loaded node
*   associated with the page, and the node ID is the second argument
*   in the page's path (e.g. node/12345 and node/12345/revisions, but not
*   comment/reply/12345).
*
* Regions:
* - $page['help']: Dynamic help text, mostly for admin pages.
* - $page['highlighted']: Items for the highlighted content region.
* - $page['content']: The main content of the current page.
* - $page['sidebar_first']: Items for the first sidebar.
* - $page['sidebar_second']: Items for the second sidebar.
* - $page['header']: Items for the header region.
* - $page['footer']: Items for the footer region.
*
* @see template_preprocess()
* @see template_preprocess_page()
* @see template_process()
*/
?>

<div id="page-wrapper">
	<div id="page">
		<!-- header -->
		<div id="header">
			<div class="section clearfix">
				<!-- social media buttons -->
				<div class="follow">
					Follow us:
					<div class="inner">
						<a href="http://www.twitter.com/FutureFirstOrg" target="_blank" class="tw" title="Twitter"></a>
						<a href="http://www.facebook.com/pages/Future-First/200912103255442" class="fb" target="_blank" title="Facebook"></a>
						<a class="plus" title="Plus" onclick="return addthis_sendto()" onmouseout="addthis_close()" onmouseover="return addthis_open(this, '', 'http://www.futurefirst.org.uk/', 'Home')" href="http://www.addthis.com/bookmark.php?v=250&amp;pub=rahf"></a>
						<script src="http://s7.addthis.com/js/250/addthis_widget.js?pub=rahf" type="text/javascript"></script>
						<a href="/feed/rss/" class="rss" title="RSS"></a>
					</div>
				</div>
				<!-- end social media buttons -->
				
				<!-- logo and slogan -->
				<?php if ($logo): ?>
					<a href="http://www.futurefirst.org.uk/" rel="home" id="logo">
						<img src="<?php print $logo; ?>" alt="<?php print t('Future First'); ?>" />
					</a>
				<?php endif; ?>

				<?php if ($site_slogan): ?>
					<div id="slogan"><?php print $site_slogan; ?></div>
				<?php endif; ?>
				<!-- end logo and slogan -->

				<div class="free-tag">FREE</div>
				
				<!-- navigation tabs -->
				<div class="menu-primary-navigation-container">
					<ul id="menu-primary-navigation" class="menu">
						<li id="menu-item-6" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-4 current_page_item menu-item-6">
							<a href="http://www.futurefirst.org.uk/">Home</a>
						</li>
						<li id="menu-item-112" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-112">	
							<a href="http://www.futurefirst.org.uk/what-we-do/in-schools/">What we do</a>
							<ul class="sub-menu">
								<li id="menu-item-212" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-212">
									<a href="http://www.futurefirst.org.uk/what-we-do/in-schools/">In Schools</a>
								</li>
								<li id="menu-item-2074" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2074">
									<a href="http://www.futurefirst.org.uk/what-we-do/our-partners/" style="border-bottom-width: 0px; border-bottom-style: initial; border-bottom-color: initial; padding-bottom: 4px;" >Our Partners</a>
								</li>
							</ul>
						</li>
						<li id="menu-item-92" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-92">
							<a href="http://www.futurefirst.org.uk/about-us/">About Us</a>
						</li>
						<li id="menu-item-91" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-91">
							<a href="http://www.futurefirst.org.uk/news/">News</a>
						</li>
						<li id="menu-item-222" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-222">
							<a href="http://www.futurefirst.org.uk/press/">Press</a>
						</li>
						<li id="menu-item-89" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-89">
							<a href="http://www.futurefirst.org.uk/our-supporters/">Supporters</a>
						</li>
						<li id="menu-item-2415" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-2415">
							<a href="http://networks.futurefirst.org.uk">New Service</a>
						</li>
						<li id="menu-item-90" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-90">
							<a href="http://www.futurefirst.org.uk/contact-us/">Contact</a>
						</li>
					</ul>
				</div>		

				<?php print render($page['header']); ?>
			</div>		
		</div>
		<!-- end header -->
		
		<!-- error messages -->
		<div id="messages">
			<div class="section clearfix">
				<?php print $messages; ?>
			</div>
		</div>
		<!-- end error messages -->

		<div id="main-wrapper" class="clearfix">
			<!-- main -->
			<div id="main" class="clearfix">
				<!-- page title -->			
				<h1 id="title"></h1>
				<!-- info page link -->
				<a href="/info" class="info-button">More information and FAQs</a>

				<div id="white">					
					<!-- content area -->
					<div id="content">
						<div class="section">							
							<!-- admin tabs -->
							<?php if ($tabs): ?>
								<div class="tabs">
									<?php print render($tabs); ?>
								</div>
							<?php endif; ?>
							<!-- end admin tabs -->
	
							<?php print render($page['help']); ?>
							
							<?php if ($action_links): ?>
								<ul class="action-links">
									<?php print render($action_links); ?>
								</ul>
							<?php endif; ?>
							
							<!-- intro text -->
							<?php if ($page['intro_text']): ?>
								<div id="intro_text" class="column sidebar">
									<div class="section">
							    		<?php print render($page['intro_text']); ?>
							    	</div>
								</div> 
							<?php endif; ?>
							<!-- end intro text -->
							
							<!-- page content -->
							<?php print render($page['content']); ?>
						</div>
					</div>
					<!-- end of content area -->
					
					<!-- right sidebar -->
					<?php if ($page['sidebar_first']): ?>
						<div id="sidebar-first" class="column sidebar">
							<div class="section">
					    		<?php print render($page['sidebar_first']); ?>
					    	</div>
						</div> 
					<?php endif; ?>
					<!-- end right sidebar -->
				</div>
			</div>
			<!-- end main -->
		</div> 

		<div id="footer" class="footer">
			<?php print render($page['footer']); ?>
		</div> 
	
		<div id="footer-bar" class="footer">
			<?php print render($page['footer_bar']); ?>
		</div>
	
		<div id="sub-footer">&copy;Future First 2012. Future First is a social business registered in England and Wales, Company Number 6830604.</div>
	</div>
</div> 
