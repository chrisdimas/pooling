<?php

/**
 * Tabs class
 *
 * @link       https://indie.systems
 * @since      1.0.0
 *
 * @package    SyncDrop
 * @subpackage SyncDrop/includes
 * @author     Chris Dimas <chris@indie.systems>
 */

class WP_Tabs
{
	
	function __construct()
	{
		$this->tabs = array();
	}
	/**
	 * Adds a new tab for the current page
	 * @param string $parent_slug  global $plugin_page
	 * @param string $tab_slug     the tab slug for the url
	 * @param string $tab_label    the tab name
	 * @param string $tab_cap      wordpress capability to access the tab
	 * @param [type] $tab_callable a callable function
	 */
	function add_tab( $parent_slug, $tab_slug, $tab_label, $tab_cap, $tab_callable)
	{
		$this->tabs[] = array( 
			'parent_slug' 	=> $parent_slug,
			'slug' 			=> $tab_slug,
			'label' 		=> $tab_label,
			'wp_cap' 		=> $tab_cap,
			'callable'		=> $tab_callable
		);
	}

	function display()
	{
		if ( ! empty( $this->tabs ) ) {
			$this->switch_tab_pages();
		?>
		<div class="wrap">
		    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		    
		    <h2 class="nav-tab-wrapper">
		        <?php if ( current_user_can('read') ): ?>
		            <?php foreach ($this->tabs as $tab): ?>
						<?php if ( current_user_can( $tab['wp_cap'] ) ): ?>
							
		               		<a href="admin.php?page=<?php echo $tab['parent_slug']; ?>&tab=<?php echo $tab['slug'];  ?>" class="nav-tab <?php echo $this->active_tab == $tab['slug'] || empty($this->active_tab) ? 'nav-tab-active' : ''; ?>"><?php echo $tab['label']; ?></a>
		                
						<?php endif ?>
		            <?php endforeach ?>
		        
		        <?php endif ?>
		    </h2>
		      <?php
		      	 // Callable to include content after tabs header. The actual page content.
		         $_callable = $this->tabs[ array_search($this->active_tab, array_column( $this->tabs,'slug') ) ]['callable'];
		         if ( is_callable( $_callable ) ) {
		             $_callable();
		         } elseif ( is_array( $_callable ) && is_object( $_callable[0] ) ) {
		         	$_callable[0]->$_callable[1]();
		         }
		      ?>
		</div>
		<?php
		}
	}

	function switch_tab_pages()
	{
		if (isset($_GET['tab'])) {
			$this->active_tab = sanitize_text_field($_GET['tab']);

		}else
		{
			$this->active_tab = $this->tabs[0]['slug'];
		}
	}
}

?>