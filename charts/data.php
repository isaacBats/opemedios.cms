<?php

include 'php-ofc-library/open-flash-chart.php';


switch($_GET['id'])
{
	case 1:
	
		$title = new title( 'Cows go mooo' );

		$pie = new pie();
		$pie->set_start_angle( 35 );
		$pie->set_animate( true );
		$pie->set_tooltip( '#val# of #total#<br>#percent# of 100%' );
		$pie->set_values( array(2,3,6,3,5,3) );
		
		$chart = new open_flash_chart();
		$chart->set_title( $title );
		$chart->add_element( $pie );
		
		
		$chart->x_axis = null;
		
		
	break;
	
	case 2:
	
		$data_1 = array();

		for( $i=0; $i<6.2; $i+=0.2 )
		{
		  $data_1[] = (sin($i) * 1.9) + 7;
		}
		
		$title = new title( "Waves go wobble" );
		
		$line_1 = new line();
		$line_1->set_values( $data_1 );
		$line_1->set_width( 2 );
		
		
		$y = new y_axis();
		$y->set_range( 0, 10, 2 );
		
		
		$chart = new open_flash_chart();
		$chart->set_title( $title );
		$chart->add_element( $line_1 );
		$chart->set_y_axis( $y );
		
	
	break;
	
	default:
	
	break;
		
		
}

echo $chart->toPrettyString();

?>
