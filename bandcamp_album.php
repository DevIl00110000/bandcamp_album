<?php

/**
 *
 * Parse the url's page to fetch some information.
 *
 * Information that we can have :
 * - Your url                        -> get_url()
 * - band name                       -> get_nameBand()
 * - Band image                      -> get_pictureBand()
 * - Album name                      -> get_nameAlbum()
 * - Album cover                     -> get_cover()
 * - album description               -> get_description()
 * - Album player                    -> get_player()
 *
 * There is a static function, it's a conditon function
 * for know if it's an album bandcamp url -> is_album()
 *
 * Download the page and put in a file cache
 *
 */

require_once 'class.cache.php';

class bandcamp_album {

	/* ------------------------------------- *\
			private
	\* ------------------------------------- */

	/* --- Variable ---*/

	/**
	 * Variable définie par le constructeur
	 * @var string
	 */
	private $url;

	/**
	 * $cache contient l'instance de cache
	 * @var class
	 */
	private $cache;

	/**
	 * $name_file_content_album_page contient le nom du fichier de cache
	 * @var string
	 */
	private $name_file_content_album_page;

	/**
	 * $content_album_page contient le chemin du fichier de cache
	 * @var [type]
	 */
	private $content_album_page;

	/* --- function  --- */

	/* ------------------------------------- *\
			public
	\* ------------------------------------- */

	/* --- Variable ---*/


	/* --- function  --- */

	/**
	 * defined :
	 * - url album
	 * - the cache
	 * - url of cache file
	 * @param string $url bandcamp url album
	 * @param string $cert your ssl certificate (exemple : __DIR__.DIRECTORY_SEPARATOR."cert.cer")
	 * @param null $dossier_cache cache tmp
	 */
	public function __construct ($url = string, $cert = null, $dossier_cache = null) {
		if (!empty($url) && $this->is_album($url)){
			$this->url = $url;

			$CacheName = str_replace([":", "/", "."], '', $url);

			$this->content_album_page = $dossier_cache."/tmp/".$CacheName.".tmp";

			if (is_null($dossier_cache) || empty($dossier_cache) || !is_string($dossier_cache)) {
				$dossier_cache = dirname(__FILE__);
			}

			$this->cache = new cache($dossier_cache, $CacheName, 60);

			$ssl_cert_ok = true;
			if (empty($cert) || !preg_match("%\.cer%", $cert)) {
				throw new Exception("You need a ssl certificate", 1);
				$ssl_cert_ok = false;
				die();
			}

			if ($ssl_cert_ok && !file_exists($dossier_cache."/tmp/".$CacheName.".tmp")) {
				$curl = curl_init($url);
				curl_setopt_array($curl, [
					CURLOPT_SSL_VERIFYPEER => true,
					CURLOPT_CAINFO => $cert,
					CURLOPT_RETURNTRANSFER => true,
					//CURLOPT_TIMEOUT => 1
				]);
				$data = curl_exec($curl);
				
				$this->cache->write($data);

				curl_close($curl);
			}

			$this->content_album_page = $dossier_cache."/tmp/".$CacheName.".tmp";
	
		} else {
			throw new Exception("Url empty or not an album url", 1);
			
		}
	}

	/**
	 * get_url Return your bandcamp url
	 * @return string
	 */
	public function get_url () {
		return $this->url;
	}

	/**
	 * return false if isn't an album url
	 * @return boolean
	 */
	public static function is_album (string $url) {
		if (preg_match("/^https:\/\/(.*).bandcamp.com\/album\/(.*)$/i", $url)) {
			return True;
		} else {
			return false;
		}
	}

	/* --- BAND --- */

	/**
	 * Return the band name
	 * @return string
	 */
	public function get_nameBand () {
		$content = file_get_contents($this->content_album_page);
		$regex = preg_match('/<meta name="title" content=".*, by (.*)">/i', $content, $matches);
		return $matches[1];
	}

	/**
	 * Return the band image but can return false if there is no image
	 * @return string
	 */
	public function get_pictureBand () {
		$content = file_get_contents($this->content_album_page);
		$regex = preg_match('/<img src="(.*)" class="band-photo" alt=".*">/i', $content, $matches);
		$img = isset($matches[1]) ? str_replace("_21.jpg", "_10.jpg", $matches[1]) : false;
		return $img;
	}

	/* --- ALBUM --- */

	/**
	 * Return the album name
	 * @return string
	 */
	public function get_nameAlbum () {
		$content = file_get_contents($this->content_album_page);
		$regex = preg_match('/<meta name="title" content="(.*), by .*">/i', $content, $matches);
		return $matches[1];
	}

	/**
	 * Return an array of some covers with different resolution
	 * Array between 0 et 12
	 * @return array
	 */
	public function get_cover () {
		$content = file_get_contents($this->content_album_page);
		$regex_For_Image = preg_match('/<link rel="image_src" href="(.*)">/i', $content, $matches_Image);
		$regex_For_Image_Id = preg_match('/https:\/\/f4\.bcbits\.com\/img\/(.*)_16\.jpg/i', $matches_Image[1], $matches_Image_Id);
		$Image_Id = $matches_Image_Id[1];

		$differenteCover = array(
			0  => "https://f4.bcbits.com/img/".$Image_Id."_3.jpg",  // 100x100
			1  => "https://f4.bcbits.com/img/".$Image_Id."_8.jpg",  // 124x124
			2  => "https://f4.bcbits.com/img/".$Image_Id."_15.jpg", // 135x135
			3  => "https://f4.bcbits.com/img/".$Image_Id."_12.jpg", // 138x138
			4  => "https://f4.bcbits.com/img/".$Image_Id."_7.jpg",  // 150x150
			5  => "https://f4.bcbits.com/img/".$Image_Id."_11.jpg", // 172x172

			6  => "https://f4.bcbits.com/img/".$Image_Id."_9.jpg",  // 210x210

			7  => "https://f4.bcbits.com/img/".$Image_Id."_4.jpg",  // 300x300
			8  => "https://f4.bcbits.com/img/".$Image_Id."_2.jpg",  // 350x350  --- Better for a twitter card ---
			9  => "https://f4.bcbits.com/img/".$Image_Id."_14.jpg", // 368x368
			10 => "https://f4.bcbits.com/img/".$Image_Id."_13.jpg", // 380x380

			11 => "https://f4.bcbits.com/img/".$Image_Id."_16.jpg", // 700x700
			12 => "https://f4.bcbits.com/img/".$Image_Id."_10.jpg"  // 709x709
		);

		return $differenteCover;
	}

	/**
	 * Return the album description
	 * @return string
	 */
	public function get_description () {
		$content = file_get_contents($this->content_album_page);
		$regex = preg_match('/<div class="tralbumData tralbum-about" itemprop="description">(.*?)<\/div>/is', $content, $matches);
		return $matches[1];
	}

	/**
	 * Return two player iframe
	 * Array entre 0 et 1
	 * @return array
	 */
	public function get_player() {
		$content = file_get_contents($this->content_album_page);
		$regex = preg_match('/tralbum_param: { name: "album", value: (.*) },/i', $content, $matches);
		$embed = array(
			// with tracklist above
			0 => '<iframe src="https://bandcamp.com/EmbeddedPlayer/album='.$matches[1].'/size=large/bgcol=333333/linkcol=0f91ff/transparent=true/" seamless></iframe>',
			// just the artwork with the player hover
			1 => '<iframe src="https://bandcamp.com/EmbeddedPlayer/album='.$matches[1].'/size=large/bgcol=333333/linkcol=0f91ff/minimal=true/transparent=true/" seamless></iframe>'
		);

		return $embed;
	}

	/**
	 * Delete the cache file
	 * @return null
	 */
	public function end(){
		$this->cache->delete_file();
	}

}
