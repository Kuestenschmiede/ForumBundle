<?php

/**
 * con4gis - the gis-kit
 *
 * @version   php 7
 * @package   con4gis
 * @author    con4gis contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2017.
 * @link      https://www.kuestenschmiede.de
 */

namespace con4gis\ForumBundle\Resources\contao\classes;

/**
 * Class C4GUtils
 * @package con4gis\ForumBundle\Resources\contao\classes
 */
class C4GUtils {
	/**
	 *
	 * Secure user generated content
	 * @param $str
	 */
	public static function secure_ugc($str) {

		// kritische Kontrollzeichen rausfiltern
		$search = array( chr(0), chr(1), chr(2), chr(3), chr(4), chr(5), chr(6), chr(7), chr(8),
		                 chr(11), chr(12), chr(14), chr(15), chr(16), chr(17), chr(18), chr(19) );
		$result = str_replace($search, ' ', $str);

		// Unerwünschte Unicode Sonderzeichen z.B. zur Umkehrung des Textflusses entfernen
		$regex = '/(?:%E(?:2|3)%8(?:0|1)%(?:A|8|9)\w)/i';
		$result = urldecode(preg_replace($regex,' ',urlencode($result)));

		// Eingangs-Html formatieren und überflüssige Leerzeichen entfernen
		return trim(htmlspecialchars($result));

	}

	/**
	 *
	 * Flatten a multi dimensional array
	 * @param array $a
	 */
	public static function array_flatten($a) {
    	$ab = array();
    	if(!is_array($a))
    		return $ab;
    	foreach($a as $value){
        	if(is_array($value)){
            	$ab = array_merge($ab,self::array_flatten($value));
        	}else{
            	array_push($ab,$value);
        	}
    	}
    	return $ab;
	}

	/**
	 *
	 * @param array $params
	 */
	public static function addParametersToURL( $url, $params )
	{
		list($urlpart, $qspart) = array_pad(explode('?', $url, 2), 2, '');
		if (!$urlpart) {
			$urlpart = $url;
		}
		parse_str($qspart, $qsvars);
		foreach ($params AS $key=>$value)
		{
			$qsvars[$key] = $value;
		}
		$newqs = http_build_query($qsvars);
		return $urlpart . '?' . $newqs;
	}

	/**
	 * compresses the raw data set for searching/indexing
	 * and removes stopwords
     *
     * @param      $rawDataSet
     * @param bool $stripStopwords
     * @param bool $preserveComma
     *
     * @return mixed|string
     */
	public static function compressDataSetForSearch($rawDataSet, $stripStopwords = true,$bPreserveComma = false, $bPreserveNumbers = false, $bPreserveHyphen = false)
	{
            $dSearch = array(
                '#ß#',
                '#Ä|ä#',
                '#Ö|ö#',
                '#Ü|ü#',
                '#Á|á|À|à|Â|â#',
                '#Ó|ó|Ò|ò|Ô|ô#',
                '#Ú|ú|Ù|ù|Û|û#',
                '#É|é|È|è|Ê|ê#',
                '#Í|í|Ì|ì|Î|î#',
                ($bPreserveComma === false)?'#([/.,+'.(($bPreserveHyphen === false)?'-':'').']*\s)#':'#([/.+'.(($bPreserveHyphen === false)?'-':'').']*\s)#',
                ($bPreserveNumbers === false)?($bPreserveComma === false)?'#([^A-Za-z'.(($bPreserveHyphen === false)?'':'-').'])#':'#([^A-Za-z,'.(($bPreserveHyphen === false)?'':'-').'])#':($bPreserveComma === false)?'#([^A-Za-z0-9'.(($bPreserveHyphen === false)?'':'-').'])#':'#([^A-Za-z0-9,'.(($bPreserveHyphen === false)?'':'-').'])#',
                '# +#'
            );
		$dReplace = array(
				'ss',
				'ae',
				'oe',
				'ue',
				'a',
				'o',
				'u',
				'e',
				'i',
				' ',
				' ',
				' '
				);

		$dataSet = trim(stripslashes(strip_tags($rawDataSet)));
		$dataSet = preg_replace($dSearch, $dReplace, $dataSet);
		$dataSet = trim(strtolower($dataSet));

		unset($dSearch);
		unset($dReplace);

		if ($stripStopwords) {
			$dSearch = array(
					'#(\s[A-Za-z]{1,2})\s#',
					'# ' . implode(' | ', $GLOBALS['TL_LANG']['C4G_FORUM']['STOPWORDS']) . ' #',
					'# +#'
			);
			$dReplace = array(
					' ',
					' ',
					' '
			);

			$dataSet = ' ' . str_replace(' ', '  ', $dataSet) . ' ';
			$dataSet = trim(preg_replace($dSearch, $dReplace, $dataSet));
		}
		return $dataSet;
	}
}
?>