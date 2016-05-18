<?php
/*
	Created by Dylan (iiNzTicTx) to only be used for educational purposes.
	This was originally a template loader for a blog I was creating.
*/
include( './scripts/mysql_details.php' );
$handle = mysqli_connect( $MYSQL_HOST, $MYSQL_USER, $MYSQL_PASS, $MYSQL_DB ) or die();

function load_data_into_template()
{
	$postID = $_GET['page'];
	$HTML = file_get_contents( './template/index.html' );	#get the HTML template
	
	global $handle;
	$query = mysqli_query( $handle, 
		"SELECT *,
			DATE_FORMAT(date, '%Y-%m-%d') AS date, 
			DATE_FORMAT(date,'%H:%i:%s') AS date_time,
			DATE_FORMAT(modified, '%Y-%m-%d') AS modified, 
			DATE_FORMAT(modified,'%H:%i:%s') AS modified_time 
		FROM pages" );
		
	if( mysqli_num_rows( $query ) > 0 )
	{
		static $count = 0;
		$navigation_html = "";
		$comments_html = "";
		while( $row = mysqli_fetch_assoc( $query ) )
		{
			#### Post on current page ####
			if( $postID == $row['ID'] ) #for this post
			{
				$post_data = array(					
					"{ POST_ID }" => $row['ID'],		 # replaces { POST_ID } in the HTML template.			
					"{POST_AUTHOR}" => $row['author'],
					"{POST_CONTENT}" => $row['content'],
					"{POST_DATE}" => $row['date'],
					"{POST_TIME}" => $row['date_time'],
					"{POST_MODIFIED}" => $row['modified'],
					"{POST_MODIFIED_TIME}" => $row['modified_time']
				);
			}
		}
			
		#### Translate template ####
		foreach( $post_data as $key => $value )
		{
			$HTML = str_replace ( "$key", $value, $HTML ); #faster than regex
		}
		$HTML = str_replace( "{NAVIGATION}", $navigation_html, $HTML );
		$HTML = str_replace( "{COMMENTS}", $comments_html, $HTML );
		echo $HTML;
	}
	return 1;
}
load_data_into_template();

/* ## Example usage ##
	<div id = 'post_content'>
		{POST_CONTENT}
	</div>
	
	#support for loops isn't in here, yet. But perhaps something like { FOR sizeof( comments ) } ...HTML CONTENT ... { END }
*/
?>
