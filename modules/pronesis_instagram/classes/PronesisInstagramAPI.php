<?php
/**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2023 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

require_once _PS_MODULE_DIR_ . 'pronesis_instagram/vendor/autoload.php';

use Katzgrau\KLogger\Logger;
use Psr\Log\LogLevel;
use Curl\Curl;

class PronesisInstagramAPI
{
    /** @var string access token */
    protected $access_token;

    /** @var int access token expiration */
    protected $access_token_expiration;

    /** @var int max items */
    protected $max_items;

    /** @var int cache life */
    protected $cache_life;

    /** @var string cache last refresh */
    protected $cache_last_update;

    /** @var bool debug */
    protected $debug;

    /** @var object logger */
    protected $logger;

    /** @var string endpoint */
    protected $endpoint = 'https://graph.instagram.com/';

    /** @var string cache file */
    protected $cache_file = '';

    /** @var string thumbnail dir */
    protected $thumbnails_dir = '';

    /** @var int token refresh tolerance in days */
    protected $token_refresh_tolerance = 3;

    /** @var bool is_connect */
    public $is_connect;

    public function __construct($shop_id = 1)
    {
        $this->access_token = \Configuration::get('INSTAGRAM_ACCESS_TOKEN');
        $this->max_items = \Configuration::get('INSTAGRAM_MAX_ITEMS');
        $this->cache_life = \Configuration::get('INSTAGRAM_CACHE_LIFE');
        $this->cache_last_update = \Configuration::get('INSTAGRAM_CACHE_LAST_UPDATE');
        $this->access_token_expiration = \Configuration::get('INSTAGRAM_ACCESS_TOKEN_EXPIRATION');
        $this->cache_file = dirname(__FILE__) . '/../cache/'. $shop_id . '/cache.json';
        $this->thumbnails_dir = dirname(__FILE__) . '/../thumbnails/';

        if (\Configuration::get('INSTAGRAM_DEBUG')) {
            $debug_level = LogLevel::DEBUG;
        } else {
            $debug_level = LogLevel::ERROR;
        }
        $this->logger = new Logger(dirname(__FILE__) . '/../logs', $debug_level, array(
            'filename' => 'instagram.log',
            'flushFrequency' => 1000,
        ));
        try {
            // test if access token works
            $curl = new Curl();
            $curl->get($this->endpoint . 'me', array(
                    'fields' => 'id',
                    'access_token' => $this->access_token,
            ));
            // if the access token is not valid or expired we receive HTTP 400
            $this->is_connect = !$curl->error;
        } catch (\Exception $e) {
            $this->logger->error('Unable to connect to endpoint: ' . $e->getMessage());
            $this->is_connect = false;
        }
    }

    /**
     * Generate feed
     *
     * @return object feed
     */
    public function getFeed()
    {
        if (!Tools::strlen($this->access_token) > 0) {
            return false;
        }
        $this->refreshToken();
        $this->refreshCache();
        $context = Context::getContext();
        $images = $this->getCache();
        if ($images) {
            $images_to_show = array_slice($images, 0, $this->max_items, true);
            foreach ($images_to_show as &$image) {
                if ($image['media_type'] == 'IMAGE') {
                    $image_path = $this->thumbnails_dir . $image['id'] . '.jpg';
                    if(!file_exists($image_path)) {
                        $this->createThumbnail($image['media_url'], $image_path);
                    }
                    $image['thumbnail'] = $context->link->getMediaLink(_MODULE_DIR_.'pronesis_instagram/thumbnails/'. $image['id'] . '.jpg');
                }
            }
            return $images_to_show;
        } else {
            return false;
        }
    }

    /**
     * Refresh token
     *
     * @return boolean true if refresh ok
     */
    public function refreshToken($force_refresh = false)
    {
        if (!Tools::strlen($this->access_token) > 0) {
            return false;
        }
        if ((time() > ($this->access_token_expiration - $this->token_refresh_tolerance * 24 * 60)) ||
            $this->access_token_expiration == 0 ||
            $force_refresh) {
            $curl = new Curl();
            $curl->get($this->endpoint . 'refresh_access_token', array(
                    'grant_type' => 'ig_refresh_token',
                    'access_token' => $this->access_token,
            ));
            if (!$curl->error) {
                if (is_object($curl->response)) {
                    $response = $curl->response;
                } else {
                    $response = json_decode($curl->response);
                }
                if (isset($response->access_token)) {
                    $this->logger->info('Refresh Token OK for token: ' . $this->access_token);
                    $next_due = time() + $response->expires_in;
                    $this->access_token = $response->access_token;
                    $this->access_token_expiration = $next_due;
                    Configuration::updateValue('INSTAGRAM_ACCESS_TOKEN', $response->access_token);
                    Configuration::updateValue('INSTAGRAM_ACCESS_TOKEN_EXPIRATION', $next_due);
                    return true;
                } else {
                    $error = 'Refresh Token Error: access_token missing from response';
                    $this->logger->error($error);
                    return false;
                }
            } else {
                $error = 'Refresh Token Error: ' . $curl->error_code . ': ' . $curl->error_message;
                $this->logger->error($error);
                return false;
            }
        }
        return false;
    }

    /**
     * Refresh cache
     *
     * @return boolean true if refresh ok
     */
    public function refreshCache($force_refresh = false)
    {
        if (!Tools::strlen($this->access_token) > 0) {
            return false;
        }
        if (time() > ($this->cache_last_update + $this->cache_life * 3600) ||
                      !file_exists($this->cache_file) ||
                      $this->cache_last_update == 0 ||
                      $force_refresh) {
            $curl = new Curl();
            $curl->get($this->endpoint . 'me/media', array(
                    'fields' => 'id,caption,media_type,media_url,username,timestamp,permalink,thumbnail_url',
                    'access_token' => $this->access_token,
            ));
            if (!$curl->error) {
                if (isset($curl->response)) {
                    if (is_object($curl->response)) {
                        $response = $curl->response;
                    } else {
                        $response = json_decode($curl->response);
                    }
                    if (isset($response->data) && count($response->data) > 0) {
                        // filter VIDEO IMAGE and CAROUSEL_ALBUM
                        $filtered_response = array();
                        foreach ($response->data as $item) {
                            if ($item->media_type == 'CAROUSEL_ALBUM') {
                                // we have to query to get first image
                                $id = $item->id;
                                $curl->get($this->endpoint . $id . '/children', array(
                                    'fields' => 'id,media_type,media_url,username,timestamp,permalink,thumbnail_url',
                                    'access_token' => $this->access_token,
                                ));
                                if (!$curl->error) {
                                    if (isset($curl->response)) {
                                        if (is_object($curl->response)) {
                                            $response_children = $curl->response;
                                        } else {
                                            $response_children = json_decode($curl->response);
                                        }
                                    }
                                    if(isset($response_children)) {
                                        $filtered_response[] = $response_children->data[0];
                                    }
                                } else {
                                    $error = 'Get children Error: ' . $curl->errorCode . ': ' . $curl->errorMessage;
                                    $this->logger->error($error);
                                    return false;
                                }
                            } else {
                                $filtered_response[] = $item;
                            }
                        }
                        $this->setCache($filtered_response);
                        $this->logger->info('Refresh Cache OK for token: ' . $this->access_token);
                        return true;
                    } else {
                        $error = 'Get Feed Error: no response';
                        $this->logger->error($error);
                        return false;
                    }
                } else {
                    $error = 'Get Feed Error: no response';
                    $this->logger->error($error);
                    return false;
                }
            } else {
                $error = 'Get Feed Error: ' . $curl->error_code . ': ' . $curl->error_message;
                $this->logger->error($error);
                return false;
            }
        }
        return false;
    }

    /**
     * Set cache
     *
     * @param object $feed feed
     *
     * @return boolean true if refresh ok
     */
    private function setCache($feed)
    {
        $json = json_encode($feed);
        Configuration::updateValue('INSTAGRAM_CACHE_LAST_UPDATE', time());
        $path_parts = pathinfo($this->cache_file);
        if (!is_dir($path_parts['dirname'])) {
            // dir doesn't exist, make it
            mkdir($path_parts['dirname']);
        }
        return (bool) file_put_contents($this->cache_file, $json);
    }

    /**
     * Get cache
     *
     * @return array images from cache
     */
    private function getCache()
    {
        if (file_exists($this->cache_file)) {
            return json_decode(Tools::file_get_contents($this->cache_file), true);
        } else {
            return false;
        }
    }

    /**
     * copyImg copy an image located in $url and save it in a path
     * according to $entity->$id_entity .
     * $id_image is used if we need to add a watermark.
     *
     * @param int $id_entity id of product or category (set in entity)
     * @param int $id_image (default null) id of the image if watermark enabled
     * @param string $url path or url to use
     * @param string $entity 'products' or 'categories'
     * @param bool $regenerate
     *
     * @return bool
     */
    private function createThumbnail($url, $file_name)
    {
        $tmpfile = tempnam(_PS_TMP_IMG_DIR_, 'ps_import');

        if (Tools::copy($url, $tmpfile)) {
            // Evaluate the memory required to resize the image: if it's too much, you can't resize it.
            if (!ImageManager::checkImageMemoryLimit($tmpfile)) {
                @unlink($tmpfile);
                return false;
            }
            ImageManager::resize(
                $tmpfile,
                $file_name,
                300,
                300
            );
            unlink($tmpfile);
            return true;
        }
        return false;
    }
}
