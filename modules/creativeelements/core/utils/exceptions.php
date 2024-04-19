<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2022 WebshopWorks.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace CE;

defined('_PS_VERSION_') or die;

/**
 * Elementor exceptions.
 *
 * Elementor exceptions handler class is responsible for handling exceptions.
 *
 * @since 2.0.0
 */
class CoreXUtilsXExceptions
{
    /**
     * HTTP status code for bad request error.
     */
    const BAD_REQUEST = 400;

    /**
     * HTTP status code for unauthorized access error.
     */
    const UNAUTHORIZED = 401;

    /**
     * HTTP status code for forbidden access error.
     */
    const FORBIDDEN = 403;

    /**
     * HTTP status code for resource that could not be found.
     */
    const NOT_FOUND = 404;

    /**
     * HTTP status code for internal server error.
     */
    const INTERNAL_SERVER_ERROR = 500;
}
