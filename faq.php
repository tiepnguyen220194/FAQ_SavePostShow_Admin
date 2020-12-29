
<form class="form-contact-home" id="form-faqs" action="" method="POST">
	<div class="bao-form">
		<input type="text" name="faq-name" class="name" placeholder="Họ và tên" required>
		<input type="number" name="faq-phone" class="phone" placeholder="Số điện thoại" required>
	</div>
		<input type="email" name="faq-email" class="email" placeholder="Địa chỉ email" required>
		<textarea rows="3" cols="50" title="Nhập nội dung câu hỏi..." name="faq-content" required></textarea>
		<input type="submit" name="faqSubmit" class="faqSubmit" value="Gửi">
</form>

<?php
	if(isset($_REQUEST['faqSubmit'])){
		global $wpdb;

		$wp_posts="wp_posts";
		$wp_term_relationships="wp_term_relationships";
		$wp_postmeta = "wp_postmeta";

		$name = $_REQUEST['faq-name'];
		$phone = $_REQUEST['faq-phone'];
		$email = $_REQUEST['faq-email'];
		$content = $_REQUEST['faq-content'];

	    date_default_timezone_set('Asia/Ho_Chi_Minh');//Ho_Chi_Minh là mặc định ở việt nam rồi
	    $date_send = date('Y-m-d H:i:s');

	    //title bài post
	    $title_question = "Câu hỏi từ - ".$name;

	    $my_post = array(
	        'post_title' => $title_question,
			'post_content'  => '',
			'post_excerpt' => $content,
	        'post_status'   => 'pending',
	        'post_author'   => 1,
	        'post_type' => 'hoi-dap',
	    );
	    $post_id = wp_insert_post($my_post,0);

	    //Thêm vào bảng term_relationships
	    $dataterms = array(
	        'object_id' => $post_id,
	        'term_taxonomy_id' => get_theme_mod( 'id_category_ask' ),
	        'term_order' =>0
	    );
	    $wpdb->insert($wp_term_relationships,$dataterms);

	    //Thêm vào bảng post_meta
		$post_meta_keys=array("wpcf-ask-name","wpcf-ask-email","wpcf-ask-phone","wpcf-ask-date");
		$post_meta_values=array($name,$email,$phone,$date_send);
		for($i=0; $i < count($post_meta_values); $i++){
			$post_meta_data = array(
				'meta_id' => null,
				'post_id' => $post_id,
				'meta_key' => $post_meta_keys[$i],
				'meta_value' => $post_meta_values[$i]
			);
			$wpdb->insert($wp_postmeta,$post_meta_data);
		}
	}
?>