<?php
/**
 * 文件上传类
 *  
 **/
class UploadFileService {

	//上传文件
	public static function upload($local_path) {
		if(!file_exists($local_path)) {
			CLog::warning("timecost[%s] msg[local file not exist]", Timer::toString());
			return false;
		}
		$tracker = fastdfs_tracker_get_connection();
		if (!fastdfs_active_test($tracker))
		{
			CLog::warning("timecost[%s] msg[connect tracker fail]", Timer::toString());
			return false;
		}
		$storage = fastdfs_tracker_query_storage_store();
		if (!$storage)
		{
			CLog::warning("timecost[%s] msg[query storage store fail]", Timer::toString());
			return false;
		}
		$file_info = fastdfs_storage_upload_by_filename($local_path, null, array(), null, $tracker, $storage);
		if(isset($file_info['filename'])) {
			return $file_info['group_name']."/".$file_info['filename'];
		}
		return false;
	}
}
