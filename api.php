<?php
include("includes/connection.php");
include("includes/lb_helper.php");
include("language/app_language.php");
include("smtp_email.php");
date_default_timezone_set("Asia/Colombo");
$live_date = date('Y-m-d');

$file_path = getBaseUrl();

define("DEFAULT_PASSWORD", '123');
define("PACKAGE_NAME", $settings_details['envato_package_name']);

// Get thumbs image
function get_thumb($filename, $thumb_size)
{
	global $file_path;
	return $thumb_path = $file_path . 'thumb.php?src=' . $filename . '&size=' . $thumb_size;
}

// Get image
function get_images($filename)
{
	global $file_path;
	return $images_path = $file_path . 'images/' . $filename;
}

// For generate randome password
function generateRandomPassword($length = 10)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

//if($settings_details['envato_buyer_name']=='' OR $settings_details['envato_purchase_code']=='' OR $settings_details['envato_api_key']=='') {

//  $set['DRIVING_EXAM_APP'][]=array('MSG'=> 'Purchase code verification failed!','success'=>'0');

//header( 'Content-Type: application/json; charset=utf-8' );
//echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
//die();
//}

function update_activity_log($user_id)
{
	global $mysqli;

	$sql = "SELECT * FROM tbl_active_log WHERE `user_id`='$user_id'";
	$result = mysqli_query($mysqli, $sql);

	if (mysqli_num_rows($result) == 0) {
		$data_log = array(
			'user_id'  =>  $user_id,
			'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
		);

		$qry = Insert('tbl_active_log', $data_log);
	} else {
		$data_log = array(
			'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
		);

		$update = Update('tbl_active_log', $data_log, "WHERE user_id = '$user_id'");
	}

	mysqli_free_result($result);
}

function send_register_email($to, $recipient_name, $subject, $message)
{
	global $file_path;
	global $app_lang;

	$message_body = '<div style="background-color: #eee;" align="center"><br />
	<table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
	<tbody>
	<tr>
	<td colspan="2" bgcolor="#FFFFFF" align="center" ><img src="' . $file_path . 'images/' . APP_LOGO . '" alt="logo" style="width:100px;height:auto"/></td>
	</tr>
	<br>
	<br>
	<tr>
	<td colspan="2" bgcolor="#FFFFFF" align="center" style="padding-top:25px;">
	<img src="' . $file_path . 'assets/images/thankyoudribble.gif" alt="header" auto-height="100" width="50%"/>
	</td>
	</tr>
	<tr>
	<td width="600" valign="top" bgcolor="#FFFFFF">
	<table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
	<tbody>
	<tr>
	<td valign="top">
	<table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
	<tbody>
	<tr>
	<td>
	<p style="color: #717171; font-size: 24px; margin-top:0px; margin:0 auto; text-align:center;"><strong>' . $app_lang['welcome_lbl'] . ', ' . $recipient_name . '</strong></p>
	<br>
	<p style="color:#15791c; font-size:18px; line-height:32px;font-weight:500;margin-bottom:30px; margin:0 auto; text-align:center;">' . $message . '<br /></p>
	<br/>
	<p style="color:#999; font-size:17px; line-height:32px;font-weight:500;">' . $app_lang['thank_you_lbl'] . ' ' . APP_NAME . '</p>
	</td>
	</tr>
	</tbody>
	</table>
	</td>
	</tr>
	</tbody>
	</table>
	</td>
	</tr>
	<tr>
	<td style="color: #262626; padding: 20px 0; font-size: 18px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">' . $app_lang['email_copyright'] . ' ' . APP_NAME . '.</td>
	</tr>
	</tbody>
	</table>
	</div>';

	send_email($to, $recipient_name, $subject, $message_body);
}



$get_helper = get_api_data($_POST['data']);

if ($get_helper['helper_name'] == "get_language_list") {

	$jsonObj = array();

	$sql = "SELECT * FROM tbl_language WHERE tbl_language.status='1' ORDER BY tbl_language.lid DESC";
	$result = mysqli_query($mysqli, $sql);

	while ($data = mysqli_fetch_assoc($result)) {

		$row['lid'] = $data['lid'];
		$row['language_name'] = $data['language_name'];

		array_push($jsonObj, $row);
	}

	$set['DRIVING_EXAM_APP'] = $jsonObj;
	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();
} else if ($get_helper['helper_name'] == "get_cat_list") {

	$jsonObj = array();

	$cat_order = API_CAT_ORDER_BY;

	$sql = "SELECT * FROM tbl_category WHERE tbl_category.status='1' ORDER BY " . $cat_order . " DESC";

	$result = mysqli_query($mysqli, $sql);

	while ($data = mysqli_fetch_assoc($result)) {

		$row['cid'] = $data['cid'];
		$row['category_name'] = $data['category_name'];
		$row['category_image'] = get_images($data['category_image']);
		$row['category_image_thumb'] = get_thumb('images/' . $data['category_image'], '300x300');

		array_push($jsonObj, $row);
	}

	$set['DRIVING_EXAM_APP'] = $jsonObj;
	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();
} else if ($get_helper['helper_name'] == "get_video_list") {

	$jsonObj = array();

	$vid_order = API_VID_ORDER_BY;

	$sql = "SELECT * FROM tbl_videos WHERE tbl_videos.status='1'";

	$result = mysqli_query($mysqli, $sql);

	while ($data = mysqli_fetch_assoc($result)) {

		$row['vid'] = $data['id'];
		$row['video_title'] = $data['video_title'];
		$row['video_url'] = $data['video_url'];

		$row['video_image'] = get_images($data['thumbnail']);


		array_push($jsonObj, $row);
	}

	$set['DRIVING_EXAM_APP'] = $jsonObj;
	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();
} else if ($get_helper['helper_name'] == "get_cat_by") {

	$cat_id = $get_helper['cat_id'];
	$lan_id = $get_helper['lan_id'];

	$jsonObj = array();

	$post_order_by = API_CAT_POST_ORDER_BY;

	$page_limit = 15;
	$limit = ($get_helper['page'] - 1) * $page_limit;

	if ($lan_id != "") {
		$who = "AND tbl_signs.lan_id =" . $lan_id . "";
	} else {
		$who = "AND tbl_signs.lan_id";
	}

	if ($cat_id != "") {
		$scat = "AND tbl_signs.cat_id =" . $cat_id . "";
	} else {
		$scat = "AND tbl_signs.cat_id";
	}

	$sort = "ORDER BY tbl_signs.id $post_order_by";

	$sql = "SELECT * FROM tbl_signs WHERE tbl_signs.status='1'
        " . $who . " " . $scat . " " . $sort . " 
        LIMIT $limit, $page_limit";

	$result = mysqli_query($mysqli, $sql);

	while ($data = mysqli_fetch_assoc($result)) {

		$row['sid'] = $data['id'];
		$row['signs_name'] = $data['signs_name'];
		$row['signs_image'] = get_images($data['signs_image']);
		$row['signs_image_thumb'] = get_thumb('images/' . $data['signs_image'], '300x300');

		array_push($jsonObj, $row);
	}

	$set['DRIVING_EXAM_APP'] = $jsonObj;
	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();
} else if ($get_helper['helper_name'] == "get_video_by") {

	$cat_id = $get_helper['cat_id'];
	$lan_id = $get_helper['lan_id'];

	$jsonObj = array();

	$post_order_by = API_CAT_POST_ORDER_BY;

	$page_limit = 15;
	$limit = ($get_helper['page'] - 1) * $page_limit;

	if ($lan_id != "") {
		$who = "AND tbl_signs.lan_id =" . $lan_id . "";
	} else {
		$who = "AND tbl_signs.lan_id";
	}

	if ($cat_id != "") {
		$scat = "AND tbl_signs.cat_id =" . $cat_id . "";
	} else {
		$scat = "AND tbl_signs.cat_id";
	}

	$sort = "ORDER BY tbl_signs.id $post_order_by";

	$sql = "SELECT * FROM tbl_signs WHERE tbl_signs.status='1'
        " . $who . " " . $scat . " " . $sort . " 
        LIMIT $limit, $page_limit";

	$result = mysqli_query($mysqli, $sql);

	while ($data = mysqli_fetch_assoc($result)) {

		$row['sid'] = $data['id'];
		$row['signs_name'] = $data['signs_name'];
		$row['signs_image'] = get_images($data['signs_image']);
		$row['signs_image_thumb'] = get_thumb('images/' . $data['signs_image'], '300x300');

		array_push($jsonObj, $row);
	}

	$set['DRIVING_EXAM_APP'] = $jsonObj;
	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();
} else if ($get_helper['helper_name'] == "get_quiz") {

	$lan_id = $get_helper['lan_id'];

	$thumbnail_post = IMG_LIMIT;
	$nonel_post = NONE_IMG_LIMIT;

	$jsonObj = array();
	$data_arr = array();

	$sql = "SELECT * FROM tbl_quiz WHERE tbl_quiz.lan_id='" . $lan_id . "' AND tbl_quiz.image_type='thumbnail_block' AND tbl_quiz.status='1'ORDER BY rand() DESC LIMIT $thumbnail_post";
	$result = mysqli_query($mysqli, $sql);

	while ($data = mysqli_fetch_assoc($result)) {
		$data_arr['id'] = $data['id'];
		$data_arr['answer'] = $data['answer'];
		$data_arr['answer_a'] = $data['answer_a'];
		$data_arr['answer_b'] = $data['answer_b'];
		$data_arr['answer_c'] = $data['answer_c'];
		$data_arr['answer_d'] = $data['answer_d'];
		$data_arr['correctAnswer'] = $data['correctAnswer'];
		if ($data['image'] == "") {
			$data_arr['image'] = "";
		} else {
			$data_arr['image'] = get_thumb('images/' . $data['image'], '400x400');
		}
		array_push($jsonObj, $data_arr);
	}
	$row['quiz_img'] = $jsonObj;

	mysqli_free_result($result);
	$jsonObj = array();
	$data_arr = array();

	$sql = "SELECT * FROM tbl_quiz WHERE tbl_quiz.lan_id='" . $lan_id . "' AND tbl_quiz.image_type='thumbnail_none' AND tbl_quiz.status='1'ORDER BY rand() DESC LIMIT $nonel_post";
	$result = mysqli_query($mysqli, $sql);

	while ($data = mysqli_fetch_assoc($result)) {
		$data_arr['id'] = $data['id'];
		$data_arr['answer'] = $data['answer'];
		$data_arr['answer_a'] = $data['answer_a'];
		$data_arr['answer_b'] = $data['answer_b'];
		$data_arr['answer_c'] = $data['answer_c'];
		$data_arr['answer_d'] = $data['answer_d'];
		$data_arr['correctAnswer'] = $data['correctAnswer'];
		if ($data['image'] == "") {
			$data_arr['image'] = "";
		} else {
			$data_arr['image'] = get_thumb('images/' . $data['image'], '400x400');
		}
		array_push($jsonObj, $data_arr);
	}
	$row['quiz_no_img'] = $jsonObj;

	$set['DRIVING_EXAM_APP'] = $row;
	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();
} else if ($get_helper['helper_name'] == "post_report") {

	$jsonObj = array();

	$user_id = cleanInput($get_helper['user_id']);
	$report_msg = cleanInput($get_helper['report_msg']);

	$data = array(
		'user_id'  =>  $user_id,
		'report_msg'  =>  $report_msg,
		'report_on'  =>  strtotime(date('d-m-Y h:i:s A')),
	);
	$qry = Insert('tbl_reports', $data);

	$data_not = array(
		'user_id' => $user_id,
		'notification_title' => 'Report successful',
		'notification_msg' => $report_msg,
		'notification_on' =>  strtotime(date('d-m-Y h:i:s A'))
	);

	$qry2 = Insert('tbl_notification', $data_not);

	$set['DRIVING_EXAM_APP'][] = array('MSG' => $app_lang['report_success'], 'success' => '1');
	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();
} else if ($get_helper['helper_name'] == "get_notification") {

	$user_id = $get_helper['user_id'];

	$jsonObj = array();

	$page_limit = 50;
	$limit = ($get_helper['page'] - 1) * $page_limit;

	$query = "SELECT * FROM tbl_notification WHERE `user_id`='$user_id' ORDER BY tbl_notification.`id` DESC LIMIT $limit, $page_limit";
	$sql = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
	while ($data = mysqli_fetch_assoc($sql)) {
		$row['id'] = $data['id'];
		$row['notification_title'] = $data['notification_title'];
		$row['notification_msg'] = $data['notification_msg'];
		$row['notification_on'] = calculate_time_span($data['notification_on'], true);

		array_push($jsonObj, $row);
	}

	$set['DRIVING_EXAM_APP'] = $jsonObj;
	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();
} else if ($get_helper['helper_name'] == "remove_notification") {

	$post_id = cleanInput($get_helper['post_id']);
	$user_id = cleanInput($get_helper['user_id']);

	$jsonObj = array();

	$sql = "SELECT * FROM tbl_notification WHERE `id`='$post_id' AND `user_id`='$user_id'";
	$res = mysqli_query($mysqli, $sql);
	if (mysqli_num_rows($res) > 0) {

		$deleteSql = "DELETE FROM tbl_notification WHERE `id`='$post_id' AND `user_id`='$user_id'";
		mysqli_query($mysqli, $deleteSql);

		$set['DRIVING_EXAM_APP'][] = array('MSG' => $app_lang['remove_success'], 'success' => '1');
	} else {
		$set['DRIVING_EXAM_APP'][] = array('MSG' => $app_lang['like_remove_error'], 'success' => '0');
	}

	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();
} else if ($get_helper['helper_name'] == "user_register") {

	$user_type = trim($get_helper['type']);

	$email = addslashes(trim($get_helper['user_email']));
	$auth_id = addslashes(trim($get_helper['auth_id']));

	$to = $get_helper['user_email'];
	$recipient_name = $get_helper['user_name'];

	$subject = str_replace('###', APP_NAME, $app_lang['register_mail_lbl']);

	$response = array();

	$user_id = '';

	switch ($user_type) {
		case 'Google': {
				$sql = "SELECT * FROM tbl_users WHERE (`user_email` = '$email' OR `auth_id`='$auth_id') AND `user_type`='Google'";

				$res = mysqli_query($mysqli, $sql);

				if (mysqli_num_rows($res) == 0) {

					$data = [
						'user_type' => 'Google',
						'user_name' => addslashes(trim($get_helper['user_name'])),
						'user_email' => addslashes(trim($get_helper['user_email'])),
						'user_phone' => '',
						'user_password' => md5(DEFAULT_PASSWORD),
						'user_gender'  => '',
						'registered_on'  =>  strtotime(date('d-m-Y h:i:s A')),
						'auth_id' => $auth_id,
						'profile_img' => '',
						'status'  =>  '1'
					];

					$qry = Insert('tbl_users', $data);

					$user_id = mysqli_insert_id($mysqli);

					send_register_email($to, $recipient_name, $subject, $app_lang['google_register_msg']);

					// login success
					$response = array(
						'user_id' =>  strval($user_id),
						'user_name' => $get_helper['user_name'],
						'user_email' => $get_helper['user_email'],
						'user_phone' => '',
						'user_gender' => '',
						'profile_img' => '',
						'auth_id' => $auth_id,
						'MSG' => $app_lang['login_success'],
						'success' => '1'
					);
				} else {

					$row = mysqli_fetch_assoc($res);

					$data = array('auth_id'  =>  $auth_id);

					$update = Update('tbl_users', $data, "WHERE id = '" . $row['id'] . "'");

					$user_id = $row['id'];

					if ($row['status'] == 0) {
						$response = array('msg' => $app_lang['account_deactive'], 'success' => '0');
					} else {
						$response = array(
							'user_id' =>  $row['id'],
							'user_name' => $row['user_name'],
							'user_email' => $row['user_email'],
							'user_phone' => $row['user_phone'],
							'user_gender' => $row['user_gender'],
							'profile_img' => $row['profile_img'],
							'auth_id' => $auth_id,
							'MSG' => $app_lang['login_success'],
							'success' => '1'
						);
					}
				}

				update_activity_log($user_id);
			}
			break;

		case 'Normal': {
				$sql = "SELECT * FROM tbl_users WHERE user_email = '$email'";
				$result = mysqli_query($mysqli, $sql);
				$row = mysqli_fetch_assoc($result);

				if (!filter_var($get_helper['user_email'], FILTER_VALIDATE_EMAIL)) {
					$response = array('MSG' => $app_lang['invalid_email_format'], 'success' => '0');
				} else if ($row['user_email'] != "") {
					$response = array('MSG' => $app_lang['email_exist'], 'success' => '0');
				} else {

					if ($_FILES['image_data']['name'] != "") {

						$imgName = rand(0, 99999) . "_" . $_FILES['image_data']['name'];

						//Main Image
						$tpath1 = 'images/' . $imgName;
						$pic1 = compress_image($_FILES["image_data"]["tmp_name"], $tpath1, 80);
					} else {
						$imgName = '';
					}

					$data = [
						'user_name' => addslashes(trim($get_helper['user_name'])),
						'user_email' => addslashes(trim($get_helper['user_email'])),
						'user_phone' => addslashes(trim($get_helper['user_phone'])),
						'user_password' => md5(trim($get_helper['user_password'])),
						'user_gender'  => addslashes(trim($get_helper['user_gender'])),
						'registered_on'  =>  strtotime(date('d-m-Y h:i:s A')),
						'profile_img' => $imgName,
						'status'  =>  '1'
					];

					$qry = Insert('tbl_users', $data);

					$user_id = mysqli_insert_id($mysqli);

					send_register_email($to, $recipient_name, $subject, $app_lang['normal_register_msg']);

					$response = array('MSG' => $app_lang['register_success'], 'success' => '1');

					update_activity_log($user_id);
				}
			}
			break;

		default: {
				$response = array('success' => '0', 'MSG' => $app_lang['register_fail']);
			}
			break;
	}

	$set['DRIVING_EXAM_APP'][] = $response;

	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();
} else if ($get_helper['helper_name'] == "user_login") {

	$response = array();

	$email = trim($get_helper['user_email']);
	$password = trim($get_helper['user_password']);
	$auth_id = trim($get_helper['auth_id']);
	$user_type = trim($get_helper['type']);

	if (!filter_var($email, FILTER_VALIDATE_EMAIL) and $email != '') {
		$response = array('MSG' => $app_lang['invalid_email_format'], 'success' => '0');

		$set['DRIVING_EXAM_APP'][] = $response;
		header('Content-Type: application/json; charset=utf-8');
		echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}

	switch ($user_type) {
		case 'Google': {
				$sql = "SELECT * FROM tbl_users WHERE (`user_email` = '$email' OR `auth_id`='$auth_id') AND (`user_type`='Google' OR `user_type`='google')";

				$res = mysqli_query($mysqli, $sql);

				if (mysqli_num_rows($res) > 0) {
					$row = mysqli_fetch_assoc($res);

					if ($row['status'] == 0) {
						$response = array('MSG' => $app_lang['account_deactive'], 'success' => '0');
					} else {
						$user_id = $row['id'];

						update_activity_log($user_id);

						$data = array('auth_id'  =>  $auth_id);

						Update('tbl_users', $data, "WHERE `id` = " . $row['id']);

						$response = array('user_id' =>  $row['id'], 'user_name' => $row['user_name'], 'user_phone' => $row['user_phone'], 'user_gender' => $row['user_gender'], 'profile_img' => $row['profile_img'], 'MSG' => $app_lang['login_success'], 'success' => '1');
					}
				} else {
					$response = array('MSG' => $app_lang['email_not_found'], 'success' => '0');
				}
			}
			break;

		case 'Normal': {
				$qry = "SELECT * FROM tbl_users WHERE user_email = '$email' AND (`user_type`='Normal' OR `user_type`='normal') AND `id` <> 0";
				$result = mysqli_query($mysqli, $qry);
				$num_rows = mysqli_num_rows($result);

				if ($num_rows > 0) {
					$row = mysqli_fetch_assoc($result);

					if ($row['status'] == 1) {
						if ($row['user_password'] == md5($password)) {

							$user_id = $row['id'];

							update_activity_log($user_id);

							$response = array('user_id' =>  $row['id'], 'user_name' => $row['user_name'], 'user_phone' => $row['user_phone'], 'user_gender' => $row['user_gender'], 'profile_img' => $row['profile_img'], 'MSG' => $app_lang['login_success'], 'success' => '1');
						} else {
							$response = array('MSG' => $app_lang['invalid_password'], 'success' => '0');
						}
					} else {
						$response = array('MSG' => $app_lang['account_deactive'], 'success' => '0');
					}
				} else {
					$response = array('MSG' => $app_lang['email_not_found'], 'success' => '0');
				}
			}
			break;

		default: {
				$response = array('success' => '0', 'MSG' => $app_lang['register_fail']);
			}
			break;
	}

	$set['DRIVING_EXAM_APP'][] = $response;
	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();
} else if ($get_helper['helper_name'] == "user_profile") {
	$jsonObj = array();

	$user_id = cleanInput($get_helper['user_id']);

	$qry = "SELECT * FROM tbl_users WHERE id = '$user_id'";
	$result = mysqli_query($mysqli, $qry);
	$row = mysqli_fetch_assoc($result);

	$data['success'] = "1";
	$data['user_id'] = $row['id'];
	$data['user_name'] = $row['user_name'];
	$data['user_email'] = ($row['user_email'] != '') ? $row['user_email'] : '';
	$data['user_phone'] = ($row['user_phone'] != '') ? $row['user_phone'] : '';
	$data['user_gender'] = $row['user_gender'];
	$data['profile_img'] = get_images($row['profile_img']);

	array_push($jsonObj, $data);

	$set['DRIVING_EXAM_APP'] = $jsonObj;

	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
	die();
} else if ($get_helper['helper_name'] == "edit_profile") {

	$jsonObj = array();

	$qry = "SELECT * FROM tbl_users WHERE id = '" . $get_helper['user_id'] . "'";
	$result = mysqli_query($mysqli, $qry);
	$row = mysqli_fetch_assoc($result);

	if (!filter_var($get_helper['user_email'], FILTER_VALIDATE_EMAIL)) {
		$set['DRIVING_EXAM_APP'][] = array('MSG' => $app_lang['invalid_user_type'], 'success' => '0');
	} else if ($row['user_email'] == $get_helper['user_email'] and $row['id'] != $get_helper['user_id']) {
		$set['DRIVING_EXAM_APP'][] = array('MSG' => $app_lang['email_not_found'], 'success' => '0');
	} else {
		$data = array(
			'user_name'  =>  cleanInput($get_helper['user_name']),
			'user_email'  =>  trim($get_helper['user_email']),
			'user_phone'  =>  cleanInput($get_helper['user_phone']),
		);

		if ($get_helper['user_password'] != "") {
			$data = array_merge($data, array("user_password" => md5(trim($get_helper['user_password']))));
		}

		$user_edit = Update('tbl_users', $data, "WHERE id = '" . $get_helper['user_id'] . "'");

		$set['DRIVING_EXAM_APP'][] = array('MSG' => $app_lang['update_success'], 'success' => '1');
	}

	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();
} else if ($get_helper['helper_name'] == "user_images_update") {

	if ($_FILES['image_data']['name'] != "") {
		$image_data = rand(0, 99999) . "_" . $_FILES['image_data']['name'];

		//Main Image
		$tpath1 = 'images/' . $image_data;
		$pic1 = compress_image_user($_FILES["image_data"]["tmp_name"], $tpath1, 80);

		$data = array(
			'profile_img'  =>  $image_data
		);

		$user_update = Update('tbl_users', $data, "WHERE id = '" . $get_helper['user_id'] . "'");
		$set['DRIVING_EXAM_APP'][] = array('MSG' => $app_lang['update_success'], 'success' => '1');
	} else {
		$set['DRIVING_EXAM_APP'][] = array('MSG' => $app_lang['update_fail'], 'success' => '0');
	}

	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();
} else if ($get_helper['helper_name'] == "account_delete") {

	$ids = cleanInput($get_helper['user_id']);

	$sql = "SELECT * FROM tbl_users WHERE `id`='$ids'";
	$res = mysqli_query($mysqli, $sql);
	if (mysqli_num_rows($res) > 0) {

		$sql_img = "SELECT * FROM tbl_users WHERE `id` IN ($ids)";
		$res_img = mysqli_query($mysqli, $sql_img);
		while ($row = mysqli_fetch_assoc($res_img)) {
			if ($row['profile_img'] != "") {
				unlink('images/' . $row['profile_img']);
			}
		}

		$deleteSql = "DELETE FROM tbl_active_log WHERE `user_id` IN ($ids)";
		mysqli_query($mysqli, $deleteSql);

		$deleteSql = "DELETE FROM tbl_notification WHERE `user_id` IN ($ids)";
		mysqli_query($mysqli, $deleteSql);

		$deleteSql = "DELETE FROM tbl_users WHERE `id` IN ($ids)";
		mysqli_query($mysqli, $deleteSql);

		$set['DRIVING_EXAM_APP'][] = array('MSG' => "Remove success", 'success' => '1');
	} else {
		$set['DRIVING_EXAM_APP'][] = array('MSG' => 'Remove error', 'success' => '0');
	}

	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();
} else if ($get_helper['helper_name'] == "forgot_pass") {

	$email = addslashes(trim($get_helper['user_email']));

	$qry = "SELECT * FROM tbl_users WHERE user_email = '$email' AND `user_type`='Normal' AND `id` <> 0";
	$result = mysqli_query($mysqli, $qry);
	$row = mysqli_fetch_assoc($result);

	if ($row['user_email'] != "") {

		$password = generateRandomPassword(7);

		$new_password = md5($password);

		$to = $row['user_email'];
		$recipient_name = $row['user_name'];
		// subject
		$subject = str_replace('###', APP_NAME, $app_lang['forgot_password_sub_lbl']);

		$message = '<div style="background-color: #f9f9f9;" align="center"><br />
				  <table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
				    <tbody>
				      <tr>
				        <td colspan="2" bgcolor="#FFFFFF" align="center"><img src="' . $file_path . 'images/' . APP_LOGO . '" alt="header" style="width:100px;height:auto"/></td>
				      </tr>
				      <tr>
				        <td width="600" valign="top" bgcolor="#FFFFFF"><br>
				          <table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
				            <tbody>
				              <tr>
				                <td valign="top"><table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
				                    <tbody>
				                      <tr>
				                        <td>
				                          <p style="color: #262626; font-size: 24px; margin-top:0px;"><strong>' . $app_lang['dear_lbl'] . ' ' . $row['user_name'] . '</strong></p>
				                          <p style="color:#262626; font-size:20px; line-height:32px;font-weight:500;margin-top:5px;"><br>' . $app_lang['your_password_lbl'] . ': <span style="font-weight:400;">' . $password . '</span></p>
				                          <p style="color:#262626; font-size:17px; line-height:32px;font-weight:500;margin-bottom:30px;">' . $app_lang['thank_you_lbl'] . ' ' . APP_NAME . '</p>

				                        </td>
				                      </tr>
				                    </tbody>
				                  </table></td>
				              </tr>
				               
				            </tbody>
				          </table></td>
				      </tr>
				      <tr>
				        <td style="color: #262626; padding: 20px 0; font-size: 18px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">' . $app_lang['email_copyright'] . ' ' . APP_NAME . '.</td>
				      </tr>
				    </tbody>
				  </table>
				</div>';

		send_email($to, $recipient_name, $subject, $message);

		$sql = "UPDATE tbl_users SET `user_password`='$new_password' WHERE `id`='" . $row['id'] . "'";
		mysqli_query($mysqli, $sql);

		$set['DRIVING_EXAM_APP'][] = array('MSG' => $app_lang['password_sent_mail'], 'success' => '1');
	} else {
		$set['DRIVING_EXAM_APP'][] = array('MSG' => $app_lang['email_not_found'], 'success' => '0');
	}
	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
	die();
} else if ($get_helper['helper_name'] == "app_details") {

	$user_id = cleanInput($get_helper['user_id']);

	$jsonObj = array();
	$query = "SELECT * FROM tbl_settings WHERE id='1'";
	$sql = mysqli_query($mysqli, $query);

	while ($data = mysqli_fetch_assoc($sql)) {

		// App Details
		$row['app_email'] = $data['app_email'];
		$row['app_author'] = $data['app_author'];
		$row['app_contact'] = $data['app_contact'];
		$row['app_website'] = $data['app_website'];
		$row['app_description'] = $data['app_description'];
		$row['app_developed_by'] = $data['app_developed_by'];

		// Envato
		$row['envato_api_key'] = $data['envato_api_key'];

		// API Latest Limit
		$row['api_latest_limit'] = $data['api_latest_limit'];

		// Ads Network
		$row['ad_network'] = $data['ad_network'];
		if ($data['ad_network'] == 'admob') {
			$row['publisher_id'] = $data['publisher_id'];
		} else {
			$row['publisher_id'] = '';
		}
		if ($data['ad_network'] == 'startapp') {
			$row['startapp_app_id'] = $data['start_ads_id'];
		} else {
			$row['startapp_app_id'] = '';
		}
		if ($data['ad_network'] == 'unity') {
			$row['unity_ads_id'] = $data['unity_ads_id'];
		} else {
			$row['unity_ads_id'] = '';
		}
		if ($data['ad_network'] == 'iron') {
			$row['iron_ads_id'] = $data['iron_ads_id'];
		} else {
			$row['iron_ads_id'] = '';
		}

		// BannerAds
		$row['banner_ad'] = $data['banner_ad'];
		if ($data['ad_network'] == 'facebook') {
			$row['banner_ad_id'] = $data['banner_facebook_id'];
		} else if ($data['ad_network'] == 'admob') {
			$row['banner_ad_id'] = $data['banner_ad_id'];
		} else if ($data['ad_network'] == 'applovins') {
			$row['banner_ad_id'] = $data['banner_applovins_id'];
		} else {
			$row['banner_ad_id'] = '';
		}

		// InterstitalAds
		$row['interstital_ad'] = $data['interstital_ad'];
		$row['interstital_ad_click'] = $data['interstital_ad_click'];
		if ($data['ad_network'] == 'facebook') {
			$row['interstital_ad_id'] = $data['interstital_facebook_id'];
		} else if ($data['ad_network'] == 'admob') {
			$row['interstital_ad_id'] = $data['interstital_ad_id'];
		} else if ($data['ad_network'] == 'applovins') {
			$row['interstital_ad_id'] = $data['interstital_applovins_id'];
		} else {
			$row['interstital_ad_id'] = '';
		}

		// NativeAds
		$row['native_ad'] = $data['native_ad'];
		$row['native_position'] = $data['native_position'];
		if ($data['ad_network'] == 'facebook') {
			$row['native_ad_id'] = $data['native_facebook_id'];
		} else if ($data['ad_network'] == 'admob') {
			$row['native_ad_id'] = $data['native_ad_id'];
		} else if ($data['ad_network'] == 'applovins') {
			$row['native_ad_id'] = $data['native_applovins_id'];
		} else {
			$row['native_ad_id'] = '';
		}

		// AdsLimits
		$row['ads_limits'] = $data['ads_limits'];
		$row['ads_count_click'] = $data['ads_count_click'];

		// CustomAds
		$row['custom_ads'] = $data['custom_ads'];
		$row['custom_ads_img'] = $data['custom_ads_img'];
		$row['custom_ads_link'] = $data['custom_ads_link'];
		$row['custom_ads_clicks'] = $data['custom_ads_clicks'];

		// is
		$row['isRTL'] = $data['isRTL'];
		$row['isVPN'] = $data['isVPN'];
		$row['isAPK'] = $data['isAPK'];
		$row['isMaintenance'] = $data['isMaintenance'];
		$row['isScreenshot'] = $data['isScreenshot'];
		$row['isLogin'] = $data['isLogin'];
		$row['isGoogleLogin'] = $data['isGoogleLogin'];

		// AppUpdate
		$row['app_update_status'] = $data['app_update_status'];
		$row['app_new_version'] = $data['app_new_version'];
		$row['app_update_desc'] = $data['app_update_desc'];
		$row['app_redirect_url'] = $data['app_redirect_url'];

		array_push($jsonObj, $row);
	}
	$set['DRIVING_EXAM_APP'] = $jsonObj;
	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();
} else {
	$get_helper = get_api_data($_POST['data']);
}
