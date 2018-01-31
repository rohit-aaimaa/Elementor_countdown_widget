<?php
namespace ElementorCountdown\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;


if( !defined('ABSPATH') ) exit; // Exit if accessed directly

class ElementorCountdown extends Widget_Base{

  protected $_has_template_content = false;

  public function get_name()
  {
    return 'countdown';
  }

  public function get_title()
  {
    return __( 'CountDown', 'countdown-for-elementor' );
  }

  public function get_icon()
  {
    return 'eicon-countdown';
  }

  public function get_categories()
  {
    return [ 'custom-elements' ];
  }

  protected function _register_controls(){

    $this->start_controls_section(
      'cdw_section_countdown_settings_general',
      [
        'label' => esc_html__('Countdown Settings', 'countdown-for-elementor')
      ]
    );

    $this->add_control('cdw_countdown_due_time',
    [
      'label' => esc_html__('Countdown Due Date', 'countdown-for-elementor'),
      'type' => Controls_Manager::DATE_TIME,
      'default' => date('Y-m-d', strtotime(' + 1 day')),
      'description' => esc_html__( 'Set the due date and time', 'countdown-for-elementor' )
    ]);

    $this->add_control(
			'eael_countdown_label_view',
			[
				'label' => esc_html__( 'Label Position', 'countdown-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'eael-countdown-label-block',
				'options' => [
					'eael-countdown-label-block' => esc_html__( 'Block', 'countdown-for-elementor' ),
					'eael-countdown-label-inline' => esc_html__( 'Inline', 'countdown-for-elementor' ),
				],
			]
		);

		$this->add_responsive_control(
			'eael_countdown_label_padding_left',
			[
				'label' => esc_html__( 'Left spacing for Labels', 'countdown-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'description' => esc_html__( 'Use when you select inline labels', 'countdown-for-elementor' ),
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-label' => 'padding-left:{{SIZE}}px;',
				],
				'condition' => [
					'eael_countdown_label_view' => 'eael-countdown-label-inline',
				],
			]
		);

    $this->end_controls_section();
  }

  protected function render()
  {
    $settings = $this->get_settings();
    $get_due_date = esc_attr($settings['cdw_countdown_due_time']);
    $due_date = date('Y-m-d G:i:s', strtotime($get_due_date));
    ?>
      <div id="countdown-wrapper">
      </div>

      <script type="text/javascript">
        jQuery(function($){
          $('#countdown-wrapper').countdown("<?php echo $due_date ?>", function(event){
            $(this).text(event.strftime('%D days %H:%M:%S'));
          });
      });
      </script>
    <?php
  }
}