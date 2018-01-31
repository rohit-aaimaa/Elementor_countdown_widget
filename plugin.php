<?php
namespace ElementorCountdown;

use ElementorCountdown\Widgets\ElementorCountdown;
use Elementor\Plugin;

if( !defined('ABSPATH') ) exit; // Exit if accessed directly

class ElementorCountdownPlugin{
  public function __construct(){
    $this->add_actions();
  }

  private function add_actions(){
    add_action( 'elementor/widgets/widgets_registered', [ $this, 'on_widgets_registered' ]);
  }

  public function on_widgets_registered()
  {
    $this->includes();
    $this->register_widget();
  }

  private function includes()
  {
    require __DIR__ . '/widgets/countdown.php';
  }

  private function register_widget()
  {
    Plugin::instance()->widgets_manager->register_widget_type( new ElementorCountdown() );
    Plugin::instance()->elements_manager->add_category('custom-elements',[
      'title' => 'Custom Elementor Modules',
      'icon' => 'dashicons-format-aside'
    ], 1);
  }
}

new ElementorCountdownPlugin();