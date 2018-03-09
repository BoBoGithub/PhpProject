<?php
/**
 * 敏感词过滤服务类
 *  
 **/
class WordsFilterService {

	//生成字典
	public static function createTree() {
		$fp = fopen(WORDSFILTER_TEXT_PATH,"r");
		if(!$fp) {
			return false;
		}
		$resTrie = trie_filter_new(); 
		while($line = fgets($fp)) {
			trie_filter_store($resTrie, trim($line));
		}
		trie_filter_save($resTrie, WORDSFILTER_BINARY_PATH);
		fclose($fp);
	}

	//检测是否含敏感词
	public static function isForbidden($content) {
		$resTrie = trie_filter_load(WORDSFILTER_BINARY_PATH);
		$arrRet = trie_filter_search($resTrie, $content);
		if(is_array($arrRet) && count($arrRet) > 0) {
			return true;
		} else {
			return false;
		}
	}
}
