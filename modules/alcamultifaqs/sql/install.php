<?php
/**
 * 2023 ALCALINK E-COMMERCE & SEO, S.L.L.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * @author ALCALINK E-COMMERCE & SEO, S.L.L. <info@alcalink.com>
 * @copyright  2023 ALCALINK E-COMMERCE & SEO, S.L.L.
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Registered Trademark & Property of ALCALINK E-COMMERCE & SEO, S.L.L.
 */
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . AlcaMultiFaq::$definition['table'] . '` (
    `' . AlcaMultiFaq::$definition['primary'] . '` int(11) NOT NULL AUTO_INCREMENT,
    `id_shop` int(10) UNSIGNED NOT NULL,
    `type` varchar(255) DEFAULT NULL,
    `id_object` int(11) NOT NULL DEFAULT \'0\',
    `position` int(11) NOT NULL DEFAULT \'0\',
    PRIMARY KEY (`' . AlcaMultiFaq::$definition['primary'] . '`)
  ) ENGINE=' . _MYSQL_ENGINE_ . '  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . AlcaMultiFaq::$definition['table'] . '_lang` (
    `' . AlcaMultiFaq::$definition['primary'] . '` int(11) NOT NULL,
    `id_lang` int(10) UNSIGNED NOT NULL,
    `id_shop` int(10) UNSIGNED NOT NULL,
    `title` text DEFAULT NULL,
    `content` text DEFAULT NULL,
    KEY `id_lang` (`id_lang`,`id_shop`,`' . AlcaMultiFaq::$definition['primary'] . '`)
  ) ENGINE=' . _MYSQL_ENGINE_ . '  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
