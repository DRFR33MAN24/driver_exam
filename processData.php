<?php
require("includes/connection.php");
require("includes/lb_helper.php");
require("language/language.php");
require("language/app_language.php");
include("smtp_email.php");



$file_path = getBaseUrl();

$response = array();

$_SESSION['class'] = "success";

switch ($_POST['action']) {

	case 'toggle_status': {
			$id = $_POST['id'];
			$for_action = $_POST['for_action'];
			$column = $_POST['column'];
			$tbl_id = $_POST['tbl_id'];
			$table_nm = $_POST['table'];

			if ($for_action == 'active') {
				$data = array($column  =>  '1');
				$edit_status = Update($table_nm, $data, "WHERE $tbl_id = '$id'");
				$_SESSION['msg'] = "13";
			} else {
				$data = array($column  =>  '0');
				$edit_status = Update($table_nm, $data, "WHERE $tbl_id = '$id'");
				$_SESSION['msg'] = "14";
			}

			$response['status'] = 1;
			$response['action'] = $for_action;
			echo json_encode($response);
		}
		break;

	case 'multi_action': {
			$action = $_POST['for_action'];
			$ids = implode(",", [$_POST['id']]);
			$tbl_nm = $_POST['table'];


			if ($ids == '') {
				$ids = $_POST['id'];
			}

			if ($action == 'enable') {
				$sql = "UPDATE $tbl_nm SET `status`='1' WHERE `id` IN ($ids)";
				mysqli_query($mysqli, $sql);
				$_SESSION['msg'] = "13";
			} else if ($action == 'disable') {
				$sql = "UPDATE $tbl_nm SET `status`='0' WHERE `id` IN ($ids)";
				if (mysqli_query($mysqli, $sql)) {
					$_SESSION['msg'] = "14";
				}
			} else if ($action == 'delete') {

				if ($tbl_nm == 'tbl_users') {

					$sql = "SELECT * FROM tbl_users WHERE `id` IN ($ids)";
					$res = mysqli_query($mysqli, $sql);
					while ($row = mysqli_fetch_assoc($res)) {
						if ($row['profile_img'] != "") {
							unlink('images/' . $row['profile_img']);
						}
					}
					$deleteSql = "DELETE FROM tbl_active_log WHERE `user_id` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);

					$deleteSql = "DELETE FROM tbl_reports WHERE `user_id` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);

					$deleteSql = "DELETE FROM $tbl_nm WHERE `id` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);
				} else if ($tbl_nm == 'tbl_reports') {
					$deleteSql = "DELETE FROM $tbl_nm WHERE `id` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);
				} else if ($tbl_nm == 'tbl_category') {

					$sql = "SELECT * FROM tbl_category WHERE `cid` IN ($ids)";
					$res = mysqli_query($mysqli, $sql);
					while ($row = mysqli_fetch_assoc($res)) {
						if ($row['category_image'] != "") {
							unlink('images/' . $row['category_image']);
						}
					}
					$deleteSql = "DELETE FROM $tbl_nm WHERE `cid` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);
				} else if ($tbl_nm == 'tbl_language') {
					$deleteSql = "DELETE FROM $tbl_nm WHERE `lid` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);
				} else if ($tbl_nm == 'tbl_quiz') {
					$sql = "SELECT * FROM tbl_quiz WHERE `id` IN ($ids)";
					$res = mysqli_query($mysqli, $sql);
					while ($row = mysqli_fetch_assoc($res)) {
						if ($row['image'] != "") {
							unlink('images/' . $row['image']);
						}
					}
					$deleteSql = "DELETE FROM $tbl_nm WHERE `id` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);
				} else if ($tbl_nm == 'tbl_signs') {

					$sql = "SELECT * FROM tbl_signs WHERE `id` IN ($ids)";
					$res = mysqli_query($mysqli, $sql);
					while ($row = mysqli_fetch_assoc($res)) {
						if ($row['signs_image'] != "") {
							unlink('images/' . $row['signs_image']);
						}
					}
					$deleteSql = "DELETE FROM $tbl_nm WHERE `id` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);
				} else if ($tbl_nm == 'tbl_videos') {

					$sql = "SELECT * FROM tbl_videos WHERE `id` IN ($ids)";
					$res = mysqli_query($mysqli, $sql);
					while ($row = mysqli_fetch_assoc($res)) {
						if ($row['thumbnail'] != "") {
							unlink('images/' . $row['thumbnail']);
						}
					}
					$deleteSql = "DELETE FROM $tbl_nm WHERE `id` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);
				} else if ($tbl_nm == 'tbl_questions_categories') {

					$sql = "SELECT * FROM tbl_questions_categories WHERE `id` IN ($ids)";
					$res = mysqli_query($mysqli, $sql);

					$deleteSql = "DELETE FROM $tbl_nm WHERE `id` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);
				}
				$_SESSION['msg'] = "12";
			}

			$response['status'] = 1;
			echo json_encode($response);
		}
		break;
	case 'check_smtp': {
			$to = trim($_POST['email']);
			$recipient_name = 'Check User';

			$subject = '[IMPORTANT] ' . APP_NAME . ' Check SMTP Configuration';

			$message = '<div style="background-color: #f9f9f9;" align="center"><br />
        		<table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
        		<tbody>
        		<tr>
        		<td colspan="2" bgcolor="#FFFFFF" align="center"><img src="' . $file_path . 'images/' . APP_LOGO . '" alt="header" /></td>
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
        		<p style="color: #262626; font-size: 24px; margin-top:0px;">Hi, ' . $_SESSION['admin_name'] . '</p>
        		<p style="color: #262626; font-size: 18px; margin-top:0px;">This is the demo mail to check SMTP Configuration. </p>
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

			send_email($to, $recipient_name, $subject, $message, true);

			$_SESSION['msg'] = "22";
			$response['status'] = 1;
			echo json_encode($response);
			break;
		}
	default:
		# code...
		break;
}
